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

        /**
         * @var array $imageExtensions
         */
        protected static $imageExtensions = ['jpg', 'jpeg', 'jfif', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

        /**
         * Check if attachment is image file. If true, display image on ticket details page. If false, make file downloadable.
         *
         * @param TicketAttachment $attachment
         */
        public static function attachmentDisplay($attachment)
        {
            self::$attachment = $attachment;

            $infoPath = pathinfo('public/storage/'.self::$attachment->file_path.self::$attachment->file_name);

            $extension = $infoPath['extension'];
            if (in_array($extension, self::$imageExtensions))
            {
                $type = 'image';
            }
            else{
                $type = 'download';
            }

            return $type;
        }
    }
