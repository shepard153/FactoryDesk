<?php

    namespace app\Http\Controllers;

    use App\Models\Department;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    
    Class DepartmentController
    {
        protected $department;

        function __construct(Department $department)
        {
            $this->department = $department;
        }

        /**
         * Get departments for first step when raising new ticket.
         * 
         * @return view
         */
        function getDepartments()
        {
            $departments = $this->department::all();

            return view ('ticket/raise_ticket')->with('departments', $departments);
        }

        /**
         * List all departments.
         * 
         * @return view
         */
        function listDepartments()
        {
            $pageTitle = "Edytor formularza";
            $departments = $this->department::all();

            return view('dashboard/departments', [
                'pageTitle' => $pageTitle,
                'departments' => $departments]);
        }

        /**
         * Create new department. If no image is provided, placeholder will be generated.
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
                $fileName = str_replace(" ", "-",$request->department_name);
                $filePath = $file->storeAs('departments_img', "department-$fileName.". $file->getClientOriginalExtension());
                $filePath = "departments_img/department-$fileName." . $file->getClientOriginalExtension();
            }
            else{
                $filePath = null;
            }       

            Department::create([
                'department_name' => $request->department_name,
                'image_path' => $filePath
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
            $request->validate(['department_name' => 'required|unique:Departments']);

            $this->department = Department::find($departmentID);
            $this->department->department_name = $request->department_name;
            $this->department->image_path = $request->image;
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

            return back()->with('message', "Konto użytkownika $departmentName zostało usunięte.");
        }

        /**
         * Generate view for new department form.
         * 
         * @return view
         */
        function addDepartment()
        {
            $pageTitle = "Edytor formularza";

            return view('dashboard/add_department', [
                'pageTitle' => $pageTitle,]);
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

            $this->department = $this->department::find($departmentID);

            return view('dashboard/edit_department', [
                'pageTitle' => $pageTitle,
                'department' => $this->department]);
        }
    }
    