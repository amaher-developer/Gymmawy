<?php

namespace Modules\Access\Http\Controllers\Admin;

use Modules\Access\Models\Role;
use Modules\Access\Models\RoleUser;
use Modules\Access\Models\User;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

class AdminAdminController extends GenericAdminController
{
    public function index()
    {
        return view('access::Admin.admin_admin_list', [
            'users' => User::admins()->orderBy('id','DESC')->get(),
            'title'  => 'list users'
        ]);
    }


    public function create()
    {

        return view('access::Admin.admin_admin_form', [
            'user' => new User(),
            'roles' => Role::all(),
            'title' => 'Add User'
        ]);

    }

    public function store()
    {
//        dd($roles = (array)request()->get('roles'));
        request()->validate( [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|unique:users',
            'password' => 'required',
        ]);

        $user_inputs = request()->except('_token', 'password_confirmation', 'roles');
        $user = User::create($user_inputs);

        $role_inputs['user_id'] = $user->id;
        $role_inputs['role_id'] = request('role_id');
        $roles = (array)request()->get('roles');
        $user->roles()->sync(array_values($roles));

        sweet_alert()->success('Done', 'Admin Added Successfully');

        return redirect()->route('listAdmins');
    }

    public function edit(User $admin)
    {
        return view('access::Admin.admin_admin_form', [
            'user' => $admin->load('roles'),
            'roles' => Role::all(),
            'title' => 'Update User Data'
        ]);

    }

    public function update(User $admin)
    {
        request()->validate( [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'phone'    => 'required|unique:users,phone,' . $admin->id,
//            'password' => 'required',
//            'role_id'  => 'required',
        ]);

        $user_inputs = request()->except('_token', 'password_confirmation', 'roles');
        if (!request('password')) {
            unset($user_inputs['password']);
        }
        $admin->update($user_inputs);
        $admin->roles()->sync((array)request('roles'));

        sweet_alert()->success('Done', 'Admin Data Updated Successfully');

        return redirect()->route('listAdmins');
    }

    public function destroy()
    {

    }

    public function toggleBlock(User $admin)
    {
        $admin->toggleBlock()->save();

        return redirect()->back();
    }


}