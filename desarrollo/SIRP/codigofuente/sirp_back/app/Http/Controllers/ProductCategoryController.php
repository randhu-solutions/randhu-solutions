<?php

namespace App\Http\Controllers;

use App\Productcategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public $status = 200;
    public function index(Request $request)
    {
        $request->limit? $p = $request->limit : $p = 10;
        $request->order? $order = $request->order : $order = 'desc';

        $category = Productcategory::orderBy('created_at',$order)
            ->paginate($p);

        $res = [
            'success' => 1,
            'response' => $category
        ];
        return response()->json($res);
    }

    public function store(Request $request)
    {
        if(!$request->category_name)
        {
            $res = [
                'success' => 0,
                'msg' => 'parametro category_name no recibido'
            ];
            $this->status = 500;
        }
        else
        {
            $validate = Productcategory::where('category_name',$request->category_name)->first();
            if($validate)
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Categoria con nombre '.$request->category_name.' ya existe'
                ];
                $this->status = 500;
            }
            else
            {
                $category = new Productcategory();
                $category->category_name = $request->category_name;
                $category->save();

                $res = [
                    'success' => 1,
                    'msg' => 'Categoria creada con exito'
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
            $category = Productcategory::where('category_name','like',$search)
                ->orderBy('created_at','asc')
                ->get();

            $res = [
                'success' => 1,
                'response' => $category
            ];
        }
        return response()->json($res ,$this->status);
    }

    public function destroy(Request $request)
    {
        if(!$request->category_id)
        {
            $res = [
                'success' => 0,
                'msg' => 'Parametro category_id no recibido'

            ];
            $this->status = 500;
        }
        else
        {
            $category = Productcategory::where('category_id',$request->category_id)->first();

            if($category){
                $category->delete();
                $res = [
                    'success' => 1,
                    'msg' => 'Categoria eliminada con exito'
                ];
            }
            else
            {
                $res = [
                    'success' => 0,
                    'msg' => 'Categoria no encontrado'
                ];
                $this->status = 500;
            }
        }
        return response()->json($res,$this->status);
    }
}
