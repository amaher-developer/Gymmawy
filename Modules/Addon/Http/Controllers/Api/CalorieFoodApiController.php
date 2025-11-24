<?php

namespace Modules\Addon\Http\Controllers\Api;

use Modules\Addon\Models\CalorieFood;
use Modules\Generic\Http\Controllers\Api\GenericApiController;

class CalorieFoodApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function calories(){

        if(!$this->validateApiRequest())
            return $this->response;

        $this->return['calories'] = CalorieFood::get();
        return $this->successResponse();
    }

}
