<?php

namespace Modules\Article\Http\Controllers\Api;

use Modules\Article\Http\Resources\ArticleCategoryResource;
use Modules\Article\Http\Resources\ArticleResource;
use Modules\Article\Http\Resources\ArticleDetailResource;
use Modules\Article\Models\Article;
use Modules\Article\Models\ArticleCategory;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Illuminate\Http\Response;

class ArticleApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function articles(){
        $categoryId = request('category_id');
        if(!$this->validateApiRequest())
            return $this->response;

        $articles = Article::active()->select('id', 'title', 'description', 'language', 'image', 'published', 'views');
        $articles->where('language', $this->lang);
        $articles->where('for_mobile', true);
        if($categoryId)
            $articles->where('category_id', $categoryId);
        $articles = $articles->orderBy("id", "desc")->paginate($this->limit);

        $this->getPaginateAttribute($articles);
        $this->return['articles'] = $articles ?  ArticleResource::collection($articles) : '';
        return $this->successResponse();
    }


    public function categories(){
        if(!$this->validateApiRequest())
            return $this->response;
        $this->return['article_categories'] = ArticleCategoryResource::collection(ArticleCategory::orderBy("id", "desc")->get());
        return $this->successResponse();
    }

    public function article(){
        $id = request('id');
        $articles = [];
        if(!$this->validateApiRequest(['id']))
            return $this->response;
        $article  = Article::with('category')->where('id', $id)->first();
        if($article)    $articles  = Article::where('category_id', $article->category_id)->limit(2)->get();
        $article = $article ? new ArticleDetailResource($article) : '';
        $this->return['article'] = $article;
        $articles = $articles ? ArticleResource::collection($articles) : '';
        $this->return['articles'] = $articles;

        if($article)    Article::find($id)->increment('views');
        return $this->successResponse();
    }
}
