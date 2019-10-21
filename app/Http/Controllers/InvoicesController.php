<?php

namespace App\Http\Controllers;

use App\Services\InvoicesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoicesController extends Controller
{
    private $service;

    public function __construct(InvoicesServices $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total'=> 'required',
            'cash' => 'required',
            'returns' => 'required',
            'products' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->toJson(), 400);
        }

        DB::beginTransaction();
        try{

            $invoice = $this->service->CreateInvoice($request);
            $invoice_details = $this->service->InsertInvoiceDetails($request['products'], $invoice);

            DB::commit();
            return apiSuccess(null, "Factura realizada con exito !!");
        }catch (\Exception $e)
        {
            DB::rollBack();
            return apiError(null, $e->getMessage(), $e->getCode());
        }


    }
    public function summary(Request $request)
    {
        try{

            $res = $this->service->getSummary($request['from_date'], $request['to_date']);

            return apiSuccess($res);
        }catch (\Exception $e)
        {
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }

    public function SalesPerMonth(Request $request)
    {
        try{

            $res = $this->service->getSalesPerMonth($request['from_date'], $request['to_date']);

            return apiSuccess($res);
        }catch (\Exception $e)
        {
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
    public function DailySales(Request $request)
    {
        try{

            $res = $this->service->getDailySales($request['from_date'], $request['to_date']);

            return apiSuccess($res);
        }catch (\Exception $e)
        {
            return apiError(null, $e->getMessage(), $e->getCode());
        }
    }
}
