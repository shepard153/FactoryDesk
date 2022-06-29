<?php

return [
    'select_department' => 'Choose deparment you want to contact.',
    'device_name' => 'Device name',
    'username' => 'Username',
    'zone_label' => 'Manufacturing zone',
    'zone_select_default' => "Choose manufacturing zone",
    'position_label' => 'Position',
    'position_select_default' => "Choose your position",
    'problem_label' => 'Problem',
    'problem_select_default' => "Choose problem",
    'message_box_label' => 'Your message (up to 500 characters) (optional)',
    'priority_label' => 'Priority (optional)',
    'priority_low' => 'Notification',
    'priority_medium' => 'Standard',
    'priority_high' => 'Critical',
    'priority' => 'Priority',
    'priorities_desc' => 'Effect',
    'priority_low_desc' => 'Remarks, ideas, upgrades, modifications, etc.',
    'priority_medium_desc' => 'PRODUCTION PROCESS IS NOT IN DANGER - Issue makes significant problems for production process.',
    'priority_high_desc' => 'PRODUCTION STOPPED OR IN HIGH RISK - Intervention expected as soon as possible.',
    'submit_form' => 'Submit',
    'submitting' => 'Submitting...',
    'ticket_sent_message' => 'Ticket was successfully submitted. Ticket ID: <strong><u>:ticketID</u></strong>. <br/>',
    'ticket_sent_acceptance_message' => "<br/> Ticket will be forwarded to <strong>:targetDepartment</strong>
        department after verification and approval by an <strong>:department</strong> department employee.<br/>
        Please inform your supervisor.",
    'close_form' => 'Close window',

    /**
     * Teams notifications
     */
    'teams_message_title' => 'Ticket :ticketID',
    'teams_message_subtitle' => 'Created on :dateCreated',
    'teams_message_text' => 'Zone: :zone. Position: :position. Problem: :problem.',
    'teams_message_url' => 'Ticket URL',
];
