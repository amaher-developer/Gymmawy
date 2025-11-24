<?php

namespace Modules\Gym\Http\Controllers\Front;

use Modules\Generic\Http\Controllers\Front\GenericFrontController;

use Modules\Gym\Models\GymAdvice;
use Illuminate\Container\Container as Application;
use Modules\Article\Http\Requests\ArticleRequest;
use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AdviceFrontController extends GenericFrontController
{
    public $adviceRepository;

    public function __construct()
    {
        parent::__construct();
    }


    public function advice($id, $slug)
    {
        $advice = GymAdvice::find($id);
        if (!$advice)
            return view('generic::Front.pages.404');

        $title = @$advice->title;
        $popular_advices = GymAdvice::orderBy(DB::raw('RAND()'))->limit(5)->get();


        $metaKeywords = $title;
        $metaDescription = $title;
        $metaImage = $advice->image;


        return view('gym::Front.advice',
            compact('title', 'metaImage', 'advice',  'popular_advices', 'metaKeywords', 'metaDescription'));
    }

    public function advices()
    {
        $page = request('page') ?? 1;

        $this->request_array = ['id', 'keyword'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $advices = GymAdvice::orderBy('id', 'DESC');
        //apply filters
        $advices->when($keyword, function ($query) use ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where('title', 'like', '%' . $keyword . '%');
            $query->orWhere('content', 'like', '%' . $keyword . '%');

            if (count($keywords) > 1) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%');
                    $query->orWhere('content', 'like', '%' . $keyword . '%');
                }
            }
        });

        $advices = $advices->paginate(8);

        $popular_advices = GymAdvice::orderBy(DB::raw('RAND()'))->limit(5)->get();

        $title = trans('global.advices');

        $title = $title .' - '.trans('global.page').' '.$page;

        return view('gym::Front.advices',
            compact('advices',  'title', 'popular_advices')
        );
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded = '';

        $destinationPath = base_path($this->articleRepository->model()::$uploads_path);
        $ThumbnailsDestinationPath = base_path($this->articleRepository->model()::$thumbnails_uploads_path);
        $waterMarkUrl = base_path('resources/assets/front/img/watermark.png');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        if (!File::exists($ThumbnailsDestinationPath)) {
            File::makeDirectory($ThumbnailsDestinationPath, $mode = 0777, true, true);
        }
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);

            if (is_image($file->getRealPath())) {
                $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


                $uploaded = $filename;

                $img = Image::make($file);
                $original_width = $img->width();
                $original_height = $img->height();

                if ($original_width > 1200 || $original_height > 900) {
                    if ($original_width < $original_height) {
                        $new_width = 1200;
                        $new_height = ceil($original_height * 900 / $original_width);
                    } else {
                        $new_height = 900;
                        $new_width = ceil($original_width * 1200 / $original_height);
                    }

                    //save used image
                    $img->encode('jpg', 90);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
//                    $img->save($destinationPath . $filename);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)
                        ->save($destinationPath . '' . $filename);

                    //create thumbnail
                    if ($original_width < $original_height) {
                        $thumbnails_width = 400;
                        $thumbnails_height = ceil($new_height * 300 / $new_width);
                    } else {
                        $thumbnails_height = 300;
                        $thumbnails_width = ceil($new_width * 400 / $new_height);
                    }
                    $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                } else {
                    //save used image
                    $img->encode('jpg', 90);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->save($destinationPath . $filename);
                    //create thumbnail
                    if ($original_width < $original_height) {
                        $thumbnails_width = 400;
                        $thumbnails_height = ceil($original_height * 300 / $original_width);
                    } else {
                        $thumbnails_height = 300;
                        $thumbnails_width = ceil($original_width * 400 / $original_height);
                    }
                    $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                }
                $inputs[$input_file] = $uploaded;
            }

        }


//        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }
}
