<?php

namespace Modules\Generic\Http\Controllers\Admin;

use Modules\Crud\Models\Migration;
use Modules\Generic\Http\Controllers\GenericController;
use Modules\Generic\Repositories\SettingRepository;
use Nwidart\Modules\Facades\Module;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class GenericAdminController extends GenericController
{
    public $user;
    public $limit;
    public $request_array;
    public $SettingRepository;
    public $systemLanguages;
    public $modules_need_migration;
    public $lang;

    public function __construct()
    {
        $this->systemLanguages = ['ar', 'en'];
        $this->SettingRepository = new SettingRepository(new Application);
        $this->limit = 10;
        $this->request_array = [];
        $this->modules_need_migration = false;

        // Move all session/auth-dependent code into middleware callback
        $this->middleware(function ($request, $next) {
            if (!request()->is('operate') && !request()->is('operate/admin/log')) {
//                AdminLog::create([
//                    'admin_id' => Auth::user()->id,
//                    'link' => request()->fullUrl()
//                ]);
            }

            $this->lang = 'ar';
            request()->session()->put('lang', 'ar');
            app()->setLocale(request()->session()->get('lang'));
            View::share('lang', $this->lang);

            if(request()->is('operate/modules*')){
                request()->session()->put('lang', 'en');
                app()->setLocale(request()->session()->get('lang'));
            }

            $user = Auth::user();
            $this->user = $user;
            $this->limit = 10;
            $this->request_array = [];
    
            $this->modules_need_migration = false;
            // if(env('APP_ENV') == 'local') {
            //     $modules = Module::all();
            //     foreach ($modules as $module) {
            //         if (!$this->modules_need_migration) {
            //             foreach (File::allFiles(module_path($module['slug']) . '/Database/Migrations/') as $module_migrations) {
            //                 $migration_name = pathinfo($module_migrations->getPathname())['filename'];
            //                 if (sizeof(Migration::where('migration', $migration_name)->get()) == 0) {
            //                     $this->modules_need_migration = true;
            //                     break;
            //                 }
            //             }
    
            //         } else {
            //             break;
            //         }
            //     }

            // }
            View::share('migrate', $this->modules_need_migration);
            View::share('systemLanguages', $this->systemLanguages);
            View::share('mainSettings', $this->SettingRepository->first());
            View::share('currentUser', $this->user);

            return $next($request);
        });

    }



    public function uploadImageForCKEditorAjax()
    {
        $input_file = 'file';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path('uploads/ckeditor/');
            $image_url = asset('uploads/ckeditor/' . $filename);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                echo $image_url;
            }
        } else {
            echo '{"status":"error"}';
        }

    }

    public function checkForNewNotifications(Request $request)
    {
//        $now = Carbon::createFromTimestamp(time());
//        $last_order_time = $request->last_order_time ? Carbon::createFromTimestamp($request->last_order_time) : $now;
//        $last_ticket_time = $request->last_ticket_time ? Carbon::createFromTimestamp($request->last_ticket_time) : $now;
//
//        $response['orders'] = Order::where('created_at', '>=', $last_order_time)->get()->toArray();
//        $response['tickets'] = Ticket::where('created_at', '>=', $last_ticket_time)->get()->toArray();
//        $response['last_time_to_get_notification'] = $last_ticket_time->toDateTimeString();
//        if (!$response['orders'] && !$response['tickets'])
//            return ['status' => false, 'data' => ['last_time_to_get_notification' => $last_ticket_time->toDateTimeString()]];
//
//        return ['status' => true, 'data' => $response];

    }


}
