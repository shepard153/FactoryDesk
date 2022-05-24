<?php

    namespace app\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Department;
    use App\Models\Problem;

    Class ProblemController extends Controller
    {

        /**
         * List all available problems.
         *
         * @return view
         */
        public function listProblems()
        {
            $pageTitle = "Edytor formularza";

            $problems = Problem::orderBy('lp', 'asc')->get();

            return view('dashboard.problems', [
                'pageTitle' => $pageTitle,
                'problems' => $problems
            ]);
        }

        /**
         * Ajax request to get available problem based on chosen position and department.
         *
         * @param Position $position
         * @param string $department
         * @param string $positionName
         * @return array $problems
         */
        public function ajaxProblemsRequest($department, $positionName)
        {
            $problems = Problem::where('departments_list', 'LIKE', "%$department%")->where('positions_list', 'LIKE', "%$positionName%")->orderBy('lp', 'asc')->get();
            return json_encode($problems);
        }

        /**
         * Create new problem for given position and department.
         *
         * @param Request $request
         * @return view
         */
        public function create(Request $request)
        {
            $request->validate(['problem_name' => 'required|unique:Problems']);

            $positions = [];

            foreach($request->request->all() as $key => $value){
                if ($key != "_token" && $key != "problem_name" && $key != "lp" && $key != "departments_list" && $key != "confirmCreate"){
                    $positions[] = str_replace('_', ' ', $key);
                }
            }

            $positions = implode(', ', $positions);

            Problem::create([
                'problem_name' => $request->problem_name,
                'positions_list' => $positions,
                'departments_list' => $request->departments_list,
                'lp' => $request->lp,
            ]);

            return back()->with('message', "Problem został utworzony.");
        }

        /**
         * Update existing problem with new data.
         *
         * @param Request $request
         * @return view
         */
        public function update(Request $request)
        {
            $problem = Problem::find($request->confirmEdit);
            $problem->problem_name = $request->problem_name;
            $problem->lp = $request->lp;

            $problem->isDirty('problem_name') == true ? $request->validate(['problem_name' => 'required|unique:Problems']) : null;

            foreach($request->request->all() as $key => $value){
                if ($key != "_token" && $key != "problem_name" && $key != "lp" && $key != "departments_list" && $key != "confirmEdit"){
                    $positions[] = str_replace('_', ' ', $key);
                }
            }

            $positions = implode(', ', $positions);

            $problem->positions_list = $positions;
            $problem->departments_list = $request->departments_list;

            $problem->save();

            return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
        }

        /**
         * Delete existing problem.
         *
         * @return view
         */
        public function delete(Request $request)
        {
            $problem = Problem::find($request->confirmDelete);
            $problemName = $request->problem_name;
            $problem->delete();

            return back()->with('message', "Problem $problemName został usunięty.");
        }
    }
