<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $status = 200;
    public function index(Request $request)
    {
        $request->limit? $p = $request->limit : $p = 10;
        $request->order? $order = $request->order : $order = 'desc';
        $user = TokenController::getUserbyToken($request->header('token'));
        $usr = User::find($user->user_id);

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
}
