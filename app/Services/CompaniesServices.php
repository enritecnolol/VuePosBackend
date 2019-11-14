<?php
namespace App\Services;

use App\Company;
use App\Invoice;
use App\InvoiceDetails;
use Illuminate\Support\Facades\DB;

class CompaniesServices {

    public function createCompany($data)
    {
        $company = new Company();
        $company->name = $data['name'];
        $company->address =  $data['address'];
        $company->country =  $data['country'];
        $company->city =  $data['city'];
        $company->logo =  $data['logo'];
        $company->phone =  $data['phone'];
        $company->email =  $data['email'];
        $company->save();

        return $company;
    }

    public function editCompany($data)
    {
        $company = Company::find($data['id']);
        $company->name = $data['name'];
        $company->address =  $data['address'];
        $company->country =  $data['country'];
        $company->city =  $data['city'];
        $company->logo =  $data['logo'];
        $company->phone =  $data['phone'];
        $company->email =  $data['email'];
        $company->update();

        return $company;
    }

    public function getCompany()
    {
        $db =  DB::connection('client');

        $query = $db->table('companies')->first();

        return $query;
    }
}
