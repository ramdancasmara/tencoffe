{{-- Product Card Component --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1 group"
     x-data="{ variant: '{{ $product->has_variants ? 'hot' : '' }}', price: {{ $product->display_price }} }">
    {{-- Image --}}
    <div class="relative overflow-hidden aspect-square">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if($product->is_new)
                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Baru</span>
            @endif
            @if($product->is_promo)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Promo</span>
            @endif
            @if($product->is_seasonal)
                <span class="bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $product->seasonal_label ?? 'Seasonal' }}</span>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div class="p-4">
        {{-- Category Label --}}
        @if($product->category)
            <span class="text-xs font-medium text-coffee-400 uppercase tracking-wide">{{ $product->category->name }}</span>
        @endif

        <h3 class="font-bold text-coffee-800 mt-1 text-sm leading-tight">{{ $product->name }}</h3>

        {{-- Variant Toggle --}}
        @if($product->has_variants)
            <div class="flex gap-1 mt-2">
                <button @click="variant='hot'; price={{ $product->price_hot ?? $product->price }}"
                        :class="variant==='hot' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600'"
                        class="text-xs font-bold px-3 py-1 rounded-full transition">🔥 Hot</button>
                <button @click="variant='cold'; price={{ $product->price_cold ?? $product->price }}"
                        :class="variant==='cold' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600'"
                        class="text-xs font-bold px-3 py-1 rounded-full transition">❄️ Cold</button>
            </div>
        @endif

        {{-- Price --}}
        <div class="flex items-center justify-between mt-3">
            <div>
                @if($product->is_promo && $product->promo_price)
                    <span class="text-xs text-gray-400 line-through">{{ $product->formatted_original_price }}</span>
                @endif
                @if($product->has_variants)
                    <p class="text-coffee-600 font-bold" x-text="'Rp ' + price.toLocaleString('id-ID')"></p>
                @else
                    <p class="text-coffee-600 font-bold">{{ $product->formatted_price }}</p>
                @endif
            </div>

            {{-- Add to Cart --}}
            <button @click="fetch('/cart/add', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({product_id: {{ $product->id }}, variant: variant || null})}).then(r=>r.json()).then(d=>{if(d.success){window.dispatchEvent(new CustomEvent('cart-updated',{detail:{count:d.count}}));window.dispatchEvent(new CustomEvent('toast',{detail:{message:d.message}}))}})"
                    class="w-9 h-9 bg-coffee-600 hover:bg-coffee-700 text-white rounded-full flex items-center justify-center transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </button>
        </div>
    </div>
</div>
