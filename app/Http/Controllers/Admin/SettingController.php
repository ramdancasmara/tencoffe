<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $general = Setting::getGroup('general');
        $social = Setting::getGroup('social');
        $order = Setting::getGroup('order');
        $payment = Setting::getGroup('payment');

        return view('admin.settings.index', compact('general', 'social', 'order', 'payment'));
    }

    public function update(Request $request)
    {
        $settings = [
            'general' => ['site_name', 'tagline', 'email', 'phone', 'phone2', 'address', 'operating_hours'],
            'social' => ['instagram', 'tiktok', 'whatsapp'],
            'order' => ['store_whatsapp', 'min_order', 'delivery_fee'],
            'payment' => ['duitku_mode', 'duitku_merchant_code', 'duitku_api_key', 'duitku_enabled'],
        ];

        foreach ($settings as $group => $keys) {
            foreach ($keys as $key) {
                $value = $request->input($key, '');
                $type = in_array($key, ['min_order', 'delivery_fee']) ? 'number' :
                    (in_array($key, ['duitku_enabled']) ? 'boolean' : 'text');

                Setting::set($key, $value, $type, $group);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
