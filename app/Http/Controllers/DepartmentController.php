<?php

    namespace app\Http\Controllers;

    use App\Models\Department;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    Class DepartmentController
    {
        protected $department;

        /**
         * Get departments for first step when raising new ticket.
         *
         * @return view
         */
        function getDepartments()
        {
            $departments = Department::all();

            return view('ticket/raise_ticket')->with('departments', $departments);
        }

        /**
         * List all departments.
         *
         * @return view
         */
        function listDepartments()
        {
            $pageTitle = "Edytor formularza";
            $departments = Department::all();

            return view('dashboard/departments', [
                'pageTitle' => $pageTitle,
                'departments' => $departments]);
        }

        /**
         * Create new department. If no image is provided, placeholder will be inserted as alt <img> atribute.
         *
         * @param Request $request
         *
         * @return string
         */
        function create(Request $request)
        {
            $request->validate(['department_name' => 'required|unique:Departments']);

            if ($request->file('image') != null){
                $file = $request->file('image');
                $fileName = str_replace(" ", "-", $request->department_name);
                $filePath = $file->storeAs('departments_img', "department-$fileName." . $file->getClientOriginalExtension());
                $filePath = "departments_img/department-$fileName." . $file->getClientOriginalExtension();
            }
            else{
                $filePath = null;
            }

            $prefix = "";

            str_word_count($request->department_name) > 1 ? $words = explode(" ", $request->department_name) : $prefix = strtoupper(substr($request->department_name, 0, 1));

            if (isset($words)){
                foreach ($words as $word){
                    $prefix .= strtoupper(substr($word, 0, 1));
                }
            }

            $tokensArray = ['d', 'c', 'f', 'p', 'u', 'n'];

            $department = Department::where('department_prefix', '=', $prefix)->first();
            $departments = Department::all();
            $department != null ? $prefix .= $tokensArray[array_rand($tokensArray)] : null;

            foreach ($departments as $department){
                if ($department->department_prefix == $prefix){
                    $tokensArray = array_values(array_filter($tokensArray, fn ($m) => $m != $prefix));
                    $prefix .= $tokensArray[array_rand($tokensArray)];
                }
            }

            $acceptance_from = $request->acceptance != null ? $request->acceptance_from : null;

            $isHidden = $request->isHidden != null ? true : false;

            Department::create([
                'department_name' => $request->department_name,
                'image_path' => $filePath,
                'department_prefix' => $prefix,
                'acceptance_from' => $acceptance_from,
                'isHidden' => $isHidden,
                'teams_webhook' => $request->teams_webhook
            ]);

            return back()->with('message', "Dział został utworzony.");
        }

        /**
         * Update existing department with new data. When no new image is provided, the old one will be kept.
         *
         * @param Request $request
         * @param Department $departmentID
         * @return string
         */
        function update(Request $request, $departmentID)
        {
            $this->department = Department::find($departmentID);
            $this->department->department_name = $request->department_name;

            $this->department->isDirty('department_name') ? $request->validate(['department_name' => 'required|unique:Departments']) : null;

            if ($request->hasFile('image')){
                $file = $request->file('image');
                $fileName = str_replace(" ", "-",$request->department_name);
                $this->department->image_path = $file->storeAs('departments_img', "department-$fileName.". $file->getClientOriginalExtension());
                $this->department->image_path = "departments_img/department-$fileName." . $file->getClientOriginalExtension();
            }

            $this->department->acceptance_from = $request->acceptance != null ? $request->acceptance_from : null;
            $this->department->isHidden = $request->isHidden != null ? true : false;

            $this->department->teams_webhook = $request->teams_webhook;

            $this->department->save();

            return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
        }

        /**
         * Delete department.
         *
         * @param Request $request
         * @return string
         */
        function delete(Request $request)
        {
            $this->department = Department::find($request->confirmDelete);
            $departmentName = $this->department->department_name;
            $this->department->delete();

            return back()->with('message', "Dział $departmentName został usunięty.");
        }

        /**
         * Generate view for new department form.
         *
         * @return view
         */
        function addDepartment()
        {
            $pageTitle = "Edytor formularza";

            $departments = Department::all();

            return view('dashboard/add_department', [
                'pageTitle' => $pageTitle,
                'departments' => $departments
            ]);
        }

        /**
         * Generate view for edit department form.
         *
         * @param Department $departmentID
         * @return view
         */
        function editDepartment($departmentID)
        {
            $pageTitle = "Edytor formularza";

            $this->department = Department::find($departmentID);

            $departments = Department::all();

            return view('dashboard/edit_department', [
                'pageTitle' => $pageTitle,
                'department' => $this->department,
                'departments' => $departments
            ]);
        }
    }
