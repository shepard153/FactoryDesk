<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use App\Models\TicketAttachment;


class TicketAttachmentController extends Controller
{
    /**
     * @var TicketAttachment $attachments
     */
    protected static $attachments;

    /**
     * @var array $type
     */
    protected static $type;

    /**
     * @var array $imageExtensions
     */
    protected static $imageExtensions = ['jpg', 'jpeg', 'jfif', 'gif', 'png', 'bmp', 'svg', 'svgz',
        'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif',
        'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

    /**
     * Check if attachment is image file. If true, display image on ticket details page. If false, make file downloadable.
     *
     * @param array $attachments
     * @return array $type
     */
    public static function attachmentDisplay($attachments)
    {
        self::$attachments = $attachments;

        foreach (self::$attachments as $attachment){
            $infoPath = pathinfo('public/storage/' . $attachment->file_path.$attachment->file_name);

            $extension = $infoPath['extension'];
            if (in_array($extension, self::$imageExtensions))
            {
                self::$type[$attachment->file_name] = 'image';
            }
            else{
                self::$type[$attachment->file_name] = 'download';
            }
        }

        return self::$type;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public static function dropzoneUpload(Request $request, $id)
    {
        if ($request->has('file')){
            $i = TicketAttachment::where('ticketID', $id)->count();
            foreach ($request->file('file') as $attachment){
                $fileName = "ticket-$id-attachment-$i." . $attachment->getClientOriginalExtension();
                $attachment->storeAs('ticket_attachments', $fileName);
                $filePath = "ticket_attachments/";
                TicketAttachment::create(['ticketID' => $id, 'file_name' => $fileName, 'file_path'=>$filePath]);
                $i++;
            }

            return response()->json(['success' => $fileName]);
        }

        return null;
    }
}
