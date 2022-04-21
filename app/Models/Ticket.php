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
            'external_ticketID',
            'department_ticketID',
            'target_department'
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
