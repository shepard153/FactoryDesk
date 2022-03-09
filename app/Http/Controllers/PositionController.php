<?php

    namespace app\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Department;
    use App\Models\Position;

    Class PositionController extends Controller
    {
        protected $department;

        function __construct(Department $department)
        {
            $this->department = $department;
        }

        public function listPositions()
        {
            $pageTitle = "Edytor formularza";

            $positions = Position::all();

            return view('dashboard.positions', [
                'pageTitle' => $pageTitle,
                'positions' => $positions
            ]);
        }

        public function create(Request $request)
        {
            $request->validate(['position_name' => 'required|unique:Positions']);

            Position::create([
                'position_name' => $request->position_name,
                'zones_list' => $request->zones_list
            ]);

            return back()->with('message', "Stanowisko zostało utworzone.");

        }

        public function update(Request $request)
        {            
            $position = Position::find($request->save);
            $position->position_name = $request->position_name;

            $position->isDirty('position_name') == true ? $request->validate(['position_name' => 'required|unique:Positions']) : null;

            $position->zones_list = $request->zones_list;
            
            $position->save();

            return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
        }

        public function delete(Request $request)
        {
            $position = Position::find($request->confirmDelete);
            $positionName = $request->position_name;
            $position->delete();

            return back()->with('message', "Stanowisko $positionName zostało usunięte.");
        }
    }
    