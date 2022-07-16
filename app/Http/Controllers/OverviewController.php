<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;

class OverviewController extends Controller
{
    /**
     * Render view for main overview page.
     */
    public function index()
    {
        $defaultDepartment = 'All';
        $departmentList = Department::all();

        return view('ticket/overview', ['defaultDepartment' => $defaultDepartment, 'departmentList' => $departmentList]);
    }

    /**
     * Get data from last 7 days for line chart.
     *
     * @param Request $request
     * @param string $department
     * @return JsonResponse
     */
    public function chartData(Request $request, $department)
    {
        if ($department != 'All'){
            for ($i = 7; $i >= 0; $i--){
                $date = new \DateTime('now');
                $date = $date->modify("-$i day");
                $date = $date->format('Y-m-d');
                $labels[] = $date;
                $new[$date] = Ticket::where('department', $department)->where('date_created', 'LIKE', "$date%")->count();
                $opened[$date] = Ticket::where('department', $department)->where('date_opened', 'LIKE', "$date%")->count();
                $closed[$date] = Ticket::where('department', $department)->where('date_closed', 'LIKE', "$date%")->count();

                $all = Ticket::where('department', $department)->count();
                $allNew = Ticket::where('department', $department)->where('ticket_status', 0)->count();
                $allOpen = Ticket::where('department', $department)->where('ticket_status', 1)->count();
                $newest = Ticket::where("department", '=', "$department")
                    ->where(function ($query){
                        $query->where('ticket_status', '!=', 2)
                            ->orWhere('ticket_status', '=', 0)
                            ->orWhere('ticket_status', '=', 1);
                    })
                    ->orderBy('date_created', 'desc')
                    ->limit(7)
                    ->get();
            }
        }
        else{
            for ($i = 7; $i >= 0; $i--){
                $date = new \DateTime('now');
                $date = $date->modify("-$i day");
                $date = $date->format('Y-m-d');
                $labels[] = $date;
                $new[$date] = Ticket::where('date_created', 'LIKE', "$date%")->count();
                $opened[$date] = Ticket::where('date_opened', 'LIKE', "$date%")->count();
                $closed[$date] = Ticket::where('date_closed', 'LIKE', "$date%")->count();

                $all = Ticket::all()->count();
                $allNew = Ticket::where('ticket_status', 0)->count();
                $allOpen = Ticket::where('ticket_status', 1)->count();
                $newest = Ticket::where(function ($query){
                    $query->where('ticket_status', '!=', 2)
                        ->orWhere('ticket_status', '=', 0)
                        ->orWhere('ticket_status', '=', 1);
                    })
                    ->orderBy('date_created', 'desc')
                    ->limit(7)
                    ->get();
            }
        }

        return json_encode([
            'labels' => $labels,
            'new' => $new,
            'opened' => $opened,
            'closed' => $closed,
            'all' => $all,
            'allOpen' => $allOpen,
            'allNew' => $allNew,
            'newest' => $newest,
        ]);
    }
}
