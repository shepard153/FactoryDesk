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
         * @return array $data
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

                $topProblems = Ticket::select('problem')
                    ->where("department", '=', "$department")
                    ->selectRaw('count (*) as occurence')
                    ->groupBy('problem')
                    ->orderBy('occurence', 'desc')
                    ->limit(5)
                    ->get();

                $mostProblematic = Ticket::select('zone')
                    ->where("department", '=', "$department")
                    ->selectRaw('count (*) AS problematic')
                    ->groupBy('zone')
                    ->orderBy('problematic', 'desc')
                    ->limit(5)
                    ->get();

                $total = Ticket::where("department", '=', "$department")->count();

                $total_new =Ticket::where('ticket_status', '=', 0)->where("department", '=', "$department")->count();

                $total_open =Ticket::where('ticket_status', '=', 1)->where("department", '=', "$department")->count();

                $total_closed =Ticket::where('ticket_status', '=', 2)->where("department", '=', "$department")->count();

                return ["newest" => $newest,
                    "topProblems" => $topProblems,
                    "mostProblematic" => $mostProblematic,
                    "total" => $total,
                    "total_new" => $total_new,
                    "total_open" => $total_open,
                    "total_closed" => $total_closed,
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

                $topProblems = Ticket::select('problem')->selectRaw('count (*) as occurence')->groupBy('problem')->orderBy('occurence', 'desc')->limit(5)->get();

                $mostProblematic = Ticket::select('zone')->selectRaw('count (*) AS problematic')->groupBy('zone')->orderBy('problematic', 'desc')->limit(5)->get();

                $total = Ticket::all()->count();

                $total_new =Ticket::where('ticket_status', '=', 0)->count();

                $total_open =Ticket::where('ticket_status', '=', 1)->count();

                $total_closed =Ticket::where('ticket_status', '=', 2)->count();

                return ["newest" => $newest,
                    "topProblems" => $topProblems,
                    "mostProblematic" => $mostProblematic,
                    "total" => $total,
                    "total_new" => $total_new,
                    "total_open" => $total_open,
                    "total_closed" => $total_closed,
                ];
            }
        }
    }
