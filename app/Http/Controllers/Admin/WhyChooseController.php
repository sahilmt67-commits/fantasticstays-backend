<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChoose;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WhyChooseController extends Controller
{
    public const ICONS = ['sparkles', 'waves', 'map', 'headphones', 'shield', 'chef', 'gem', 'phone'];

    public function index()
    {
        $points = WhyChoose::orderBy('display_order')->orderBy('id')->get();

        return view('admin.why-choose.index', [
            'points' => $points,
            'icons' => self::ICONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => ['required', 'string', Rule::in(self::ICONS)],
            'display_order' => 'nullable|integer',
        ]);

        WhyChoose::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon' => $validated['icon'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return back()->with('success', 'Why Choose point created.');
    }

    public function update(Request $request, WhyChoose $whyChoose)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => ['required', 'string', Rule::in(self::ICONS)],
            'display_order' => 'nullable|integer',
        ]);

        $whyChoose->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon' => $validated['icon'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $whyChoose->display_order,
        ]);

        return back()->with('success', 'Why Choose point updated.');
    }

    public function destroy(WhyChoose $whyChoose)
    {
        $whyChoose->delete();

        return back()->with('success', 'Why Choose point deleted.');
    }
}
