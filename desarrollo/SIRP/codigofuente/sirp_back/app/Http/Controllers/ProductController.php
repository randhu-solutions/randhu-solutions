<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $status = 200;
    public function index(Request $request)
    {
        $request->limit? $p = $request->limit : $p = 10;
        $request->order? $order = $request->order : $order = 'desc';

        $products = Product::with('category','brand')
            ->orderBy('created_at',$order)
            ->paginate($p);

        $res = [
            'success' => 1,
            'response' => $products
        ];
        return response()->json($res);
    }

    public function store(Request $request)
    {
        if(!$request->product_name)
        {
            $res = [
                'success' => 0,
                'msg' => 'parametro product_name no recibido'
            ];
            $this->status = 500;
        }
        elseif(!$request->price)
        {
            $res = [
                'success' => 0,
                'msg' => 'parametro price no recibido'
            ];
            $this->status = 500;
        }
        elseif(!$request->product_code)
        {
            $res = [
                'success' => 0,
                'msg' => 'parametro product_code no recibido'
            ];
            $this->status = 500;
        }
        else
        {
            $validate = Product::where('product_code',$request->product_code)->first();
            if($validate)
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Producto con codigo '.$request->product_code.' ya existe'
                ];
                $this->status = 500;
            }
            else
            {
                $product = new Product();
                $product->product_name = $request->product_name;
                $product->product_code = $request->product_code;
                $product->category_id = $request->category_id;
                $product->brand_id = $request->brand_id;
                $product->price = $request->price;
                $product->save();

                $res = [
                    'success' => 1,
                    'msg' => 'Producto creado con exito'
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
            $products = Product::with('category','brand')
                ->where('product_name','like',$search)
                ->orwhere('product_code','like',$search)
                ->orderBy('created_at','asc')
                ->get();

            $res = [
                'success' => 1,
                'response' => $products
            ];
        }
        return response()->json($res ,$this->status);
    }

    public function update(Request $request)
    {
        if(!$request->product_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro product_id no recibido'
            ];
            $this->status = 500;
        }
        else
        {
            $product = Product::where('product_id', $request->product_id)->first();

            if($product)
            {
                if (!empty($request->product_code)){
                    $code = Product::where('product_code', $request->product_code)->first();
                    if($code)
                    {
                        $res = [
                            'success' => 0,
                            'msg' => 'Ya existe un producto registrado con el codigo'.$request->product_code
                        ];
                        return response()->json($res,500);
                    }
                    else
                    {
                        $product->product_code = $request->product_code;
                    }

                }
                if (!empty($request->product_name)) {
                    $product->product_name = $request->product_name;
                }
                if (!empty($request->category)) {
                    $product->category_id = $request->category;
                }
                if (!empty($request->brand_id)) {
                    $product->brand_id = $request->brand;
                }
                if (!empty($request->price)) {
                    $product->price = $request->price;
                }

                $product->update();

                $res = [
                    'success' => 1,
                    'msg' => 'Producto Actualizado con Exito'
                ];
            }
            else
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Producto no encontrado'
                ];
                $this->status = 500;
            }
        }
        return response()->json($res);
    }

    public function destroy(Request $request)
    {
        if(!$request->product_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro product_id no recibido'

            ];
            $this->status = 500;
        }
        else
        {
            $product = Product::where('product_id',$request->product_id)->first();

            if($product){
                $product->delete();
                $res = [
                    'success' => 1,
                    'msg' => 'Producto eliminado con exito'
                ];
            }
            else
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Producto no encontrado'
                ];
                $this->status = 500;
            }
        }
        return response()->json($res,$this->status);
    }

    public function productIA(Request $request)
    {
        if(!$request->product_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro product_id no recibido'

            ];
            $this->status = 500;
        }
        else
        {
            $api = new Client;
            try
            {

                $product = $api->request('POST',
                    'https://ia-product-recommendation.herokuapp.com/product/'.$request->product_id);

                $data = json_decode($product->getbody(),TRUE);

            }
            catch (BadResponseException $e)
            {
                $data = $e->getCode();

            }
            if($data == 500)
            {
                $res = [
                    'success' => 0,
                    'msg' => 'No hay productos recomendados'
                ];
                $this->status = $data;
            }
            else
            {
                $res= [
                    'success' => 1,
                    'response' => $data
                ];
            }
        }
        return response()->json($res,$this->status);
    }


}

