<table class="table table-striped table-bordered table-hover" id="sample_3">
    <thead>
    <tr class="">
        <th> Name</th>
        <th> Slug</th>
        <th> Description</th>
        <th> Edit</th>
        <th> Remove</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roles as $role)
        <tr>
            <td> {{$role->display_name}}</td>
            <td> {{$role->name}}</td>
            <td> {{$role->description}}</td>
            <td>
                <a href="{{route('editRole',$role->id)}}" class="btn btn-sm yellow"> Edit
                    <i class="fa fa-edit"></i>
                </a>
            </td>
            <td>
                <a href="{{route('deleteRole',$role->id)}}" class="btn btn-sm red"> Delete
                    <i class="fa fa-times"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>