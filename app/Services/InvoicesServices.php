<?php
namespace App\Services;

use App\Invoice;
use App\InvoiceDetails;

class InvoicesServices {

    public function CreateInvoice($data)
    {

        $invoice = Invoice::create([
            'total' => $data['total'],
            'cash' => $data['cash'],
            'returns' => $data['returns'],
        ]);

        return $invoice;
    }

    public function InsertInvoiceDetails($products, $invoice)
    {
        foreach ($products as $product)
        {
            $invoice_details = new InvoiceDetails();

            $invoice_details->quantity  = $product['quantity'];
            $invoice_details->price  = $product['price'];
            $invoice_details->total  = $product['total'];
            $invoice_details->product_id  = $product['id'];
            $invoice_details->invoice_id  = $invoice->id;

            $invoice_details->save();
        }

        return $invoice_details;
    }

}
