<div class="form-body">
    {{csrf_field()}}
    <div class="tabbable-line">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#role_data_tab" data-toggle="tab"> Role Data </a>
            </li>
            <li>
                <a href="#permissions_tab" data-toggle="tab">Permissions</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="role_data_tab">
                <div class="form-group">
                    <label class="col-md-3 control-label">Role Name</label>
                    <div class="col-md-9">
                        <input id="display_name" value="{{ old('display_name', $role->display_name) }}"
                               name="display_name" type="text" class="form-control"
                               placeholder="Role Name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">slug</label>
                    <div class="col-md-9">
                        <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}"
                               class="form-control" placeholder="" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control"
                                  rows="3">{{ old('description', $role->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="permissions_tab">
                @foreach($groups as $group)
                    <h2>{{ucfirst($group->name)}}</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Allow/Deny</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($group->permissions as $permission)
                            <tr>
                                <td>{{$permission->display_name}}</td>
                                <td>{{$permission->name}}</td>
                                <td>{{$permission->description}}</td>
                                <td>
                                    <input type="checkbox"
                                           name="permissions[{{ $permission->id }}]"
                                           value="1"
                                           @if(array_key_exists($permission->id, old('permissions', []))) checked
                                           @else
                                           {{(is_object($role->perms()->find($permission->id))) ? 'checked' : ''}}
                                           @endif
                                           class="make-switch"
                                           data-size="small"
                                           data-on-text="&nbsp;Allow&nbsp;&nbsp;"
                                           data-off-text="&nbsp;Deny&nbsp;">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green">{{$submit_button}}</button>
            <input type="reset" class="btn default" value="Reset">
        </div>
    </div>
</div>