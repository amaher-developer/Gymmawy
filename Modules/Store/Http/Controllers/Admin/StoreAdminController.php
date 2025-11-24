<?php

namespace Modules\Store\Http\Controllers\Admin;

use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Store\Http\Requests\StoreRequest;
use Modules\Store\Repositories\StoreRepository;
use Modules\Store\Models\Store;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class StoreAdminController extends GenericAdminController
{
     public $StoreRepository;

         public function __construct()
         {
             parent::__construct();

             $this->StoreRepository=new StoreRepository(new Application);
         }


    public function index()
    {

        $title = 'stores List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $stores = $this->StoreRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $stores = $this->StoreRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $stores->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $stores = $stores->get();
                             $array = $this->prepareForExport($stores);
                             $fileName = 'stores-' . Carbon::now()->toDateTimeString();
                             $file = Excel::create($fileName, function ($excel) use ($array) {
                                 $excel->setTitle('title');
                                 $excel->sheet('sheet1', function ($sheet) use ($array) {
                                     $sheet->fromArray($array);
                                 });
                             });
                             $file = $file->string('xlsx');
                             return [
                                 'name' => $fileName,
                                 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($file)
                             ];
                         }
                         if ($this->limit) {
                             $stores = $stores->paginate($this->limit);
                             $total = $stores->total();
                         } else {
                             $stores = $stores->get();
                             $total = $stores->count();
                         }


        return view('store::Admin.store_admin_list', compact('stores','title', 'total', 'search_query'));
    }

    private function prepareForExport($data)
    {
        return array_map(function ($row) {
            return [
                'ID' => $row['id']
            ];
        }, $data->toArray());
    }

    public function create()
    {
        $title = 'Create Store';
        return view('store::Admin.store_admin_form', ['store' => new Store(),'title'=>$title]);
    }

    public function store(StoreRequest $request)
    {
        $store_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->StoreRepository->create($store_inputs);
        sweet_alert()->success('Done', 'Store Added successfully');
        return redirect(route('listStore'));
    }

    public function edit($id)
    {
        $store =$this->StoreRepository->withTrashed()->find($id);
        $title = 'Edit Store';
        return view('store::Admin.store_admin_form', ['store' => $store,'title'=>$title]);
    }

    public function update(StoreRequest $request, $id)
    {
        $store =$this->StoreRepository->withTrashed()->find($id);
        $store_inputs = $this->prepare_inputs($request->except(['_token']));
        $store->update($store_inputs);
        sweet_alert()->success('Done', 'Store Updated successfully');
        return redirect(route('listStore'));
    }

    public function destroy($id)
      {
          $store =$this->StoreRepository->withTrashed()->find($id);
          if($store->trashed())
          {
              $store->restore();
          }
          else
          {
              $store->delete();
          }
        sweet_alert()->success('Done', 'Store Deleted successfully');
        return redirect(route('listStore'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->StoreRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->StoreRepository->model()::$thumbnails_uploads_path);
        
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
                            $inputs[$input_file]=$uploaded;
                    }
        
                }
        

        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}
