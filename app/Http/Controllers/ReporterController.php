<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Codedge\Fpdf\Fpdf\Fpdf;

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

        $items = Ticket::selectRaw(implode(',', $columns))
            ->where('department', auth()->user()->department)
            ->whereBetween('date_created', [$request->startDate, $request->endDate])
            ->get()
            ->toArray();

        switch ($request->fileFormat){
            case 'csv':
                $this->exportToCsv($columns, $items);
                break;
            case 'pdf':
                $this->exportToPDF($columns, $items);
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
            "rugdeskreport_$date.csv"
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Description', 'File Transfer');
        $response->setCallback($content_callback);
        echo "\xEF\xBB\xBF";
        $response->send();
        exit;
    }

    public function exportToPdf($columns, $items)
    {
        $pdf = new FPDF();
        $pdf->AddPage('O');
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        // Header
        $w = array(40, 30, 25, 45, 40, 30, 25, 45, 45, 45, 40, 45, 35, 35, 40, 40);
        for($i=0;$i<count($columns);$i++)
            $pdf->Cell($w[$i],7,$columns[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;
        foreach($items as $row)
        {
            $pdf->Cell($w[0],6,$row['device_name'],'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,$row['username'],'LR',0,'L',$fill);
            $pdf->Cell($w[2],6,$row['zone'],'LR',0,'R',$fill);
            $pdf->Cell($w[3],6,$row['position'],'LR',0,'R',$fill);
            $pdf->Cell($w[4],6,$row['problem'],'LR',0,'R',$fill);
            $pdf->Cell($w[5],6,$row['external_ticketID'],'LR',0,'R',$fill);
            $pdf->Cell($w[6],6,$row['priority'],'LR',0,'R',$fill);
            $pdf->Cell($w[7],6,$row['owner'],'LR',0,'R',$fill);
            if($pdf->GetX() < 230)
{
$pdf->Cell($length,7,$parameter,1,0,'C',1);
}
            $pdf->Ln();
            $fill = !$fill;
        }
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');

        $pdf->Output();
        exit;
    }
}
