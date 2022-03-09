<?php

    namespace app\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use App\Http\Controllers\Controller;
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
        protected $ticket;
        protected $attachment;

        function __construct(Ticket $ticket)
        {
            $this->ticket = $ticket;
        }

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

        function ajaxPositionsRequest(Position $position, $zoneName)
        {
            $positions = $position->getPositionsByZone($zoneName);
            return json_encode($positions);
        }

        function ajaxProblemsRequest(Problem $problem, $department, $positionName)
        {
            $problems = $problem->getProblemsByPosition($positionName, $department);
            return json_encode($problems);
        }

        function ajaxProblemsForStaff(Problem $problem, $department)
        {
            $problems = $problem->getProblemsByDepartment($department);
            return json_encode($problems);          
        }

        function sendTicket(Request $request)
        {
            $this->ticket->createTicket($request->name, $request->department, $request->zoneSelect, $request->positionSelect, $request->problemSelect, 
                                    $request->prioritySelect, $request->message);

            $ticketID = $this->ticket->ticketID;

            if ($request->file('attachment') != null){
                $file = $request->file('attachment');
                $filePath = $file->storeAs('ticket_attachments', "ticket-$ticketID-attachment.". $file->getClientOriginalExtension());
                $filePath = "ticket_attachments/";
                $fileName = "ticket-" . $ticketID . "-attachment." . $file->getClientOriginalExtension();
                $this->attachment->insertAttachmentData($ticketID, $fileName, $filePath);
            }
            else{
                $filePath = null;
            }           

            return view("ticket/ticket_sent")->with('ticketID', $ticketID);
        }

        function ticketList(Request $request) 
        {
            $pageTitle = "Zgłoszenia";

            if ($request->sort != null){
                $request->order = $request->order == 'desc' ? 'asc': 'desc';
                $tickets = $this->ticket->getTickets($request->sort, $request->order);
            }else{
                $tickets = $this->ticket->getTickets();
            }

            $arrows = str_replace(array('asc','desc'), array('fa-solid fa-arrow-up-wide-short','fa-solid fa-arrow-down-wide-short'), $request->order);

            return view("dashboard/tickets", [
                'pageTitle' => $pageTitle,
                'tickets' => $tickets->paginate(20)->withQueryString(),
                'sort' => $request->sort,
                'order' => $request->order,
                'arrows' => $arrows]);
        }

        function ticketListByStatus(Request $request, $status) 
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
                default:
                    $status = 'active';
            }
            if ($request->sort != null){
                $request->order = $request->order == 'desc' ? 'asc': 'desc';
                $tickets = $this->ticket->getTicketsByStatus($status, $request->sort, $request->order);
            }else{
                $tickets = $this->ticket->getTicketsByStatus($status);                
            }

            $arrows = str_replace(array('asc','desc'), array('fa-solid fa-arrow-up-wide-short','fa-solid fa-arrow-down-wide-short'), $request->order);

            return view("dashboard/tickets", [
                'pageTitle' => $pageTitle,
                'tickets' => $tickets->paginate(20)->withQueryString(),
                'sort' => $request->sort,
                'order' => $request->order,
                'arrows' => $arrows]);
        }

        function ticketDetails(Department $departments, Problem $problem, Note $notes, TicketHistory $history, TicketAttachment $attachment, Staff $staff, $id)
        {
            $pageTitle = "Zgłoszenia";
            $ticket = $this->ticket::where('ticketID', $id)->first();
            $departments = Department::all();
            $problems = $problem->getProblemsByDepartment($ticket->department);
            $notes = Note::where('ticketID', $id)->get();
            $history = TicketHistory::where('ticketID', $id)->get();
            $attachment = TicketAttachment::where('ticketID', $id)->first();
            $staffMembers = Staff::all();
            
            return view("dashboard/ticket", [
                'pageTitle' => $pageTitle,
                'ticket' => $ticket,
                'departments' => $departments,
                'problems' => $problems,
                'notes' => $notes,
                'history' => $history,
                'attachment' => $attachment,
                'staffMembers' => $staffMembers]);   
        }

        function modifyTicketAction(Request $request, $id)
        {
            if ($request->takeTicket != null || $request->closeTicket != null || $request->reopenTicket != null){
                $ticket = Ticket::find($id);

                if ($request->takeTicket){
                    $ticket->ticket_status = 1;
                    $ticket->date_modified = new \DateTime('NOW');
                    $ticket->date_opened = new \DateTime('NOW');

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

                $ticket->owner = auth()->user()->name;

                $this->addToHistory($request, $id);

                $ticket->save();
            }
            else if ($request->editTicket != null){
                $ticket = Ticket::find($id);

                $ticket->department = $request->departmentSelect;
                $ticket->problem = $request->problemSelect;
                $ticket->priority = $request->prioritySelect;
                $ticket->owner = auth()->user()->name;
            
                $this->addToHistory($request, $id, $ticket);

                $ticket->save();

                $message = "Zmiany zostały zapisane";
            }

            return back()->with('message', $message);
        }

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

        function addNote(Request $request, $id)
        {
            $note = new Note;
            $note->ticketID = $id;
            $note->username = auth()->user()->name;
            $note->contents = $request->noteContents;
            $note->created_at = new \DateTime('NOW');
            $note->save();

            $ticket = Ticket::find($id);
            $ticket->date_modified = new \DateTime('NOW');
            $ticket->save();

            return back()->with('message', "Pomyślnie dodano notatkę.");
        }
    }
