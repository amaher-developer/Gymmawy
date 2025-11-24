<?php

namespace Modules\Banner\Http\Controllers\Admin;

use Modules\Gym\Models\Category;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Banner\Http\Requests\BannerRequest;
use Modules\Banner\Repositories\BannerRepository;
use Modules\Banner\Models\Banner;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BannerAdminController extends GenericAdminController
{
    public $BannerRepository;

    public function __construct()
    {
        parent::__construct();

        $this->BannerRepository = new BannerRepository(new Application);
    }


    public function index()
    {

        $title = 'banners List';
        $this->request_array = ['id', 'lang', 'category_id', 'type'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if (request('trashed')) {
            $banners = $this->BannerRepository->with('category')->onlyTrashed()->orderBy('id', 'DESC');
        } else {
            $banners = $this->BannerRepository->with('category')->orderBy('id', 'DESC');
        }


        //apply filters
        $banners->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });
        $banners->when($lang, function ($query) use ($id) {
            $query->where('lang', '=', $lang);
        });
        $banners->when($category_id, function ($query) use ($id) {
            $query->where('category_id', '=', $category_id);
        });
        $banners->when($type, function ($query) use ($id) {
            $query->where('type', '=', $type);
        });
        $search_query = request()->query();


        if ($this->limit) {
            $banners = $banners->paginate($this->limit);
            $total = $banners->total();
        } else {
            $banners = $banners->get();
            $total = $banners->count();
        }


        return view('banner::Admin.banner_admin_list', compact('banners', 'title', 'total', 'search_query'));
    }


    public function create()
    {
        $title = 'Create Banner';
        return view('banner::Admin.banner_admin_form', ['banner' => new Banner(), 'categories' => Category::all(),'title' => $title]);
    }

    public function store(BannerRequest $request)
    {
        $banner_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->BannerRepository->create($banner_inputs);
        sweet_alert()->success('Done', 'Banner Added successfully');
        return redirect(route('listBanner'));
    }
    public function edit($id)
    {
        $banner =$this->BannerRepository->withTrashed()->find($id);
        $title = 'Edit Banner';
        return view('banner::Admin.banner_admin_form', ['banner' => $banner,'title'=>$title,'categories' => Category::all()]);
    }

    public function update(BannerRequest $request, $id)
    {
        $banner =$this->BannerRepository->withTrashed()->find($id);
        $banner_inputs = $this->prepare_inputs($request->except(['_token']));
        $banner->update($banner_inputs);
        sweet_alert()->success('Done', 'Banner Updated successfully');
        return redirect(route('listBanner'));
    }

    public function destroy($id)
    {
        $banner = $this->BannerRepository->withTrashed()->find($id);
        unlink(Banner::$uploads_path . $banner['original_image']);
        unlink(Banner::$thumbnails_uploads_path . $banner['original_image']);

        $banner->delete();

        sweet_alert()->success('Done', 'Banner Deleted successfully');
        return redirect(route('listBanner'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded = '';

        $destinationPath = base_path($this->BannerRepository->model()::$uploads_path);
        $ThumbnailsDestinationPath = base_path($this->BannerRepository->model()::$thumbnails_uploads_path);

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
                    $img->encode('jpg', 90)->save($destinationPath . $filename);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($destinationPath . '' . $filename);

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
                    $img->encode('jpg', 90)->save($destinationPath . $filename);
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


        !$inputs['deleted_at'] ? $inputs['deleted_at'] = null : '';

        return $inputs;
    }

}
