<?php
namespace App\Services;

use App\Product;

class ProductsServices
{
    public function insertProduct($data){

        $product  = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->barcode = $data['barcode'];
        $product->img = $data['img'];
        $product->existence = $data['existence'];
        $product->tax = $data['tax'];
        $product->categoria_id = $data['category'];
        $product->save();

        return $product;
    }
}
