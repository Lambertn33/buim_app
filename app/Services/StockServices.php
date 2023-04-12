<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class StockServices
{
    public function getSampleExcel()
    {
        $filepath = public_path('files/stock_sample.xlsx');
        return Response::download($filepath); 
    }
}
