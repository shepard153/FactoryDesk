<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    /**
     * Ajax request to get available positions based on chosen zone.
     *
     * @param string $zoneName
     * @return JsonResponse $positions
     */
    public function ajaxPositionsRequest($zoneName = null)
    {
        if ($zoneName === null) {
            $positions = Position::all();
            return json_encode($positions);
        }
        else{
            $positions = Position::where('zones_list', 'LIKE', "%$zoneName%")->get();
            return json_encode($positions);
        }
    }

    /**
     * Create new position for the given zone.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $request->validate(['position_name' => 'required|unique:Positions']);

        $zones = implode(', ', $request->zones);

        Position::create([
            'position_name' => $request->position_name,
            'zones_list' => $zones
        ]);

        return back()->with('message', __('dashboard_editor.position_created'));
    }

    /**
     * Update existing position with new data.
     *
     * @param Request $request
     * @return view
     */
    public function update(Request $request)
    {
        $position = Position::find($request->confirmEdit);
        $position->position_name = $request->position_name;
        $position->isDirty('position_name') == true ? $request->validate(['position_name' => 'required|unique:Positions']) : null;

        $position->zones_list = implode(', ', $request->zones);

        $position->save();

        return back()->with('message', __('dashboard_editor.position_updated'));
    }

    /**
     * Delete existing position.
     *
     * @param Request $request
     * @return view
     */
    public function delete(Request $request)
    {
        $position = Position::find($request->confirmDelete);
        $positionName = $request->position_name;
        $position->delete();

        return back()->with('message', __('dashboard_editor.position_deleted'));
    }
}
