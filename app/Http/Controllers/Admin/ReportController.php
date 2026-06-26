<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{

    public function export(): StreamedResponse
    {

        $transactions = Transaction::with([
            'customer',
            'service'
        ])->get();


        $filename = 'laporan-laundry-' . date('Y-m-d') . '.csv';


        return response()->streamDownload(function () use ($transactions) {

            $handle = fopen('php://output', 'w');


            fputcsv($handle, [
                'Invoice',
                'Pelanggan',
                'Layanan',
                'Total',
                'Status',
                'Tanggal'
            ]);


            foreach ($transactions as $trx) {

                fputcsv($handle, [

                    $trx->invoice_number,

                    $trx->customer->name ?? '-',

                    $trx->service->name ?? '-',

                    $trx->total_amount,

                    $trx->status,

                    $trx->created_at

                ]);

            }


            fclose($handle);


        }, $filename);

    }

}
