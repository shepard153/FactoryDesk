<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TicketAttachmentController as AttachmentController;
use App\Http\Controllers\SettingsController as Settings;
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

class TicketController extends Controller
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
     * Render view for new ticket request. Function also finds the device name.
     *
     * @param Zone $zone
     * @param Request $request
     * @return view
     */
    public function ticketRequest(Zone $zone, Request $request)
    {
		$domain = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $department = $request->department;

        $zones = Zone::all();

        return view("ticket/ticket_step2", ['domain' => $domain, "department" => $department, "zones" => $zones]);
    }

    /**
     * Ajax request to get all problems for chosen department. Function is a part of a dashboard ticket
     * details service. If agent changes ticket department, all available problems will be listed.
     * Same goes for ticket owner list.
     *
     * @param Problem $problem
     * @param string $department
     * @return JsonResponse with $problems, $members
     */
    public function ajaxForTicketDetails($department)
    {
        $problems = Problem::where('departments_list', 'LIKE', "%$department%")->orderBy('lp', 'asc')->get();
        $members = Staff::where('department', '=', $department)->orderBy('name', 'asc')->get();
        return json_encode(['problems' => $problems, 'members' => $members]);
    }

    /**
     * Sends ticket to database while attachment (if provided) is placed in ticket_attachments folder on disk.
     * If operation is successful, the user will be prompted with success message and ticket ID via ticketSent function.
     *
     * acceptanceCheck variable is used to check if requested department requires acceptance from another department first.
     *
     * @param Request $request
     * @return view
     */
    public function sendTicket(Request $request)
    {
        $acceptanceCheck = Department::where('department_name', $request->department)->first();

        if ($acceptanceCheck->acceptance_from != null){
            $department = $acceptanceCheck->acceptance_from;
            $targetDepartment = $request->department;
        }
        else{
            $department = $request->department;
            $targetDepartment = null;
        }

        $newest = Ticket::where('department', $department)->orderBy('ticketID', 'desc')->first();

        if ($newest != null && $newest->department_ticketID != null){
            $newest = $newest->department_ticketID;
            preg_match_all('/([a-zA-Z]+)(\d+)/', $newest, $matches);
            $updatedID = $matches[1][0] . $matches[2][0] + 1;
        }
        else{
            $updatedID = Department::where('department_name', $department)->first();
            $updatedID = $updatedID->department_prefix . 1;
        }

        $this->ticket = Ticket::create(['device_name' => $request->device_name,
            'department' => $department,
            'zone' => $request->zoneSelect,
            'position' => $request->positionSelect,
            'problem' => $request->problemSelect,
            'ticket_message' => $request->message,
            'priority' => $request->prioritySelect,
            'username' => $request->username,
            'department_ticketID' => $updatedID,
            'target_department' => $targetDepartment,
            'time_spent' => \DateTime::createFromFormat('H:i', '00:00')
        ]);

        if ($acceptanceCheck->acceptance_from != null){
            $this->ticket->ticket_status = -1;
            $this->ticket->save();
        }

        $ticketID = $this->ticket->ticketID;

        $attachments = $request->file('file');
        if ($attachments != null && $attachments[0]->getClientOriginalName() != 'blob'){
            AttachmentController::dropzoneUpload($request, $ticketID);
        }

        return response()->json(['id' => $ticketID]);
    }

    /**
     * Render view for ticket sent confirmation message.
     *
     * @param int $id
     * @return view
     */
    public function ticketSent($id)
    {
        $ticket = Ticket::find($id);
        $department = Department::where('department_name', $ticket->department)->first();

        $ticketSentMessage = __('raise_ticket_form.ticket_sent_message', ['ticketID' => $ticket->department_ticketID]);

        if ($department->teams_webhook != null){
            $connector = new \Sebbmyr\Teams\TeamsConnector("$department->teams_webhook");
            $card = new \Sebbmyr\Teams\Cards\HeroCard();
            $card->setTitle(__('raise_ticket_form.teams_message_title', ['ticketID' => $ticket->department_ticketID]))
                ->setSubtitle(__('raise_ticket_form.teams_message_subtitle', ['dateCreated' => $ticket->date_created]))
                ->setText(__('raise_ticket_form.teams_message_text', ['zone' => $ticket->zone, 'position' => $ticket->position, 'problem' => $ticket->problem]))
                ->addButton("openUrl", __('raise_ticket_form.teams_message_url'), url('/') . "/ticket/$ticket->ticketID");
            $connector->send($card);
        }

        if ($ticket->target_department != null){
            $ticketSentMessage .= __('raise_ticket_form.ticket_sent_acceptance_message', [
                'targetDepartment' => $ticket->target_department,
                'department' => $ticket->department,
            ]);
        }

        return view("ticket/ticket_sent", ['ticketSentMessage' => $ticketSentMessage]);
    }

    /**
     * List of tickets owned by currently logged in staff member (both opened and closed) with some additional stats information.
     *
     * @param string $status
     * @return view
     */
    public function memberTickets($status = 'taken')
    {
        $pageTitle = __('dashboard_tickets.my_tickets_page_title');

        if ($status == 'taken'){
            $latestTickets = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->orderBy('date_modified', 'desc')->limit(10)->get();
            $tickets = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->orderBy('date_modified', 'desc')->paginate(10)->withQueryString();
        }
        else{
            $latestTickets = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->orderBy('date_closed', 'desc')->limit(10)->get();
            $tickets = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->orderBy('date_closed', 'desc')->paginate(10)->withQueryString();
        }

        $ticketsOpen = Ticket::where('ticket_status', '=', 1)->where('owner', '=', auth()->user()->name)->get()->count();
        $ticketsClosed = Ticket::where('ticket_status', '=', 2)->where('owner', '=', auth()->user()->name)->get()->count();

        return view("dashboard/my_tickets", ['pageTitle' => $pageTitle,
            'latestTickets' => $latestTickets,
            'tickets' => $tickets,
            'ticketsOpen' => $ticketsOpen,
            'ticketsClosed' => $ticketsClosed,
        ]);
    }

    /**
     * List all tickets for agents. By default pagination is set to 20 tickets per page. Here you can also change the sorting arrows
     * in $arrows array. Default ones are from font awesome package.
     *
     * @param Request $request
     * @param string $status
     * @return view
     */
    public function ticketList(Request $request, $status = 'active')
    {
        $pageTitle = __('dashboard_tickets.page_title');
        $settings = Settings::getSettings();

        switch ($status){
            case 'awaiting':
                $status = -1;
                break;
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

            if (auth()->user()->department == 'All' && $status == 'active'){
                $tickets = Ticket::where(function($query) {
                    return $query
                        ->where('ticket_status', '=', 0)
                        ->orWhere('ticket_status', '=', 1);
                    })
                    ->orderBy($request->sort, $request->order)
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
            else if (auth()->user()->department == 'All'){
                $tickets = Ticket::where('ticket_status', '=', $status)
                    ->orderBy($request->sort, $request->order)
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
             else if ($status == 'active'){
                $tickets = Ticket::where('department', '=', auth()->user()->department)
                    ->where(function($query) {
                        return $query
                            ->where('ticket_status', '=', 0)
                            ->orWhere('ticket_status', '=', 1);
                        })
                    ->orderBy($request->sort, $request->order)
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
            else{
                $tickets = Ticket::where('department', '=', auth()->user()->department)
                    ->where('ticket_status', '=', $status)
                    ->orderBy($request->sort, $request->order)
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
        }else{
            if (auth()->user()->department == 'All' && $status == 'active'){
                $tickets = Ticket::where(function($query) {
                    return $query
                        ->where('ticket_status', '=', 0)
                        ->orWhere('ticket_status', '=', 1);
                    })
                    ->orderBy('date_modified', 'desc')
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
            else if (auth()->user()->department == 'All'){
                $tickets = Ticket::where('ticket_status', '=', $status)
                    ->orderBy('date_modified', 'desc')
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
            else if ($status == 'active'){
                $tickets = Ticket::where('department', '=', auth()->user()->department)
                    ->where(function($query) {
                        return $query
                            ->where('ticket_status', '=', 0)
                            ->orWhere('ticket_status', '=', 1);
                    })
                    ->orderBy('date_modified', 'desc')
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
            }
            else{
                $tickets = Ticket::where('department', '=', auth()->user()->department)
                    ->where('ticket_status', '=', $status)
                    ->orderBy('date_modified', 'desc')
                    ->paginate($settings['tickets_pagination'])
                    ->withQueryString();
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
     * @return Response
     */
    public function ticketDetails($id)
    {
        $pageTitle = __('dashboard_tickets.page_title');
        $ticket = Ticket::where('ticketID', $id)->first();

        $ticket->target_department != null ? $ticket->department = $ticket->target_department : null;

        $departments = Department::all();
        $problems = Problem::where('departments_list', 'LIKE', "%$ticket->department%")->get();
        $notes = Note::where('ticketID', $id)->orderBy('created_at','desc')->get();
        $history = TicketHistory::where('ticketID', $id)->orderBy('date_modified','desc')->get();
        $attachments = TicketAttachment::where('ticketID', $id)->get();
        $attachmentsDisplay = $attachments != null ? AttachmentController::attachmentDisplay($attachments) : null;
        $staffMembers = Staff::where('department', '=', $ticket->department)->get();

        return view("dashboard/ticket", [
            'pageTitle' => $pageTitle,
            'ticket' => $ticket,
            'departments' => $departments,
            'problems' => $problems,
            'notes' => $notes,
            'history' => $history,
            'attachments' => $attachments,
            'attachmentsDisplay' => $attachmentsDisplay,
            'staffMembers' => $staffMembers
        ]);
    }

    /**
     * All available ticket actions (Take/Close/Reopen),
     *
     * @param Request $request
     * @param int $id
     * @return string $message
     */
    public function modifyTicketAction(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $ticket->date_modified = new \DateTime('NOW');

        if ($request->takeTicket){
            $ticket->ticket_status = 1;
            $ticket->date_opened = new \DateTime('NOW');
            $ticket->owner = auth()->user()->name;
            $ticket->department = auth()->user()->department;
            $ticket->target_department = null;

            $this->addToHistory($request, $id);

            $message = __('dashboard_tickets.ticket_taken');
        }
        else if ($request->closeTicket || $request->rejectTicket){
            $ticket->ticket_status = 2;
            $ticket->date_closed = new \DateTime('NOW');
            $ticket->ticket_type = $request->closeTicket ? $request->ticketType : 'invalid';
            $ticket->closing_notes = $request->closingNotes;

            $ticket->owner = $request->rejectTicket ? auth()->user()->name : $ticket->owner;

            $this->addNote($request, $id, $request->closingNotes);
            $this->addToHistory($request, $id);

            $message = $request->closeTicket ? __('dashboard_tickets.ticket_closed') : __('dashboard_tickets.ticket_rejected');
        }
        else if ($request->acceptTicket){
            $newTicket = $ticket->replicate();

            $ticket->ticket_status = 2;
            $ticket->date_closed = new \DateTime('NOW');
            $ticket->owner = auth()->user()->name;
            $ticket->ticket_type = 'valid';
            $ticket->closing_notes = $request->closingNotes;

            $newTicket->department = $ticket->target_department;
            $newTicket->target_department = null;
            $newTicket->ticket_status = 0;

            $department = Department::where('department_name', $newTicket->department)->first();
            $newest = Ticket::where('department', $newTicket->department)->orderBy('ticketID', 'desc')->first();

            if ($newest != null && $newest->department_ticketID != null){
                $newest = $newest->department_ticketID;
                preg_match_all('/([a-zA-Z]+)(\d+)/', $newest, $matches);
                $updatedID = $department->department_prefix . $matches[2][0] + 1;
            }
            else{
                $updatedID = $department->department_prefix . 1;
            }

            $newTicket->department_ticketID = $updatedID;
            $newTicket->save();

            $this->addNote($request, $id, $request->closingNotes);
            $this->addToHistory($request, $id, $newTicket);

            $message = __('dashboard_tickets.ticket_accepted');
        }
        else if ($request->reopenTicket){
            $ticket->ticket_status = 1;
            $ticket->date_modified = new \DateTime('NOW');
            $ticket->date_closed = null;

            $this->addToHistory($request, $id);

            $message = __('dashboard_tickets.ticket_reopened');
        }
        else if ($request->editTicket){
            $ticket->date_modified = new \DateTime('NOW');
            $ticket->department = $request->departmentSelect;
            $ticket->problem = $request->problemSelect;
            $ticket->priority = $request->prioritySelect;
            $ticket->owner = $request->ownerSelect;

            $this->addToHistory($request, $id, $ticket);

            $message = __('dashboard_tickets.ticket_saved');
        }

        $ticket->save();

        return back()->with('message', $message);
    }

    /**
     * @param int $id
     * @param string $timer
     * @return JsonResponse $time_spent
     */
    public function ticketTimerAction($id, $timer)
    {
        $this->ticket = Ticket::find($id);
        $this->ticket->time_spent = new \DateTime($this->ticket->time_spent);

        switch ($timer){
            case ('5'):
                $this->ticket->time_spent->add(new \DateInterval('PT5M'));
                break;
            case ('15'):
                $this->ticket->time_spent->add(new \DateInterval('PT15M'));
                break;
            case ('30'):
                $this->ticket->time_spent->add(new \DateInterval('PT30M'));
                break;
        }

        $this->ticket->save();

        return json_encode(['time_spent' => $this->ticket->time_spent]);
    }

    /**
     * Part of modify ticket action. All changes are sent to database and are listed in ticket details view.
     *
     * @param Request $request
     * @param int $id
     * @param Ticket $ticket = null
     * @return null
     */
    public function addToHistory(Request $request, $id, $ticket = null)
    {
        if ($request->takeTicket || $request->closeTicket || $request->reopenTicket || $request->acceptTicket || $request->rejectTicket) {
            $history = new TicketHistory;
            $history->ticketID = $id;
            $history->username = auth()->user()->name;
            $request->takeTicket != null ? $history->contents = __('dashboard_tickets.taken_by', ['username' => auth()->user()->name]) : null;
            $request->closeTicket != null ? $history->contents = __('dashboard_tickets.closed_by', ['username' => auth()->user()->name]) : null;
            $request->reopenTicket != null ? $history->contents = __('dashboard_tickets.reopened_by', ['username' => auth()->user()->name]) : null;
            $request->acceptTicket != null ? $history->contents = __('dashboard_tickets.accepted_by', ['username' => auth()->user()->name,
                                                                    'department' => $ticket->department, 'ticketID' => $ticket->department_ticketID]) : null;
            $request->rejectTicket != null ? $history->contents = __('dashboard_tickets.rejected_by', ['username' => auth()->user()->name]) : null;

            $history->save();
        }
        else if ($request->editTicket){
            $dirtyArray = array();
            $ticket->isDirty('department') == true ? array_push($dirtyArray, __('dashboard_tickets.department_changed', [
                'original' => $ticket->getOriginal('department'),
                'new' => $request->departmentSelect
                ])) : null;
            $ticket->isDirty('problem') == true ? array_push($dirtyArray, __('dashboard_tickets.problem_changed', [
                'original' => $ticket->getOriginal('problem'),
                'new' => $request->problemSelect
                ])) : null;
            $ticket->isDirty('priority') == true ? array_push($dirtyArray, __('dashboard_tickets.priority_changed', [
                'original' => $ticket->getOriginal('priority'),
                'new' => $request->prioritySelect
                ])) : null;
            $ticket->isDirty('owner') == true ? array_push($dirtyArray, __('dashboard_tickets.owner_changed', [
                'original' => $ticket->getOriginal('owner'),
                'new' => $request->ownerSelect
                ])) : null;

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
    public function addNote(Request $request, $id, $contents = null)
    {
        $contents = $contents == null ? $request->noteContents : $contents;

        Note::create(['ticketID' => $id,
            'username' => auth()->user()->name,
            'contents' => $contents]);

        $ticket = Ticket::find($id);
        $ticket->date_modified = new \DateTime('NOW');
        $ticket->save();

        return back()->with('message', __('dashboard_tickets.note_added'));
    }
}
