<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use App\Models\Ticket;

class ReporterController extends Controller
{
    /**
     * Render view for reporter.
     *
     * @return string $pageTitle
     */
    public function reporter()
    {
        $pageTitle = __('dashboard_reporting.page_title');

        return view('dashboard/reporter', ['pageTitle' => $pageTitle]);
    }

    /**
     * Generate report and export to selected format.
     *
     * @param Request $request
     * @return Response
     */
    public function getReport(Request $request)
    {
        $columns = [];

        foreach ($request->request as $k => $v){
            if (str_contains($k, 'is')){
                if (config('database.default') == 'pgsql'){
                    $columns [] = "\"$v\"";
                }
                else{
                    $columns [] = "$v";
                }
            }
        }

        if (auth()->user()->department == 'All'){
            $items = Ticket::selectRaw(implode(',', $columns))
            ->whereDate('date_created', '>=', Carbon::create($request->startDate)->toDateString())
            ->whereDate('date_created', '<=', Carbon::create($request->startDate)->addDay()->toDateString())
            ->get()
            ->toArray();
        }
        else{
            $items = Ticket::selectRaw(implode(',', $columns))
            ->where('department', auth()->user()->department)
            ->whereDate('date_created', '>=', Carbon::create($request->startDate)->toDateString())
            ->whereDate('date_created', '<=', Carbon::create($request->startDate)->addDay()->toDateString())
            ->get()
            ->toArray();
        }

        switch ($request->fileFormat){
            case 'csv':
                $this->exportToCsv($columns, $items);
                break;
            default:
                break;
        }

        return back()->with('message', __('dashboard_reporting.report_generated'));
    }

    /**
     * Export report data to CSV format. Remove quotation marks from column names, translate ticket type
     * and name file with current date.
     *
     * @param array $columns
     * @param array $items
     * @return void
     */
    public function exportToCsv($columns, $items)
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->setDelimiter(";");
        $columns = preg_replace('/(^[\"\']|[\"\']$)/', "", $columns);
        $csv->insertOne($columns);

        foreach ($items as $item){
            if ($item['ticket_type'] == 'valid'){
                $item['ticket_type'] = __('dashboard_tickets.ticket_type_valid');
            }
            else if ($item['ticket_type'] == null){
                $item['ticket_type'] = '';
            }
            else{
                $item['ticket_type'] = __('dashboard_tickets.ticket_type_invalid');
            }

            if ($item['date_created']){
                $item['date_created'] = Carbon::create($item['date_created'])->toDateTimeString();
            }

            $csv->insertOne($item);
        }

        $flush_threshold = 1000;
        $content_callback = function () use ($csv, $flush_threshold) {
            foreach ($csv->chunk(1024) as $offset => $chunk) {
                echo $chunk;
                if ($offset % $flush_threshold === 0) {
                    flush();
                }
            }
        };

        $date = Carbon::create('now')->format('YmdHi');

        $response = new StreamedResponse();
        $response->headers->set('Content-Encoding', 'none');
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8 BOM');

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            config('app.name')."_$date.csv"
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Description', 'File Transfer');
        $response->setCallback($content_callback);
        echo "\xEF\xBB\xBF";
        $response->send();

        exit;
    }
}
