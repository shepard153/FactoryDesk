<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function listSettings()
    {
        $pageTitle = 'Ustawienia globalne';

        $settings = Settings::pluck('value', 'name')->all();

        return view('dashboard/settings', ['pageTitle' => $pageTitle, 'settings' => $settings]);
    }

    static public function getSettings() {
        $settings = Cache::remember('settings', 60, function() {
            return Settings::pluck('value', 'name')->all();
        });
        return $settings;
    }

    public function setSettings(Request $request)
    {
        $modified_by = auth::user()->login;

        Settings::where('name', 'dashboard_refreshTime')
            ->update(['value' => $request->dashboard_refreshTime, 'modified_by' => $modified_by]);
        Settings::where('name', 'dashboard_newestToDisplay')
            ->update(['value' => $request->dashboard_newestToDisplay, 'modified_by' => $modified_by]);

        Cache::forget('settings');

        return back()->with('message', 'Dane zostały zaktualizowane.');
    }
}
