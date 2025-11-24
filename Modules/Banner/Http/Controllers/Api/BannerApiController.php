<?php

namespace Modules\Banner\Http\Controllers\Api;

use Modules\Banner\Http\Resources\BannerResource;
use Modules\Banner\Models\Banner;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BannerApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function banners(){
        $categoryId = request('category_id');
//        $gymId = request('gym_id');
        $lang = request('lang');
        if(!$this->validateApiRequest(['lang']))
            return $this->response;

        $banners = Banner::select('id', 'title', 'image', 'lang', 'category_id', 'gym_id', 'url', 'phone', 'date_from', 'date_to');
        $banners->where('lang', $lang);
        $banners->whereDate('date_from','<=', Carbon::now())->whereDate('date_to','>=', Carbon::now());
        if($categoryId)
            $banners->where('category_id', $categoryId);
//        if($gymId)
//            $banners->where('gym_id', $gymId);
        $banner = $banners->orderBy(DB::raw('RAND()'))->first();

//        $this->return['banners'] = $banners ? BannerResource::collection($banners) : '';
        $this->return['banner'] = $banner ? new BannerResource($banner) : '';
        return $this->successResponse();
    }


}
