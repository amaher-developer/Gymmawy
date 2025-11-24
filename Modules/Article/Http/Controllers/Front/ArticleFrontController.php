<?php

namespace Modules\Article\Http\Controllers\Front;

use Modules\Article\Models\ArticleCategory;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;

use Illuminate\Container\Container as Application;
use Modules\Article\Http\Requests\ArticleRequest;
use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Repositories\ArticleCategoryRepository;
use Modules\Article\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ArticleFrontController extends GenericFrontController
{
    public $articleRepository;
    public $articleCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->articleRepository = new ArticleRepository(new Application);
        $this->articleCategoryRepository = new ArticleCategoryRepository(new Application);
    }


    public function index()
    {

        $title = trans('admin.my_articles');
        $this->request_array = ['id'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $articles = $this->articleRepository->with(['user'])->where('user_id', Auth::user()->id)->withTrashed()->orderBy('id', 'DESC');


        //apply filters
        $articles->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });
        $search_query = request()->query();


        $total = $articles->count();
        $articles = $articles->paginate($this->limit);


        return view('article::Front.user.article_front_list', compact('articles', 'title', 'total', 'search_query'));
    }

    private function prepareForExport($data)
    {
        return array_map(function ($row) {
            return [
                'ID' => $row['id']
            ];
        }, $data->toArray());
    }

    public function article($id, $slug)
    {
        $article = $this->articleRepository->active()->with(['category', 'user', 'tags' => function($q){
            $q->where('language', $this->lang);
        }])->find($id);
        if (!$article)
            return view('generic::Front.pages.404');
        $article->increment('views', 1);
//        $similar_articles = Article::with(['category'])->where('id', '!=', $id)->orderByRaw("RAND()")->limit(3)->get();
        $title = @$article->title;
        $article_categories = $this->articleCategoryRepository->orderBy('id', 'ASC')->get();
        $popular_articles = Article::language()->with(['user'])->orderBy('views', 'desc')->limit(5)->get();
//        $last_articles = Article::with(['user'])->orderBy('id', 'desc')->limit(5)->get();


        $metaKeywords = $article->meta_keywords;
        if(!$metaKeywords) {
            $metaKeywords = $article->title;
            if (@$article->category->name) $metaKeywords .= ', ' . $article->category->name;
            ltrim($metaKeywords, ', ');
        }

        $metaDescription = $article->meta_description ? $article->meta_description : $article->title;
        $metaImage = $article->image;


        return view('article::Front.article',
            compact('title', 'metaImage', 'article', 'article_categories', 'popular_articles', 'metaKeywords', 'metaDescription'));
    }

    public function articles()
    {
        $category_id = request('category_id');
        $page = request('page') ?? 1;

        $this->request_array = ['id', 'keyword'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;

        $articles = Article::language()->where('published', 1)->with(['user'])->orderBy('id', 'DESC');
        //apply filters
        $articles->when($category_id, function ($query) use ($category_id) {
            $query->where('category_id', '=', $category_id);
        })->when($keyword, function ($query) use ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where('title', 'like', '%' . $keyword . '%');
            $query->orWhere('description', 'like', '%' . $keyword . '%');

            if (count($keywords) > 1) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%');
                    $query->orWhere('description', 'like', '%' . $keyword . '%');
                }
            }
        });

        $articles = $articles->paginate(8);

        $article_categories = $this->articleCategoryRepository->orderBy('id', 'ASC')->get();
        $popular_articles = Article::language()->with(['user'])->orderBy('views', 'desc')->limit(5)->get();
//        $last_articles = Article::language()->with(['user'])->orderBy('id', 'desc')->limit(5)->get();

        $category_id ? $title = @$articles[0]->category->name : $title = trans('global.articles');

        $title = $title .' - '.trans('global.page').' '.$page;

        $metaKeywords = ', ' . implode(', ', $article_categories->pluck('name')->toArray());
        return view('article::Front.articles',
            compact('articles', 'article_categories', 'title', 'popular_articles', 'metaKeywords')
        );
    }


    public function articleTags()
    {
        $tag = request('tag');
        $title = trans('global.tag').': #'.@$tag;

        $articles = Article::language()->where('published', 1)->with(['user'])->orderBy('id', 'DESC');
        //apply filters
        $articles->when($tag, function ($query) use ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                return $q->where('name', str_replace('-', ' ', $tag));
            });
        });

        $articles = $articles->paginate(8);

        $article_categories = $this->articleCategoryRepository->orderBy('id', 'ASC')->get();
        $popular_articles = Article::language()->with(['user'])->orderBy('views', 'desc')->limit(5)->get();


        $metaKeywords = ', ' . implode(', ', $article_categories->pluck('name')->toArray());
        return view('article::Front.tag-articles',
            compact('articles', 'article_categories', 'title', 'popular_articles', 'metaKeywords')
        );
    }

    public function youtube_code($url)
    {
        if ($url) {
            $video_id = explode("?v=", $url);
            return $video_id[1];
        }
        return null;
    }

    public function create()
    {
        $title = trans('admin.article_add');
        $categories = ArticleCategory::all();
        return view('article::Front.user.article_front_form', ['article' => new Article(), 'categories' => $categories, 'title' => $title]);
    }

    public function store(ArticleRequest $request)
    {
        $article_inputs = $this->prepare_inputs($request->except(['_token']));
        $article_inputs['user_id'] = Auth::user()->id;
        if (@$request->youtube) $article_inputs['youtube'] = $this->youtube_code($request->youtube);
        $this->articleRepository->create($article_inputs);
        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_added'));
        return redirect(route('listUserArticle'));
    }

    public function edit($id)
    {
        $article = $this->articleRepository->withTrashed()->find($id);
        $title = trans('admin.article_edit');
        $categories = ArticleCategory::all();
        return view('article::Front.user.article_front_form', ['article' => $article, 'categories' => $categories, 'title' => $title]);
    }

    public function update(ArticleRequest $request, $id)
    {
        $article = $this->articleRepository->withTrashed()->find($id);
        $article_inputs = $this->prepare_inputs($request->except(['_token']));
        if (@$request->youtube) $article_inputs['youtube'] = $this->youtube_code($request->youtube);
        $article->update($article_inputs);
        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
        return redirect(route('listUserArticle'));
    }

    public function destroy($id)
    {
        $article = $this->articleRepository->withTrashed()->find($id);
        if ($article->trashed()) {
            $article->restore();
            sweet_alert()->success(trans('admin.done'), trans('admin.successfully_enabled'));
        } else {
            $article->delete();
            sweet_alert()->success(trans('admin.done'), trans('admin.successfully_deleted'));
        }
        return redirect(route('listUserArticle'));
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
