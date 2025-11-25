<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    public function index()
    {
        $setting = StoreSetting::first() ?? new StoreSetting();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'theme_color' => 'required|string|max:7',
            'banner_image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'store_status' => 'required|in:open,closed'
        ]);

        $setting = StoreSetting::first();

        if (!$setting) {
            $setting = new StoreSetting();
        }

        if ($request->hasFile('banner_image')) {
            if ($setting->banner_image) {
                Storage::disk('public')->delete($setting->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $setting->fill($validated);
        $setting->save();

        return back()->with('success', 'Settings updated successfully!');
    }
}
