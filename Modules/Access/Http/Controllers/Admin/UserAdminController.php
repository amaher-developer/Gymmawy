<?php

namespace Modules\Access\Http\Controllers\Admin;

use Modules\Access\Http\Requests\UserRequest;
use Modules\Access\Models\Role;
use Modules\Access\Models\User;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserAdminController extends GenericAdminController
{
    private $selected = [];

    public function __construct()
    {
        parent::__construct();
        $this->request_array = [
            'user_id', 'name', 'phone', 'email', 'limit',
        ];
    }

    public function index(Request $request)
    {
        foreach ($this->request_array as $item) $$item = @$request->has($item) ? @$request->$item : '';
        $users = User::Users()
            ->when($phone, function ($query) use ($phone) {
                $query->where('phone', 'like', '%' . $phone . '%');
            })
            ->when($email, function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            })
            ->when($name, function ($query) use ($name) {
                $names = explode(' ', $name);
                foreach ($names as $name) {
                    $query->where('name', 'like', '%' . $name . '%');
                }
            })
            ->orderBy('id', 'DESC');


        if ($request->ajax() && $request->exists('export')) {
            $_users = $users->get();
            $array = $this->prepareForExport($_users);
            $fileName = 'customers-' . Carbon::now()->toDateTimeString();
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
            $this->selected['limit'] = $this->limit;
            $paginated = true;
            $users = $users->paginate($this->limit)->appends(compact($this->request_array));
            $users_count = $users->total();
        } else {
            $paginated = false;
            $users = $users->get();
            $users_count = $users->count();
        }


        $title = 'Customers List';

        return view('access::Admin.user_admin_list', [
            'users' => $users,
            'paginated' => $paginated,
            'title' => $title,
            'users_count' => $users_count,
            'selected' => $this->selected,
            'request' => $request->all(),

        ]);
    }

    private function prepareForExport($users)
    {
        return array_map(function ($user) {
            return [
                'ID' => $user['id'],
                'Name' => $user['name'],
                'Mobile' => $user['phone'],
            ];
        }, $users->toArray());
    }

    public function create()
    {
        $title = 'Create Customer';

        return view('access::Admin.user_admin_form', ['user' => new User(), 'roles' => Role::get(), 'title' => $title]);
    }

    public function store(UserRequest $request)
    {
        $user_inputs = $this->prepare_inputs($request->except(['_token', 'roles']));
        $user = User::create($user_inputs);
        $user->attachRole(Role::find(request()->input('roles')));
        sweet_alert()->success('Done', 'Customer Added successfully');
        return redirect(route('listUser'));
    }

    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(User::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        } else {
            unset($inputs[$input_file]);
        }

        $input_file = 'mobile_image';
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);
            $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = base_path(User::$uploads_path);
            $upload_success = $file->move($destinationPath, $filename);
            if ($upload_success) {
                $inputs[$input_file] = $filename;
            }
        } else {
            unset($inputs[$input_file]);
        }
        return $inputs;
    }

    public function edit(User $user)
    {
        $title = 'Edit Customer';
        return view('access::Admin.user_admin_form', ['user' => $user->load('roles'), 'roles' => Role::get(), 'title' => $title]);
    }

    public function update(UserRequest $request, User $user)
    {

        if (request('password'))
            $user_inputs = $this->prepare_inputs($request->except(['_token', 'roles']));
        else
            $user_inputs = $this->prepare_inputs($request->except(['_token', 'roles', 'password']));

        $user_inputs['block'] = (int)$request->block;

        $user->update($user_inputs);

        if (request()->input('roles')) {
            /** @var  $roles Role */
            $user->detachRoles($user->roles);
            $user->roles()->attach(array(request()->input('roles')));
        }
        if (request()->input('roles') == 0) {
            DB::table('role_user')->where('user_id', request('id'))->delete();
        }
        sweet_alert()->success('Done', 'Customer updated successfully');
        return redirect(route('listUser'));
    }

    public function destroy(User $user)
    {
//        $user->delete();
        sweet_alert()->success('Done', 'Customer deleted successfully');
        return redirect(route('listUser'));
    }


}
