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

        function insertAttachmentData($ticketID, $fileName, $filePath)
        {
            DB::table('Ticket_attachments')->insert(['ticketID' => $ticketID, 'file_name' => $fileName, 'file_path' => $filePath]);
        }

    }
