<?php
namespace App\Services;

use App\Invoice;
use App\InvoiceDetails;
use Illuminate\Support\Facades\DB;

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
    public function getSummary($from_date, $to_date)
    {
        $summary = DB::connection('client')->table('invoices')
            ->select(DB::raw('count(*) as quantity'), DB::raw('IFNULL(SUM(total), 0)as total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date]);

        return $summary->first();
    }
    public function getSalesPerMonth($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoices')
            ->select(DB::raw('MONTH(created_at) as _month'), DB::raw('IFNULL(SUM(total), 0)as total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('MONTH(created_at)'));

        $data = [];

        foreach($Sales->get() as $sale)
        {
            $obj = [
                'Ene.' => [0],
                'Feb.' => [0],
                'Mar.' => [0],
                'Abr.' => [0],
                'May.' => [0],
                'Jun.' => [0],
                'Jul.' => [0],
                'Ago.' => [0],
                'Sep.' => [0],
                'Oct.' => [0],
                'Nov.' => [0],
                'Dic.' => [0]
            ];

            $obj[getMonthName($sale->_month, true)] = [$sale->total];

            array_push($data, $obj);
        }

        return $data;
    }
    public function getDailySales($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoices')
            ->select(DB::raw('DAY(created_at) as _day'), DB::raw('MONTH(created_at) as _month'), DB::raw('IFNULL(SUM(total), 0)as total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('MONTH(created_at)'),DB::raw('DAY(created_at)'));

        return $Sales->get();
    }
    public function getSalesByCategory($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoice_details as id')
            ->select(DB::raw('SUM(total) as total'), DB::raw('c.id'), DB::raw('c.name'))
            ->leftJoin('products as p', 'id.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'p.categoria_id', '=', 'c.id')
            ->whereBetween(DB::raw('DATE(id.created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('p.categoria_id'));

        return $Sales->get();
    }
    public function getHourlySales($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoices')
            ->select(
                DB::raw('SUM(total) as total'),
                DB::raw('HOUR(invoices.created_at) as hour'),
                DB::raw('date_format(invoices.created_at, \'%h %p\') as hour_format')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('HOUR(invoices.created_at)'),DB::raw('hour_format'))
        ->orderBy('hour', 'ASC');

        return $Sales->get();
    }
    public function getSalesByDaysOfTheWeek($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoices')
            ->select(
                DB::raw('(ELT(WEEKDAY(created_at) + 1, \'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\')) AS days_num'),
                DB::raw('(ELT(WEEKDAY(created_at) + 1, \'Lunes\', \'Martes\', \'Miercoles\', \'Jueves\', \'Viernes\', \'Sabado\', \'Domingo\')) AS days'),
                DB::raw('SUM(total) AS total')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('days'),DB::raw('days_num'))
        ->orderBy('days_num', 'ASC');

        return $Sales->get();
    }
    public function getMostSellingProducts($from_date, $to_date)
    {
        $Sales = DB::connection('client')->table('invoice_details as invoice_d')
            ->select(
                DB::raw('count(invoice_d.quantity) as count'),
                DB::raw('sum(total) as total'),
                DB::raw('product_id'),
                DB::raw('prod.name')
            )
            ->leftJoin('products as prod', 'invoice_d.product_id', '=', 'prod.id')
            ->whereBetween(DB::raw('DATE(invoice_d.created_at)'), [$from_date, $to_date])
            ->groupBy(DB::raw('invoice_d.product_id '))
        ->orderBy('count', 'DESC')->limit(5);

        return $Sales->get();
    }

}
