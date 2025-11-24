<?php

namespace Modules\Client\Http\Controllers\Admin;

use Modules\Gym\Models\Gym;
use Illuminate\Container\Container as Application;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Client\Http\Requests\ClientRequest;
use Modules\Client\Repositories\ClientRepository;
use Modules\Client\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ClientAdminController extends GenericAdminController
{
     public $ClientRepository;

         public function __construct()
         {
             parent::__construct();

             $this->ClientRepository=new ClientRepository(new Application);
         }


    public function index()
    {

        $title = 'clients List';
        $this->request_array = ['id', 'phone', 'status'];
        $request_array = $this->request_array;
        foreach ($request_array as $item) $$item = request()->has($item) ? request()->$item : false;
        if(request('trashed'))
        {
            $clients = $this->ClientRepository->with('gym.gym_brand')->onlyTrashed()->orderBy('id', 'DESC');
        }
        else
        {
            $clients = $this->ClientRepository->with('gym.gym_brand')->orderBy('id', 'DESC');
        }


             //apply filters
                $clients->when($id, function ($query) use ($id) {
                        $query->where('id','=', $id);
                });
                $clients->when($phone, function ($query) use ($phone) {
                    $query->where('phone','=', $phone);
                });
                $clients->when((@isset($status) && $status), function ($query) use ($status) {
                    if($status == 1)
                        $query->whereDate('date_to', '>=', Carbon::now()->toDateString());
                    else
                        $query->whereDate('date_to', '<', Carbon::now()->toDateString());
                });
                 $search_query = request()->query();

                       if (request()->ajax() && request()->exists('export')) {
                             $clients = $clients->get();
                             $array = $this->prepareForExport($clients);
                             $fileName = 'clients-' . Carbon::now()->toDateTimeString();
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
                             $clients = $clients->paginate($this->limit);
                             $total = $clients->total();
                         } else {
                             $clients = $clients->get();
                             $total = $clients->count();
                         }


        return view('client::Admin.client_admin_list', compact('clients','title', 'total', 'search_query'));
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
        $token = Str::random(60);
        $title = 'Create Client';
        return view('client::Admin.client_admin_form', ['client' => new Client(),'title'=>$title,'token'=>$token]);
    }

    public function store(ClientRequest $request)
    {
        $client_inputs = $this->prepare_inputs($request->except(['_token']));
        if($client_inputs['gym_id']){
            $gym = Gym::where('id', $client_inputs['gym_id'])->first();
            if(!$gym){
                return Redirect::back()->withErrors(['gym_id' => 'The Gym ID not exists!']);
            }
        }
        $this->ClientRepository->create($client_inputs);
        sweet_alert()->success('Done', 'Client Added successfully');
        return redirect(route('listClient'));
    }

    public function migrate($id)
    {
        $client = $this->ClientRepository->withTrashed()->find($id);
        try {
            $client_curl = new \GuzzleHttp\Client();
            $response = $client_curl->request('GET', $client->sw_url.'/api/gym-migrate', ['form_params' => []]);
            $result = json_decode($response->getBody()->getContents());
            $last_migrate  = @$result->last_migrate;
            if(@$client){
                Client::where('id', $client->id)->update(['last_migrate' => $last_migrate]);
            }

            return @$last_migrate;
        } catch (\Exception $e) {
            return '0';
        }
    }
    public function edit($id)
    {
        $token = Str::random(60);
        $client =$this->ClientRepository->withTrashed()->find($id);
        $title = 'Edit Client';
        return view('client::Admin.client_admin_form', ['client' => $client,'title'=>$title,'token'=>$token]);
    }

    public function update(ClientRequest $request, $id)
    {
        $client =$this->ClientRepository->withTrashed()->find($id);
        $client_inputs = $this->prepare_inputs($request->except(['_token']));
        if($client_inputs['gym_id']){
            $gym = Gym::where('id', $client_inputs['gym_id'])->first();
            if(!$gym){
                return Redirect::back()->withErrors(['gym_id' => 'The Gym ID not exists!']);
            }
        }
        $client->update($client_inputs);
        sweet_alert()->success('Done', 'Client Updated successfully');
        return redirect(route('listClient'));
    }

    public function destroy($id)
      {
          $client =$this->ClientRepository->withTrashed()->find($id);
          if($client->trashed())
          {
              $client->restore();
          }
          else
          {
              $client->delete();
          }
        sweet_alert()->success('Done', 'Client Deleted successfully');
        return redirect(route('listClient'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

                $destinationPath = base_path($this->ClientRepository->model()::$uploads_path);
                $ThumbnailsDestinationPath = base_path($this->ClientRepository->model()::$thumbnails_uploads_path);
        
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
        

//        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

}
