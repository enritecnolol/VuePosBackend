<?php

namespace App\Http\Controllers;

use App\Category;
use App\Services\CategoriesServices;
use Composer\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    private $service;

    public function __construct(CategoriesServices $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try{
            $res = $this->service->getCategories();

            if(!empty($res) && !is_null($res)){
                return apiSuccess($res);
            }else{
                return apiSuccess(null, "No hay data disponible");
            }

        }catch (\Exception $e){
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function CategoriesPaginate(Request $request)
    {

        try{
            $res = $this->service->getCategoriesPaginate($request->size);

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
            'name'=> 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        if(Category::where('name', strtoupper($request->name))->first() != null){
            return apiError(null, "Esta categoria ya existe", 201);
        }

        try{

            $this->service->insertCategory($request);
            return apiSuccess(null, "Categoria insertada correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        if(Category::where('name', $request->name)->first()){
            return apiError(null, "Esta categoria ya existe", 201);
        }

        try{

            $this->service->editCategory($request);
            return apiSuccess(null, "Categoria editada correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }

    public function delete(Request $request)
    {

        try{

            $this->service->deleteCategory($request);
            return apiSuccess(null, "Categoria eliminada correctamente");

        }catch (\Exception $e){

            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
}
