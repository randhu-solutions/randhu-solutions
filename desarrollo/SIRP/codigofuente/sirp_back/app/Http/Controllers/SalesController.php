<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SaleDetail;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public $status = 200;
    public function index(Request $request)
    {
        $request->limit? $p = $request->limit : $p = 10;
        $request->order? $order = $request->order : $order = 'desc';

        $sale = Sale::with('detail')->orderBy('created_at',$order)
            ->paginate($p);

        $res = [
            'success' => 1,
            'response' => $sale
        ];
        return response()->json($res);
    }

    public function store(Request $request)
    {

    }

    public function show(Request $request)
    {
        if(!$request->search)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro search no recibido'
            ];
            $this->status = 500;
        }
        else
        {
            $search = explode('-',$request->search);
            $sale = Sale::with('detail')->where([
                ['serie',$search[0]],
                ['number',$search[1]]])
                ->orderBy('created_at','asc')
                ->get();

            $res = [
                'success' => 1,
                'response' => $sale
            ];
        }
        return response()->json($res ,$this->status);
    }

    public function destroy(Request $request)
    {
        if(!$request->sale_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro sale_id no recibido'

            ];
            $this->status = 500;
        }
        else
        {
            $sale = Sale::with('detail')
                ->where('sale_id',$request->sale_id)
                ->first();
            if($sale){

                $detail = SaleDetail::where('sale_id',$request->sale_id)->get();
                if($detail)
                {
                    foreach ($detail as $det)
                    {
                        $det->delete();
                    }
                }
                $sale->delete();

                $res = [
                    'success' => 1,
                    'msg' => 'Venta eliminada con exito'
                ];
            }
            else
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Venta no encontrado'
                ];
                $this->status = 500;
            }
        }
        return response()->json($res,$this->status);
    }
}
