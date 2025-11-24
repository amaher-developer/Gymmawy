@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
        <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
        <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">{{ $title }}</a>
        </li>
        </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createGym')}}" class="btn btn-lg btn-success">{{trans('admin.add')}} Gym</a>
     @if(request('trashed'))
            <a href="{{route('listGym')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
        @else
            <a href="{{route('listGym')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
        @endif
            <a href="" url="{{request()->fullUrlWithQuery(['export'=>1])}}" id="export" class="btn red btn-outline"><i class="icon-paper-clip"></i> {{trans('admin.export')}}</a>
@endsection
@section('page_body')
    <div class="row">

        <button class="btn btn-info filter_trigger_button" style="margin-bottom: 10px">{{trans('admin.show_hide_filters')}}</button>

        <form action="" id="filter_form">
            <table class="table table-striped table-bordered table-hover ">
                <tbody>
                <tr>
                    <th>{{trans('admin.id')}}</th>
                    <td><input id="id" value="{{ request('id')}}" name="id" class="form-control"
                               type="number" placeholder="{{trans('admin.id')}}"/></td>
                </tr>
                <tr>
                    <th>{{trans('admin.city')}}</th>
                    <td>
                        <select name="city_id" id="city" class="form-control" >
                            <option value="">{{trans('admin.choose_city')}}</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}"
                                        @if($city->id == @request('city_id')) selected @endif>{{$city->name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{{trans('admin.district')}}</th>
                    <td><select name="district_id" id="district_id" class="form-control" >
                            <option value="">{{trans('admin.choose_district')}}</option>
                            @foreach($districts as $district)
                                <option value="{{$district->id}}" class="districts_of_city_{{$district->city_id}}"
                                        @if($district->id == @request('district_id')) selected @endif>{{$district->name}}</option>
                            @endforeach
                        </select></td>
                </tr>
                <tr>
                    <th>{{trans('admin.enabled')}}</th>
                    <td><select name="published" id="published" class="form-control" >
                            <option value="">{{trans('admin.choose')}}</option>
                                <option value="0" class=""
                                        @if(isset($_GET['published']) && (request('published') === '0')) selected @endif>{{trans('admin.disabled')}}</option>
                                <option value="1"
                                        @if(request('published') && (request('published') == 1)) selected @endif>{{trans('admin.enabled')}}</option>
                        </select></td>
                </tr>

                <tr>
                    <th>{{trans('admin.order_by')}}</th>
                    <td><select name="order_by" id="order_by" class="form-control" >
                            <option value="">{{trans('admin.choose')}}</option>
                            <option value="date" class=""
                                    @if(isset($_GET['order_by']) && (request('order_by') == 'date')) selected @endif>{{trans('admin.date')}}</option>
                            <option value="views"
                                    @if(request('order_by') && (request('order_by') == 'views')) selected @endif>{{trans('admin.views')}}</option>
                        </select></td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-offset-9 col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn green form-control">{{trans('admin.apply')}}</button>
                    </div>
                </div>
            </div>


        </form>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <th>{{trans('admin.total_count')}}</th>
                        <td>{{ $total }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-responsive border-top userlist-table ">
            <table class="table card-table table-striped table-vcenter text-nowrap mb-0 " >
        <thead>
        <tr class="">
            <th>#</th>
            <th>id</th>
            <th>name</th>
            <th>name en</th>
            <th>image</th>
            <th>{{trans('admin.view')}}</th>
            <th>published</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($gyms as $key=> $gym)
            <tr>
                <td> {{ $key+1 }}</td>
                <td> {{ $gym->id }}</td>
                <td> {{ $gym->name }}
                <br/><span style="font-size: 10px;">{{$gym->address}}</span>
                <br/><span style="font-size: 10px;">{{$gym->district->name}}, {{$gym->district->city->name}}</span>
                </td>
                <td>
                    {{@$gym->gym_brand->name_en}}
                </td>
                <td> <img src="{{ $gym->image }}" style="height: 60px;object-fit: cover;"></td>
                <td> {{ $gym->views }}</td>
                <td> {!! $gym->published == '1' ? '<span style="color:green"><i class="fa fa-check"></i></span>' : '<span style="color:red"><i class="fa fa-times"></i></span>' !!} </td>
                <td>

                    <a href="{{str_replace('.com', '.com/ar', route('gym', [$gym->id, $gym->slug]))}}" target="_blank" class="btn btn-xs yellow">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="{{route('editGym',$gym->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteGym',$gym->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteGym',$gym->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
        </div>
     <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $gyms->appends($search_query)->render()  !!}
            </div>
        </div>
@endsection

@section('scripts')
    @parent

    <script>

        $(document).on('click', '#export', function (event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('url'),
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                },
                error: function (request, error) {
                    swal("Operation failed", "Something went wrong.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });

        });

        $("#filter_form").slideUp();
        $(".filter_trigger_button").click(function () {
            $("#filter_form").slideToggle(300);
        });

        $(document).on('click', '.remove_filter', function (event) {
            event.preventDefault();
            var filter = $(this).attr('id');
            $("#" + filter).val('');
            $("#filter_form").submit();
        });



        var city = $("#city").val();
        var district_id = $("#district_id").val();
        $("#district_id option").hide();
        $("#district_id option:first").show();
        $("#district_id option.districts_of_city_" + city).show();

        $("#city").change(function (e) {
            var city = $("#city").val();
            $("#district_id option").hide();
            $("#district_id option:selected").removeAttr('selected');
            $("#district_id option.districts_of_city_" + city).show();
            $("#district_id option:first").show();
            $("#district_id option:first").attr('selected', true);
        });



    </script>

@endsection
