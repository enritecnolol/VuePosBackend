<?php

namespace App\Http\Controllers;

use App\Services\CompaniesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    private $service;

    public function __construct(CompaniesServices $service)
    {
        $this->service = $service;
    }

    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'logo' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        DB::connection('client')->beginTransaction();
        try{
            $this->service->createCompany($request);
            DB::connection('client')->commit();
            return apiSuccess(null, "Compañia registrada con exito !!");
        }catch (\Exception $e)
        {
            DB::connection('client')->rollBack();
            return apiError(null, $e->getMessage(), $e->getCode());
        }

    }

    public function edit (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'logo' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        DB::connection('client')->beginTransaction();
        try{
            $this->service->editCompany($request);
            DB::connection('client')->commit();
            return apiSuccess(null, "Compañia registrada con exito !!");
        }catch (\Exception $e)
        {
            DB::connection('client')->rollBack();
            return apiError(null, $e->getMessage(), $e->getCode());
        }

    }

    public function index ()
    {
        try{

            $res = $this->service->getCompany();

            if(!empty($res) && !is_null($res))
            {
                return apiSuccess($res);
            }else{
                return apiSuccess("[]", "No hay data disponible");
            }
        }catch (\Exception $e)
        {
            return apiError(null, $e->getMessage(), $e->getCode());
        }

    }
}
