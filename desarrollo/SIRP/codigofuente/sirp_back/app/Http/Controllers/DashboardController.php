<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function sales(Request $request)
    {
        $sale = Sale::SelectRaw('year(sale_date) year, monthname(sale_date) month, count(*) data')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->get();

        $res = [
            'success' => 1,
            'response' => $sale
        ];

        return response()->json($res);

    }
}
