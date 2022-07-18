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

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_modified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'device_name',
        'department',
        'zone',
        'position',
        'problem',
        'ticket_message',
        'priority',
        'username',
        'time_spent',
        'department_ticketID',
        'target_department',
        'ticket_type',
        'closing_notes',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
      */
    protected $attributes = [
        'ticket_status' => 0,
    ];
}
