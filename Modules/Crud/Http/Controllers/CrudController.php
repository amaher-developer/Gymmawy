<?php

namespace Modules\Crud\Http\Controllers;

use Modules\Crud\Http\Requests\CrudRequest;
use Modules\Crud\Models\Crud;
use Modules\Crud\Models\Migration;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Nwidart\Modules\Facades\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CrudController extends GenericAdminController
{
    private $required_langs;

    function __construct()
    {

        parent::__construct();
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->required_langs = explode(',', env('PRIMARY_LANG'));
    }

    public function index()
    {
        $modules = Module::all()->toArray();

        foreach ($modules as $key=> &$module) {
            $module['migrate'] = false;
            foreach (File::allFiles(module_path($module['slug']) . '/Database/Migrations/') as $module_migrations) {
                $migration_name = pathinfo($module_migrations->getPathname())['filename'];
                if (sizeof(Migration::where('migration', $migration_name)->get()) == 0) {
                    $module['migrate'] = true;
                }
            }
        }



        return view('crud::list', compact('modules'));
    }

    public function create()
    {
        return view('crud::add', ['module' => new Crud()]);
    }

    public function store(CrudRequest $request)
    {
        $module['slug'] = str_slug(str_singular($request->input('name')));
        if (!Module::exists($module['slug'])) {
            shell_exec("php artisan make:module {$module['slug']} --quick");
            sweet_alert()->success('Done', 'Module Added successfully');
        } else {
            //sweet_alert()->error('Error', 'Module Exists');
        }

        return redirect(route('listModules'));
    }

    public function show($slug)
    {
        $crud = (object)Module::where('slug', $slug)->toArray();
        $migrate = false;
        $rollback = false;
        foreach (File::allFiles(module_path($slug) . '/Database/Migrations/') as $module_migrations) {
            $migration_name = pathinfo($module_migrations->getPathname())['filename'];
            if (sizeof(Migration::where('migration', $migration_name)->get()) == 0) {
                $migrate = true;
            } else {
                $rollback = true;
            }
        }
        return view('crud::show', ['module' => $crud, 'migrate' => $migrate, 'rollback' => $rollback]);
    }

    public function addController(Request $request, $slug)
    {
        $request->validate( [
            'name' => 'required',
            'section' => 'required'
        ]);
        $crud = (object)Module::where('slug', $slug)->toArray();

        $name = studly_case($request->input('name'));
        $name = str_replace('controller', '', $name);
        $name = str_replace('Controller', '', $name);
//        $name = studly_case($name);
        $sections = ['1' => 'Admin', '2' => 'Api', '3' => 'Front'];
        $section = $sections[$request->input('section')];

        $command = "php artisan make:module:controller {$slug} {$section}/{$name}{$section}Controller";
        $return = shell_exec($command);

        // Create Controller
        $AdminControllerTemplate = $this->create_controller_template($crud->name, $name, $section);
        file_put_contents(module_path($slug) . '/Http/Controllers/' . $section . '/' . $name . $section . 'Controller.php', $AdminControllerTemplate);

        //sweet_alert()->success('Done', 'Controller Added successfully');
        return redirect(route('showModule', $slug));
    }

    private function create_controller_template($main_module_name, $model_name, $Folder)
    {
        $ControllerTemplate = $this->get_template('Controller.txt');
        $ControllerTemplate = $this->replace_template($ControllerTemplate, array(
            '{{ucf_module_name}}' => ucfirst($main_module_name),
            '{{lcf_module_name}}' => strtolower($main_module_name),
            '{{lcf_model_name}}' => strtolower($model_name),
            '{{ucf_model_name}}' => $model_name,
            '{{ucf_folder_name}}' => ucfirst($Folder),
            '{{lcf_folder_name}}' => strtolower($Folder),
            '{{plural_model_name}}' => str_plural(strtolower($model_name)),
            '{{ucf_plural_model_name}}' => ucfirst(str_plural(strtolower($model_name))),
            '{{list_route_name}}' => 'list' . ucfirst(str_plural(strtolower($model_name)))
        ));
        return $ControllerTemplate;
    }

    private function get_template($template_name)
    {
        return file_get_contents(module_path('crud') . '/Templates/' . $template_name);

    }

    private function replace_template($template, $replaces)
    {
        foreach ($replaces as $key => $replace) {
            $template = str_replace($key, $replace, $template);
        }
        return $template;
    }

    public function addRequest(Request $request, $slug)
    {
        $request->validate( [
            'name' => 'required',
        ]);

        $name = studly_case(str_singular($request->input('name')));
        $name = str_replace('request', '', $name);
        $name = studly_case($name);

        shell_exec("php artisan make:module:request {$slug} {$name}Request");
        $request_template = file_get_contents(module_path($slug) . '/Http/Requests/' . $name . 'Request.php');
        $request_template = str_replace('false', 'true', $request_template);
        file_put_contents(module_path($slug) . '/Http/Requests/' . $name . 'Request.php', $request_template);

        //sweet_alert()->success('Done', 'Request Added successfully');
        return redirect(route('showModule', $slug));
    }

    public function addModel(Request $request, $slug)
    {
        $request->validate( [
            'name' => 'required',
        ]);

        $crud = (object)Module::where('slug', $slug)->toArray();
        $name = studly_case(str_singular($request->input('name')));
//Create Model
        $models_path = module_path($slug) . '/Models/';
        $model_template = $this->create_model_template($crud->name, $name);
        if (!File::exists($models_path)) {
            File::makeDirectory($models_path, $mode = 0755, true, true);
        }
        file_put_contents(module_path($slug) . '/Models/' . $name . '.php', $model_template);

        //Create Repository Folder
        $repository_path = module_path($slug) . '/Repositories/';
        if (!File::exists($repository_path)) {
            File::makeDirectory($repository_path, $mode = 0755, true, true);
        }

        //Create Repository
        $model_template = $this->create_repository_template($crud->name, $name);
        $repository_name = $name . 'Repository';
        file_put_contents($repository_path . $repository_name . '.php', $model_template);


        return redirect(route('showModule', $slug));
    }

    private function create_model_template($main_module_name, $model_name)
    {
        $pure_fields = (array)request('fields');
        $pure_types = (array)request('types');
        $multi_language = (array)request()->get('multi_language');

        $studly_model = studly_case($model_name);
        $appends_attribute = '';
        $appends_functions = '';

        foreach ($pure_fields as $key => $pure_field) {
            if (@$multi_language[$key]) {
                $appends_attribute .= "'$pure_field'" . ',';
                $appends_functions .=
                    '    public function get' . studly_case($pure_field) . 'Attribute()
    {
        $lang = \'' . $pure_field . '_\'. $this->lang;
        return $this->$lang;
    }

';
            }
            // For File Inputs
            if ($pure_types[$key] == 4) {
                $pure_field_thumbnail=$pure_field."_thumbnail";
//                $appends_attribute .= "'$pure_field'" . ',';
                $appends_attribute .= "'$pure_field_thumbnail'" . ',';
                $appends_functions .=
                    '    public function get' . studly_case($pure_field) . 'Attribute($' . $pure_field . ')
    {
        if($' . $pure_field . ')
        {
            return Asset(self::$uploads_path.$' . $pure_field . ');
        }
        else
            return $' . $pure_field . ';
    }
    
        public function get' . studly_case($pure_field) . 'ThumbnailAttribute()
    {
        if ($this->' . $pure_field . ') {
            return str_replace(self::$uploads_path,self::$thumbnails_uploads_path , $this->' . $pure_field . ');
        } else
            return $this->' . $pure_field . ';
    }


';
            }
        }

        $AdminControllerTemplate = $this->get_template('Model.txt');
        $AdminControllerTemplate = $this->replace_template($AdminControllerTemplate, array(
            '{{ucf_module_name}}' => ucfirst($main_module_name),
            '{{ucf_model_name}}' => $studly_model,
            '{{lc_plural_model_name}}' => strtolower(str_plural($studly_model)),
            '{{ucf_plural_model_name}}' => ucfirst(strtolower(str_plural($studly_model))),
            '{{appends_attribute}}' => $appends_attribute,
            '{{appends_functions}}' => $appends_functions,
            '{{lcf_module_name}}' => strtolower($main_module_name),

        ));
        return $AdminControllerTemplate;
    }

    public function addMigration(Request $request, $slug)
    {
        $request->validate( [
            'table_name' => 'required',
            'type' => 'required',
        ]);

        $types = ['1' => 'create', '2' => 'table'];
        $type = $types[$request->input('type')];
        $name = $request->input('name');
        $table_name = $request->input('table_name');
        $name = !empty($name) ? $name : $type . '_' . $table_name . '_table';
        $command = "php artisan make:module:migration {$slug} {$name} --{$type}={$table_name}";
        $result = shell_exec($command);
        return redirect(route('showModule', $slug));
    }

    public function addMiddleware(Request $request, $slug)
    {
        $request->validate( [
            'name' => 'required',
        ]);

        $name = studly_case(str_singular($request->input('name')));
        $command = "php artisan make:module:middleware {$slug} {$name} ";
        shell_exec($command);
        //sweet_alert()->success('Done', 'Middleware Added successfully');
        return redirect(route('showModule', $slug));
    }

    public function addSeeder(Request $request, $slug)
    {
        $request->validate( [
            'name' => 'required',
        ]);

        $name = studly_case(str_singular($request->input('name')));
        shell_exec("php artisan make:module:seeder {$slug} {$name}");
        //sweet_alert()->success('Done', 'Seeder Added successfully');
        return redirect(route('showModule', $slug));
    }

    public function reverseStatus($slug)
    {

    }

    public function addSubModule(Request $request, $slug)
    {
        $crud = (object)Module::where('slug', $slug)->toArray();

        $name = $request->input('name');
        $table_name = str_plural(strtolower(str_replace(' ', '_', $name)));
        $controller_name = $name = studly_case(str_singular($name));
        $sections = ['1' => 'Admin', '2' => 'Api', '3' => 'Front'];
        $adminSection = $sections['1'];
        $adminFolder = strtolower($adminSection);
        $apiSection = $sections['2'];
        $apiFolder = strtolower($apiSection);
        $frontSection = $sections['3'];
        $frontFolder = strtolower($frontSection);


        list($fields, $types) = $this->get_fields_in_all_language();


        //create migrations
        $migration_name = "create_{$table_name}_table";
        $command = "php artisan make:module:migration {$slug} {$migration_name} --create={$table_name}";
        $result = shell_exec($command);
        $migration_file_name = substr($result, (strpos('Created Migration: ', $result) + 19), (18 + strlen($migration_name))) . '.php';
        $migration_inputs = $this->prepare_migrations_inputs($fields, $types);
        $factory_inputs = $this->prepare_factories_inputs($fields, $types);
        $migration_template = file_get_contents(module_path($slug) . '/Database/Migrations/' . $migration_file_name);
        $migration_template = str_replace('$table->increments(\'id\');', $migration_inputs, $migration_template);
        file_put_contents(module_path($slug) . '/Database/Migrations/' . $migration_file_name, $migration_template);


        //Create Controllers Folders
        $controller_path = module_path($slug) . '/Http/Controllers/Admin/';
        if (!File::exists($controller_path)) {
            File::makeDirectory($controller_path, $mode = 0755, true, true);
        }

        $controller_path = module_path($slug) . '/Http/Controllers/Api/';
        if (!File::exists($controller_path)) {
            File::makeDirectory($controller_path, $mode = 0755, true, true);
        }

        $controller_path = module_path($slug) . '/Http/Controllers/Front/';
        if (!File::exists($controller_path)) {
            File::makeDirectory($controller_path, $mode = 0755, true, true);
        }

        //Create Model
        $models_path = module_path($slug) . '/Models/';
        $model_template = $this->create_model_template($crud->name, $name);
        if (!File::exists($models_path)) {
            File::makeDirectory($models_path, $mode = 0755, true, true);
        }
        file_put_contents(module_path($slug) . '/Models/' . $name . '.php', $model_template);

        //Create Repository Folder
        $repository_path = module_path($slug) . '/Repositories/';
        if (!File::exists($repository_path)) {
            File::makeDirectory($repository_path, $mode = 0755, true, true);
        }

        //Create Repository
        $model_template = $this->create_repository_template($crud->name, $name);
        $repository_name = $name . 'Repository';
        file_put_contents($repository_path . $repository_name . '.php', $model_template);


        //Create Request
        shell_exec("php artisan make:module:request {$slug} {$name}Request");
        $request_template = file_get_contents(module_path($slug) . '/Http/Requests/' . $name . 'Request.php');
        $request_template = str_replace('false', 'true', $request_template);
        file_put_contents(module_path($slug) . '/Http/Requests/' . $name . 'Request.php', $request_template);

        // Create Admin Controller
        $AdminControllerTemplate = $this->create_admin_controller_template($crud->name, $controller_name, $adminFolder,$slug);
        file_put_contents(module_path($slug) . '/Http/Controllers/' . $adminSection . '/' . $name . $adminSection . 'Controller.php', $AdminControllerTemplate);

        // Create Api Controller
        $ApiControllerTemplate = $this->create_controller_template($crud->name, $controller_name, $apiFolder);
        file_put_contents(module_path($slug) . '/Http/Controllers/' . $apiSection . '/' . $name . $apiSection . 'Controller.php', $ApiControllerTemplate);


        // Create Front Controller
        $FrontControllerTemplate = $this->create_controller_template($crud->name, $controller_name, $frontFolder);
        file_put_contents(module_path($slug) . '/Http/Controllers/' . $frontSection . '/' . $name . $frontSection . 'Controller.php', $FrontControllerTemplate);


        // Create Factory
        $FactoryTemplate = $this->create_factory_template($crud->name, $controller_name, $factory_inputs, $slug);
        file_put_contents(base_path('database/factories') . '/' . $name  . 'Factory.php', $FactoryTemplate);


        // Create Admin routes
        $AdminRoutesTemplate = $this->create_admin_route_template($adminFolder, $name, $slug);
        $this->create_route_directory($slug, $adminSection);
        file_put_contents(module_path($slug) . '/Routes/' . $adminSection . '/' . $name . $adminSection . 'Routes.php', $AdminRoutesTemplate);

        // Create Api routes
        $ApiRoutesTemplate = $this->create_api_route_template($name);
        $this->create_route_directory($slug, $apiSection);
        file_put_contents(module_path($slug) . '/Routes/' . $apiSection . '/' . $name . $apiSection . 'Routes.php', $ApiRoutesTemplate);


        // Create Front routes
        $FrontRoutesTemplate = $this->create_front_route_template($name);
        $this->create_route_directory($slug, $frontSection);
        file_put_contents(module_path($slug) . '/Routes/' . $frontSection . '/' . $name . $frontSection . 'Routes.php', $FrontRoutesTemplate);


        // Create Admin Views
        $views_folder_path = module_path($crud->slug) . '/Resources/Views/' . ucfirst($adminFolder);
        if (!File::exists($views_folder_path)) {
            File::makeDirectory($views_folder_path, $mode = 0755, true, true);
        }

        $AdminFormTemplate = $this->create_admin_form_template($slug, $controller_name);
        file_put_contents(module_path($slug) . '/Resources/Views/' . $adminSection . '/' . strtolower($name) . strtolower('_' . $adminSection . '_' . 'form.blade.php'), $AdminFormTemplate);


        $AdminListTemplate = $this->create_admin_list_template($controller_name, $slug);
        file_put_contents(module_path($slug) . '/Resources/Views/' . $adminSection . '/' . strtolower($name) . strtolower('_' . $adminSection . '_' . 'list.blade.php'), $AdminListTemplate);



        // Create Front Views
        $views_folder_path = module_path($crud->slug) . '/Resources/Views/' . ucfirst($frontFolder);
        if (!File::exists($views_folder_path)) {
            File::makeDirectory($views_folder_path, $mode = 0755, true, true);
        }


        return redirect(route('showModule', $slug));
    }

    private function get_fields_in_all_language()
    {
        $fields = request()->get('fields');
        $nullable = request()->get('nullable');
        $db_only = request()->get('db_only');
        $multi_language = request()->get('multi_language');
        $types = request()->get('types');
        $new_fields = array();
        $new_types = array();
        $new_db_only = array();
        $new_nullable = array();
        foreach ($fields as $key => $field) {

            if (@$multi_language[$key]) {
                $new_fields[] = $field . '_en';
                $new_fields[] = $field . '_ar';

                $new_types[] = $types[$key];
                $new_types[] = $types[$key];
                $new_db_only[] = $db_only[$key];
                $new_db_only[] = $db_only[$key];
                $new_nullable[] = $nullable[$key];
                $new_nullable[] = $nullable[$key];
            } else {
                $new_fields[] = $field;
                $new_types[] = $types[$key];
                $new_db_only[] = $db_only[$key];
                $new_nullable[] = $nullable[$key];
            }
        }
        return [$new_fields, $new_types, $new_db_only, $new_nullable];
    }

    private function prepare_migrations_inputs(array $fields, array $types)
    {
//        $null_able = request()->get('nullable');

        $migration = '$table->increments(\'id\');';

        foreach ($types as $key => $type) {

            $field_name = str_replace(' ', '', $fields[$key]);
            switch ($type) {
                case 1:
                case 2:
                case 4:
                    $migration .= '
            $table->string' . "('$field_name')";
                    break;

                case 3:
                    $migration .= '
            $table->integer' . "('$field_name')";
                    break;
                case 10:
                    $migration .= '
            $table->integer' . "('$field_name')";
                    break;
                case 5:
                    $migration .= '
            $table->text' . "('$field_name')";
                    break;
                case 6:
                    $migration .= '
            $table->integer' . "('$field_name')->unsigned()";
                    break;
                case 7:
                case 8:
                    $migration .= '
            $table->tinyInteger' . "('$field_name')";
                    break;
                case 9:
                    $migration .= '
            $table->date' . "('$field_name')";
                    break;
                default:
                    break;
            }
            // edit: by default every migration field will be nullable
            $migration .= '->nullable();';


//            if (@$null_able[$key]) {
//                $migration .= '->nullable();';
//            } else {
//                $migration .= ';';
//            }


        }
        $migration .= '
            $table->softDeletes();';
        return $migration;

    }

    private function prepare_factories_inputs(array $fields, array $types)
    {
//        $null_able = request()->get('nullable');

        $factory = '';

        foreach ($types as $key => $type) {

            $field_name = str_replace(' ', '', $fields[$key]);
            switch ($type) {
                case 1:
                    if (strpos($field_name, 'password') === true) {
                        $factory .= '
            ' . "'$field_name'  => " . " bcrypt('123456') , ";
                    }else {
                        $factory .= '
            ' . "'$field_name' => " . ' $faker->name ,';
                    }
                    break;
                case 2:
                    $factory .= '
            ' . "'$field_name' => " . ' $faker->unique()->safeEmail ,';
                    break;
                case 4:
                $factory .= '
            ' . "'$field_name' => " . ' $faker->imageUrl(640, 480) , ';
                    break;

                case 3:
                    if (strpos($field_name, '_id') !== false) {
                        $factory .= '
            ' . "'$field_name'  => " . ' $faker->numberBetween(0, 50), ';
                    }else{
                        $factory .= '
            ' . "'$field_name'  => " . ' $faker->randomNumber, ';
                    }
                    break;
                case 10:
                    $factory .= '
            ' . "'$field_name' => " .  ' $faker->randomFloat, ';
                    break;
                case 5:
                    $factory .= '
            ' . "'$field_name' => "  . ' $faker->text ,';
                    break;
                case 6:
                    $factory .= '
            ' . "'$field_name'  => " . ' $faker->numberBetween(0, 50) ,';
                    break;
                case 7:
                    $factory .= '
            ' . "'$field_name'  => " . ' $faker->numberBetween(0, 1), ';
                    break;
                case 8:
                    $factory .= '
            ' . "'$field_name'  => " . ' $faker->numberBetween(0, 1), ';
                    break;
                case 9:
                    $factory .= '
            ' . "'$field_name' => "  . ' $faker->dateTime ,';
                    break;
                default:
                    break;
            }


        }

        return $factory;

    }

    private function create_repository_template($main_module_name, $model_name)
    {
        $RepositoryTemplate = $this->get_template('Repository.txt');
        $studly_model = studly_case($model_name);
        $RepositoryTemplate = $this->replace_template($RepositoryTemplate, array(
            '{{ucf_module_name}}' => ucfirst($main_module_name),
            '{{ucf_model_name}}' => $studly_model,
        ));
        return $RepositoryTemplate;
    }

    private function create_admin_controller_template($main_module_name, $model_name, $adminFolder,$slug)
    {
        $AdminControllerTemplate = $this->get_template('AdminController.txt');
        $AdminControllerTemplate = $this->replace_template($AdminControllerTemplate, array(
            '{{module_slug}}' => $slug,
            '{{ucf_module_name}}' => ucfirst($main_module_name),
            '{{lcf_module_name}}' => strtolower($main_module_name),
            '{{lcf_model_name}}' => strtolower($model_name),
            '{{ucf_model_name}}' => studly_case($model_name),
            '{{ucf_folder_name}}' => ucfirst($adminFolder),
            '{{lcf_folder_name}}' => strtolower($adminFolder),
            '{{plural_model_name}}' => str_plural(strtolower($model_name)),
            '{{ucf_plural_model_name}}' => ucfirst(str_plural(strtolower($model_name))),
            '{{list_route_name}}' => 'list' . $model_name
        ));
        return $AdminControllerTemplate;
    }

    private function create_factory_template($main_module_name, $model_name, $factory_inputs,$slug)
    {
        $DatabaseSeederFilePath = base_path('database/seeds/DatabaseSeeder.php');
        $DatabaseSeederFile = file_get_contents($DatabaseSeederFilePath);
        $DatabaseSeederNewContent = '
        /*$factory*/
        factory(\App\Modules'.'"\"'.$main_module_name.'\Models'.'"\"'.$model_name.'::class, 50)->create();
        ';
        $DatabaseSeederNewContent = str_replace('"', '', $DatabaseSeederNewContent);
        $DatabaseSeederFinalFile = str_replace('/*$factory*/', $DatabaseSeederNewContent, $DatabaseSeederFile);
        file_put_contents($DatabaseSeederFilePath, $DatabaseSeederFinalFile);

        $FactoryTemplate = $this->get_template('Factory.txt');
        $FactoryTemplate = $this->replace_template($FactoryTemplate, array(
            '{{module_slug}}' => $slug,
            '{{ucf_module_name}}' => ucfirst($main_module_name),
            '{{ucf_model_name}}' => studly_case($model_name),
            '{{ucf_factory_fakers}}' => $factory_inputs,
        ));
        return $FactoryTemplate;
    }

    private function create_admin_route_template($adminFolder, $controller_name, $lcf_slug)
    {
        $AdminRoutesTemplate = $this->get_template('AdminRoutes.txt');
        $AdminRoutesTemplate = $this->replace_template($AdminRoutesTemplate, array(
            '{{ucf_folder}}' => ucfirst($adminFolder),
            '{{lcf_folder}}' => $adminFolder,
            '{{lcf_slug}}' => $lcf_slug,
            '{{lcf_controller_name}}' => strtolower($controller_name),
            '{{slug_controller_name}}' => $this->from_camel_case($controller_name),
            '{{ucf_controller_name}}' => $controller_name
        ));
        return $AdminRoutesTemplate;

    }

    private function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    private function create_route_directory($slug, $folder)
    {
        $path = module_path($slug) . '/Routes/' . $folder . '/';

        if (!File::exists($path)) {
            $folder_path = module_path($slug) . '/Routes/' . $folder;
            File::makeDirectory($folder_path, $mode = 0755, true, true);
            if (strtolower($folder) == 'admin' || strtolower($folder) == 'front') {
                $web_file_path = module_path($slug) . '/Routes/web.php';
                $web_content = file_get_contents($web_file_path);

                $web_content .= '
            
foreach (File::allFiles(__DIR__ . \'/' . $folder . '\') as $route) {
require_once $route->getPathname();
}';
                file_put_contents($web_file_path, $web_content);


            } elseif (strtolower($folder) == 'api') {
                $api_file_path = module_path($slug) . '/Routes/api.php';
                $api_content = file_get_contents($api_file_path);

                $api_content .= '
            
foreach (File::allFiles(__DIR__ . \'/' . $folder . '\') as $route) {
require_once $route->getPathname();
}';
                file_put_contents($api_file_path, $api_content);
            }

        }
    }

    private function create_api_route_template($controller_name)
    {
        $ApiRoutesTemplate = $this->get_template('ApiRoutes.txt');
        $ApiRoutesTemplate = $this->replace_template($ApiRoutesTemplate, array(
            '{{lcf_controller_name}}' => strtolower($controller_name),
        ));
        return $ApiRoutesTemplate;

    }

    private function create_front_route_template($controller_name)
    {
        $FrontRoutesTemplate = $this->get_template('FrontRoutes.txt');
        $FrontRoutesTemplate = $this->replace_template($FrontRoutesTemplate, array(
            '{{lcf_controller_name}}' => strtolower($controller_name),
        ));
        return $FrontRoutesTemplate;

    }


//    private function create_lang_template($slug, $controller_name)
//    {
//        $fields_lang = '';
//        $uc_field = ucfirst($controller_name);
//        $lc_field = strtolower($controller_name);
//        $fields_lang .= "'$lc_field'=>'$uc_field',
//        ";
//        $model_plural = strtolower(str_plural($controller_name));
//        $uc_field = ucfirst($model_plural);
//        $fields_lang .= "'$model_plural'=>'$uc_field',
//        ";
//
//        foreach ($fields as $field) {
//            $uc_field = ucfirst($field);
//            $fields_lang .= "'$field'=>'$uc_field',
//            ";
//        }
////        $AdminRoutesTemplate = $this->get_template('lang.txt');
////        $AdminRoutesTemplate = $this->replace_template($AdminRoutesTemplate, array(
////            '{{FormData}}' => $fields_lang
////        ,
////        ));
//        return $AdminRoutesTemplate;
//
//    }

    private function create_admin_form_template($slug, $controller_name)
    {
        list($fields, $types, $db_only, $nullable) = $this->get_fields_in_all_language();
        $AdminRoutesTemplate = $this->get_template('AdminForm.txt');
        $AdminRoutesTemplate = $this->replace_template($AdminRoutesTemplate, array(
            '{{lc_module}}' => strtolower($slug),
            '{{lcf_module_name}}' => strtolower($slug),
            '{{lcf_model_name}}' => strtolower($controller_name),
            '{{plural_model_name}}' => strtolower(str_plural($controller_name)),
            '{{ucf_plural_model_name}}' => ucfirst(strtolower(str_plural($controller_name))),
            '{{lc_model}}' => strtolower($controller_name),
            '{{uc_model}}' => ucfirst($controller_name),
            '{{uc_model_modular}}' => ucfirst(str_plural($controller_name)),
            '{{FormData}}' => $this->prepare_form_inputs($fields, $types, $controller_name, $slug, $db_only, $nullable)
        ,
        ));
        return $AdminRoutesTemplate;

    }

    private function prepare_form_inputs(array $fields, array $types, $model_name, $slug, array $db_only, array $nullable)
    {

        $form = '';
        $migration = '';
        foreach ($types as $key => $type) {

            $field_name = strtolower($fields[$key]);
            if ($db_only[$key]) {
                $fieldTemplate = $this->get_template('fields/db-field.txt');
            } else {
                switch ($type) {
                    case 1:
                        $fieldTemplate = $this->get_template('fields/text-field.txt');
                        break;
                    case 2:
                        $fieldTemplate = $this->get_template('fields/email.txt');
                        break;
                    case 3:
                        $fieldTemplate = $this->get_template('fields/number.txt');
                        break;
                    case 4:
                        $fieldTemplate = $this->get_template('fields/file.txt');
                        break;
                    case 5:
                        $fieldTemplate = $this->get_template('fields/text-area.txt');
                        break;
                    case 6:
                        $fieldTemplate = $this->get_template('fields/select.txt');
                        break;
                    case 7:
                        $fieldTemplate = $this->get_template('fields/radio.txt');
                        break;
                    case 8:
                        $fieldTemplate = $this->get_template('fields/checkbox.txt');
                        break;
                    case 9:
                        $fieldTemplate = $this->get_template('fields/date.txt');
                        break;
                    default:
                        break;
                }
            }


            $label = ucfirst($field_name);
            $label = str_replace('_', ' ', $label);

            $required = '';
            foreach ($this->required_langs as $lang) {
                if (strpos(strtolower($field_name), "_" . $lang) != false)
                    $required = 'required';
            }
            $fieldTemplate = $this->replace_template($fieldTemplate, array(
                '{{ucf_field}}' => ucwords($label),
                '{{lcf_module_name}}' => strtolower($slug),
                '{{lcf_controller_name}}' => strtolower($model_name),
                '{{lcf_field}}' => strtolower($field_name),
                '{{lcf_field_plural}}' => str_plural(str_replace('_id', '', $field_name)),
                '{{lcf_field_singular}}' => strtolower(str_replace('_id', '', $field_name)),
                '{{lcf_model}}' => strtolower($model_name),
                '{{required}}' => $nullable[$key] == 0 ? $required : ''
            ));
            $form .= $fieldTemplate . '
            ';

        }
        return $form;

    }

    private function create_admin_list_template($controller_name, $slug)
    {
        $fields = request()->get('fields');
        $list = request()->get('show_in_list');

        $AdminRoutesTemplate = $this->get_template('AdminList.txt');
        $listcontent = "<td> {{ $" . $controller_name . "->id }}</td>";
        $listhead = '<th>id</th>';

        foreach ($fields as $key => $field) {
            if ($list[$key] == "0")
                continue;
            $listcontent .= "<td> {{ $$controller_name->$field }}</td>";
            $listhead .= "<th>$field</th>";
        }
        $AdminRoutesTemplate = $this->replace_template($AdminRoutesTemplate, array(
            '{{listhead}}' => $listhead,
            '{{listattributes}}' => strtolower($listcontent),
            '{{ucf_controller_name}}' => ucfirst($controller_name),
            '{{lcf_controller_name}}' => strtolower($controller_name),
            '{{lcf_plural_controller_name}}' => strtolower(str_plural($controller_name))
        ));

        return $AdminRoutesTemplate;


    }

    public function runMigration(Request $request, $slug)
    {
        $command = "php artisan module:migrate {$slug}";
        $result = shell_exec($command);
        //sweet_alert()->success('Done', 'Migration run successfully');
        return redirect(route('showModule', $slug));
    }

    public function resetMigration(Request $request, $slug)
    {
        $command = "php artisan module:migrate:reset {$slug}";
        shell_exec($command);
        //sweet_alert()->success('Done', 'Migration reset successfully');
        return redirect(route('showModule', $slug));
    }

    public function refreshMigration(Request $request, $slug)
    {
        $command = "php artisan module:migrate:refresh {$slug}";
        shell_exec($command);
        //sweet_alert()->success('Done', 'Migration refresh successfully');
        return redirect(route('showModule', $slug));
    }

    public function rollbackMigration(Request $request, $slug)
    {
        $command = "php artisan module:migrate:rollback {$slug}";
        shell_exec($command);
        //sweet_alert()->success('Done', 'Migration rollback successfully');
        return redirect(route('showModule', $slug));
    }
}
