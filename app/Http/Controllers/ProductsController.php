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

    public function index(Request $request)
    {
        try{
            $res = $this->service->getProducts($request->category);

            if(!empty($res) && !is_null($res)){
                return apiSuccess($res);
            }else{
                return apiSuccess(null, "No hay data disponible");
            }

        }catch (\Exception $e){
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }

    public function search(Request $request)
    {
        try{
            $res = $this->service->getProductsOrProductSearching($request->search);

            if(!empty($res) && !is_null($res)){
                return apiSuccess($res);
            }else{
                return apiSuccess(null, "No hay data disponible");
            }

        }catch (\Exception $e){
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function ProductsPaginate(Request $request)
    {
        try{
            $res = $this->service->getProductsPaginate($request->size);

            if(!empty($res) && !is_null($res)){
                return apiSuccess($res);
            }else{
                return apiSuccess(null, "No hay data disponible");
            }

        }catch (\Exception $e){
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price' => 'required',
            'barcode' => 'required',
            'img' => 'required',
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
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        try{

            $this->service->editProduct($request);
            return apiSuccess(null, "El producto editada correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function delete(Request $request)
    {

        try{

            $this->service->deleteProduct($request);
            return apiSuccess(null, "Producto eliminado correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
}
