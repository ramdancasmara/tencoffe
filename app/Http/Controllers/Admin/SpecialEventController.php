<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SpecialEventController extends Controller
{
    public function index()
    {
        $settings = Setting::getGroup('special_event');
        $galleries = Gallery::where('group', 'special_event')->ordered()->get();

        return view('admin.special-event.index', compact('settings', 'galleries'));
    }

    public function updateSettings(Request $request)
    {
        Setting::set('special_event_enabled', $request->boolean('enabled') ? '1' : '0', 'boolean', 'special_event');
        Setting::set('special_event_emoji', $request->emoji ?? '🌙', 'text', 'special_event');
        Setting::set('special_event_badge', $request->badge ?? 'Event Spesial', 'text', 'special_event');
        Setting::set('special_event_title', $request->title ?? 'Menu Spesial', 'text', 'special_event');
        Setting::set('special_event_subtitle', $request->subtitle ?? '', 'text', 'special_event');

        return redirect()->route('admin.special-event.index')->with('success', 'Pengaturan event berhasil disimpan.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|integer|min:0',
        ]);

        $dir = public_path('images/special-event');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($dir, $filename);

                Gallery::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'group' => 'special_event',
                    'price' => $request->price ?: null,
                    'image' => 'special-event/' . $filename,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.special-event.index')->with('success', 'Gambar berhasil diupload.');
    }

    public function updateGallery(Request $request, Gallery $gallery)
    {
        $data = $request->only(['title', 'description', 'price']);
        $data['is_active'] = $request->boolean('is_active', true);

        // Remove image
        if ($request->input('remove_image') === '1' && !$request->hasFile('image')) {
            $oldPath = public_path('images/' . $gallery->image);
            if (File::exists($oldPath)) File::delete($oldPath);
            $data['image'] = null;
        }

        // Upload new image
        if ($request->hasFile('image')) {
            // Delete old image
            $oldPath = public_path('images/' . $gallery->image);
            if (File::exists($oldPath)) File::delete($oldPath);

            $dir = public_path('images/special-event');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($dir, $filename);
            $data['image'] = 'special-event/' . $filename;
        }

        $gallery->update($data);
        return redirect()->route('admin.special-event.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroyGallery(Gallery $gallery)
    {
        $path = public_path('images/' . $gallery->image);
        if (File::exists($path)) File::delete($path);

        $gallery->delete();
        return redirect()->route('admin.special-event.index')->with('success', 'Gambar berhasil dihapus.');
    }
}
