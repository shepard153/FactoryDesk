<?php

    namespace App\Models;

    use App\Models\Ticket;
    use App\Models\Department;
    use Illuminate\Support\Facades\DB;

    Class Dashboard
    {
        protected $newest;
        protected $topProblems;
        protected $mostProblematic;
        protected $total;
        protected $total_new;
        protected $total_open;
        protected $total_closed;
        public $fetchData = [];

        function getTicketDataAll()
        {
            #Najnowsze zgłoszenia spośród wszystkich działów
            $this->newest = DB::table("Tickets")->select()->where('ticket_status', '=', 0)->orderBy('date_created', 'desc')->get();
              
            #Najczęstsze problemy spośród wszystkich działów
            $this->topProblems = DB::table("Tickets")->selectRaw("problem, COUNT (*) AS occurence")->groupBy('problem')->orderBy('occurence', 'desc')->get();
          
            #Najwięcej zgłoszeń spośród wszystkich działów
            $this->mostProblematic = DB::table("Tickets")->selectRaw('zone, COUNT(*) AS problematic')->groupBy('zone')->orderBy('problematic', 'desc')->get();
              
            #Wszystkie zgłoszenia spośród wszystkich działów
            $this->total = DB::table("Tickets")->count();
              
            #Nowe zgłoszenia spośród wszystkich działów
            $this->total_new = DB::table("Tickets")->where('ticket_status', '=', 0)->count();
              
            #Zgłoszenia aktywne spośród wszystkich działów
            $this->total_open = DB::table("Tickets")->where('ticket_status', '=', 1)->count();
              
            #Zgłoszenia zamknięte spośród wszystkich działów
            $this->total_closed = DB::table("Tickets")->where('ticket_status', '=', 2)->count();

            return $this->fetchData = ["newest" => $this->newest,
                                        "topProblems" => $this->topProblems,
                                        "mostProblematic" => $this->mostProblematic,
                                        "total" => $this->total,
                                        "total_new" => $this->total_new,
                                        "total_open" => $this->total_open,
                                        "total_closed" => $this->total_closed,
                                    ];
        }

        function getTicketDataByDepartment($department)
        {
            #Najnowsze zgłoszenia
            $this->newest = DB::table("Tickets")->select()->where("department", '=', "$department")->where('ticket_status', '=', 0)->orderBy('date_created', 'desc')->get();
            
            #Najczęstsze problemy
            $this->topProblems = DB::table("Tickets")->selectRaw("problem, COUNT (*) AS occurence")->where('department', '=', "$department")->groupBy('problem')->orderBy('occurence', 'desc')->get();
          
            #Najwięcej zgłoszeń
            $this->mostProblematic = DB::table("Tickets")->selectRaw('zone, COUNT(*) AS problematic')->where('department', '=', "$department")->groupBy('zone')->orderBy('problematic', 'desc')->get();
  
            #Wszystkie zgłoszenia
            $this->total = DB::table("Tickets")->where('department', '=', "$department")->count();
          
            #Nowe zgłoszenia
            $this->total_new = DB::table("Tickets")->where('department', '=', "$department")->where('ticket_status', '=', 0)->count();
          
            #Zgłoszenia aktywne
            $this->total_open = DB::table("Tickets")->where('department', '=', "$department")->where('ticket_status', '=', 1)->count();

            #Zgłoszenia zamknięte
            $this->total_closed = DB::table("Tickets")->where('department', '=', "$department")->where('ticket_status', '=', 2)->count();
            
            return $this->fetchData = ["newest" => $this->newest, 
                                        "topProblems" => $this->topProblems,
                                        "mostProblematic" => $this->mostProblematic,
                                        "total" => $this->total,
                                        "total_new" => $this->total_new,
                                        "total_open" => $this->total_open,
                                        "total_closed" => $this->total_closed,
                                    ];
        }
    }
