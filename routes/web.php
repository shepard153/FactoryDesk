<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProblemController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DepartmentController::class, 'getDepartments']);
Route::get('ticket_step2/{department}', [TicketController::class, 'ticketRequest']);
Route::get('ticket_step2/ajax/zone/{id}', [TicketController::class, 'ajaxPositionsRequest']);
Route::get('ticket_step2/{department}/ajax/position/{id}', [TicketController::class, 'ajaxProblemsRequest']);
Route::post('sendTicket', [TicketController::class, 'sendTicket'])->name('sendTicket');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('loginAction', [LoginController::class, 'loginAction'])->name('loginAction');
Route::get('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    /**
     * Form Actions
     */
    Route::post('modifyTicketAction/{id}', [TicketController::class, 'modifyTicketAction'])->name('modifyTicketAction');
    Route::post('addNote/{id}', [TicketController::class, 'addNote'])->name('addNote');
    Route::post('addMemberAction', [StaffController::class, 'create'])->name('addMemberAction');
    Route::post('editMemberAction/{staffID}', [StaffController::class, 'update'])->name('editMemberAction');
    Route::post('deleteMemberAction', [StaffController::class, 'delete'])->name('deleteMemberAction');
    Route::post('modifySelfStaff', [StaffController::class, 'modifySelfStaff']);
    Route::post('addDepartmentAction', [DepartmentController::class, 'create'])->name('addDepartmentAction');
    Route::post('editDepartmentAction/{departmentID}', [DepartmentController::class, 'update'])->name('editDepartmentAction');
    Route::post('deleteDepartmentAction', [DepartmentController::class, 'delete'])->name('deleteDepartmentAction');
    Route::post('addZoneAction', [ZoneController::class, 'create'])->name('addZoneAction');
    Route::post('editZoneAction', [ZoneController::class, 'update'])->name('editZoneAction');
    Route::post('deleteZoneAction', [ZoneController::class, 'delete'])->name('deleteZoneAction');
    Route::post('addPositionAction', [PositionController::class, 'create'])->name('addPositionAction');
    Route::post('editPositionAction', [PositionController::class, 'update'])->name('editPositionAction');
    Route::post('deletePositionAction', [PositionController::class, 'delete'])->name('deletePositionAction');
    Route::post('addProblemAction', [ProblemController::class, 'create'])->name('addProblemAction');
    Route::post('editProblemAction', [ProblemController::class, 'update'])->name('editProblemAction');
    Route::post('deleteProblemAction', [ProblemController::class, 'delete'])->name('deleteProblemAction');

     /**
      * Views
      */
    Route::get('dashboard', [DashboardController::class, 'loadDashboard']);
    Route::get('tickets', [TicketController::class, 'ticketList']);
    Route::get('tickets/{status}', [TicketController::class, 'ticketListByStatus']);
    Route::get('ticket/{id}', [TicketController::class, 'ticketDetails']);
    Route::get('ticket/ajax/{department}', [TicketController::class, 'ajaxForTicketDetails']);
    Route::get('staff', [StaffController::class, 'loadStaffList']);
    Route::get('addMember', [StaffController::class, 'addMember']);
    Route::get('profile/staff', [StaffController::class, 'profileSettings']);
    Route::get('staff/{staffID}', [StaffController::class, 'editMember']);
    Route::get('departments', [DepartmentController::class, 'listDepartments']);
    Route::get('department/{departmentID}', [DepartmentController::class, 'editDepartment']);
    Route::get('addDepartment', [DepartmentController::class, 'addDepartment']);
    Route::get('zones', [ZoneController::class, 'listZones']);
    Route::get('positions', [PositionController::class, 'listPositions']);
    Route::get('problems', [ProblemController::class, 'listProblems']);
});
