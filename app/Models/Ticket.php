<?php
    
    namespace App\Models;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Eloquent\Model;

    class Ticket extends Model
    {
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'Tickets';

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $primaryKey = 'ticketID';

        const CREATED_AT = 'date_created';
        const UPDATED_AT = 'date_modified';

        function __construct($id = null)
        {
            if ($id != null){
                $this->getTicketDetails($id);
            }
        }

        /**
         * The model's default values for attributes.
         *
         * @var array
         */
        protected $attributes = [
            'ticket_status' => 0,
        ];

        function setTicketName(){
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $this->name = gethostbyaddr($ipaddress);
        }

        public function createTicket(string $ticketName, string $ticketDepartment, string $ticketZone, string $ticketPosition, 
                                    string $ticketProblem, int $ticketPriority, string $ticketMessage = null, int $ticketStatus = 0)
        {
            $this->setTicketName($ticketName);

            $this->ticketID = DB::table("Tickets")->insertGetId(['name'=>$this->name,
                                                        'department'=>$ticketDepartment,
                                                        'zone'=>$ticketZone,
                                                        'position'=>$ticketPosition,
                                                        'problem'=>$ticketProblem,
                                                        'ticket_message'=>$ticketMessage,
                                                        'priority'=>$ticketPriority,
                                                        'ticket_status'=>$ticketStatus]);

        }

        public function getTickets($sort = null, $order = null)
        {
            if ($sort == null){
                return DB::table("Tickets")->select()->orderBy('date_modified', 'desc');
            }
            else{
                return DB::table("Tickets")->select()->orderBy($sort, $order);
            }
        }

        public function getTicketsByStatus($status, $sort = null, $order = null)
        {
            if ($status == 2 && $sort == null){
                return DB::table("Tickets")->select()->where('ticket_status', '=', $status)->orderBy('date_closed', 'desc');
            }
            else if($status == 'active'){
                if ($sort == null){
                    return DB::table("Tickets")->select()->where('ticket_status', '=', 0)->orWhere('ticket_status', '=', 1)->orderBy('date_modified', 'desc');              
                }
                else{
                    return DB::table("Tickets")->select()->where('ticket_status', '=', 0)->orWhere('ticket_status', '=', 1)->orderBy($sort, $order);              
                }
            }
            else if($sort == null){
                return DB::table("Tickets")->select()->where('ticket_status', '=', $status)->orderBy('date_modified', 'desc');
            }
            else{
                return DB::table("Tickets")->select()->where('ticket_status', '=', $status)->orderBy($sort, $order);
            }

        }
    }
