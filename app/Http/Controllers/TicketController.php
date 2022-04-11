<?php

    namespace app\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\TicketAttachmentController as AttachmentController;
    use App\Models\Ticket;
    use App\Models\TicketAttachment;
    use App\Models\TicketHistory;
    use App\Models\Note;
    use App\Models\Zone;
    use App\Models\Position;
    use App\Models\Problem;
    use App\Models\Staff;
    use App\Models\Department;
    use App\Models\Priority;

    Class TicketController extends Controller
    {
        /**
         * @var Ticket $ticket
         */
        protected $ticket;

        /**
         * @var TicketAttachment $attachment
         */
        protected $attachment;

        /**
         * Render view for new ticket request. Function finds users IP address and with it, finds the device name and
         * strips it from ".carcgl.com" to prevent long names, eg. PLTR-10-XXX-D, instead of PLTR-10-XXX-D.carcgl.com.
         * After that the department name is taken from the URL and stripped from spaces - %20.
         *
         * @param Zone $zone
         * @param Request $request
         * @return view
         */
        function ticketRequest(Zone $zone, Request $request)
        {
            $ipaddress = $request->ip();
            $domain = gethostbyaddr($ipaddress);

            if (strpos($domain, ".carcgl.com"))
            {
                $domain = str_replace(".carcgl.com", "", $domain);
            }

            $zones = Zone::all();

            $url = url()->current();
            $department = explode("/", $url);
            $department = json_decode(str_replace("%20", " ", json_encode(end($department))));

            return view("ticket/ticket_step2", ['domain' => $domain, "department" => $department, "zones" => $zones]);
        }

        /**
         * Ajax request to get available positions based on chosen zone.
         *
         * @param Position $position
         * @param string $zoneName
         * @return array $positions
         */
        function ajaxPositionsRequest($zoneName)
        {
            $positions = Position::where('zones_list', 'LIKE', "%$zoneName%")->get();
            return json_encode($positions);
        }

        /**
         * Ajax request to get available problem based on chosen position and department.
         *
         * @param Position $position
         * @param string $department
         * @param string $positionName
         * @return array $problems
         */
        function ajaxProblemsRequest($department, $positionName)
        {
            $problems = Problem::where('departments_list', 'LIKE', "%$department%")->where('positions_list', 'LIKE', "%$positionName%")->orderBy('lp', 'asc')->get();
            return json_encode($problems);
        }

        /**
         * Ajax request to get all problems for chosen department. Function is a part of a dashboard ticket
         * details service. If agent changes ticket department, all available problems will be listed.
         * Same goes for ticket owner list.
         *
         * @param Problem $problem
         * @param string $department
         * @return array @problems
         */
        function ajaxForTicketDetails($department)
        {
            $problems = Problem::where('departments_list', 'LIKE', "%$department%")->orderBy('lp', 'asc')->get();
            $members = Staff::where('department', '=', $department)->orderBy('name', 'asc')->get();
            return json_encode(['problems' => $problems, 'members' => $members]);
        }

        /**
         * Send ticket and place data in database while attachment (if provided) is placed in ticket_attachments folder on disk.
         * If operation is successful, the user will be prompted with success message na ticket ID.
         *
         * @param Request $request
         * @return view
         */
        function sendTicket(Request $request)
        {
            $this->ticket = Ticket::create(['device_name' => $request->device_name,
                'department' => $request->department,
                'zone' => $request->zoneSelect,
                'position' => $request->positionSelect,
                'problem' => $request->problemSelect,
                'ticket_message' => $request->message,
                'priority' => $request->prioritySelect,
                'username' => $request->username]);

            $ticketID = $this->ticket->ticketID;

            if ($request->hasFile('attachment')){
                $file = $request->file('attachment');

                $request->validate(['attachment' => 'max:5120']);

                $filePath = $file->storeAs('ticket_attachments', "ticket-$ticketID-attachment.". $file->getClientOriginalExtension());
                $fileName = "ticket-" . $ticketID . "-attachment." . $file->getClientOriginalExtension();
                $filePath = "ticket_attachments/";
                TicketAttachment::create(['ticketID' => $ticketID, 'file_name' => $fileName, 'file_path'=>$filePath]);
            }
            else{
                $filePath = null;
            }

            return view("ticket/ticket_sent")->with('ticketID', $ticketID);
        }

        /**
         * List of tickets owned by currently logged in staff member (both opened and closed) with some additional stats information.
         *
         * @return view
         */
        public function memberTickets($status = 'taken')
        {
            $pageTitle = "Moje zgłoszenia";

            if ($status == 'taken'){
                $latestTickets = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->orderBy('date_modified', 'desc')->get();
                $tickets = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->orderBy('date_modified', 'desc')->paginate(10)->withQueryString();
            }
            else{
                $latestTickets = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->orderBy('date_closed', 'desc')->get();
                $tickets = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->orderBy('date_closed', 'desc')->paginate(10)->withQueryString();
            }

            $ticketsOpen = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->get()->count();
            $ticketsClosed = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->get()->count();

            $percentageSolved = $ticketsClosed * 100 / ($ticketsClosed + $ticketsOpen) ;

            return view("dashboard/my_tickets", ['pageTitle' => $pageTitle,
                'latestTickets' => $latestTickets,
                'tickets' => $tickets,
                'ticketsOpen' => $ticketsOpen,
                'ticketsClosed' => $ticketsClosed,
                'percentageSolved' => $percentageSolved,
            ]);
        }

        /**
         * List all tickets for agents. By default pagination is set to 20 tickets per page. Here you can also change the sorting arrows
         * in $arrows array. Default ones are from font awesome package.
         *
         * @param Request $request
         * @return view
         */
        function ticketList(Request $request, $status = 'active')
        {
            $pageTitle = "Zgłoszenia";

            switch ($status){
                case "new":
                    $status = 0;
                    break;
                case "taken":
                    $status = 1;
                    break;
                case "closed":
                    $status = 2;
                    break;
            }

            if ($request->sort != null){
                $request->order = $request->order == 'desc' ? 'asc': 'desc';
                if ($status == 'active'){
                    $tickets = Ticket::where('ticket_status', '=', 0)->orWhere('ticket_status', '=', 1)->orderBy($request->sort, $request->order)->paginate(20)->withQueryString();
                }
                else{
                    $tickets = Ticket::where('ticket_status', '=', $status)->orderBy($request->sort, $request->order)->paginate(20)->withQueryString();
                }
            }else{
                if ($status == 'active'){
                    $tickets = Ticket::where('ticket_status', '=', 0)->orWhere('ticket_status', '=', 1)->orderBy('date_modified', 'desc')->paginate(20)->withQueryString();
                }
                else{
                    $tickets = Ticket::where('ticket_status', '=', $status)->orderBy('date_modified', 'desc')->paginate(20)->withQueryString();
                }
            }

            $arrows = str_replace(array('asc','desc'), array('fa-solid fa-arrow-up-wide-short','fa-solid fa-arrow-down-wide-short'), $request->order);

            return view("dashboard/tickets", [
                'pageTitle' => $pageTitle,
                'tickets' => $tickets,
                'sort' => $request->sort,
                'order' => $request->order,
                'arrows' => $arrows]);
        }

        /**
         * Render ticket details page for given ticket ID.
         *
         * @param int $id
         * @return view
         */
        function ticketDetails($id)
        {
            $pageTitle = "Zgłoszenia";
            $ticket = Ticket::where('ticketID', $id)->first();
            $departments = Department::all();
            $problems = Problem::where('departments_list', 'LIKE', "%$ticket->department%")->get();
            $notes = Note::where('ticketID', $id)->get();
            $history = TicketHistory::where('ticketID', $id)->get();
            $attachment = TicketAttachment::where('ticketID', $id)->first();
            $attachmentDisplay = $attachment != null && $attachment->file_name != null ? AttachmentController::attachmentDisplay($attachment) : null;
            $staffMembers = Staff::where('department', '=', $ticket->department)->get();

            return view("dashboard/ticket", [
                'pageTitle' => $pageTitle,
                'ticket' => $ticket,
                'departments' => $departments,
                'problems' => $problems,
                'notes' => $notes,
                'history' => $history,
                'attachment' => $attachment,
                'attachmentDisplay' => $attachmentDisplay,
                'staffMembers' => $staffMembers]);
        }

        /**
         * All available ticket actions (Take/Close/Reopen),
         *
         * @param Request $request
         * @param int @id
         * @return string $message
         */
        function modifyTicketAction(Request $request, $id)
        {
            $ticket = Ticket::find($id);

            if ($request->takeTicket != null || $request->closeTicket != null || $request->reopenTicket != null){
                if ($request->takeTicket){
                    $ticket->ticket_status = 1;
                    $ticket->date_modified = new \DateTime('NOW');
                    $ticket->date_opened = new \DateTime('NOW');
                    $ticket->time_spent = \DateTime::createFromFormat('H:i', '00:00');
                    $ticket->owner = auth()->user()->name;

                    $message = "Podjęto zgłoszenie.";
                }
                else if ($request->closeTicket){
                    $ticket->ticket_status = 2;
                    $ticket->date_modified = new \DateTime('NOW');
                    $ticket->date_closed = new \DateTime('NOW');

                    $message = "Zamknięto zgłoszenie.";
                }
                else{
                    $ticket->ticket_status = 1;
                    $ticket->date_modified = new \DateTime('NOW');
                    $ticket->date_closed = null;

                    $message = "Ponownie otwarto zgłoszenie.";
                }

                $this->addToHistory($request, $id);

                $ticket->save();
            }
            else if ($request->editTicket){
                $ticket = Ticket::find($id);

                $ticket->department = $request->departmentSelect;
                $ticket->problem = $request->problemSelect;
                $ticket->priority = $request->prioritySelect;
                $ticket->owner = $request->ownerSelect;
                $ticket->external_ticketID = $request->external_ticketID;

                $this->addToHistory($request, $id, $ticket);

                $ticket->save();

                $message = "Zmiany zostały zapisane";
            }
            else if ($request->timerAction){
                $ticket->time_spent == null ? $ticket->time_spent = \DateTime::createFromFormat('H:i', '00:00') : $ticket->time_spent = new \DateTime($ticket->time_spent);
                switch ($request->timerAction){
                    case ('5'):
                        $ticket->time_spent->add(new \DateInterval('PT5M'));
                        break;
                    case ('15'):
                        $ticket->time_spent->add(new \DateInterval('PT15M'));
                        break;
                    case ('30'):
                        $ticket->time_spent->add(new \DateInterval('PT30M'));
                        break;
                }

                $ticket->save();

                $message = "Zmiany zostały zapisane";
            }

            return back()->with('message', $message);
        }

        /**
         * Part of modify ticket action. All changes are sent to database and are available in ticket details view.
         *
         * @param Request $request
         * @param int $id
         * @param Ticket $ticket = null
         * @return null
         */
        function addToHistory(Request $request, $id, $ticket = null)
        {
            if ($request->takeTicket != null || $request->closeTicket != null || $request->reopenTicket != null){
                $history = new TicketHistory;
                $history->ticketID = $id;
                $history->username = auth()->user()->name;
                $request->takeTicket != null ? $history->contents = "Zgłoszenie podjęte przez ". auth()->user()->name : null;
                $request->closeTicket != null ? $history->contents = "Zgłoszenie zamknięte przez ". auth()->user()->name : null;
                $request->reopenTicket != null ? $history->contents = "Zgłoszenie ponownie otwarte przez ". auth()->user()->name : null;
                $history->save();
            }
            else if ($request->editTicket != null){
                $dirtyArray = array();
                $ticket->isDirty('department') == true ? array_push($dirtyArray, "Zmieniono dział z ".$ticket->getOriginal('department')." na $request->departmentSelect") : null;
                $ticket->isDirty('problem') == true ? array_push($dirtyArray, "Zmieniono problem z ".$ticket->getOriginal('problem')." na $request->problemSelect") : null;
                $ticket->isDirty('priority') == true ? array_push($dirtyArray, "Zmieniono priorytet z ".$ticket->getOriginal('priority')." na $request->prioritySelect") : null;
                $ticket->isDirty('owner') == true ? array_push($dirtyArray, "Zmieniono osobę odpowiedzialną z ".$ticket->getOriginal('owner')." na $request->ownerSelect") : null;

                foreach ($dirtyArray as $edit){
                    $history = new TicketHistory;
                    $history->ticketID = $id;
                    $history->username = auth()->user()->name;
                    $history->contents = $edit;
                    $history->save();
                }
            }
        }

        /**
         * Create note action.
         *
         * @param Request $request
         * @param int $id
         * @return string
         */
        function addNote(Request $request, $id)
        {
            Note::create(['ticketID' => $id,
                'username' => auth()->user()->name,
                'contents' => $request->noteContents]);

            $ticket = Ticket::find($id);
            $ticket->date_modified = new \DateTime('NOW');
            $ticket->save();

            return back()->with('message', "Pomyślnie dodano notatkę.");
        }
    }
