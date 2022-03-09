<?php

    namespace app\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Department;
    use App\Models\Zone;

    Class ZoneController extends Controller
    {
        public function listZones()
        {
            $pageTitle = "Edytor formularza";

            $zones = Zone::all();

            return view('dashboard.zones', [
                'pageTitle' => $pageTitle,
                'zones' => $zones
            ]);
        }

        public function create(Request $request)
        {
            $request->validate(['zone_name' => 'required|unique:Zones']);

            Zone::create(['zone_name' => $request->zone_name]);

            return back()->with('message', "Obszar został utworzony.");

        }

        public function update(Request $request)
        {
            $request->validate(['zone_name' => 'required|unique:Zones']);
            
            $zone = Zone::find($request->save);
            $zone->zone_name = $request->zone_name;
            $zone->save();

            return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
        }

        public function delete(Request $request)
        {
            $zone = Zone::find($request->confirmDelete);
            $zoneName = $request->zone_name;
            $zone->delete();

            return back()->with('message', "Obszar $zoneName został usunięty");
        }
    }
    