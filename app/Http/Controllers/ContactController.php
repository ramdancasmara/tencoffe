<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class ContactController extends Controller
{
    public function index()
    {
        $general = Setting::getGroup('general');
        $social = Setting::getGroup('social');

        return view('contact', compact('general', 'social'));
    }
}
