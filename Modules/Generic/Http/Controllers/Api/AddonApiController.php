<?php

namespace Modules\Generic\Http\Controllers\Api;

use Modules\Addon\Http\Resources\CalorieCategoryResource;
use Modules\Addon\Http\Resources\CalorieResource;
use Modules\Addon\Models\CalorieCategory;
use Modules\Addon\Models\CalorieFood;
use Modules\Generic\Http\Controllers\Api\GenericApiController;

class AddonApiController extends GenericApiController
{
    public function calorieCategories(){
        if(!$this->validateApiRequest())
            return $this->response;

        $categories = CalorieCategory::orderBy("id", "desc")->get();
        $this->return['calorie_categories'] = $categories ? CalorieCategoryResource::collection($categories): '';
        return $this->successResponse();
    }

    public function calorieFoods(){
        $categoryId = request('category_id');
        $lang = request('lang');
        if(!$this->validateApiRequest(['category_id']))
            return $this->response;


        $foods = CalorieFood::where('category_id', $categoryId)->orderBy("id", "desc")->get();
        $foods = $foods ? CalorieResource::collection($foods) : '';
        $this->return['foods'] = $foods;
        return $this->successResponse();
    }

}
