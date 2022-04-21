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

        /**
         * List all available positions.
         * 
         * @return view
         */
        public function listPositions()
        {
            $pageTitle = "Edytor formularza";

            $positions = Position::all();

            return view('dashboard.positions', [
                'pageTitle' => $pageTitle,
                'positions' => $positions
            ]);
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

            Position::create([
                'position_name' => $request->position_name,
                'zones_list' => $request->zones_list
            ]);

            return back()->with('message', "Stanowisko zostało utworzone.");

        }

        /**
         * Update existing position with new data.
         * 
         * @param Request $request
         * @return view
         */
        public function update(Request $request)
        {            
            $position = Position::find($request->save);
            $position->position_name = $request->position_name;

            $position->isDirty('position_name') == true ? $request->validate(['position_name' => 'required|unique:Positions']) : null;

            $position->zones_list = $request->zones_list;
            
            $position->save();

            return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
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

            return back()->with('message', "Stanowisko $positionName zostało usunięte.");
        }
    }
    