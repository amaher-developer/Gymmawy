<?php

namespace Modules\Article\Http\Controllers\Admin;

use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Article\Http\Requests\ArticleCategoryRequest;
use Modules\Article\Repositories\ArticleCategoryRepository;
use Modules\Article\Models\ArticleCategory;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ArticleCategoryAdminController extends GenericAdminController
{
     public $ArticleCategoryRepository;

         public function __construct()
         {
             parent::__construct();

             $this->ArticleCategoryRepository=new ArticleCategoryRepository(new Application);
         }


    public function index()
    {

        $title = 'articlecategories List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $articlecategories = $this->ArticleCategoryRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $articlecategories = $this->ArticleCategoryRepository->orderBy('id', 'DESC');
        }


             //apply filters
                $articlecategories->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $articlecategories = $articlecategories->get();
                             $array = $this->prepareForExport($articlecategories);
                             $fileName = 'articlecategories-' . Carbon::now()->toDateTimeString();
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
                             $articlecategories = $articlecategories->paginate($this->limit);
                             $total = $articlecategories->total();
                         } else {
                             $articlecategories = $articlecategories->get();
                             $total = $articlecategories->count();
                         }


        return view('article::Admin.articlecategory_admin_list', compact('articlecategories','title', 'total', 'search_query'));
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
        $title = 'Create ArticleCategory';
        return view('article::Admin.articlecategory_admin_form', ['articlecategory' => new ArticleCategory(),'title'=>$title]);
    }

    public function store(ArticleCategoryRequest $request)
    {
        $articlecategory_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->ArticleCategoryRepository->create($articlecategory_inputs);
        sweet_alert()->success('Done', 'ArticleCategory Added successfully');
        return redirect(route('listArticleCategory'));
    }

    public function edit($id)
    {
        $articlecategory =$this->ArticleCategoryRepository->withTrashed()->find($id);
        $title = 'Edit ArticleCategory';
        return view('article::Admin.articlecategory_admin_form', ['articlecategory' => $articlecategory,'title'=>$title]);
    }

    public function update(ArticleCategoryRequest $request, $id)
    {
        $articlecategory =$this->ArticleCategoryRepository->withTrashed()->find($id);
        $articlecategory_inputs = $this->prepare_inputs($request->except(['_token']));
        $articlecategory->update($articlecategory_inputs);
        sweet_alert()->success('Done', 'ArticleCategory Updated successfully');
        return redirect(route('listArticleCategory'));
    }

    public function destroy($id)
      {
          $articlecategory =$this->ArticleCategoryRepository->withTrashed()->find($id);
          if($articlecategory->trashed())
          {
              $articlecategory->restore();
          }
          else
          {
              $articlecategory->delete();
          }
        sweet_alert()->success('Done', 'ArticleCategory Deleted successfully');
        return redirect(route('listArticleCategory'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->ArticleCategoryRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->ArticleCategoryRepository->model()::$thumbnails_uploads_path);
        
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
