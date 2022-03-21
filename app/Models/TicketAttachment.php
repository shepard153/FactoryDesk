<?php

    namespace App\Models;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class TicketAttachment extends Model
    {
        use HasFactory;
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'Ticket_attachments';

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $primaryKey = 'attachmentID';

        public $timestamps = null;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'ticketID',
            'file_name',
            'file_path'
        ];
    }
