<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Ticket;

class ReporterController extends Controller
{
    public function reporter()
    {
        $pageTitle = "Raportowanie";

        return view('dashboard/reporter', ['pageTitle' => $pageTitle]);
    }

    public function getReport(Request $request)
    {
        $columns = [];

        foreach ($request->request as $k => $v){
            if (str_contains($k, 'is')){
                $columns [] = "$v";
            }
        }

        $items = Ticket::selectRaw(implode(',', $columns))->where('department', auth()->user()->department)->get()->toArray();

        switch ($request->fileFormat){
            case 'csv':
                $this->exportToCsv($columns, $items);
                break;
            case 'pdf':
                break;
        }

        return back()->with('message', "Raport wygenerowany.");
    }

    public function exportToCsv($columns, $items)
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->setDelimiter(";");
        $csv->insertOne($columns);
        foreach ($items as $item){
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

        $date = new \DateTime('now');
        $date = $date->format('YmdHi');

        $response = new StreamedResponse();
        $response->headers->set('Content-Encoding', 'none');
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8 BOM');

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            "report$date.csv"
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Description', 'File Transfer');
        $response->setCallback($content_callback);
        echo "\xEF\xBB\xBF";
        $response->send();
        die;
    }
}
