<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Render view for settings page.
     *
     * @return view
     */
    public function listSettings()
    {
        $pageTitle = __('dashboard_settings.page_title');

        $settings = Settings::pluck('value', 'name')->all();

        return view('dashboard/settings', ['pageTitle' => $pageTitle, 'settings' => $settings]);
    }

    /**
     * Get global settings from DB and store them in cache for 60 minutes or until changes
     * are made.
     *
     * @return Settings $settings
     */
    static public function getSettings() {
        $settings = Cache::remember('settings', 60, function() {
            return Settings::pluck('value', 'name')->all();
        });
        return $settings;
    }

    /**
     * Update settings with new data and clear current settings cache.
     *
     * @param Request $request
     * @return Response
     */
    public function setSettings(Request $request)
    {
        $modified_by = auth::user()->login;

        Settings::where('name', 'dashboard_refreshTime')
            ->update(['value' => $request->dashboard_refreshTime, 'modified_by' => $modified_by]);
        Settings::where('name', 'dashboard_newestToDisplay')
            ->update(['value' => $request->dashboard_newestToDisplay, 'modified_by' => $modified_by]);
        Settings::where('name', 'dashboard_chartDaySpan')
            ->update(['value' => $request->dashboard_chartDaySpan, 'modified_by' => $modified_by]);
        Settings::where('name', 'tickets_pagination')
            ->update(['value' => $request->tickets_pagination, 'modified_by' => $modified_by]);

        Cache::forget('settings');

        return back()->with('message', __('dashboard_settings.settings_updated') );
    }
}
