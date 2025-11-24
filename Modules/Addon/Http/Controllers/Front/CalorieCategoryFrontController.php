<?php

namespace Modules\Addon\Http\Controllers\Front;

use Modules\Addon\Models\CalorieCategory;
use Modules\Addon\Models\CalorieFood;
use Modules\Addon\Repositories\CalorieCategoryRepository;
use Modules\Addon\Repositories\CalorieFoodRepository;
use Modules\Article\Models\Article;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Illuminate\Container\Container as Application;

class CalorieCategoryFrontController extends GenericFrontController
{

    public $calorieCategoryRepository;
    public function __construct()
    {
        parent::__construct();

        $this->calorieCategoryRepository = new CalorieCategoryRepository(new Application);
    }

    public function categories()
    {

        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $calorie_categories = CalorieCategory::orderBy('id', 'ASC');

        $calorie_categories = $calorie_categories->get();

        $title = trans('global.calorie_categories');

        $metaKeywords = ', '.implode(', ', $calorie_categories->pluck('name')->toArray());

        $latest_articles = Article::active()->with('user')->where('language', $this->lang)->limit(4)->orderBy('id', 'desc')->get();


        return view('addon::Front.calorie_categories',
            compact('calorie_categories', 'title' ,'metaKeywords', 'latest_articles')
        );
    }
}
