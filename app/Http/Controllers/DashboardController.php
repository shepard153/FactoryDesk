<?php

namespace app\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingsController as Settings;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Render view for dashboard.
     *
     * @return view
     */
    public function loadDashboard()
    {
        $dashboardData = $this->dashboardData();
        $user = Auth::getUser();
        $pageTitle = "Dashboard";
        $settings = Settings::getSettings();

        return view("dashboard/dashboard", [
            'user' => $user,
            'pageTitle' => $pageTitle,
            'dashboard' => $dashboardData,
            'settings' => $settings
        ]);
    }

    /**
     * Data for ajax query.
     *
     * @return JsonResponse
     */
    public function ajaxDashboardData()
    {
        return json_encode(['dashboardData' => $this->dashboardData()]);
    }

    /**
     * Get stats data for dashboard view.
     *
     * @return JsonResponse
     */
    public function dashboardData()
    {
        $department = Auth::user()->department;

        $limit = Settings::getSettings()['dashboard_newestToDisplay'];

        if ($department != "All"){
            $newest = Ticket::where("department", '=', "$department")
                ->where(function ($query){
                    $query->where('ticket_status', '!=', 2)
                        ->orWhere('ticket_status', '=', 0)
                        ->orWhere('ticket_status', '=', 1);
                })
                ->orderBy('date_created', 'desc')
                ->limit($limit)
                ->get();

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

            return [
                'newest' => $newest,
                'all' => $all,
                'allNew' => $allNew,
                'allOpen' => $allOpen,
                'topProblem' => $topProblem,
                'mostProblematic' => $mostProblematic
            ];
        }
        else{
            $newest = Ticket::where(function ($query){
            $query->where('ticket_status', '!=', 2)
                ->orWhere('ticket_status', '=', 0)
                ->orWhere('ticket_status', '=', 1);
            })
            ->orderBy('date_created', 'desc')
            ->limit($limit)
            ->get();;

            $topProblem = Ticket::select('problem')->selectRaw('count (*) as occurence')->groupBy('problem')->orderBy('occurence', 'desc')->limit(1)->get();

            $mostProblematic = Ticket::select('zone')->selectRaw('count (*) AS problematic')->groupBy('zone')->orderBy('problematic', 'desc')->limit(1)->get();

            $all = Ticket::count();
            $allNew = Ticket::where('ticket_status', 0)->count();
            $allOpen = Ticket::where('ticket_status', 1)->count();

            return ["newest" => $newest,
                "topProblem" => $topProblem,
                "mostProblematic" => $mostProblematic,
                "all" => $all,
                "allNew" => $allNew,
                "allOpen" => $allOpen,
            ];
        }
    }

    /**
     * Data for dashboard chart.
     *
     * @param string $startDate
     * @return JsonResponse
     */
    public function chartData($startDate = null)
    {
        $department = Auth::user()->department;

        $startDate == 'null' ? $startDate = new \DateTime('2022-05-20') : $startDate = new \DateTime($startDate);
        $timeNow = new \DateTime('NOW');
        $diff = $timeNow->diff($startDate);
        $diff = $diff->format('%a');

        if ($department != 'All'){
            for ($i = $diff; $i >= 0; $i--){
                $date = new \DateTime('now');
                $date = $date->modify("-$i day");
                $date = $date->format('Y-m-d');
                $labels[] = $date;
                $new[$date] = Ticket::where('department', $department)->where('date_created', 'LIKE', "$date%")->count();
                $opened[$date] = Ticket::where('department', $department)->where('date_opened', 'LIKE', "$date%")->count();
                $closed[$date] = Ticket::where('department', $department)->where('date_closed', 'LIKE', "$date%")->count();
            }

            $chartLegendNew = Ticket::where('department', $department)->whereBetween('date_created', [$startDate, $timeNow])->count();
            $chartLegendOpen = Ticket::where('department', $department)->whereBetween('date_opened', [$startDate, $timeNow])->count();
            $chartLegendClosed = Ticket::where('department', $department)->whereBetween('date_closed', [$startDate, $timeNow])->count();
        }
        else{
            for ($i = $diff; $i >= 0; $i--){
                $date = new \DateTime('now');
                $date = $date->modify("-$i day");
                $date = $date->format('Y-m-d');
                $labels[] = $date;
                $new[$date] = Ticket::where('date_created', 'LIKE', "$date%")->count();
                $opened[$date] = Ticket::where('date_opened', 'LIKE', "$date%")->count();
                $closed[$date] = Ticket::where('date_closed', 'LIKE', "$date%")->count();
            }

            $chartLegendNew = Ticket::whereBetween('date_created', [$startDate, $timeNow])->count();
            $chartLegendOpen = Ticket::whereBetween('date_opened', [$startDate, $timeNow])->count();
            $chartLegendClosed = Ticket::whereBetween('date_closed', [$startDate, $timeNow])->count();
        }

        return json_encode(['labels' => $labels,
            'new' => $new,
            'opened' => $opened,
            'closed' => $closed,
            'chartLegendNew' => $chartLegendNew,
            'chartLegendOpen' => $chartLegendOpen,
            'chartLegendClosed' => $chartLegendClosed,
        ]);
    }
}
