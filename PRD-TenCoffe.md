# PRD — TenCoffe (Website Kafe & Ordering System)

> **Dokumen ini adalah blueprint lengkap** untuk membangun aplikasi web kafe TenCoffe.
> Dibuat berdasarkan analisis mendalam dari aplikasi serupa yang sudah berjalan di production.
> Semua fitur, struktur database, alur kerja, dan detail teknis sudah teruji dan siap diimplementasi.

---

## 📋 Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Database](#3-struktur-database)
4. [Halaman Publik](#4-halaman-publik)
5. [Admin Panel](#5-admin-panel)
6. [Sistem Keranjang & Checkout](#6-sistem-keranjang--checkout)
7. [Integrasi Payment Gateway (Duitku)](#7-integrasi-payment-gateway-duitku)
8. [Sistem Autentikasi](#8-sistem-autentikasi)
9. [Sistem File Upload](#9-sistem-file-upload)
10. [Sistem Settings](#10-sistem-settings)
11. [Desain & UI/UX](#11-desain--uiux)
12. [Seeder Data Awal](#12-seeder-data-awal)
13. [Deployment](#13-deployment)
14. [Checklist Implementasi](#14-checklist-implementasi)

---

## 1. Gambaran Umum

**TenCoffe** adalah website kafe yang memungkinkan pelanggan:
- Melihat menu lengkap dengan kategori dan harga
- Menambahkan item ke keranjang belanja (tanpa perlu login)
- Checkout via WhatsApp, pembayaran manual (kasir), atau payment gateway online
- Melacak status pesanan

Dan admin bisa:
- Mengelola produk, kategori, banner, pesanan, event spesial, dan pengaturan
- Melihat dashboard statistik penjualan
- Mengupdate status pesanan

---

## 2. Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Database** | SQLite |
| **Frontend** | Blade Templates + Tailwind CSS v4 |
| **JavaScript** | Alpine.js (reactive UI tanpa build step) |
| **Build Tool** | Vite |
| **Payment** | Duitku Payment Gateway (opsional) |
| **Hosting Target** | DirectAdmin Shared Hosting |

### Package Yang Dibutuhkan
```json
{
    "require": {
        "laravel/framework": "^12.0",
        "guzzlehttp/guzzle": "^7.0" 
    },
    "require-dev": {
        "laravel/pint": "^1.0",
        "pestphp/pest": "^3.0"
    }
}
```

### NPM Dependencies
```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.0",
        "axios": "^1.7",
        "laravel-vite-plugin": "^1.2",
        "tailwindcss": "^4.0",
        "vite": "^6.0"
    }
}
```

---

## 3. Struktur Database

### 3.1 Tabel `users`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK auto | |
| name | string | |
| email | string unique | |
| role | string default `'admin'` | Role: `admin` atau `customer` |
| email_verified_at | timestamp nullable | |
| password | string | Hashed |
| remember_token | string nullable | |
| timestamps | | created_at, updated_at |

**Method di Model:**
- `isAdmin(): bool` → `return $this->role === 'admin'`

---

### 3.2 Tabel `categories`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| name | string | Nama kategori |
| slug | string unique | Auto-generate dari nama |
| description | text nullable | Deskripsi singkat |
| image | string nullable | Path gambar |
| sort_order | int default 0 | Urutan tampil |
| is_active | boolean default true | |
| timestamps | | |

**Relationships:** `hasMany(Product)`
**Scopes:** `active()`, `ordered()`

---

### 3.3 Tabel `products`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| category_id | FK → categories (cascade) | |
| name | string | Nama produk |
| slug | string unique | Auto-generate dari nama |
| description | text nullable | |
| price | int | Harga dasar (atau min dari hot/cold) |
| price_hot | int nullable | Harga varian Hot |
| price_cold | int nullable | Harga varian Cold |
| has_variants | boolean default false | Punya varian Hot/Cold? |
| image | string nullable | |
| is_active | boolean default true | |
| is_featured | boolean default false | Tampil di "Best Sellers" |
| is_new | boolean default false | Badge "Baru" |
| is_promo | boolean default false | Sedang promo? |
| promo_price | int nullable | Harga promo (non-variant) |
| is_seasonal | boolean default false | Menu musiman? |
| seasonal_label | string nullable | Label musiman custom |
| sort_order | int default 0 | |
| timestamps | | |

**Relationships:** `belongsTo(Category)`
**Scopes:** `active()`, `featured()`, `seasonal()`, `ordered()`
**Accessors:**
- `display_price` → return promo_price jika is_promo, else price
- `formatted_price` → `Rp xx.xxx`
- `formatted_original_price` → harga asli (saat promo)
- `image_url` → cek `public/images/` lalu `public/storage/`, fallback `no-product.svg`
- `getPriceForVariant($variant)` → return price_hot/price_cold sesuai variant

---

### 3.4 Tabel `orders`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| order_number | string unique | Format: `TEN` + `yymmdd` + `NNNN` (4 digit sequential) |
| customer_name | string | |
| customer_email | string nullable | |
| customer_phone | string | |
| customer_address | text nullable | Untuk delivery |
| notes | text nullable | Catatan pesanan |
| order_type | enum | `dine-in`, `pickup`, `delivery` |
| subtotal | int | Total sebelum ongkir |
| delivery_fee | int default 0 | |
| total | int | subtotal + delivery_fee |
| payment_method | string | `manual`, `whatsapp`, `duitku` |
| payment_status | string default `pending` | |
| duitku_reference | string nullable | ID ref dari Duitku |
| duitku_payment_url | string nullable | URL bayar Duitku |
| duitku_va_number | string nullable | Virtual account number |
| status | string | `pending` / `paid` / `processing` / `ready` / `completed` / `cancelled` |
| paid_at | timestamp nullable | Waktu pembayaran |
| timestamps | | |

**Relationships:** `hasMany(OrderItem)`
**Static Method:** `generateOrderNumber()` → prefix `TEN` + date + counter
**Accessors:**
- `status_label` → Label Indonesia (Menunggu, Dibayar, Diproses, Siap, Selesai, Dibatalkan)
- `status_color` → Tailwind CSS class per status
- `formatted_total` → `Rp xx.xxx`

---

### 3.5 Tabel `order_items`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| order_id | FK → orders (cascade) | |
| product_id | FK → products nullable (nullOnDelete) | |
| product_name | string | Snapshot nama saat order |
| product_price | int | Snapshot harga saat order |
| quantity | int | |
| subtotal | int | price × quantity |
| timestamps | | |

**Relationships:** `belongsTo(Order)`, `belongsTo(Product)`

---

### 3.6 Tabel `banners`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| title | string | |
| subtitle | string nullable | |
| image | string | Path gambar |
| link | string nullable | URL target |
| sort_order | int default 0 | |
| is_active | boolean default true | |
| timestamps | | |

**Scopes:** `active()`, `ordered()`

---

### 3.7 Tabel `galleries`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| title | string nullable | Nama paket / judul |
| group | string | `menu` atau `special_event` |
| product_id | FK → products nullable (nullOnDelete) | Link ke produk (opsional) |
| price | int nullable | Harga mandiri (untuk event items) |
| image | string | |
| sort_order | int default 0 | |
| is_active | boolean default true | |
| timestamps | | |

**Relationships:** `belongsTo(Product)`
**Scopes:** `active()`, `ordered()`, `group($name)`
**Accessors:** `formatted_price`, `image_url`
**Static:** `groups()` → `['menu' => 'Menu Utama', 'special_event' => 'Event Spesial']`

---

### 3.8 Tabel `settings`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| key | string unique | |
| value | text nullable | |
| type | string | `text`, `number`, `boolean` |
| group | string | `general`, `social`, `order`, `payment`, `special_event` |
| timestamps | | |

**Static Methods:**
- `Setting::get($key, $default)` → ambil satu setting
- `Setting::set($key, $value, $type, $group)` → simpan/update setting
- `Setting::getGroup($group)` → ambil semua setting dalam satu group sebagai array

---

### 3.9 Tabel Framework Laravel

Buat juga migration standar Laravel:
- `cache` + `cache_locks`
- `jobs` + `job_batches` + `failed_jobs`
- `sessions`
- `password_reset_tokens`

---

## 4. Halaman Publik

### 4.1 Home Page (`GET /`)

**Controller:** `HomeController@index`
**View:** `resources/views/home.blade.php`

**Sections (urutan dari atas ke bawah):**

1. **Hero Section**
   - Full-screen gradient background (warna tema kafe)
   - Logo kafe (bulat, border, shadow)
   - Judul besar: `TEN COFFE`
   - Tagline
   - Deskripsi singkat
   - 2 tombol CTA: "Lihat Menu" → `/menu`, "Best Sellers" → scroll ke section
   - Scroll indicator animasi bounce di bawah

2. **Banner Carousel**
   - Data dari tabel `banners` (active, ordered)
   - Auto-slide setiap 4 detik (Alpine.js)
   - Navigasi manual: tombol prev/next + dots indicator
   - Responsive: aspect-ratio berbeda mobile vs desktop

3. **Best Sellers / Featured Products**
   - Ambil max 8 produk dengan `is_featured = true`
   - Grid 1-4 kolom responsive
   - Setiap card: gambar, kategori label, nama, harga, tombol add-to-cart
   - Produk dengan `has_variants`: tampil toggle Hot/Cold dengan Alpine.js
   - Produk promo: tampil harga coret + badge "Promo"

4. **Kategori Showcase**
   - Tampil semua kategori aktif dengan jumlah produk
   - Grid cards, klik → redirect ke `/menu/{slug}`

5. **Menu Seasonal** (opsional, tampil jika ada produk seasonal)
   - Grid max 4 produk dengan `is_seasonal = true`
   - Section background gradient khusus (warna berbeda)
   - Badge promo, harga, tombol add-to-cart

6. **Event Spesial** (opsional, toggle dari admin)
   - Controlled via setting `special_event_enabled`
   - Data gambar dari `galleries` table (group = `special_event`)
   - Konfigurasi: emoji, badge text, judul, subtitle → dari `settings` table
   - Gambar **tanpa harga** → klik buka lightbox (Alpine.js)
   - Gambar **dengan harga** → tampil harga + tombol add-to-cart
   - Tombol "Order Sekarang" di bawah → link ke `/menu`

7. **CTA Section**
   - "Pesan Sekarang" dan "Hubungi Kami" buttons

**Data yang dipass ke view:**
```php
compact('banners', 'featuredProducts', 'categories', 'seasonalProducts', 'settings',
        'specialEventSettings', 'specialEventEnabled', 'specialEventImages')
```

---

### 4.2 Menu Page (`GET /menu` dan `GET /menu/{slug}`)

**Controller:** `MenuController@index`, `MenuController@category`
**View:** `resources/views/menu.blade.php`

**Fitur:**
- **Filter kategori**: Pill buttons di atas (Semua + setiap kategori aktif)
- **Product grid**: 1-4 kolom responsive
- **Setiap product card:**
  - Gambar (aspect-square, hover zoom)
  - Label kategori (warna berbeda per kategori)
  - Nama produk
  - Badge: Baru, Promo, Seasonal
  - Harga (formatted Rupiah)
  - Produk promo: harga coret + harga promo
  - Produk variant (Hot/Cold): toggle button dengan Alpine.js, harga berubah sesuai pilihan
  - Tombol add-to-cart (+)
- **Gradual Loading**: Tampil 8 produk pertama, tombol "Lihat Lainnya (X menu lagi)" untuk load 8 berikutnya (Alpine.js `visibleCount`)
- **Spacing**: Tombol load more punya jarak `py-16` dari card di atasnya

---

### 4.3 Cart Page (`GET /cart`)

**Controller:** `CartController@index`
**View:** `resources/views/cart.blade.php`

**Fitur:**
- Keranjang berbasis **session** (tanpa login)
- List item: gambar, nama, harga, badge variant (Hot/Cold), badge Paket Ramadhan (event item)
- Tombol +/- quantity (AJAX update)
- Tombol hapus item (AJAX)
- Panel ringkasan (sticky di desktop): total item, total harga, tombol "Checkout"
- Tombol "Lanjut Belanja" → link ke `/menu`
- State kosong: ilustrasi + pesan + link ke menu

**API Endpoints Cart:**

| Method | URL | Parameter | Keterangan |
|--------|-----|-----------|------------|
| POST | `/cart/add` | `product_id`, `quantity?`, `variant?` (hot/cold) | Tambah produk biasa |
| POST | `/cart/add-event` | `gallery_id`, `quantity?` | Tambah item event spesial |
| PUT | `/cart/update` | `cart_key`, `quantity` | Update jumlah (0 = hapus) |
| DELETE | `/cart/remove` | `cart_key` | Hapus item |
| GET | `/cart/count` | - | JSON `{ count: N }` untuk badge navbar |

**Cart Key Format:**
- Produk biasa tanpa variant: `"{product_id}"`
- Produk dengan variant: `"{product_id}_hot"` atau `"{product_id}_cold"`
- Event item: `"event_{gallery_id}"`

**Cart Session Structure:**
```php
session('cart') = [
    '5' => ['quantity' => 2, 'variant' => null],
    '3_hot' => ['quantity' => 1, 'variant' => 'hot'],
    'event_7' => ['quantity' => 1, 'name' => 'Paket Spesial', 'price' => 60000, 'image' => '...', 'type' => 'event'],
];
```

---

### 4.4 Checkout Page (`GET /checkout`, `POST /checkout`)

**Controller:** `CheckoutController@index`, `CheckoutController@store`
**View:** `resources/views/checkout.blade.php`

**GET /checkout:**
- Redirect ke menu jika cart kosong
- Resolve harga dari database (bukan session) → anti-tamper
- Tampilkan form checkout + ringkasan pesanan

**Form Fields:**
- Nama Lengkap (required)
- WhatsApp/No HP (required)
- Email (optional)
- Tipe Pesanan: visual cards (Dine In / Take Away / Delivery) — Alpine.js
- Alamat Pengiriman (muncul jika Delivery dipilih)
- Catatan Pesanan (optional)
- Metode Pembayaran: Manual/Kasir, WhatsApp, Online (Duitku - jika aktif)
- Ringkasan pesanan (sticky sidebar): item list, subtotal, ongkir (jika delivery), total

**POST /checkout — Alur:**
1. Validate form
2. Loop cart, resolve harga dari DB
3. Hitung subtotal + delivery_fee (setting `delivery_fee`, hanya untuk delivery)
4. Buat `Order` dengan `generateOrderNumber()`
5. Buat `OrderItem` per item (snapshot nama & harga dari DB)
6. **Routing pembayaran:**
   - `whatsapp` → redirect ke `wa.me/{nomor}?text={pesan formatted}`
   - `duitku` → buat transaksi via DuitkuService → redirect ke payment URL
   - `manual` → redirect ke `/order/status/{orderNumber}`
7. Kosongkan cart session

**Format Pesan WhatsApp:**
```
🛒 *PESANAN BARU - TENCOFFE*
━━━━━━━━━━━━━━━━━━━
📋 No. Pesanan: *TEN260222-0001*
👤 Nama: John Doe
📱 HP: 08123456789
📧 Email: john@email.com
📍 Tipe: Pickup

*Detail Pesanan:*
• Americano (Hot) x2 = Rp 30.000
• Croissant x1 = Rp 25.000

━━━━━━━━━━━━━━━━━━━
Subtotal: Rp 55.000
*TOTAL: Rp 55.000*

📝 Catatan: Gula sedikit

Terima kasih! 🙏
```

---

### 4.5 Order Status Page (`GET /order/status/{orderNumber}`)

**Controller:** `OrderController@status`
**View:** `resources/views/order-status.blade.php`

- Tampil detail pesanan setelah checkout
- Informasi pelanggan, items, total, status dengan warna

---

### 4.6 Order Tracking Page (`GET /order/track`)

**Controller:** `OrderController@track`, `OrderController@trackSearch`
**View:** `resources/views/order-track.blade.php`

- Form input nomor pesanan
- POST → cari order → tampil detail atau "tidak ditemukan"

---

### 4.7 Contact Page (`GET /contact`)

**Controller:** `ContactController@index`
**View:** `resources/views/contact.blade.php`

- Tampil info dari settings: nama toko, telepon, email, alamat, jam operasional
- Link sosial media: WhatsApp, Instagram, TikTok

---

## 5. Admin Panel

Semua route admin diawali `/admin` dan dilindungi middleware `AdminMiddleware`.

**Sidebar Menu Admin (urutan):**
1. Dashboard
2. Produk
3. Kategori
4. Pesanan
5. Banner
6. Event Spesial
7. Pengaturan
8. Lihat Website (link ke home)

---

### 5.1 Login (`GET /admin/login`)

**Controller:** `Admin\AuthController`
- Form email + password
- Validasi role admin setelah `auth()->attempt()`
- Regenerate session
- Redirect ke dashboard

---

### 5.2 Dashboard (`GET /admin/dashboard`)

**Controller:** `Admin\DashboardController@index`

**Statistik yang ditampilkan:**
- Pesanan hari ini (count)
- Pendapatan hari ini (sum total dari orders completed/paid)
- Pendapatan bulan ini
- Total produk aktif
- Total kategori
- Pesanan pending (count)
- **Pesanan terbaru**: 10 order terakhir dengan detail items

---

### 5.3 Produk (CRUD) (`/admin/products`)

**Controller:** `Admin\ProductController` (resource except `show`)
**Views:** `admin/products/index.blade.php`, `admin/products/form.blade.php`

**Index:**
- Pencarian by nama
- Filter by kategori (dropdown)
- Pagination: 10/25/50/semua per halaman
- Tabel: gambar, nama, kategori, harga (hot/cold jika variant), status badges, aksi (edit/hapus)

**Form Create/Edit:**
- Nama (required)
- Kategori (dropdown, required)
- Deskripsi (textarea)
- Harga (required, int)
- Toggle `has_variants` → tampil input `price_hot` dan `price_cold` (Alpine.js), sembunyikan input `price` biasa
- Upload gambar (max 2MB, preview)
- Checkboxes: Aktif, Featured, Baru, Promo, Seasonal
- Jika promo: tampil input `promo_price`
- Jika seasonal: tampil input `seasonal_label`
- Tombol hapus gambar

**Logika simpan variant:**
- Jika `has_variants` = true → `price` = `min(price_hot, price_cold)`
- Jika `has_variants` = false → `price_hot` = null, `price_cold` = null

---

### 5.4 Kategori (CRUD) (`/admin/categories`)

**Controller:** `Admin\CategoryController` (resource, inline forms)
**View:** `admin/categories/index.blade.php`

- List dengan jumlah produk per kategori
- Inline form create/edit (Alpine.js toggle)
- Fields: nama, deskripsi, gambar, is_active
- Auto-generate slug dari nama (`Str::slug()`)
- Tidak bisa hapus jika masih ada produk di kategori tersebut

---

### 5.5 Pesanan (`/admin/orders`)

**Controller:** `Admin\OrderController`
**Views:** `admin/orders/index.blade.php`, `admin/orders/show.blade.php`

**Index:**
- Pencarian: no. pesanan, nama, telepon
- Filter by status (dropdown)
- Pagination 15 per halaman
- Tabel: no. pesanan (link ke detail), pelanggan (nama + HP), tipe, total, metode bayar, status badge, tanggal, aksi
- **Aksi per row:** tombol lihat (mata) + tombol hapus (tempat sampah merah) dengan konfirmasi

**Detail (show):**
- Info pelanggan lengkap
- List item pesanan dengan harga & qty
- Ringkasan: subtotal, ongkir, total
- **Update status**: dropdown + tombol Update
  - Status: Pending → Paid → Processing → Ready → Completed / Cancelled
  - Set `paid_at` otomatis saat status diubah ke `paid`
- Tombol WhatsApp → buka `wa.me/{phone}`
- **Tombol Hapus Pesanan** (merah, konfirmasi) → hapus order + items

---

### 5.6 Banner (CRUD) (`/admin/banners`)

**Controller:** `Admin\BannerController` (resource, inline forms)
**View:** `admin/banners/index.blade.php`

- Inline create/edit
- Fields: title, subtitle, gambar (required, max 4MB), link URL, is_active, sort_order
- Preview gambar dalam card

---

### 5.7 Event Spesial (`/admin/special-event`)

**Controller:** `Admin\SpecialEventController`
**View:** `admin/special-event/index.blade.php`

**3 section di halaman ini:**

1. **Pengaturan Event:**
   - Checkbox: Tampilkan Event Spesial di Home
   - Emoji (untuk badge)
   - Teks Badge (cth: "Ramadhan Kareem")
   - Judul Event (cth: "Menu Ramadhan")
   - Deskripsi
   - Tersimpan di `settings` table (group `special_event`)

2. **Upload Gambar:**
   - Batch upload (multiple files, max 2MB each)
   - Judul / Nama Paket
   - **Harga Paket** (opsional) → jika diisi, customer bisa langsung order (masuk cart sebagai event item)
   - Gambar disimpan di `public/images/special-event/`

3. **Gallery Gambar:**
   - Grid card per gambar
   - Tampil: gambar, judul, harga (jika ada), status aktif/nonaktif
   - Edit inline: ubah judul, harga, ganti gambar, toggle aktif
   - Hapus dengan konfirmasi
   - Preview section di bawah (tampilan seperti di homepage)

---

### 5.8 Pengaturan (`/admin/settings`)

**Controller:** `Admin\SettingController`
**View:** `admin/settings/index.blade.php`

**Groups & Keys:**

| Group | Key | Label | Tipe |
|-------|-----|-------|------|
| `general` | `site_name` | Nama Toko | text |
| `general` | `tagline` | Tagline | text |
| `general` | `email` | Email | text |
| `general` | `phone` | Telepon | text |
| `general` | `phone2` | Telepon 2 | text |
| `general` | `address` | Alamat | text |
| `general` | `operating_hours` | Jam Operasional | text |
| `social` | `instagram` | Instagram | text |
| `social` | `tiktok` | TikTok | text |
| `social` | `whatsapp` | WhatsApp | text |
| `order` | `store_whatsapp` | WhatsApp Pesanan | text |
| `order` | `min_order` | Min. Order | number |
| `order` | `delivery_fee` | Ongkos Kirim | number |
| `payment` | `duitku_mode` | Mode Duitku | text |
| `payment` | `duitku_merchant_code` | Merchant Code | text |
| `payment` | `duitku_api_key` | API Key | text |
| `payment` | `duitku_enabled` | Enable Duitku | boolean |

Form bulk update: semua key ditampilkan per group dengan input sesuai tipe.

---

## 6. Sistem Keranjang & Checkout

### Alur Lengkap:

```
Customer buka Menu → Pilih produk/variant → Klik "+" add to cart
    ↓ (AJAX POST /cart/add atau /cart/add-event)
Badge keranjang di navbar update (CustomEvent 'cart-updated')
Toast notification muncul ("Americano (Hot) ditambahkan ke keranjang")
    ↓
Customer buka /cart → Lihat items, adjust qty, hapus
    ↓
Klik "Checkout" → /checkout
    ↓
Isi form: nama, HP, tipe pesanan, alamat (jika delivery), catatan
Pilih metode bayar → Klik tombol
    ↓
┌─ WhatsApp → Redirect wa.me dengan pesan formatted
├─ Duitku   → Redirect ke payment URL Duitku
└─ Manual   → Redirect ke /order/status/{orderNumber}
    ↓
Cart dikosongkan, Order + OrderItems tersimpan di database
```

### Anti-Tamper:
- Harga **SELALU** di-resolve dari database saat checkout, bukan dari session
- Session hanya menyimpan: product_id, quantity, variant (untuk produk biasa)
- Event items menyimpan harga di session karena gallery price bisa berubah

### Cart Badge Navbar:
- Endpoint `GET /cart/count` mengembalikan `{ count: N }`
- Dipanggil saat page load
- Diupdate via `CustomEvent('cart-updated', { detail: { count: N } })`

### Toast Notification:
- Alpine.js component di layout
- Trigger: `CustomEvent('toast', { detail: { message: '...' } })`
- Auto-dismiss setelah beberapa detik

---

## 7. Integrasi Payment Gateway (Duitku)

### Service: `app/Services/DuitkuService.php`

### Config: `config/duitku.php`
```php
return [
    'mode' => env('DUITKU_MODE', 'sandbox'),
    'merchant_code' => env('DUITKU_MERCHANT_CODE', ''),
    'api_key' => env('DUITKU_API_KEY', ''),
    'callback_url' => env('DUITKU_CALLBACK_URL', '/api/duitku/callback'),
    'return_url' => env('DUITKU_RETURN_URL', '/order/status'),
    'expiry_period' => env('DUITKU_EXPIRY_PERIOD', 1440),

    'urls' => [
        'sandbox' => [
            'inquiry' => 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry',
            'check' => 'https://sandbox.duitku.com/webapi/api/merchant/transactionStatus',
            'payment_methods' => 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
        ],
        'production' => [
            'inquiry' => 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry',
            'check' => 'https://passport.duitku.com/webapi/api/merchant/transactionStatus',
            'payment_methods' => 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
        ],
    ],
];
```

### Method Kunci:
- `isEnabled()` → cek setting DB atau config
- `getPaymentMethods($amount)` → GET available methods
- `createTransaction($order, $paymentMethod)` → V2 inquiry, simpan reference/URL ke order
- `validateCallback($data)` → validasi signature: `md5(merchantCode + amount + merchantOrderId + apiKey)`
- `checkTransaction($merchantOrderId)` → cek status

### Callback: `POST /api/duitku/callback`
- **CSRF-exempt** (daftar di middleware)
- `resultCode === '00'` → status = `paid`, payment_status = `paid`, paid_at = now
- Lainnya → status = `cancelled`

### Toggle:
- Setting `duitku_enabled` di admin → tampil/sembunyikan opsi pembayaran di checkout

---

## 8. Sistem Autentikasi

- **Admin only** — tidak ada registrasi/login customer
- **Middleware:** `AdminMiddleware` → cek `auth()->check() && auth()->user()->isAdmin()`
- Daftarkan di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['admin' => AdminMiddleware::class]);
    $middleware->validateCsrfTokens(except: ['api/duitku/callback']);
})
```
- Route group admin: `prefix('admin')->middleware(['auth', 'admin'])->as('admin.')`

---

## 9. Sistem File Upload

| Entity | Disk | Max Size | Path Simpan | Format |
|--------|------|----------|-------------|--------|
| Products | `public` | 2MB | `products/` | jpg, png, webp |
| Categories | `public` | 2MB | `categories/` | jpg, png, webp |
| Banners | `public` | 4MB | `banners/` | jpg, png, webp |
| Galleries | `public` | 4MB | `galleries/` | jpg, png, webp |
| Event Images | Direct `public_path` | 2MB | `public/images/special-event/` | jpg, png, webp |

### Image URL Resolution (di Model accessor `image_url`):
```php
public function getImageUrlAttribute(): string
{
    if (!$this->image) return asset('images/no-product.svg');
    
    // 1. Cek public/images/ (seeded/static)
    if (file_exists(public_path('images/' . $this->image)))
        return asset('images/' . $this->image);
    
    // 2. Cek public/storage/ (uploaded via admin)
    if (file_exists(public_path('storage/' . $this->image)))
        return asset('storage/' . $this->image);
    
    return asset('images/no-product.svg');
}
```

### Cleanup:
- Saat gambar di-replace: hapus file lama dari storage
- Saat entity dihapus: hapus file dari storage

---

## 10. Sistem Settings

### Model `Setting` — Helper Methods:

```php
// Ambil satu setting
$value = Setting::get('site_name', 'Default');

// Simpan/update setting
Setting::set('site_name', 'TenCoffe', 'text', 'general');

// Ambil semua setting dalam satu group
$general = Setting::getGroup('general');
// Returns: ['site_name' => 'TenCoffe', 'phone' => '08xx...', ...]
```

Settings dipakai di:
- Layout (navbar, footer): nama toko, telepon, sosmed
- Home: event spesial config
- Checkout: WhatsApp nomor, delivery fee
- Payment: Duitku credentials
- Contact: semua info toko

---

## 11. Desain & UI/UX

### Color Palette (Tailwind Custom):
Definisikan warna `coffee-*` di Tailwind config:
```
coffee-50 through coffee-900 — gradasi warna kopi/cokelat
```

### Layout Publik (`layouts/app.blade.php`):
- **Navbar fixed** di atas: logo, menu links (Home, Menu, Lacak, Kontak), cart badge, tombol hamburger mobile
- Navbar berubah transparan → solid saat scroll (Alpha.js + scroll event)
- **Footer**: logo + deskripsi, quick links, info kontak + sosmed
- **Mobile responsive**: hamburger menu, grid responsive

### Layout Admin (`layouts/admin.blade.php`):
- **Sidebar kiri** fixed (warna gelap coffee): logo + menu items dengan icon SVG
- Mobile: sidebar slide-in, tombol hamburger di header
- **Main content**: header dengan judul + link "Admin" + logout (responsive)
- **Card component**: `.card` class — `bg-white rounded-2xl shadow-sm`
- **Form components**: `.input-field`, `.btn-primary`, `.btn-outline`

### Komponen UI Berulang:
- Badge status pesanan (warna per status)
- Badge produk (Baru, Promo, Seasonal, Hot/Cold)
- Toast notification (Alpine.js)
- Lightbox image viewer (Alpine.js)
- Modal konfirmasi hapus (native `confirm()`)

---

## 12. Seeder Data Awal

### `DatabaseSeeder.php` harus membuat:

1. **1 Admin User**
   - Email: (email admin TenCoffe)
   - Password: `admin123` (bcrypt)
   - Role: `admin`

2. **5 Kategori:**
   - Main Course (sort 1)
   - Lite Bites (sort 2)
   - Breakfast (sort 3)
   - Coffee (sort 4)
   - Non-Coffee (sort 5)

3. **Produk per Kategori:**
   - Main Course: ~25-30 item (nama, harga, deskripsi)
   - Lite Bites: ~20 item
   - Breakfast: ~3-5 item
   - Coffee: ~20 item (dengan variant hot/cold, `has_variants = true`, `price_hot` & `price_cold`)
   - Non-Coffee: ~20 item (dengan variant hot/cold)
   - Beberapa ditandai `is_featured`, `is_new`
   - Slug auto-generate dari nama

4. **Settings (22+ key-value):**
   - Group `general`: site_name, tagline, email, phone, phone2, address, operating_hours
   - Group `social`: instagram, tiktok, whatsapp
   - Group `order`: store_whatsapp, min_order (0), delivery_fee (10000)
   - Group `payment`: duitku_mode, duitku_merchant_code, duitku_api_key, duitku_enabled
   - Group `special_event`: 5 keys (enabled, emoji, badge, title, subtitle) — default disabled

5. **Gambar menu** di `public/images/menu/` (opsional, seeder bisa set path gambar)

---

## 13. Deployment

### Struktur Server (DirectAdmin):
```
~/domains/tencoffe.com/
├── public_html/          ← Document root (symlinks ke public/)
│   ├── index.php         ← Modified paths: /../laravel/...
│   ├── build/            ← Symlink ke laravel/public/build/
│   ├── storage/          ← Symlink ke laravel/storage/app/public/
│   └── images/           ← Symlink ke laravel/public/images/
└── laravel/              ← Source code Laravel
    ├── .env
    ├── artisan
    ├── ...
```

### File `deploy.sh` (di root project):
```bash
#!/bin/bash
cd ~/domains/tencoffe.com/laravel

echo "Pulling latest changes..."
git pull origin main

echo "Running migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite

echo "Done!"
```

### File `update.bat` (lokal Windows):
```bat
@echo off
cd /d D:\APLIKASI\HERD\tencoffe
git add -A
set /p msg="Commit message: "
git commit -m "%msg%"
git push origin main
echo Done! Now run ./deploy.sh on server
pause
```

### Penting untuk Deployment:
- `.env` di server **JANGAN** masuk git (ada di `.gitignore`)
- `APP_KEY` harus satu baris saja di `.env`, jangan sampai duplikat
- Storage symlink: `php artisan storage:link`
- Build assets lokal: `npm run build` sebelum push
- Jangan cache config di server jika ada masalah key

---

## 14. Checklist Implementasi

### Phase 1 — Foundation
- [ ] Install Laravel 12, setup SQLite database
- [ ] Setup Tailwind CSS v4 + Alpine.js + Vite
- [ ] Definisikan custom color palette (coffee-*)
- [ ] Buat semua migration (9 tabel + framework tables)
- [ ] Buat semua Model dengan relationships, scopes, accessors
- [ ] Buat AdminMiddleware
- [ ] Setup routes (web.php)

### Phase 2 — Admin Panel
- [ ] Layout admin (sidebar + main content)
- [ ] Admin login/logout
- [ ] Dashboard dengan statistik
- [ ] CRUD Kategori (inline form)
- [ ] CRUD Produk (form page, variant hot/cold, promo, seasonal)
- [ ] CRUD Banner (inline form)
- [ ] CRUD Event Spesial (settings + gallery + harga mandiri)
- [ ] Pengaturan (bulk update key-value)
- [ ] Kelola Pesanan (list, detail, update status, hapus)

### Phase 3 — Halaman Publik
- [ ] Layout publik (navbar fixed + footer)
- [ ] Home page (hero, banner, featured, kategori, seasonal, event spesial, CTA)
- [ ] Menu page (filter kategori, grid produk, variant selector, gradual loading)
- [ ] Contact page (info dari settings)

### Phase 4 — Cart & Checkout
- [ ] Cart system (session-based, add/update/remove/count)
- [ ] Cart page (list items, qty controls, summary)
- [ ] Checkout page (form + order creation)
- [ ] WhatsApp integration (formatted message + redirect)
- [ ] Order status page
- [ ] Order tracking page

### Phase 5 — Payment & Polish
- [ ] Duitku payment gateway integration (opsional)
- [ ] Toast notification system
- [ ] Responsive testing (mobile, tablet, desktop)
- [ ] Seeder data awal
- [ ] Build assets (npm run build)
- [ ] Deployment setup (deploy.sh, update.bat)

---

> **Catatan:** Dokumen ini sudah mencakup SEMUA fitur yang ada di aplikasi referensi.
> Tinggal ganti nama "TenCoffe", warna tema, logo, dan data produk sesuai kebutuhan.
> Struktur kode, alur, dan logika bisa diikuti 1:1.
