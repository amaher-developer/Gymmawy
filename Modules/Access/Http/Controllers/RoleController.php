<?php

namespace Modules\Access\Http\Controllers;

use Modules\Access\Http\Requests\RoleRequest;
use Modules\Access\Models\Group;
use Modules\Access\Models\Role;
use App\Http\Controllers\Controller;
use Modules\Generic\Http\Controllers\Admin\GenericAdminController;

class RoleController extends GenericAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $title='Roles List';
        return view('access::role.list', ['roles' => $roles,'title'=>$title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::with('permissions')->get();
        $title='Create Role';
        return view('access::role.add', ['groups' => $groups, 'role' => new Role(),'title'=>$title]);
    }

    /**
     * @param RoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $permissions = $request->input('permissions');
        $role = $request->only(['name', 'display_name', 'description']);
        $role = Role::create($role);
        if ($role) {
            $permissions = is_array($permissions) ? $permissions : [];
            $role->attachPermissions(array_keys($permissions));
            sweet_alert()->success('Done', 'Role Permissions added successfully');
            return redirect(route('listRoles'));
        } else {
            sweet_alert()->info('Something went wrong', 'please re-enter fields and submit again');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $groups = Group::with('permissions')->get();
        $title='Edit Role';
        return view('access::role.edit', [
            'role' => $role,
            'groups' => $groups,
            'title'=>$title
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Modules\Access\Http\Requests\RoleRequest $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $role)
    {
        $role = Role::find($role);
        $role_input = $request->only(['name', 'display_name', 'description']);
        $role->update($role_input);

        $permissions = $request->input('permissions');
        $role->perms()->sync(array_keys($permissions));
        sweet_alert()->success('Updated', 'Role Permissions updated successfully');
        return redirect(route('listRoles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $deleted = ($role->users()->count()) ? FALSE : $role->delete();
        if ($deleted) {
            sweet_alert()->success('Role Deleted', 'Role and all its permissions has been deleted successfully');
            return redirect(route('listRoles'));
        }
        sweet_alert()->overlay('Error', "role could not deleted because it's assigned to one or more admin",'error');
        return redirect(route('listRoles'));

    }
}
