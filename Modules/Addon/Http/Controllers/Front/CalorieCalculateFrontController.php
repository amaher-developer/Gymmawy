<?php

namespace Modules\Addon\Http\Controllers\Front;

use Modules\Addon\Models\CalorieFood;
use Modules\Addon\Repositories\CalorieCategoryRepository;
use Modules\Addon\Repositories\CalorieFoodRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;

class CalorieCalculateFrontController extends GenericFrontController
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
        $title = trans('global.calculate_calories');
        return view('addon::Front.calculates.calories',
            compact( 'title' )
        );
    }
    public function caloriesResult()
    {
        $age = request('age');
        $activity = request('activity');
        $height = request('height');
        $weight = request('weight');
        $gender = request('gender');

        if(!$age || !$activity || !$gender || !$weight || !$height)
            return '<div class="alert alert-danger">'.trans('global.calculate_calorie_error_result').'</div>';
        // for men: BMR = 10W + 6.25H - 5A + 5
        // for female: BMR = 10W + 6.25H - 5A - 161
        if($gender == 1)
            $result = (10* $weight) + (6.25 * $height) - (5 * $age) + 5;
        elseif($gender == 2)
            $result = (10* $weight) + (6.25 * $height) - (5 * $age) - 161;

        return '<div class="alert alert-success"><b>'.trans('global.result').':</b>'.' '.trans('global.calculate_calorie_result', ["result" => round($result*$activity, 2)]).'</div>';

    }

    public function bmi()
    {
        $title = trans('global.calculate_bmi');
        return view('addon::Front.calculates.bmi',
            compact( 'title' )
        );
    }
    public function bmiResult()
    {
        $height = request('bmi_height');
        $weight = request('bmi_weight');

        if(!$weight || !$height)
            return '<div class="alert alert-danger">'.trans('global.calculate_calorie_error_result').'</div>';

        // bmi = mass (kg) / height^2 (m)
        $height = $height /100;
        $result = ($weight) / ($height * $height);

        return '<div class="alert alert-success"><b>'.trans('global.result').':</b>'.' '.trans('global.calculate_bmi_result', ["result" => round($result, 2)]).'</div>';

    }

    public function ibw()
    {
        $title = trans('global.calculate_ibw');
        return view('addon::Front.calculates.ibw',
            compact( 'title' )
        );
    }
    public function ibwResult()
    {
        $height = request('ibw_height');

        if(!$height)
            return '<div class="alert alert-danger">'.trans('global.calculate_calorie_error_result').'</div>';

        // bmi = mass (kg) / height^2 (m)
        // 18.5 to 25
        $height = $height /100;
        $min_weight  = 18.5 * ($height * $height);
        $max_weight  = 25 * ($height * $height);

        return '<div class="alert alert-success"><b>'.trans('global.result').':</b>'.' '.trans('global.calculate_ibw_result', ["result1" => round($min_weight, 2), "result2" => round($max_weight, 2)]).'</div>';

    }

    public function water()
    {
        $title = trans('global.calculate_water');
        return view('addon::Front.calculates.water',
            compact( 'title' )
        );
    }
    public function waterResult()
    {
        $weight = request('water_weight');

        if(!$weight)
            return '<div class="alert alert-danger">'.trans('global.calculate_calorie_error_result').'</div>';

        $water = 0.034 * $weight;
        $cups = 4.23 * $water;


        return '<div class="alert alert-success"><b>'.trans('global.result').':</b>'.' '.trans('global.calculate_water_result', ["result1" => round($water, 2), "result2" => round($cups, 2)]).'</div>';

    }
}
