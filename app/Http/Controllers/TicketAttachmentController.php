<?php

    namespace app\Http\Controllers;

    use Illuminate\Support\Facades\Storage;
    use App\Http\Controllers\TicketController;
    use App\Models\Ticket;
    use App\Models\TicketAttachment;


    Class TicketAttachmentController extends Controller
    {
        /**
         * @var TicketAttachment $attachment
         */
        protected static $attachment;

        protected static $attachmentMimeImage = ['jpg', 'png', 'gif'];

        /**
         *
         *
         * @param TicketAttachment $attachment
         */
        public static function attachmentDisplay($attachment)
        {
            self::$attachment = $attachment;

            $infoPath = pathinfo('public/storage/'.self::$attachment->file_path.self::$attachment->file_name);

            $extension = $infoPath['extension'];
            if (in_array($extension, self::$attachmentMimeImage))
            {
                $type = 'image';
            }
            else{
                $type = 'download';
            }

            return $type;
        }
    }
