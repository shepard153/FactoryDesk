<?php

return [
    'page_title' => 'Tickets',

    /**
     * Tickets list
     */
    'filter_awaiting' => 'Awaiting',
    'filter_new' => 'New',
    'filter_in_progress' => 'In progress',
    'filter_closed' => 'Closed',
    'filter_active' => 'Active',
    'pagination_displaying' => 'Displaying :start - :end from :total tickets.',
    'pagination_of' => 'of :last_page',
    'table_ID' => 'ID',
    'table_status' => 'Status',
    'table_zone' => 'Zone',
    'table_position' => 'Position',
    'table_problem' => 'Problem',
    'table_device' => 'Device',
    'table_date_created' => 'Date created',
    'table_date_modified' => 'Date modified',
    'table_date_closed' => 'Date closed',
    'table_owner' => 'Person responsible',
    'table_nothing_found' => 'No tickets found',

    /**
     * Ticket detials
     */
    'ticket_header' => 'Ticket details',
    'awaiting_header' => 'Ticket awaiting acceptance',
    'awaiting_accept_desc' => "<b>Accept ticket</b> - ticket will be forwarded to department displayed in <b>Department</b> select option.",
    'awaiting_reject_desc' => '<b>Reject ticket</b> - ticket will be closed.',
    'awaiting_take_desc' => '<b>Take ticket</b> - in case you can resolve ticket on your own.',
    'navtab_ticket' => 'Ticket',
    'navtab_add_note' => 'Add note',
    'navtab_ticket_history' => 'Ticket history',
    'date_taken' => 'Date taken',
    'pill_closed_permamently' => 'Closed permamently',
    'raised_by' => 'Raised by',
    'department' => 'Department',
    'priority' => 'Priority',
    'priority_low' => 'Notification',
    'priority_medium' => 'Standard',
    'priority_high' => 'Critical',
    'external_ticket' => 'External ticket ID',
    'time_spent_on' => 'Time spent on resolving',
    'timer_minutes_button' => 'minutes',
    'accept_ticket' => 'Accept ticket',
    'reject_ticket' => 'Reject ticket',
    'take_ticket' => 'Take ticket',
    'save_button' => 'Save changes',
    'close_ticket' => 'Close ticket',
    'reopen_ticket' => 'Reopen ticket',
    'modal_confirm' => 'Confirm',
    'modal_cancel' => "Cancel",
    'close_ticket_note' => 'Add short note before closing ticket (max 250 characters)',
    'accept_ticket_note' => 'Add short note before accepting ticket (max 250 characters)',
    'attachments' => 'Attachments',
    'download_attachment' => 'Download attachment',
    'no_attachments' => 'No attachments found',
    'ticket_message' => 'Ticket message',
    'no_message' => 'No message',
    'add_note_label' => 'Note (max 250 characters)',
    'add_note_button' => 'Add note',
    'note' => 'Note added :created_at by :username',
    'edit_header' => 'Edited by :username on :date',
    'ticket_type' => 'Ticket type',
    'ticket_type_valid' => 'Valid',
    'ticket_type_invalid' => 'Invalid',

    /**
     * Ticket action notifications
     */
    'ticket_taken' => 'Ticket taken',
    'ticket_closed' => 'Ticket closed',
    'ticket_rejected' => 'Ticket rejected',
    'ticket_accepted' => 'Ticket accepted',
    'ticket_reopened' => 'Ticket reopened',
    'ticket_saved' => 'Changes has been saved',
    'note_added' => 'Note added',

    /**
     * History messages
     */
    'taken_by' => 'Ticket taken by :username',
    'closed_by' => 'Ticket closed by :username',
    'reopened_by' => 'Ticket reopened by :username',
    'rejected_by' => 'Ticket rejected by :username',
    'accepted_by' => 'Ticket accepted by :username and sent to :department department with ID: :ticketID',
    'department_changed' => 'Department changed from :original to :new',
    'problem_changed' => 'Problem changed from :original to :new',
    'priority_changed' => 'Priority changed from :original to :new',
    'owner_changed' => 'Person responsible changed from :original to :new',
    'external_ticket_set' => 'Ticket has been set as external with ID: :externalID',

    /**
     * My tickets
     */
    'my_tickets_page_title' => 'My tickets',
    'my_statistics' => 'My statistics',
    'last_taken' => 'Last taken tickets',
    'all_taken' => 'All taken tickets',
    'last_closed' => 'Last closed tickets',
    'all_closed' => 'All closed tickets',
];
