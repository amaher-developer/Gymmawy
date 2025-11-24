<?php

namespace Modules\Crud\Http\Controllers;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

use Nwidart\Modules\Facades\Module;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ZipArchiveController extends GenericAdminController
{

    public function export(Request $request)
    {
        if($request->module) {
            $files = glob(base_path('Modules/' . ucfirst($request->module) . '/'));

            if (!File::exists( base_path('/exports'))) {
                File::makeDirectory(base_path('/exports'), $mode = 0755, true, true);
            }
            Zipper::make('exports/' . ucfirst($request->module) . '.zip')->add($files)->close();
            chmod(base_path('exports/'.ucfirst($request->module) . '.zip'), 0777);
        }
        sweet_alert()->success('Done', 'Extract module successfully in (exports/) folder');
        return redirect(route('listModules'));
    }


    public function import(Request $request)
    {
        if($request->module && File::exists(base_path('exports/'.$request->module.'.zip'))) {
            $slug = str_slug(str_singular($request->module));
            if (!Module::exists($slug)) {
                shell_exec("php artisan make:module {$slug} --quick");
            }

            Zipper::make(base_path( 'exports/'. ucfirst($request->module) . '.zip'))->extractTo('Modules/'. ucfirst($request->module));
            $command = "chmod -R 777 ".base_path('Modules/'.ucfirst($request->module));
            shell_exec($command);
        }

        if($request->module == 'all'){
            foreach (glob("exports/*.zip") as $file) {
                $filename = trim(str_replace('exports/', '',$file));
                $modulename = ucfirst(trim(str_replace('.zip', '',$filename)));

                if( !File::exists(base_path('Modules/'.$modulename.'/'))){
                    $slug = str_slug(str_singular($modulename));
                    if (!Module::exists($slug)) {
                        shell_exec("php artisan make:module {$modulename} --quick");
                    }

                    Zipper::make(base_path( 'exports/'. ($modulename) . '.zip'))->extractTo('Modules/'. ($modulename));
                    $command = "chmod -R 777 ".base_path('Modules/'.($modulename));
                    shell_exec($command);


                }
            }
        }
        sweet_alert()->success('Done', 'Import '.ucfirst($request->module).' module successfully.');
        return redirect(route('listModules'));
    }

}