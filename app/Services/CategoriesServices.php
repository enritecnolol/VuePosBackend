<?php
namespace App\Services;

use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriesServices
{
    public function insertCategory($data)
    {

        $category = Category::create([
           'name' => strtoupper($data['name'])
        ]);

        return $category;
    }

    public function getCategories()
    {

        $category = Category::all()->makeHidden(['created_at', 'updated_at']);

        return $category;
    }

    public function getCategoriesPaginate($size)
    {

        $category = DB::connection('client')
            ->table('categories')
            ->select('id', 'name')
            ->paginate($size);

        return $category;
    }

    public function editCategory($data)
    {

        $category = Category::find($data->id);
        $category->name = strtoupper($data->name);
        $category->update();

        return $category;
    }

    public function deleteCategory($data)
    {

        $category = Category::find($data->id)->delete();

        return $category;
    }
}
