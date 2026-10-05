<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageExperience;
use App\Models\HomepageFaq;
use App\Models\HomepagePost;
use App\Models\HomepageStat;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', [
            'settings' => $settings,
            'tab' => $request->query('tab', 'general'),
            'experiences' => HomepageExperience::orderBy('display_order')->orderBy('id')->get(),
            'stats' => HomepageStat::orderBy('display_order')->orderBy('id')->get(),
            'posts' => HomepagePost::orderBy('display_order')->orderBy('id')->get(),
            'faqs' => HomepageFaq::orderBy('display_order')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
