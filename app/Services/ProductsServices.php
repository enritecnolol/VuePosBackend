<?php
namespace App\Services;

use App\Product;
use Illuminate\Support\Facades\DB;

class ProductsServices
{
    public function insertProduct($data){

        $product  = new Product();
        $product->name = strtoupper($data['name']);
        $product->price = $data['price'];
        $product->barcode = $data['barcode'];
        $product->img = $data['img'];
        $product->categoria_id = $data['category']['id'];
        $product->save();

        return $product;
    }

    public function getProductsPaginate($size)
    {
        $products = DB::connection('client')
            ->table('products')
            ->select('id', 'img','name', 'price', 'barcode', 'categoria_id')
            ->where('status', true)
            ->paginate($size);

        return $products;
    }

    public function getProducts($category)
    {
        $products = DB::connection('client')
            ->table('products')
            ->select('id', 'img','name', 'price', 'barcode', 'categoria_id')
            ->where('status', true);

        if($category){
            $products->where('categoria_id', $category);
        }

        return $products->get();
    }

    public function getProductsOrProductSearching($search)
    {

        $products = DB::connection('client')
            ->table('products')
            ->select('id', 'img','name', 'price', 'barcode', 'categoria_id')
            ->where('barcode', $search)->orWhere('name', 'like', '%' . $search . '%');


        return $products->get();
    }
    public function editProduct($data)
    {
        $product = Product::find($data->id);
        $product->name = strtoupper($data->name);
        $product->img = $data->img;
        $product->price = $data->price;
        $product->barcode = $data->barcode;
        $product->categoria_id = $data['category']['id'];

        $product->update();

        return $product;
    }

    public function deleteProduct($data)
    {

        $product = Product::find($data->id);
        $product->status = false;
        $product->update();

        return $product;
    }
}
