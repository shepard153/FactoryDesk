<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Staff;
use App\Models\Department;

class StaffController
{
    /**
     * @var Staff $member
     */
    protected $member;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'login' => 'required|max:100|unique:Staff',
            'email' => 'required|email|max:255|unique:Staff',
            'username' => 'required|max:255',
            'password' => 'required|min:8',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return string
     */
    public function create(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect('addMember')
                    ->withErrors($validator);
        }

        $isAdmin = $request->isAdmin == null ? 0 : 1;

        Staff::create([
            'name' => $request->username,
            'login' => $request->login,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department' => $request->departmentSelect,
            'isAdmin' => $isAdmin,
        ]);

        return back()->with('message', 'Konto użytkownika zostało utworzone.');
    }

    /**
     * Update existing member with new data.
     *
     * @param Request $request
     * @param int $staffID
     * @return string
     */
    public function update(Request $request, int $staffID)
    {
        $request->validate([
            'email' => 'email|max:255',
            'username' => 'max:255',
        ]);

        $isAdmin = $request->isAdmin == null ? 0 : 1;

        $this->member = Staff::find($staffID);
        $this->member->email = $request->email;
        $this->member->name = $request->username;
        $this->member->department = $request->departmentSelect;

        if ($request->password != null){
            $request->validate(['password' => 'min:8']);
            $this->member->password = bcrypt($request->password);
        }

        $this->member->isAdmin = $isAdmin;
        $this->member->save();

        return back()->with('message', 'Konto użytkownika zostało zaktualizowane.');
    }

    /**
     * Delete member account.
     *
     * @param Request $request
     * @return string $memberName
     */
    public function delete(Request $request)
    {
        $this->member = Staff::find($request->confirmDelete);
        $memberName = $this->member->name;
        $this->member->delete();

        return back()->with('message', "Konto użytkownika $memberName zostało usunięte.");
    }

    /**
     * Update authenticated member accout with new data.
     *
     * @param Request $request
     * @return string
     */
    public function modifySelfStaff(Request $request)
    {
        $this->member = Staff::find(auth()->user()->staffID);
        $this->member->email = $request->email;
        $request->validate(['email' => 'email|max:255']);

        if ($request->password == null){
            $this->member->save();
        }
        else{
            $request->validate([
                'password' => 'current_password:web',
                'newPassword' => 'required|min:8|confirmed']);
            $this->member->password = bcrypt($request->newPassword);
            $this->member->save();
        }

        return back()->with('message', "Wprowadzone zmiany zostały zapisane.");
    }

    /**
     * Render view with all staff members.
     *
     * @param Staff $staff
     * @return view
     */
    public function loadStaffList(Staff $staff)
    {
        $pageTitle = "Użytkownicy";

        $staffMembers = $staff::all();

        return view('dashboard/staff', [
            'pageTitle' => $pageTitle,
            'staffMembers' => $staffMembers]);
    }

    /**
     * Render view for member creation form.
     *
     * @param Department $departments
     * @return view
     */
    public function addMember(Department $departments)
    {
        $pageTitle = "Użytkownicy";

        $departments = $departments::all();

        return view('dashboard/add_member', [
            'pageTitle' => $pageTitle,
            'departments' => $departments]);
    }

    /**
     * Render view for edit member form.
     *
     * @param Staff $staff
     * @param Department $departments
     * @param int $staffID
     * @return view
     */
    public function editMember(Staff $staff, Department $departments, $staffID)
    {
        $pageTitle = "Użytkownicy";

        $staff = $staff::find($staffID);
        $departments = $departments::all();

        return view('dashboard/edit_member', [
            'pageTitle' => $pageTitle,
            'member' => $staff,
            'departments' => $departments]);
    }

    /**
     * Render view for profile settings.
     *
     * @return view
     */
    public function profileSettings()
    {
        $pageTitle = "Ustawienia konta";

        return view('dashboard.profile', [
            'pageTitle' => $pageTitle,
        ]);
    }
}
