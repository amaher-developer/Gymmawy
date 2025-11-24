<?php

namespace Modules\Addon\Http\Controllers\Front;

use Modules\Addon\Models\CalorieFood;
use Modules\Addon\Repositories\CalorieCategoryRepository;
use Modules\Addon\Repositories\CalorieFoodRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;

class CalorieFoodFrontController extends GenericFrontController
{

    public $calorieFoodRepository;
    public $calorieCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->calorieFoodRepository = new CalorieFoodRepository(new Application);
        $this->calorieCategoryRepository = new CalorieCategoryRepository(new Application);
    }


    public function calories()
    {
        $category_id = request('category_id');

        $this->request_array = ['id', 'keyword'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $calories = CalorieFood::orderBy('id', 'ASC');
        //apply filters
        $calories->when($category_id, function ($query) use ($category_id) {
            $query->where('category_id', '=', $category_id);
        });

        $calories = $calories->paginate(8);

        $calorie_categories = $this->calorieCategoryRepository->orderBy('id', 'ASC')->get();
        $calorie_category = $this->calorieCategoryRepository->where('id', $category_id)->first();
        $category_id ? $title = @$calorie_category->name : $title = trans('global.calories_table');

        $metaKeywords = ', '.implode(', ', $calorie_categories->pluck('name')->toArray());
        $metaImage = $calorie_category->image;
        return view('addon::Front.calories',
            compact('calories', 'metaImage','calorie_categories', 'title' ,'metaKeywords', 'category_id', 'calorie_category')
        );
    }
}
