<?php

namespace App\Http\Controllers;

use App\Productbrand;
use Illuminate\Http\Request;

class ProductBrandController extends Controller
{
    public $status = 200;
    public function index(Request $request)
    {
        $request->limit? $p = $request->limit : $p = 10;
        $request->order? $order = $request->order : $order = 'desc';

        $products = Productbrand::orderBy('created_at',$order)
            ->paginate($p);

        $res = [
            'success' => 1,
            'response' => $products
        ];
        return response()->json($res);
    }

    public function store(Request $request)
    {
        if(!$request->brand_name)
        {
            $res = [
                'success' => 0,
                'msg' => 'parametro brand_name no recibido'
            ];
            $this->status = 500;
        }
        else
        {
            $validate = Productbrand::where('brand_name',$request->brand_name)->first();
            if($validate)
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Marca con nombre '.$request->brand_name.' ya existe'
                ];
                $this->status = 500;
            }
            else
            {
                $product = new Productbrand();
                $product->brand_name = $request->brand_name;
                $product->save();

                $res = [
                    'success' => 1,
                    'msg' => 'Marca creada con exito'
                ];
            }
        }
        return response()->json($res, $this->status);
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
            $search = '%'.$request->search.'%';
            $brand = Productbrand::where('brand_name','like',$search)
                ->orderBy('created_at','asc')
                ->get();

            $res = [
                'success' => 1,
                'response' => $brand
            ];
        }
        return response()->json($res ,$this->status);
    }

    public function destroy(Request $request)
    {
        if(!$request->brand_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro brand_id no recibido'

            ];
            $this->status = 500;
        }
        else
        {
            $brand = Productbrand::where('brand_id',$request->brand_id)->first();

            if($brand){
                $brand->delete();
                $res = [
                    'success' => 1,
                    'msg' => 'Marca eliminada con exito'
                ];
            }
            else
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Marca no encontrado'
                ];
                $this->status = 500;
            }
        }
        return response()->json($res,$this->status);
    }
}
