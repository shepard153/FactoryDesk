<?php

    namespace app\Http\Controllers;

    use App\Models\Dashboard;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;

    class DashboardController extends Controller
    {
        protected $dashboard;

        function __construct(Dashboard $dashboard)
        {
            $this->dashboard = $dashboard;
        }

        /**
         * Render view for dashboard.
         * 
         * @return view
         */
        function loadDashboard()
        {
            $dashboardData = $this->dashboardData();
            $user = Auth::getUser();
            $pageTitle = "Dashboard";
            return view("dashboard/dashboard", ['user'=>$user, 'pageTitle'=>$pageTitle, 'dashboard'=>$dashboardData]);
        }

        /**
         * Get stats data for dashboard view.
         * 
         * @return array $data
         */
        function dashboardData()
        {
            if (Auth::user()->department != "All"){
                $data = $this->dashboard->getTicketDataByDepartment(Auth::user()->department);

                return $data;
            }
            else{
                $data = $this->dashboard->getTicketDataAll();

                return $data;
            }
        }
    }
