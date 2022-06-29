<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Zone;

class ZoneController extends Controller
{
    /**
     * Ajax request to get available zones.
     *
     * @return JsonResponse $zones
     */
    public function ajaxZonesRequest()
    {
        $zones = Zone::all();
        return json_encode($zones);
    }

    /**
     * Create new zone for given department.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $request->validate(['zone_name' => 'required|unique:Zones']);

        Zone::create(['zone_name' => $request->zone_name]);

        return back()->with('message', __('dashboard_editor.zone_created') );
    }

    /**
     * Update existing zone with new data.
     *
     * @param Request @request
     * @return view
     */
    public function update(Request $request)
    {
        $request->validate(['zone_name' => 'required|unique:Zones']);

        $zone = Zone::find($request->save);
        $zone->zone_name = $request->zone_name;
        $zone->save();

        return back()->with('message', __('dashboard_editor.zone_updated') );
    }

    /**
     * Delete existing zone.
     *
     * @param Request $request
     * @return view
     */
    public function delete(Request $request)
    {
        $zone = Zone::find($request->confirmDelete);
        $zoneName = $request->zone_name;
        $zone->delete();

        return back()->with('message', __('dashboard_editor.zone_deleted'));
    }
}
