<?php

namespace Modules\Article\Http\Controllers\Admin;

use Modules\Article\Models\ArticleCategory;
use Modules\Article\Models\ArticleImage;
use Modules\Article\Models\Tag;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Article\Http\Requests\ArticleRequest;
use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ArticleAdminController extends GenericAdminController
{
    public $ArticleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->ArticleRepository = new ArticleRepository(new Application);
    }


    public function index()
    {

        $title = 'articles List';
        $this->request_array = ['id', 'published', 'order_by', 'limit'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if (request('trashed')) {
            $articles = $this->ArticleRepository->onlyTrashed();
        } else {
            $articles = $this->ArticleRepository;
        }


        //apply filters
        $articles->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });

        $articles->when(isset($published) && ($published != ''), function ($query) use ($published) {
            $query->where('published', '=', $published);
        });
        $search_query = request()->query();

        $articles->when(isset($order_by) && ($order_by != ''), function ($query) use ($order_by) {
            if($order_by == 'views'){
                $query->orderBy('views', 'desc');
            }else if($order_by == 'date'){
                $query->orderBy('created_at', 'desc');
            }
        });

        if (request()->ajax() && request()->exists('export')) {
            $articles = $articles->get();
            $array = $this->prepareForExport($articles);
            $fileName = 'articles-' . Carbon::now()->toDateTimeString();
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
        if(@$limit){
            $this->limit = @(int)$limit;
        }
        if ($this->limit) {
            $articles = $articles->orderBy('id', 'desc')->paginate($this->limit);
            $total = $articles->total();
        } else {
            $articles = $articles->orderBy('id', 'desc')->get();
            $total = $articles->count();
        }


        return view('article::Admin.article_admin_list', compact('articles', 'title', 'total', 'search_query'));
    }

    private function prepareForExport($data)
    {
        return array_map(function ($row) {
            return [
                'ID' => $row['id']
            ];
        }, $data->toArray());
    }

    public function indexImages()
    {
        $title = trans('admin.images');
        $images = ArticleImage::orderBy('id', 'DESC');
        $total = $images->count();
        $images = $images->paginate($this->limit);


        return view('article::Admin.article_images_admin_list', compact('images', 'title', 'total'));
    }

    public function deleteArticleImage($id)
    {
        $image = ArticleImage::find($id);
        unlink(ArticleImage::$uploads_path . $image->image);
        $image->delete();

        sweet_alert()->success('Done', 'Article Image Deleted successfully');
        return redirect(route('listArticleImages'));


    }

    public function uploadArticleImage(Request $request)
    {
        $image_inputs = $this->prepare_inputs($request->except(['_token']));
        unset($image_inputs['published']);
        ArticleImage::create($image_inputs);

        sweet_alert()->success('Done', 'Article Image Added successfully');
        return redirect(route('listArticleImages'));

    }

    public function create()
    {
        $title = 'Create Article';
        $categories = ArticleCategory::all();
        $tags = Tag::get()->pluck('name')->toArray();
        return view('article::Admin.article_admin_form', ['article' => new Article(), 'tags' => $tags, 'categories' => $categories, 'title' => $title]);
    }

    public function store(ArticleRequest $request)
    {
        $article_inputs = $this->prepare_inputs($request->except(['_token', 'tags']));
        $article_inputs['user_id'] = Auth::user()->id;
        $article_inputs['description'] = strip_tags($request->description, "<img><img/><a><br><br/><p><strong><b><h1><h2><h3><h4>");
        if (@$request->slug) {
            $string = str_replace(' ', '-', strtolower(str_replace('&', '', $request->slug)));
            $article_inputs['slug'] = str_replace('/', '', $string);
        }
        $article = $this->ArticleRepository->create($article_inputs);
        if ($request->tags) {
            $tagArr = (explode(',', $request->tags));
            array_map(function ($q) {
                Tag::updateOrCreate(['name' => $q], ['name' => $q]);
            }, $tagArr);
            $tagIds = Tag::whereIn('name', $tagArr)->get()->pluck('id');
            $article->tags()->sync($tagIds);
        }
        if (@$request->youtube) $article_inputs['youtube'] = $this->youtube_code($request->youtube);
        sweet_alert()->success('Done', 'Article Added successfully');
        return redirect(route('listArticle'));
    }

    public function edit($id)
    {
        $article = $this->ArticleRepository->with('tags')->withTrashed()->find($id);
        $title = 'Edit Article';
        $categories = ArticleCategory::all();
        $tags = Tag::get()->pluck('name')->toArray();
        return view('article::Admin.article_admin_form', ['article' => $article, 'tags' => $tags, 'categories' => $categories, 'title' => $title]);
    }

    public function update(ArticleRequest $request, $id)
    {
        $article = $this->ArticleRepository->withTrashed()->find($id);
        $article_inputs = $this->prepare_inputs($request->except(['_token', 'tags']));
        $article_inputs['description'] = strip_tags($request->description, "<img><img/><a><br><br/><p><strong><b><h1><h2><h3><h4>");
        if (@$request->youtube) $article_inputs['youtube'] = $this->youtube_code($request->youtube);
        if (@$request->calculates) $article_inputs['calculates'] = (object)($request->calculates);
        if (@$request->slug) {
            $string = str_replace(' ', '-', strtolower(str_replace('&', '', $request->slug)));
            $article_inputs['slug'] = str_replace('/', '', $string);
        }
        $article->update($article_inputs);

        if ($request->tags) {
            $tagArr = (explode(',', $request->tags));
            array_map(function ($q) {
                Tag::updateOrCreate(['name' => $q], ['name' => $q]);
            }, $tagArr);
            $tagIds = Tag::whereIn('name', $tagArr)->get()->pluck('id');
            $article->tags()->sync($tagIds);
        }
        sweet_alert()->success('Done', 'Article Updated successfully');
        return redirect(route('listArticle'));
    }

    public function backlink($id)
    {
        $article = $this->ArticleRepository->with('tags')->withTrashed()->find($id);
        if (!$article->is_backlinks) {
            $tags = Tag::with('articles')->whereHas('articles', function ($q) use ($id) {
                $q->where('article_id', '!=', $id);
            })->get();

            $search = $article->description;
            foreach ($tags as $tag) {
                $get_article = ($tag->articles->random(1)->first());
                $search = preg_replace("/" . $tag->name . "/i", '<a href="' . route('article', [$get_article->id, $get_article->slug]) . '" title="' . $tag->name . '" >' . $tag->name . '</a>', $search);
            }
            $article->description = $search;
            $article->is_backlinks = true;
            $article->save();
        }
        return redirect(route('editArticle', $id));
    }

    public function youtube_code($url)
    {
        if ($url) {
            $video_id = explode("?v=", $url);
            return $video_id[1];
        }
        return null;
    }

    public function destroy($id)
    {
        $article = $this->ArticleRepository->withTrashed()->find($id);
        if ($article->trashed()) {
            $article->restore();
        } else {
            $article->delete();
        }
        sweet_alert()->success('Done', 'Article Deleted successfully');
        return redirect(route('listArticle'));
    }


    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded = '';

        $destinationPath = base_path(Article::$uploads_path);
        $ThumbnailsDestinationPath = base_path(Article::$thumbnails_uploads_path);
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
        $inputs['published'] = $inputs['published'] ?? 0;

        return $inputs;
    }


    public function getWebsiteContent()
    {

        $articles = Article::get();
        foreach ($articles as $article) {
            $article->description = str_replace('http://localhost/dalilgym/', 'https://gymmawy.com/', $article->description);
            $article->save();
        }

        dd('ssss');

        for ($i = 670; $i < 820; $i++) {
            echo '<a href="http://localhost/dalilgym/operate/article/getWebsiteArticle?id=' . $i . '" target="_blank">' . 'http://localhost/dalilgym/operate/article/getWebsiteArticle?id=' . $i . '</a><br/><br/>';
        }
        $article = Article::where('id', request()->get('id'))->first();
        $article->description = preg_replace('/srcset="(.*?)"/i', ' ', $article->description);
        $article->save();
        dd('ss');

        preg_match_all('/ href="(.*?)"/s', $article->description, $links);

        if ($links[1]) {
            foreach ($links[1] as $link) {
                $article->description = str_replace($link, route('article', [$article->id, $article->slug]), $article->description);
                $article->save();
            }
        }
        $content = $article->description;

//        preg_match_all('/ src="(.*?)"/s', $content,  $images);
//        if($images[1]){
//            foreach ($images[1]  as $image){
//                $array = explode('.', $image);
//                $extension = end($array);
//                $imageName = rand(1000, 9999) . time() . '.'.$extension;
//                $img = Article::$uploads_path .'content'.'/'. $imageName;
//                Image::make($image)
//                    ->save($img);
//                $content = str_replace($image, asset($img), $content);
//                $article->description = $content;
//                $article->save();
//            }
//        }
//        return redirect()->route('getWebsiteArticle', 'id='.request()->get('id'));


        $url = (request()->get('url'));
//        $link_array = explode('/',$url);
//        $url = end($link_array);
//        $url = urlencode($url);
        $getWebsite = file_get_contents($url);
        $waterMarkUrl = base_path('resources/assets/front/img/watermark.png');

//    dd($getWebsite);
//    $match = array();
//    $t = preg_match('/<h1 class="postTitle inline vBottom">(.*?)\</h1>/s', $getWebsite, $matches);
//    $url = preg_match("/<h1 class=\"postTitle inline vBottom\">(.+)<\/h1>/", $getWebsite, $match);
//
//    print_r($match);
//$text = '#<li class="inline vTop"><span>(.*?)<\/span><a  data-role="dynamicFieldsSearchLink" href="(.*?)"><strong>(.*?)</strong>#';
        $get_title = '#<meta property="og:title" content="(.*?)" />#';
        preg_match($get_title, $getWebsite, $title);
        $title = $title[1];

        $get_content = '/<!-- content -->(.*?)\<div id="review"/s';
        preg_match($get_content, $getWebsite, $content);
        if (!$content) {
            $get_content = '/<!-- content -->(.*?)\<div class="essb_links/s';
            preg_match($get_content, $getWebsite, $content);
        }
        $content = $content[1];
        if ($content) {
            preg_match_all('/src="(.*?)"/s', $content, $images);
            if ($images[1]) {
                foreach ($images[1] as $image) {
                    $imageName = rand(1000, 9999) . time() . '.jpg';
                    $img = Article::$uploads_path . 'content' . '/' . $imageName;
                    Image::make($image)
                        ->save($img);
                    $content = str_replace($image, asset($img), $content);
                }
            }
//            $content = preg_replace('/srcset="(.*?)"/i', ' ', $content);

        }
//        $get_summary = '/<div class="font-16">(.*?)\<\/div>/s';
//        preg_match($get_summary, $getWebsite, $summary);
//        $summary = $summary[1].'</p>';

        $imageName = '';
        $get_image = '#<meta property="og:image" content="(.*?)" />#';
        preg_match($get_image, $getWebsite, $image);
        $image = $image[1];

        if ($image) {
            $imageName = rand(1000, 9999) . time() . '.jpg';
            $img = Article::$uploads_path . '/' . $imageName;

            Image::make($image)
//                ->insert($waterMarkUrl, 'bottom-left', 5, 5)
                ->save($img);

            $img_thumb = Article::$thumbnails_uploads_path . $imageName;
            Image::make($image)
//                ->insert($waterMarkUrl, 'bottom-left', 5, 5)
                ->save($img_thumb);


//            file_put_contents($img, file_get_contents($image));
//            file_put_contents($img_thumb, file_get_contents($image));
        }

        Article::updateOrCreate(['title' => $title, 'user_id' => Auth::user()->id, 'category_id' => 17], ['user_id' => Auth::user()->id, 'category_id' => 17, 'language' => 'en', 'title' => $title, 'image' => $imageName, 'description' => $content]);


        echo '<form action="" method="get">' . '<input type="text" name="url" value=""/><input type="submit">';

    }

}
