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
        for ($i = 7; $i >= 0; $i--){
            $date = new \DateTime('now');
            $date = $date->modify("-$i day");
            $date = $date->format('Y-m-d');
            $labels[] = $date;
            $new[$date] = Ticket::where('department', $department)->where('date_created', 'LIKE', "$date%")->count();
            $opened[$date] = Ticket::where('department', $department)->where('date_opened', 'LIKE', "$date%")->count();
            $closed[$date] = Ticket::where('department', $department)->where('date_closed', 'LIKE', "$date%")->count();
        }

        $all = Ticket::where('department', $department)->count();
        $allNew = Ticket::where('department', $department)->where('ticket_status', 0)->count();
        $allOpen = Ticket::where('department', $department)->where('ticket_status', 1)->count();

        $topProblem = Ticket::select('problem')->where("department", '=', "$department")
        ->selectRaw('count (*) as occurence')
        ->groupBy('problem')
        ->orderBy('occurence', 'desc')
        ->limit(1)
        ->get();

    $mostProblematic = Ticket::select('zone')
        ->where("department", '=', "$department")
        ->selectRaw('count (*) AS problematic')
        ->groupBy('zone')
        ->orderBy('problematic', 'desc')
        ->limit(1)
        ->get();

        return json_encode([
            'labels' => $labels,
            'new' => $new,
            'opened' => $opened,
            'closed' => $closed,
            'all' => $all,
            'allOpen' => $allOpen,
            'allNew' => $allNew,
            'topProblem' => $topProblem,
            'mostProblematic' => $mostProblematic
        ]);
    }
}
