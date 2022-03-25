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
         * Create new problem for given position and department.
         *
         * @param Request $request
         * @return view
         */
        public function create(Request $request)
        {
            $request->validate([
                'problem_name' => 'required|unique:Problems',
                'lp' => 'required|unique:Problems'
            ]);

            Problem::create([
                'problem_name' => $request->problem_name,
                'positions_list' => $request->positions_list,
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
            $problem = Problem::find($request->save);
            $problem->problem_name = $request->problem_name;
            $problem->lp = $request->lp;

            $problem->isDirty('problem_name') == true ? $request->validate(['problem_name' => 'required|unique:Problems']) : null;
            $problem->isDirty('lp') == true ? $request->validate(['lp' => 'required|unique:Problems']) : null;

            $problem->positions_list = $request->positions_list;
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
