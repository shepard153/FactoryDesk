<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketAttachmentController as AttachmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ReporterController;
use App\Http\Controllers\SettingsController;


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
Route::get('ticket_step2/ajax/zone/{id}', [PositionController::class, 'ajaxPositionsRequest']);
Route::get('ticket_step2/{department}/ajax/position/{id}', [ProblemController::class, 'ajaxProblemsRequest']);
Route::post('sendTicket', [TicketController::class, 'sendTicket'])->name('sendTicket');
Route::get('ticket_sent/{id}', [TicketController::class, 'ticketSent'])->name('ticketSent');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('loginAction', [LoginController::class, 'loginAction'])->name('loginAction');
Route::get('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    /**
     * Ajax Calls
     */
    Route::get('dashboard/ajax', [DashboardController::class, 'ajaxDashboardData']);
    Route::post('ticket/{id}/ajax/{timer}', [TicketController::class, 'ticketTimerAction'])->name('ticketTimerAction');
    Route::get('ticket/ajax/{department}', [TicketController::class, 'ajaxForTicketDetails']);
    Route::post('ticket/{id}/dropzoneUpload', [AttachmentController::class, 'dropzoneUpload'])->name('dropzoneUpload');
    Route::get('formEditor/ajax/zones', [ZoneController::class, 'ajaxZonesRequest']);
    Route::get('formEditor/ajax/positions', [PositionController::class, 'ajaxPositionsRequest']);
    Route::get('formEditor/ajax/departments', [DepartmentController::class, 'ajaxDepartmentsRequest']);

    /**
     * Form Actions
     */
    Route::post('paginationHelper', [TicketController::class, 'paginationHelper'])->name('paginationHelper');
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
    Route::post('getReport', [ReporterController::class, 'getReport'])->name('getReport');
    Route::post('setSettings', [SettingsController::class, 'setSettings'])->name('setSettings');

     /**
      * Views
      */
    Route::get('dashboard', [DashboardController::class, 'loadDashboard']);
    Route::get('my_tickets', [TicketController::class, 'memberTickets']);
    Route::get('my_tickets/{status}', [TicketController::class, 'memberTickets']);
    Route::get('tickets', [TicketController::class, 'ticketList']);
    Route::get('tickets/{status}', [TicketController::class, 'ticketList']);
    Route::get('ticket/{id}', [TicketController::class, 'ticketDetails']);
    Route::get('staff', [StaffController::class, 'loadStaffList']);
    Route::get('addMember', [StaffController::class, 'addMember']);
    Route::get('profile/staff', [StaffController::class, 'profileSettings']);
    Route::get('staff/{staffID}', [StaffController::class, 'editMember']);
    Route::get('departments', [DepartmentController::class, 'listDepartments']);
    Route::get('department/{departmentID}', [DepartmentController::class, 'editDepartment']);
    Route::get('addDepartment', [DepartmentController::class, 'addDepartment']);
    Route::get('formEditor', [EditorController::class, 'index']);
    Route::get('reporter', [ReporterController::class, 'reporter']);
    Route::get('settings', [SettingsController::class, 'listSettings']);
});
