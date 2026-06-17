<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * Get all site settings formatted as key => value.
     */
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return response()->json([
            'success' => true,
            'data'    => $settings,
        ]);
    }

    /**
     * Get site setting by key.
     */
    public function show($key)
    {
        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $setting->value,
        ]);
    }

    /**
     * Update site setting by key.
     */
    public function update(Request $request, $key)
    {
        $request->validate([
            'value' => 'required|array',
        ]);

        $setting = SiteSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->input('value')]
        );

        return response()->json([
            'success' => true,
            'message' => "Setting '{$key}' updated successfully.",
            'data'    => $setting->value,
        ]);
    }
}
