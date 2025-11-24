<?php

namespace Modules\Gym\Http\Controllers\Admin;

use Modules\Article\Models\Article;
use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Gym\Http\Requests\GymAdminRequest;
use Modules\Gym\Http\Requests\GymRequest;
use Modules\Gym\Models\Category;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\GymCallCenterLog;
use Modules\Gym\Models\GymImage;
use Modules\Gym\Models\Service;
use Modules\Gym\Repositories\GymRepository;
use Modules\Trainer\Models\DistrictTrainer;
use Modules\Trainer\Models\Trainer;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Gym\Http\Requests\GymBrandRequest;
use Modules\Gym\Repositories\GymBrandRepository;
use Modules\Gym\Models\GymBrand;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Request;

class GymAdminController extends GenericAdminController
{
    public $GymRepository;

    public function __construct()
    {
        parent::__construct();

        $this->GymRepository = new GymRepository(new Application);
    }


    public function index()
    {

        $title = 'gyms List';
        $this->request_array = ['id', 'district_id', 'published', 'order_by'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if (request('trashed')) {
            $gyms = $this->GymRepository->with(['gym_brand', 'district.city'])->onlyTrashed();
        } else {
            $gyms = $this->GymRepository->with(['gym_brand', 'district.city']);
        }


        //apply filters
        $gyms->when($id, function ($query) use ($id) {
            $query->where('id', '=', $id);
        });
        $gyms->when($district_id, function ($query) use ($district_id) {
            $query->where('district_id', '=', $district_id);
        });
        $gyms->when(isset($published) && ($published != ''), function ($query) use ($published) {
            $query->where('published', '=', $published);
        });

//        $gyms->where('published', 0);
//        $gyms->orderBy('updated_at', 'desc');
        $search_query = request()->query();

        $gyms->when(isset($order_by) && ($order_by != ''), function ($query) use ($order_by) {
            if($order_by == 'views'){
                $query->orderBy('views', 'desc');
            }else if($order_by == 'date'){
                $query->orderBy('created_at', 'desc');
            }
        });
        if (request()->ajax() && request()->exists('export')) {
            $gyms = $gyms->get();
            $array = $this->prepareForExport($gyms);
            $fileName = 'gyms-' . Carbon::now()->toDateTimeString();
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
            $gyms = $gyms->orderBy('id', 'DESC')->paginate($this->limit);
            $total = $gyms->total();
        } else {
            $gyms = $gyms->orderBy('id', 'DESC')->get();
            $total = $gyms->count();
        }

        $cities = City::get();
        $districts = District::get();

        return view('gym::Admin.gym_admin_list', compact('gyms', 'title', 'total', 'search_query', 'cities', 'districts'));
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

        $title = 'Create Gym';
        $gym_category_ids = [];
        $gym_service_ids = [];

        $cities = City::get();
        $districts = District::get();
        $categories = Category::get();
        $services = Service::get();

        $getImages = '';
        return view('gym::Admin.gym_admin_create', [
            'gym' => new GymBrand(),
            'getImages' => $getImages,
            'gym_service_ids' => (array)$gym_service_ids,
            'gym_category_ids' => (array)$gym_category_ids,
            'cities' => $cities,
            'districts' => $districts,
            'services' => $services,
            'categories' => $categories,
            'title' => $title]);
    }

    public function store(Request $request)
    {
        $gymbrand_inputs['user_id'] = Auth::user()->id;
        $gymbrand_inputs['name_ar'] = $request['name_ar'];
        $gymbrand_inputs['name_en'] = $request['name_en'];
        $gymbrand_inputs['description_ar'] = $request['description_ar'];
        $gymbrand_inputs['description_en'] = $request['description_en'];
        $gymbrand_inputs['main_phone'] = $request['main_phone'];
        $gymbrand_inputs['socials'] = (array_filter($request['socials']));

        if(is_file($request['logo']))
            $gymbrand_inputs = $this->uploadFile($gymbrand_inputs, 'logo');

        $gymbrand = GymBrand::create($gymbrand_inputs);

        $gym_inputs = $request->all(['address', 'address_ar', 'address_en', 'district_id', 'cover_image', 'image', 'phones', 'lat', 'lng']);
        $gym_inputs = $this->uploadFile($gym_inputs, 'cover_image');
        $gym_inputs = $this->uploadFile($gym_inputs, 'image');
        if($gym_inputs['phones']) $gym_inputs['phones'] = (explode(',', $gym_inputs['phones'])); else unset($gym_inputs['phones']);

        $gym_inputs['gym_brand_id'] = $gymbrand->id;

        $gym_inputs['published'] = $request['published'] ?? 0;
        $gym_inputs['featured'] = $request['featured'] ?? 0;

        if($gym_inputs['cover_image'])  $gym_inputs = $this->uploadFile($gym_inputs, 'cover_image'); else unset($gym_inputs['cover_image']);
        if($gym_inputs['image'])  $gym_inputs = $this->uploadFile($gym_inputs, 'image'); else unset($gym_inputs['image']);


        $gym = Gym::create($gym_inputs);

        if(count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
        if(count((array)$request->services) > 0) $gym->services()->sync($request->services);

        $images = [];
        if(isset($request->images)) $images = explode(',', trim($request->images, ','));
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


        sweet_alert()->success('Done', 'Gym Added successfully');
        return redirect(route('listGym'));
    }

    public function edit($id)
    {

        $title = 'Edit Gym';
        $gym = $this->GymRepository->with(['gym_brand','services', 'categories', 'images'])->where('id', $id)->withTrashed()->first();

        $gym_category_ids = $gym_service_ids = $images = [];
        if ($gym) {
            $gym_category_ids = $gym->categories->pluck('id')->toArray();
            $gym_service_ids = $gym->services->pluck('id')->toArray();
            $images = $gym->images->pluck('image');
        }

        $cities = City::get();
        $districts = District::get();
        $categories = Category::get();
        $services = Service::get();

        $getImages = '';
        if ($images)
            $getImages = $this->getFileInfo($images);



        return view('gym::Admin.gym_admin_edit',
            [
                'gym' => $gym,
                'getImages' => $getImages,
                'gym_service_ids' => (array)$gym_service_ids,
                'gym_category_ids' => (array)$gym_category_ids,
                'cities' => $cities,
                'districts' => $districts,
                'services' => $services,
                'categories' => $categories,
                'title' => $title]);
    }

    public function update(GymAdminRequest $request, $id)
    {
        $gym = Gym::with(['gym_brand'])->withTrashed()->find($id);

        $gymbrand_inputs['name_ar'] = $request['name_ar'];
        $gymbrand_inputs['name_en'] = $request['name_en'];
        $gymbrand_inputs['description_ar'] = $request['description_ar'];
        $gymbrand_inputs['description_en'] = $request['description_en'];
        $gymbrand_inputs['main_phone'] = $request['main_phone'];
        $gymbrand_inputs['socials'] = json_encode(array_filter($request['socials']));

        if(is_file($request['logo']))
            $gymbrand_inputs = $this->uploadFile($gymbrand_inputs, 'logo');

        GymBrand::where('id', $gym->gym_brand_id)->update($gymbrand_inputs);


        $gym_inputs = ($request->all(['address', 'address_ar', 'address_en', 'district_id', 'cover_image', 'image', 'phones', 'lat', 'lng']));

        $gym_inputs['published'] = $request['published'] ?? 0;
        $gym_inputs['featured'] = $request['featured'] ?? 0;

        if($gym_inputs['cover_image'])  $gym_inputs = $this->uploadFile($gym_inputs, 'cover_image'); else unset($gym_inputs['cover_image']);
        if($gym_inputs['image'])  $gym_inputs = $this->uploadFile($gym_inputs, 'image'); else unset($gym_inputs['image']);

        if($gym_inputs['phones']) $gym_inputs['phones'] = json_encode(explode(',', $gym_inputs['phones'])); else unset($gym_inputs['phones']);
        $gym_inputs['gym_brand_id'] = $gym->gym_brand_id;
        Gym::where('id', $gym->id)->update($gym_inputs);

        if(count((array)$request->categories) > 0) $gym->categories()->sync($request->categories);
        if(count((array)$request->services) > 0) $gym->services()->sync($request->services);

        $images = [];
        if(isset($request->images)) $images = explode(',', trim($request->images, ','));

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

        sweet_alert()->success('Done', 'Gym Updated successfully');
        return redirect(route('listGym'));
    }


    private function getFileInfo($files)
    {
        $path_url = asset(GymImage::$uploads_path);
        $path = $_SERVER["DOCUMENT_ROOT"].'/'.GymImage::$uploads_path;
        $video = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $fileAndPath = $path.$fileName;
            if(file_exists($fileAndPath)){
                $headers = (array)get_headers($file, true);
                $video[] = array('name' => $fileName, 'size' => $headers['content-length'], 'path' => $path_url);
            }
        }
        return $video;
    }

    public function destroy($id)
    {
        $gym = $this->GymRepository->withTrashed()->find($id);
        if ($gym->trashed()) {
            $gym->restore();
        } else {
            $gym->delete();
        }
        sweet_alert()->success('Done', 'Gym Deleted successfully');
        return redirect(route('listGym'));
    }


    public function uploadImages(Request $request)
    {
        $input_file = 'file';
        $this->uploadFile($request, $input_file);//$this->prepare_inputs($request);
        return Response::json(['target_file' => $this->imageName], 200);
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $inputs = $this->uploadFile($inputs, $input_file);

        $input_file = 'cover_image';
        $inputs = $this->uploadFile($inputs, $input_file);

//        $input_file = 'file';
//        $inputs = $this->uploadFile($inputs, $input_file);

        return $inputs;
    }

    private function uploadFile($inputs, $file)
    {

        $input_file = $file;
        $uploaded = '';

        $destinationPath = base_path(GymBrand::$uploads_path);
        $ThumbnailsDestinationPath = base_path(GymBrand::$thumbnails_uploads_path);
//        $waterMarkUrl = base_path('resources/assets/front/img/watermark.png');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        if (!File::exists($ThumbnailsDestinationPath)) {
            File::makeDirectory($ThumbnailsDestinationPath, $mode = 0777, true, true);
        }
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            if (is_image($file->getRealPath())) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


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
//                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
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
//                    $img->insert($waterMarkUrl, 'bottom-left', 5, 5);
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

    function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getWebsiteContent(){

//        $phones = GymBrand::select('id', 'main_phone')->get();
//        foreach ($phones as $phone){
//            $getPhones = explode(',',$phone->main_phone);
//            $phone->main_phone = trim($getPhones[0]);
//            $phone->save();
//        }


dd('ss');
        $url = (request()->get('url'));
        $getWebsite = ($this->curl_get_contents('https://cairogyms.com/category/personal-trainers/'.$url));

        $patternMatches = '/<div class="td_module_16 td_module_wrap td-animation-stack">(.*?)<\/table>/s';
        $patternImageMatches = '/<div class="td-module-thumb"><a href="(.*?)" rel="bookmark" class="td-image-wrap " title="(.*?)" ><img class="entry-thumb" src="" alt="(.*?)" title="(.*?)" data-type="image_tag" data-img-url="(.*?)"  width="160" height="160" \/><\/a><\/div>/s';
        $patternMatches = '/<table>
<tr>
<td>Name<\/td>
<td>(.*?)<\/td>
<\/tr>
<tr>
<td>Current Job<\/td>
<td>(.*?)<\/td>
<\/tr>
<tr>
<td>Gym<\/td>
<td>(.*?)<\/td>
<\/tr>
<tr>
<\/tr>
<\/table>/s';
        preg_match_all($patternMatches, $getWebsite, $matches);
        preg_match_all($patternImageMatches, $getWebsite, $image_matches);

        dd($image_matches);

        $gyms = [];
        $x = 0;
        foreach($matches[1] as $i => $names){

            $getImage = ($image_matches[5][$i]);
            $imageName = '';
            if($getImage){
                $getOrginalImage = substr($getImage, 0, strpos($getImage, "?"));
                $imageName = rand(1000, 9999) . time() . '.jpg';
                $img = Trainer::$uploads_path . '/' . $imageName;
                $img_thumb = Trainer::$thumbnails_uploads_path . '/' . $imageName;
                Image::make($getOrginalImage)
                    ->save($img);
                Image::make($getImage)
                    ->save($img_thumb);
            }

            $gyms[$x]['user_id'] = 1;
            $gyms[$x]['city_id'] = 1;
            $gyms[$x]['name_ar'] = $names;
            $gyms[$x]['name_ar'] = $names;
            $gyms[$x]['image'] = $imageName;
            $gyms[$x]['reference_url'] = $image_matches[1][$i];
            $gyms[$x]['name_en'] = $names;
            $gyms[$x]['about_ar'] = $matches[2][$i];
            $gyms[$x]['about_en'] = $matches[2][$i];
            if($matches[3][$i] != 'N/A')    $gyms[$x]['gym_name'] = $matches[3][$i]; else $gyms[$x]['gym_name'] = '';

            Trainer::updateOrCreate(['name_en' => $names], $gyms[$x]);

            $x++;
        }

dd('dd');
        if(isset($matches) && (count($matches[0]) > 0)){
            foreach ($matches[0] as $key => $match) {
                $patternAddress = '/<td>Address<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternAddress, $match, $getAddress);
                $getAddress = @trim(strip_tags($getAddress[2][0]));

                $patternTel = '/<td>Telephone<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternTel, $match, $getTel);
                $getTel = @trim(strip_tags($getTel[2][0]));

                $patternUrl = '/<td>Website<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternUrl, $match, $getUrl);
                $getUrl = @trim(strip_tags($getUrl[2][0]));

                $patternHours = '/<td>Hours<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternHours, $match, $getHours);
                $getHours = @trim(strip_tags($getHours[2][0]));

                $patternNameAndImage = '/<img width="([0-9]+)?" height="([0-9]+)?" class="entry-thumb" src="(.*?)" (.*?) title="(.*?)"/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternNameAndImage, $match, $getNameAndImage);
                $getName = @trim(strip_tags($getNameAndImage[5][0]));
                $getImage = @trim(strip_tags($getNameAndImage[3][0]));

                if($getImage){
                    $imageName = rand(1000, 9999) . time() . '.jpg';
                    $img = Gym::$uploads_path . '/' . $imageName;
                    $img_thumb = Gym::$thumbnails_uploads_path . '/' . $imageName;

                    Image::make($getImage)
                        ->save($img);
                    Image::make($getImage)
                        ->save($img_thumb);
                }
                if($getName && $getAddress && $getTel){
                    echo $getName.' - '.$getAddress.'<br/>';
                    $brand = GymBrand::updateOrCreate(['name_en' => $getName, 'user_id' => Auth::user()->id], ['user_id' => Auth::user()->id, 'name_ar' => $getName, 'name_en' => $getName, 'logo' => $imageName, 'main_phone' => $getTel]);
                    Gym::updateOrCreate(['address' => $getAddress], ['gym_brand_id' => $brand->id, 'district_id' => 1000,'image' => $imageName, 'address' => $getAddress, 'phones' => [$getTel]]);

                }
            }
        }

        dd('ss');

        if(\request('index') == 1){
            $district_id = \request('district_id');
            $gym_id = \request('gym_id');
            Gym::where('id', $gym_id)->update(['district_id' => $district_id, 'published' => 1]);
            dd('sss');

        }


        $gyms = Gym::where('published', 0)->limit(20)->get();
        $return = '';
        foreach ($gyms as $gym){
            $address = trim(mb_substr($gym->address, -50, null, 'UTF-8'));
            $addresss = array_filter(explode(' ', $address));

            $district = new District();
            $district = $district->where('name_ar', 'like', "%".$address."%")->orWhere('name_en', 'like', "%".$address."%");
            foreach ($addresss as $add){
                $district = $district->orWhere('name_ar', 'like', "%".$add."%")->orWhere('name_en', 'like', "%".$add."%");
            }
            $districts = $district->get();
            $return .= $gym->address.' - <form method="get" action="http://localhost/dalilgym/operate/gym/getWebsiteGym?index=1">
 <input type="hidden" name="index" value="1" />
 <input type="hidden" name="gym_id" value="'.$gym->id.'" />
<br/><select name="district_id">';
            if($districts) {
                foreach ($districts as $district) {
                    $return .= '<option value = "' . $district->id . '"> ' . $district->name . '</option>';
                }
                $return .= '</select><button type="submit"  name="Submit">Submit</button>
                </form><br/><hr/><br/>';
            }
//                var_dump(@$address.' - '. @implode(', ', $district));
        }
        return $return;



        dd('ss');




        /* --------------- */

        $url = (request()->get('url'));
        $getWebsite = ($this->curl_get_contents('http://www.yallaforma.com/trainers/page/'.$url));

        $patternMatches = '/<article class="member">(.*?)<\/article>/s';
        preg_match_all($patternMatches, $getWebsite, $matches);

        foreach ($matches[0] as $match){

            $patternName = '/<li class="title">(.*?)<\/li>/s';
            @preg_match_all($patternName, $match, $getName);
            $getName = @trim(strip_tags($getName[1][0]));

            $patternAge = '/<p><label>السن<\/label><span class="numb">(.*?)<\/span><\/p>/s';
            @preg_match_all($patternAge, $match, $getAge);
            $getAge = @trim(strip_tags($getAge[1][0]));

            $patternExperience = '/<p><label>الخبرة<\/label><span class="numb">(.*?)<\/span><\/p>/s';
            @preg_match_all($patternExperience, $match, $getExperience);
            $getExperience = @trim(strip_tags($getExperience[1][0]));

            $patternGender = '/<p><label>النوع<\/label>(.*?)<\/p>/s';
            @preg_match_all($patternGender, $match, $getGender);
            $getGender = @trim(strip_tags($getGender[1][0]));

            $patternDistrict = '/<p><label>المنطقة<\/label>(.*?)<\/p>/s';
            @preg_match_all($patternDistrict, $match, $getDistrict);
            $getDistrict = @trim(strip_tags($getDistrict[1][0]));
            if($getDistrict){
                $district = District::where('name_ar', 'like', '%'.$getDistrict.'%')->first();
            }

            $patternDescription = '/<p><label>التخصص<\/label>(.*?)<\/p>/s';
            @preg_match_all($patternDescription, $match, $getDescription);
            $getDescription = @trim(strip_tags($getDescription[1][0]));

            $arr = [
                'user_id' => Auth::user()->id,
                'name_ar' => $getName,
                'name_en' => $getName,
                'about_ar' => $getDescription,
                'about_en' => $getDescription,
                'experience' => (int)$getExperience,
                'gender' => $getGender = 'ذكر' ? 1 : 2,
                'birthday' => Carbon::now()->subYears((int)$getAge)->toDateString(),
                'city_id' => @$district->city_id
            ];
            $trainer = Trainer::create($arr);

            if(@$district){
                DistrictTrainer::insert(['trainer_id'=> $trainer->id, 'district_id' => $district->id]);
            }

        }


        dd('ss');

        $url = (request()->get('url'));
        $getWebsite = ($this->curl_get_contents('https://cairogyms.com/?sfid=32718&sf_paged='.$url));

        $patternMatches = '/<div class="td_module_16 td_module_wrap td-animation-stack">(.*?)<\/table>/s';
        preg_match_all($patternMatches, $getWebsite, $matches);

        if(isset($matches) && (count($matches[0]) > 0)){
            foreach ($matches[0] as $key => $match) {
                $patternAddress = '/<td>Address<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternAddress, $match, $getAddress);
                $getAddress = @trim(strip_tags($getAddress[2][0]));

                $patternTel = '/<td>Telephone<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternTel, $match, $getTel);
                $getTel = @trim(strip_tags($getTel[2][0]));

                $patternUrl = '/<td>Website<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternUrl, $match, $getUrl);
                $getUrl = @trim(strip_tags($getUrl[2][0]));

                $patternHours = '/<td>Hours<\/td>(.*?)<td>(.*?)<\/td>/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternHours, $match, $getHours);
                $getHours = @trim(strip_tags($getHours[2][0]));

                $patternNameAndImage = '/<img width="([0-9]+)?" height="([0-9]+)?" class="entry-thumb" src="(.*?)" (.*?) title="(.*?)"/s';
                $match = trim(preg_replace('/\s\s+/', ' ', $match));
                @preg_match_all($patternNameAndImage, $match, $getNameAndImage);
                $getName = @trim(strip_tags($getNameAndImage[5][0]));
                $getImage = @trim(strip_tags($getNameAndImage[3][0]));

                if($getImage){
                    $imageName = rand(1000, 9999) . time() . '.jpg';
                    $img = Gym::$uploads_path . '/' . $imageName;
                    $img_thumb = Gym::$thumbnails_uploads_path . '/' . $imageName;

                    Image::make($getImage)
                        ->save($img);
                    Image::make($getImage)
                        ->save($img_thumb);
                }
                if($getName && $getAddress && $getTel){
                    echo $getName.' - '.$getAddress.'<br/>';
                    $brand = GymBrand::updateOrCreate(['name_en' => $getName, 'user_id' => Auth::user()->id], ['user_id' => Auth::user()->id, 'name_ar' => $getName, 'name_en' => $getName, 'logo' => $imageName, 'main_phone' => $getTel]);
                    Gym::updateOrCreate(['address' => $getAddress], ['gym_brand_id' => $brand->id, 'district_id' => 1000,'image' => $imageName, 'address' => $getAddress, 'phones' => [$getTel]]);

                }
            }
        }

        dd('ss');
        /* ---------------------------------- */
        $url = (request()->get('url'));
        $getWebsite = ($this->curl_get_contents($url));

        $getContent = '/<article class="gym-item">(.*?)<\/article>/s';
        preg_match_all($getContent, $getWebsite, $matches);

        if(isset($matches) && (count($matches[0]) > 0)){
            foreach ($matches[0] as $key => $match) {
                    $patternName = '/<div class="gym-title">(.*?)<a href="(.*?)">(.*?)<\/a>(.*?)<\/div>/s';
                    @preg_match_all($patternName, $match, $getName);
                    $getName = @trim(strip_tags($getName[3][0]));

                    $patternImage = '/<img width="(.*?)" height="(.*?)" src="(.*?)" class="attachment-gym_logo_90 size-gym_logo_90"/s';
                    @preg_match_all($patternImage, $match, $getImage);
                    $getImage = @trim(strip_tags($getImage[3][0]));

                    $patternAddress = '/<li><span class="gym_address"><\/span>(.*?)<\/li>/s';
                    @preg_match_all($patternAddress, $match, $getAddress);
                    $getAddress = @trim(strip_tags($getAddress[1][0]));

                    $patternPhone = '/<li(.*?)><span class="gym_phone"><\/span>(.*?)<\/li>/s';
                    @preg_match_all($patternPhone, $match, $getPhone);
                    $getPhone = @(trim(strip_tags($getPhone[2][0])));

                    if($getName && $getAddress && $getPhone){
                        $imageName = '';
                        if($getImage) {
                            $imageName = rand(1000, 9999) . time() . '.jpg';
                            $img = Gym::$uploads_path . '/' . $imageName;
                            $img_thumb = Gym::$thumbnails_uploads_path . '/' . $imageName;

                            Image::make($getImage)
                                ->save($img);
                            Image::make($getImage)
                                ->save($img_thumb);
                        }
                       $brand = GymBrand::updateOrCreate(['name_en' => $getName, 'user_id' => Auth::user()->id], ['user_id' => Auth::user()->id, 'name_ar' => $getName, 'name_en' => $getName, 'logo' => $imageName, 'main_phone' => $getPhone]);
                        Gym::updateOrCreate(['gym_brand_id' => $brand->id], ['district_id' => 1000,'image' => $imageName, 'address' => $getAddress, 'phones' => [$getPhone]]);
                    }
            }
        }
        dd('sss');

        /* ---------------------------------- */

        $get_content = '/<!-- content -->(.*?)\<div id="review"/s';
        preg_match($get_content, $getWebsite, $content);
        if(!$content){
            $get_content = '/<!-- content -->(.*?)\<div class="essb_links/s';
            preg_match($get_content, $getWebsite, $content);
        }
        $content = $content[1];
        if($content){
            preg_match_all('/src="(.*?)"/s', $content,  $images);
            if($images[1]){
                foreach ($images[1]  as $image){
                    $imageName = rand(1000, 9999) . time() . '.jpg';
                    $img = Article::$uploads_path .'content'.'/'. $imageName;
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

        if($image) {
            $imageName = rand(1000, 9999) . time() . '.jpg';
            $img = Article::$uploads_path .'/'. $imageName;

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



    }

    public function saveCommentAjax(){
        $gym_id = \request('gym_id');
        $rate = \request('rate');
        $comment = \request('comment');
        if($gym_id && $comment){
            GymCallCenterLog::updateOrCreate(['gym_id' => $gym_id], ['gym_id' => $gym_id, 'comment' => $comment, 'rate' => $rate]);
            return 1;
        }
        return 0;
    }

}
