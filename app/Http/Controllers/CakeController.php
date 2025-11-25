<?php

namespace App\Http\Controllers;

use App\Models\Cake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CakeController extends Controller
{
    public function index()
    {
        $cakes = Cake::orderBy('created_at', 'desc')->get();
        return view('admin.cakes.index', compact('cakes'));
    }

    public function create()
    {
        return view('admin.cakes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'nullable|string',
            'status' => 'required|in:available,unavailable'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('cakes', 'public');
        }

        Cake::create($validated);

        return redirect()->route('cakes.index')->with('success', 'Cake added successfully!');
    }

    public function edit(Cake $cake)
    {
        return view('admin.cakes.edit', compact('cake'));
    }

    public function update(Request $request, Cake $cake)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'nullable|string',
            'status' => 'required|in:available,unavailable'
        ]);

        if ($request->hasFile('image')) {
            if ($cake->image) {
                Storage::disk('public')->delete($cake->image);
            }
            $validated['image'] = $request->file('image')->store('cakes', 'public');
        }

        $cake->update($validated);

        return redirect()->route('cakes.index')->with('success', 'Cake updated successfully!');
    }

    public function destroy(Cake $cake)
    {
        if ($cake->image) {
            Storage::disk('public')->delete($cake->image);
        }

        $cake->delete();
        return redirect()->route('cakes.index')->with('success', 'Cake deleted successfully!');
    }
}
