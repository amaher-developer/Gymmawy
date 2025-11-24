<?php

namespace Modules\Generic\Http\Controllers\Front;

use Modules\Access\Models\User;
use Modules\Access\Models\UserAddress;
use Modules\Addon\Models\Bodybuilder;
use Modules\Addon\Models\CalorieCategory;
use Modules\Article\Models\Article;
use Modules\Article\Models\ArticleCategory;
use Modules\Ask\Models\Question;
use Modules\Banner\Models\Banner;
use Modules\Generic\Http\Requests\ContactRequest;
use Modules\Generic\Models\City;
use Modules\Generic\Models\Contact;
use Modules\Generic\Models\District;
use Modules\Generic\Models\Feedback;
use Modules\Generic\Models\Newsletter;
use Modules\Generic\Models\Setting;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\GymBrand;
use Modules\Gym\Models\GymFavorite;
use Modules\Item\Models\Item;
use Modules\Location\Models\Area;
use Modules\Trainer\Models\Trainer;
use Modules\Trainer\Models\TrainerFavorite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Thujohn\Rss\Rss;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\File;

class MainFrontController extends GenericFrontController
{
    public $total_sitemap_links;
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
//        $title = 'دليل جيم';
        $home_vars = [];
        $features_gyms = Gym::active()->with(['categories', 'gym_brand', 'district.city', 'favorites'])->where('featured', true)->limit(12)->
        orderBy(DB::raw('RAND()'))->
//        orderBy('views', 'desc')->
        get();
        
        //        $latest_gyms = Gym::with(['district.city', 'user'])->where('published', 1)->limit(6)->orderBy(DB::raw('RAND()'))->get();
        $calorie_categories = CalorieCategory::orderBy('id', 'asc')->get();
        $latest_trainers = Trainer::active()->limit(12)->orderByRaw('RAND()')->get();
        $latest_articles = Article::active()->with('user')->where('language', $this->lang)->limit(4)->orderBy('id', 'desc')->get();
        $banner = Banner::where('is_web', true)->where('date_to', '>', Carbon::now())->first();

        return view('generic::Front.layouts.home', compact('home_vars', 'banner','calorie_categories', 'features_gyms', 'latest_articles', 'latest_trainers'));
    }


    public function about()
    {
        return view('generic::Front.pages.about', [
            'title' => trans('global.about_us'),
            'content' => $this->mainSettings->about
        ]);
    }

    public function terms()
    {
        return view('generic::Front.pages.terms', [
            'title' => trans('global.terms'),
            'content' => $this->mainSettings->terms
        ]);
    }

    public function policy()
    {
        return view('generic::Front.pages.policy', [
            'title' => trans('global.policy'),
            'content' => $this->mainSettings->policy
        ]);
    }
    public function mobileTerms()
    {
        return view('generic::Front.pages.mobile_terms', [
            'title' => trans('global.terms'),
            'content' => $this->mainSettings->terms
        ]);
    }

    public function mobilePolicy()
    {
        return view('generic::Front.pages.mobile_policy', [
            'title' => trans('global.policy'),
            'content' => $this->mainSettings->policy
        ]);
    }

    public function favorites()
    {
        $gyms = Gym::active()->with(['district.city', 'categories', 'favorites'])->whereHas('favorites', function ($q) {
            $q->where('user_id', Auth::user()->id);
        })->get();

        $trainers = Trainer::active()->with(['favorites'])->whereHas('favorites', function ($q) {
            $q->where('user_id', Auth::user()->id);
        })->get();

        return view('generic::Front.favorites', [
            'title' => trans('global.wishlist'),
            'gyms' => $gyms,
            'trainers' => $trainers,
        ]);
    }

    public function contactCreate()
    {

        return view('generic::Front.pages.contact', [
            'title' => trans('global.contact'),
            'about' => $this->mainSettings->about
        ]);
    }

    /**
     * @return string
     */
    public function contactStore(ContactRequest $request)
    {
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $msg = $request->message;
        $setting = $this->mainSettings;

        $data = array(
            'name' => $name
        , 'phone' => $phone
        , 'email' => $email
        , 'msg' => $msg
        );
        Mail::send('emails.contact_us', $data, function ($message) use ($data, $setting) {
            $message->from($data['email'], $data['name']);
            $message->to($setting->support_email, trans('global.contact_us'))->subject(trans('global.contact_us'));
        });


        Contact::create($data);

        return redirect()->route('thanks');

    }

    public function newsletter(Request $request)
    {
        $email = $request->email;
        if (!$email)
            return false;

        Newsletter::updateOrCreate(['email' => $email], ['email' => $email]);
        return true;

    }

    public function feedbackStore(Request $request)
    {
        if ($request->feedback)
            Feedback::create(['feedback' => $request->feedback, 'user_id' => Auth::user()->id]);

        sweet_alert()->success(trans('admin.done'), trans('admin.successfully_added'));
        return redirect()->back();
    }

    public function thanks()
    {
        return view('generic::Front.pages.thanks', [
            'title' => trans('global.thank_you')]);
    }

    public function home()
    {
        $currentGym = GymBrand::where('user_id', Auth::user()->id)->first();
        $currentTrainer = Trainer::where('user_id', Auth::user()->id)->first();
        return view('generic::Front.user.home', [
            'title' => trans('global.home'),
            'currentTrainer' => $currentTrainer,
            'currentGym' => $currentGym,
        ]);
    }

    public function searchRedirect(Request $request)
    {
        if ($request->get('type') == 2) {
            return redirect()->route('trainers', ['district' => $request->get('district'), 'city' => $request->get('city')]);
        } else {
            return redirect()->route('gyms', ['district' => $request->get('district'), 'city' => $request->get('city')]);
        }
    }

    public function addFavoriteByAjax()
    {

        $type = \request()->get('type');
        $id = \request()->get('id');
        $userId = Auth::user()->id;

        if ($type == 1) {
            GymFavorite::firstOrCreate(['gym_id' => $id, 'user_id' => $userId]);
            return 'true';
        } else if ($type == 2) {
            TrainerFavorite::firstOrCreate(['trainer_id' => $id, 'user_id' => $userId]);
            return 'true';
        }

        return 'false';

    }

    public function removeFavoriteByAjax()
    {

        $type = \request()->get('type');
        $id = \request()->get('id');
        $userId = Auth::user()->id;
        if ($type == 1) {
            GymFavorite::where('gym_id', $id)->where('user_id', $userId)->forceDelete();
            return 'true';
        } else if ($type == 2) {
            TrainerFavorite::where('trainer_id', $id)->where('user_id', $userId)->forceDelete();
            return 'true';
        }

        return 'false';

    }

    function createWatermark()
    {
        return view('generic::Front.pages.watermark', [
            'title' => trans('global.watermark'),
        ]);
    }

    function storeWatermark(Request $request)
    {
        $position = $request->position;
        if ($position == 1)
            $position = 'top-right';
        else if ($position == 2)
            $position = 'bottom-right';
        else if ($position == 3)
            $position = 'top-left';
        else
            $position = 'bottom-left';

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:4096',
        ]);
        $image = $request->file('image');
        $input['image'] = time() . '.' . $image->getClientOriginalExtension();
        $imgFile = Image::make($image->getRealPath());

        if (is_image($image->getRealPath())) {
            $filename = rand(0, 20000) . time() . '.' . $image->getClientOriginalExtension();


            $uploaded = $filename;

            $img = Image::make($image);
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
                $img->insert(public_path('resources/assets/front/img/watermark.png'), $position, 20, 20);
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
                $img->insert(public_path('resources/assets/front/img/watermark.png'), $position, 20, 20);
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

        /* insert watermark at bottom-right corner with 10px offset */
        $imgFile->insert(public_path('resources/assets/front/img/watermark.png'), $position, 20, 20);
//      $imgFile->save(public_path('/uploads/watermark').'/'.$input['image']);

        $imgFile->encode('png');
        $type = 'png';
        $new_image = 'data:image/' . $type . ';base64,' . base64_encode($imgFile);

        return view('generic::Front.pages.watermark', [
            'title' => trans('global.watermark'),
            'new_image' => $new_image
        ]);

    }

    function rss(Rss $rss)
    {
        $feed = $rss->feed('2.0', 'UTF-8');
        $feed->channel([
            'title' => "Channel's title",
            'description' => "Channel's description",
            'link' => "http://www.test.com/"
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $feed->item([
                'title' => 'Item ' . $i,
                'description|cdata' => 'Description ' . $i,
                'link' => 'http://www.test.com/article-' . $i
            ]);
        }

        return response($feed, 200)->header('Content-Type', 'text/xml');
    }


    function sitemap()
    {

        $this->total_sitemap_links = 0;

        $a = '<?xml version="1.0" encoding="UTF-8"?>';

        $a .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url>
                <loc>' . env('APP_URL') . '</loc>
                <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
                </url>';
        $languages = ['ar', 'en'];
        foreach ($languages as $lang) {

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/login</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/register</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/password/reset</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/contact</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/gyms</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/trainers</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/articles</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/calorie-categories</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/calculate-ibw</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/calculate-bmi</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/calculate-calories</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/bodybuilders</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/calculate-water</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/asks</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/asks/tags</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . env('APP_URL') . '/' . $lang . '/ask/create-question</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . str_replace('https://', 'https://app.',env('APP_URL') ) . '/' . $lang . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . str_replace('https://', 'https://demo.',env('APP_URL') ) . '/' . $lang . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';
            $a .= '<url>
        <loc>' . str_replace('https://', 'https://training.',env('APP_URL') ) . '/' . $lang . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
        </url>';

            $this->total_sitemap_links = 14;
            /* =====================================*/
            $calorieCategories = new CalorieCategory();
            if ($calorieCategories->count() > 0) {
                $records = $calorieCategories->get();
                $i = 0;
                foreach ($records as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/calories/' . $record['id'] . '/' . $this->generateSlug($record['name_'.$lang]) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }
            /* =====================================*/
            $articleCategories = new ArticleCategory();
            if ($articleCategories->count() > 0) {
                $records = $articleCategories->get();
                $i = 0;
                foreach ($records as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/articles/' . $record['id'] . '/' . $this->generateSlug($record['name_'.$lang]) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }
            /* =====================================*/
            $articles = new Article();
            $articles = $articles->where('published', true)->where('language', $lang)->orderBy('id', 'desc')->get();
            if ($articles->count() > 0) {
                $i = 0;
                foreach ($articles as $record) {
                    if($record['slug']){ $slug = $record['slug'];}else{$slug =  $this->generateSlug($record['title']);}
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/article/' . $record['id'] . '/' . $slug . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }
            /* =====================================*/
            $bodybuilders = new Bodybuilder();
            if ($bodybuilders->count() > 0) {
                $records = $bodybuilders->get();
                $i = 0;
                foreach ($records as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/bodybuilder/' . $record['id'] . '/' . $this->generateSlug($record['name_'.$lang]) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }
            /* =====================================*/
            $gyms = new Gym();
            $gyms = $gyms->with('gym_brand')->where('published', true)->get();
            if ($gyms->count() > 0) {
                $i = 0;
                foreach ($gyms as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/gym/' . $record['id'] . '/' . $this->generateSlug($record['gym_brand']['name_'.$lang]) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }

            /* =====================================*/
            $trainers = new Trainer();
            $trainers = $trainers->where('published', true)->get();
            if ($trainers->count() > 0) {
                $i = 0;
                foreach ($trainers as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/trainer/' . $record['id'] . '/' . $this->generateSlug($record['name_'.$lang]) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }
            /* =====================================*/
            $questions = new Question();
            $questions = $questions->where('published', true)->get();
            if ($questions->count() > 0) {
                $i = 0;
                foreach ($questions as $record) {
                    $a .= '<url>
              <loc>' . env('APP_URL') . '/' . $lang . '/ask/' . $record['id'] . '/' . $this->generateSlug($record['question']) . '</loc>
        <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
              </url>';
                    $i++;
                    $this->total_sitemap_links++;
                }
                echo $this->total_sitemap_links.'<br/>';
            }

            echo '<br/><hr/><br/>';

        }

        $a .= '</urlset>';

        $f = fopen(public_path('sitemap.xml'), 'w');
        fwrite($f, $a);
        fclose($f);


    }

    private function generateSlug($name){
//        echo $name.'<br/>';
        $string = str_replace(' ', '-',strtolower(str_replace('&', '', $name)));
        $string = str_replace('/', '', $string);
        $string = htmlspecialchars($string);
        return urldecode($string);
    }


}
