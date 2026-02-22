#!/bin/bash
# ============================================
# TenCoffe Deployment Script for DirectAdmin
# Shared Hosting (tencoffe.com)
# ============================================
# Jalankan script ini di SSH terminal server:
#   bash deploy.sh
# ============================================

set -e

echo "========================================="
echo "  TenCoffe Deployment Script"
echo "========================================="

APP_DIR="$HOME/tencoffe"
PUBLIC_DIR="$HOME/domains/tencoffe.com/public_html"

# 1. Buat direktori aplikasi
echo "[1/8] Membuat direktori aplikasi..."
mkdir -p "$APP_DIR"

# 2. Extract app files
echo "[2/8] Extracting tencoffe-app.zip..."
cd "$APP_DIR"
unzip -o "$HOME/tencoffe-app.zip"

# 3. Extract public files  
echo "[3/8] Extracting tencoffe-public.zip ke public_html..."
cd "$PUBLIC_DIR"
# Backup existing files
if [ -f "index.html" ]; then
    mv index.html index.html.bak
fi
unzip -o "$HOME/tencoffe-public.zip"

# 4. Replace index.php with production version
echo "[4/8] Mengganti index.php dengan versi production..."
cp "$APP_DIR/deploy/public_html/index.php" "$PUBLIC_DIR/index.php"

# 5. Setup .env
echo "[5/8] Setup environment file..."
cp "$APP_DIR/deploy/.env.production" "$APP_DIR/.env"
cd "$APP_DIR"
php artisan key:generate

# 6. Setup database
echo "[6/8] Setup database SQLite..."
touch "$APP_DIR/database/database.sqlite"
php artisan migrate --seed --force

# 7. Set permissions
echo "[7/8] Setting permissions..."
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"
chmod 664 "$APP_DIR/database/database.sqlite"

# 8. Create storage symlink
echo "[8/8] Creating storage link..."
ln -sf "$APP_DIR/storage/app/public" "$PUBLIC_DIR/storage"

# Clear & optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "========================================="
echo "  ✅ Deployment Berhasil!"
echo "========================================="
echo "  URL: https://tencoffe.com"
echo "  Admin: https://tencoffe.com/admin/login"
echo "  Email: admin@tencoffe.com"
echo "  Password: admin123"
echo "========================================="
echo ""
echo "⚠️  PENTING: Segera ganti password admin setelah login!"
