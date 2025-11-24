<table class="table table-striped table-bordered table-hover" id="sample_3">
    <thead>
    <tr class="">
        <th>Display Name</th>
        <th> Slug</th>
        <th> description</th>
        <th>show</th>
        <th>Export</th>
        <th>Import</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($modules as $module)

        <tr>
            <td>@if($module['migrate'])<i class="icon-settings red" style="color: red;font-size: 14px "></i>@endif <span>{{$module['name']}}</span></td>
            <td> {{$module['slug']}}</td>
            <td> {{$module['description']}}</td>
            <td>
                <a href="{{route('showModule', $module['slug'])}}" class="btn btn-sm blue"> Show </a>
            </td>
            <td>
                <a href="{{ route('create-zip',['download'=>'zip', 'module'=> $module['slug']]) }}" class="btn btn-sm  btn-info" > Export </a>
            </td>
            <td>
                @if(\Illuminate\Support\Facades\File::exists(base_path('exports/'.ucfirst($module['slug']).'.zip')))
                <a href="{{ route('import-zip',['download'=>'zip', 'module'=> $module['slug']]) }}" class="btn btn-sm  red" > Import </a>
                @endif
            </td>
            <td>
                {{--<input type="checkbox"--}}
                {{--name="status"--}}
                {{--value="1"--}}
                {{--{{($module->status) ? 'checked' : ''}}--}}
                {{--class="make-switch module-status"--}}
                {{--id="{{$module->id}}"--}}
                {{--route="{{route('reverseStatus',$module->id)}}"--}}
                {{--data-size="small"--}}
                {{--data-on-text="&nbsp;Enabled&nbsp;&nbsp;"--}}
                {{--data-off-text="&nbsp;Disabled&nbsp;">--}}
                {{($module['enabled']) ? 'Enabled' : 'Disabled'}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
