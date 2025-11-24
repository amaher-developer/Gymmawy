<?php

namespace Modules\Ask\Http\Controllers\Admin;

use Modules\Article\Models\ArticleCategory;
use Modules\Article\Models\Tag;
use Modules\Ask\Models\Answer;
use Modules\Ask\Repositories\AnswerRepository;
use Modules\Ask\Repositories\QuestionRepository;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Ask\Http\Requests\AskRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AskAdminController extends GenericAdminController
{
     public $QuestionRepository;
     public $AnswerRepository;

         public function __construct()
         {
             parent::__construct();

             $this->QuestionRepository=new QuestionRepository(new Application);
             $this->AnswerRepository=new AnswerRepository(new Application);
         }


    public function index()
    {
        $title = trans('admin.questions');
        $this->request_array = ['id', 'order_by'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $asks = $this->QuestionRepository->onlyTrashed();
        }
        else
        {
            $asks = $this->QuestionRepository;
        }

     //apply filters
        $asks->when($id, function ($query) use ($id) {
                $query->where('id','=', $id);
        });
         $search_query = request()->query();

        $asks->when(isset($order_by) && ($order_by != ''), function ($query) use ($order_by) {
            if($order_by == 'views'){
                $query->orderBy('views', 'desc');
            }else if($order_by == 'date'){
                $query->orderBy('created_at', 'desc');
            }
        });
         if ($this->limit) {
             $asks = $asks->orderBy('id', 'DESC')->paginate($this->limit);
             $total = $asks->total();
         } else {
             $asks = $asks->orderBy('id', 'DESC')->get();
             $total = $asks->count();
         }

        return view('ask::Admin.ask_admin_list', compact('asks','title', 'total', 'search_query'));
    }
    public function indexAnswer()
    {
        $title = trans('admin.answers');
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $asks = Answer::with(['question', 'parent_answer'])->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $asks = Answer::with(['question', 'parent_answer'])->orderBy('id', 'DESC');
        }

     //apply filters
        $asks->when($id, function ($query) use ($id) {
                $query->where('id','=', $id);
        });
         $search_query = request()->query();

         if ($this->limit) {
             $asks = $asks->paginate($this->limit);
             $total = $asks->total();
         } else {
             $asks = $asks->get();
             $total = $asks->count();
         }
        return view('ask::Admin.ask_answer_admin_list', compact('asks','title', 'total', 'search_query'));
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
        $title = 'Create Ask';
        $article_categories = ArticleCategory::get();
        return view('ask::Admin.ask_admin_form', ['ask' => new Ask(),'title'=>$title, 'article_categories' => $article_categories]);
    }

    public function store(AskRequest $request)
    {
        $ask_inputs = $this->prepare_inputs($request->except(['_token']));
        $this->QuestionRepository->create($ask_inputs);
        sweet_alert()->success('Done', 'Ask Added successfully');
        return redirect(route('listAsk'));
    }

    public function edit($id)
    {
        $question =$this->QuestionRepository->withTrashed()->find($id);
        $questionTags = ($question->tags->pluck('name')->implode(','));
        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        $get_tags = array_rand(array_flip($tags), 8);
        $tags = '"'.implode('", "', $tags).'"';
        $title = trans('admin.edit_question');
        return view('ask::Admin.ask_admin_form', ['ask' => $question,'title'=>$title, 'questionTags' => $questionTags, 'article_categories' => $article_categories, 'tags' => $tags, 'get_tags' => $get_tags]);
    }

    public function update(AskRequest $request, $id)
    {
        $ask =$this->QuestionRepository->withTrashed()->find($id);
        $ask_inputs = $this->prepare_inputs($request->except(['_token']));
        $ask->update($ask_inputs);
        sweet_alert()->success('Done', 'Ask Updated successfully');
        return redirect(route('listAsk'));
    }


    public function editAnswer($id)
    {
        $answer =Answer::withTrashed()->find($id);
        $title = trans('admin.edit_answer');
        return view('ask::Admin.ask_answer_admin_form', ['ask' => $answer,'title'=>$title]);
    }

    public function updateAnswer(AskRequest $request, $id)
    {
        $ask =Answer::withTrashed()->find($id);
        $ask_inputs = $this->prepare_inputs($request->except(['_token']));
        $ask->update($ask_inputs);
        sweet_alert()->success('Done', 'Ask Answer Updated successfully');
        return redirect(route('listAskAnswer'));
    }

    public function destroy($id)
      {
          $ask =$this->QuestionRepository->withTrashed()->find($id);
          if($ask->trashed())
          {
              Answer::where('question_id', $id)->restore();
              $ask->restore();
          }
          else
          {
              Answer::where('question_id', $id)->delete();
              $ask->delete();
          }
        sweet_alert()->success('Done', 'Ask Deleted successfully');
        return redirect(route('listAsk'));
    }

    public function destroyAnswer($id)
      {
          $ask =Answer::withTrashed()->find($id);
          if($ask->trashed())
          {
              Answer::where('parent_id', $id)->restore();
              $ask->restore();
          }
          else
          {
              Answer::where('parent_id', $id)->delete();
              $ask->delete();
          }
        sweet_alert()->success('Done', 'Ask Deleted successfully');
        return redirect(route('listAsk'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->QuestionRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->QuestionRepository->model()::$thumbnails_uploads_path);
        
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
