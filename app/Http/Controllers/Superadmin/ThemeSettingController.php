<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class ThemeSettingController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;

        return view('superadmin.theme-settings.index', [
            'themeSettings' => $school->theme_settings ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
        ]);

        $school = Auth::user()->school;

        $school->update([
            'theme_settings' => [
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
            ],
        ]);

        return redirect()->back()->with('success', 'Theme settings updated successfully.');
    }
}
