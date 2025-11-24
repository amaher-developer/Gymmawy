<?php

namespace Modules\Article\Http\Controllers\Admin;

use Modules\Article\Http\Requests\TagRequest;
use Modules\Article\Models\Tag;
use Modules\Article\Repositories\TagRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Article\Http\Requests\ArticleCategoryRequest;
use Modules\Article\Repositories\ArticleCategoryRepository;
use Modules\Article\Models\ArticleCategory;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TagAdminController extends GenericAdminController
{
     public $TagRepository;

         public function __construct()
         {
             parent::__construct();

             $this->TagRepository=new TagRepository(new Application);
         }


    public function index()
    {

        $title = 'Tags List';
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $tags = $this->TagRepository->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $tags = $this->TagRepository->orderBy('id', 'DESC');
        }


             //apply filters
        $tags->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                           $tags = $tags->get();
                             $array = $this->prepareForExport($tags);
                             $fileName = 'tags-' . Carbon::now()->toDateTimeString();
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
                             $tags = $tags->paginate($this->limit);
                             $total = $tags->total();
                         } else {
                             $tags = $tags->get();
                             $total = $tags->count();
                         }


        return view('article::Admin.tag_admin_list', compact('tags','title', 'total', 'search_query'));
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
        $title = 'Create Tag';
        return view('article::Admin.tag_admin_form', ['tag' => new Tag(),'title'=>$title]);
    }

    public function store(TagRequest $request)
    {
        $tag_inputs = $request->except(['_token']);
        $this->TagRepository->create($tag_inputs);
        sweet_alert()->success('Done', 'Tag Added successfully');
        return redirect(route('listTag'));
    }

    public function edit($id)
    {
        $tag =$this->TagRepository->withTrashed()->find($id);
        $title = 'Edit Tag';
        return view('article::Admin.tag_admin_form', ['tag' => $tag,'title'=>$title]);
    }

    public function update(TagRequest $request, $id)
    {
        $tag =$this->TagRepository->withTrashed()->find($id);
        $tag_inputs = $request->except(['_token']);
        $tag->update($tag_inputs);
        sweet_alert()->success('Done', 'Tag Updated successfully');
        return redirect(route('listTag'));
    }

    public function destroy($id)
      {
          $tag =$this->TagRepository->withTrashed()->find($id);
          if($tag->trashed())
          {
              $tag->restore();
          }
          else
          {
              $tag->delete();
          }
        sweet_alert()->success('Done', 'Tag Deleted successfully');
        return redirect(route('listTag'));
    }


}
