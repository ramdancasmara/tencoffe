<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->ordered()->get();
        $featuredProducts = Product::with('category')->active()->featured()->ordered()->take(8)->get();
        $categories = Category::active()->ordered()->withCount(['products' => fn($q) => $q->active()])->get();
        $seasonalProducts = Product::with('category')->active()->seasonal()->ordered()->take(4)->get();

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $specialEventEnabled = Setting::get('special_event_enabled', false);
        $specialEventSettings = Setting::getGroup('special_event');
        $specialEventImages = $specialEventEnabled
            ? Gallery::active()->ordered()->group('special_event')->get()
            : collect();

        return view('home', compact(
            'banners', 'featuredProducts', 'categories', 'seasonalProducts',
            'settings', 'specialEventSettings', 'specialEventEnabled', 'specialEventImages'
        ));
    }
}
