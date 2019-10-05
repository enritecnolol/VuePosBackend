<?php

namespace App\Http\Controllers;

use App\Product;
use App\Services\ProductsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    private $service;

    public function __construct(ProductsServices $service)
    {
        $this->service = $service;
    }

    public function index()
    {

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price' => 'required',
            'barcode' => 'required',
            'img' => 'required',
            'existence' => 'required',
            'tax' => 'required',
            'category' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        if(Product::where('barcode', $request->barcode)->first() != null){
            return apiError(null, "Este codigo ya existe", 201);
        }

        try{

            $this->service->insertProduct($request);
            return apiSuccess(null, "Producto insertado correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }

    }
    public function edit()
    {

    }
}
