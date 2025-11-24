<?php

namespace Modules\Gym\Http\Controllers\Admin;

use Modules\Article\Models\Tag;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

use Modules\Gym\Http\Requests\GymAdviceRequest;
use Modules\Gym\Models\GymAdvice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class GymAdviceAdminController extends GenericAdminController
{
    public function index()
    {
        $title = 'gym advice List';
        if (request('trashed')) {
            $advices = GymAdvice::onlyTrashed()->paginate(50);
        } else {
            $advices = GymAdvice::paginate(50);
        }
        return view('gym::Admin.gym_advice_admin_list', compact('advices', 'title'));
    }

    public function create()
    {
        $title = 'Create Gym Advice';
        $tags = Tag::get()->pluck('name')->toArray();
        return view('gym::Admin.gym_advice_admin_form', ['advice' => new GymAdvice(), 'tags' => $tags,'title'=>$title]);
    }

    public function store(GymAdviceRequest $request)
    {
        $advice_inputs = $this->prepare_inputs($request->except(['_token']));
        GymAdvice::create($advice_inputs);

        sweet_alert()->success('Done', 'Gym Advice Added successfully');
        return redirect(route('listGymAdvice'));
    }

    public function edit($id)
    {
        $advice = GymAdvice::withTrashed()->find($id);
        $title = 'Edit Advice';
        return view('gym::Admin.gym_advice_admin_form', ['advice' => $advice, 'title'=>$title]);
    }

    public function update(GymAdviceRequest $request, $id)
    {
        $advice = GymAdvice::withTrashed()->find($id);
        $advice_inputs = $this->prepare_inputs($request->except(['_token']));

        $advice->update($advice_inputs);

        sweet_alert()->success('Done', 'Advice Updated successfully');
        return redirect(route('listGymAdvice'));
    }

    public function destroy($id)
    {
        $article = GymAdvice::withTrashed()->find($id);
        if($article->trashed())
        {
            $article->restore();
        }
        else
        {
            $article->delete();
        }
        sweet_alert()->success('Done', 'Advice Deleted successfully');
        return redirect(route('listGymAdvice'));
    }


    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded = '';

        $destinationPath = base_path(GymAdvice::$uploads_path);
        $ThumbnailsDestinationPath = base_path(GymAdvice::$thumbnails_uploads_path);
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
//        $inputs['published'] = $inputs['published'] ?? 0;

        return $inputs;
    }





}
