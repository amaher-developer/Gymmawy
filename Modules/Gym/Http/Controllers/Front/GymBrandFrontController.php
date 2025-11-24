<?php

namespace Modules\Gym\Http\Controllers\Front;

use Modules\Generic\Http\Controllers\Front\GenericFrontController;

use Modules\Gym\Http\Requests\GymBrandRequest;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\GymImage;
use Modules\Gym\Models\Service;
use Modules\Gym\Repositories\GymBrandRepository;
use Illuminate\Container\Container as Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class GymBrandFrontController extends GenericFrontController
{
    public $GymBrandRepository;

    public function __construct()
    {
        parent::__construct();

        $this->GymBrandRepository = new GymBrandRepository(new Application);
    }


    public function index()
    {
        $title = trans('admin.gym_brands');

        $gyms = $this->GymBrandRepository->where('gym_id', @Auth::user()->gym->id)->orderBy('id', 'DESC');
        $gyms = $gyms->get();
        $total = $gyms->count();

        return view('gym::Front.user.gym_front_form', compact('gyms','title', 'total', 'search_query'));
    }

    public function show()
    {
        $gym = GymBrand::with(['gyms.images'])->where('user_id', Auth::user()->id)->withTrashed()->first();
        if(!($gym))
            return redirect()->route('storeUserGymBrand');
        $title = trans('admin.gym_show');
        return view('gym::Front.user.gym_front_view', ['gym' => $gym, 'title' => $title]);
    }


    public function create()
    {
        $title = trans('global.add_gym');

        $categories = Category::get();
        $services = Service::get();
        return view('gym::Front.user.gym_front_create',
            [
                'gym' => new Gym(),
                'categories' => $categories,
                'services' => $services,
                'title'=>$title
            ]);
    }

    public function store(GymBrandRequest $request)
    {
        $gymbrand_inputs['user_id'] = Auth::user()->id;
        $gymbrand_inputs['name_ar'] = $request['name_'.$this->lang];
        $gymbrand_inputs['name_en'] = $request['name_'.$this->lang];
        $gymbrand_inputs['description_ar'] = $request['description_'.$this->lang];
        $gymbrand_inputs['description_en'] = $request['description_'.$this->lang];
        $gymbrand_inputs['main_phone'] = $request['main_phone'];
        $gymbrand_inputs['socials'] = $request['socials'];
        $gymbrand_inputs = $this->uploadFile($gymbrand_inputs, 'logo');
        $gymbrand = $this->GymBrandRepository->create($gymbrand_inputs);

        $gym_inputs = $request->all(['address', 'district_id', 'cover_image', 'image', 'phones', 'lat', 'lng']);
        $gym_inputs = $this->uploadFile($gym_inputs, 'cover_image');
        $gym_inputs = $this->uploadFile($gym_inputs, 'image');
        if($gym_inputs['phones']) $gym_inputs['phones'] = explode(',', $gym_inputs['phones']);
        $gym_inputs['gym_brand_id'] = $gymbrand->id;

        $gym = Gym::create($gym_inputs);

        if(count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
        if(count((array)$request->services) > 0) $gym->services()->sync($request->services);
        $images = [];
        if($request->images) $images = explode(',', trim($request->images, ','));

        if (count($images) > 0) {
            $oldImages = GymImage::whereNotIn('image', $images)->where('gym_id', $gym->id)->get();
            foreach ($oldImages as $oldImage) {
                unlink(GymImage::$uploads_path.$oldImage['original_image']);
                unlink(GymImage::$thumbnails_uploads_path.$oldImage['original_image']);
                GymImage::where('id', $oldImage['id'])->delete();
            }

            foreach ($images as $image)
                GymImage::updateOrCreate(['gym_id'=> $gym->id ,'image' => $image], ['gym_id'=> $gym->id,'image' => $image]);
        }

        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_added'));
        return redirect(route('showUserGymBrand'));
    }

    public function edit()
    {
        $gym = $this->GymBrandRepository->where('user_id', $this->user->id)->withTrashed()->first();
        $title = trans('admin.gym_edit');
        return view('gym::Front.user.gym_front_edit', ['gym' => $gym,'title'=>$title]);
    }

    public function update(GymBrandRequest $request)
    {
        $gym = $this->GymBrandRepository->withTrashed()->find(Auth::user()->gym->id);
        $gym_inputs = $this->uploadFile($request->except(['_token']), 'logo');
        $gym->update($gym_inputs);
        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
        return redirect(route('showUserGymBrand'));
    }

    public function destroy($id)
    {
        $gym =$this->GymBrandRepository->withTrashed()->find($id);
        if($gym->trashed())
        {
            $gym->restore();
        }
        else
        {
            $gym->delete();
        }
        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_deleted'));
        return redirect(route('listUserGymBrand'));
    }


    private function prepare_inputs($inputs)
    {
        $input_file = 'logo';
        $inputs = $this->uploadFile($inputs, $input_file);

        $input_file = 'image';
        $inputs = $this->uploadFile($inputs, $input_file);

        $input_file = 'cover_image';
        $inputs = $this->uploadFile($inputs, $input_file);

//        $input_file = 'file';
//        $inputs = $this->uploadFile($inputs, $input_file);

        return $inputs;
    }

    private function uploadFile($inputs, $file){

        $input_file = $file;
        $uploaded = '';

        $destinationPath = base_path(GymBrand::$uploads_path);
        $ThumbnailsDestinationPath = base_path(GymBrand::$thumbnails_uploads_path);
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
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)  . '-' . rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


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
//                    $img->save($destinationPath . $filename);
                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90);
                    $img->save($destinationPath . '' . $filename);

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
                $inputs[$input_file] = $this->imageName = (string)$uploaded;
            }

        }
        //        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }



}
