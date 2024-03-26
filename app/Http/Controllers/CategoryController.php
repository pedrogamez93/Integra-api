<?php

namespace App\Http\Controllers;

use App\Category;
use App\EducationalMaterial;
use App\Helpers\UtilHelper;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->utilHelper = new UtilHelper();
    }
    public function index()
    {
        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'categories' => Category::get(),
                'status' => true,
            ],
        ]);
    }

    public function categoriesItem($slug)
    {
        $page = request()->query('page', 1);
        $category = Category::where('slug', $slug)->first();

        $educationalMaterial = EducationalMaterial::whereIn('is_activity', [1, 0])
            ->orderBy('datetime', 'desc')
            ->where('datetime', '<=', $this->utilHelper->getDateCurrent())
            ->where('category_id', $category->id)
            ->paginate(5);

        foreach ($educationalMaterial as $key => $value) {
            $value->img = [
                "src" => $value->getFirstMedia('image_educational_material') ? env('APP_URL') . $value->getFirstMediaUrl('image_educational_material', 'thumb') : '',
                "alt" => $value->getFirstMedia('image_educational_material') ? $value->getFirstMedia('image_educational_material')->name : '',
            ];

            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            unset($value->datetime);
            $value->date = $date;
            unset($value->media);
            unset($value->src);
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'educational_material' => $educationalMaterial,
                'status' => true,
            ],
        ]);

    }
}
