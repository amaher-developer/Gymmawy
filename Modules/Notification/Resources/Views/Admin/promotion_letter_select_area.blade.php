@extends('generic::layouts.form')
@section('breadcrumb')

@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="{{route('createPromotionLetter')}}" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div class="form-group">
                <label class="col-md-3 control-label">City</label>
                <div class="col-md-9">
                    <select id="city" class="form-control">
                        <option value="">Select</option>
                        @forelse($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">District</label>
                <div class="col-md-9">
                    <select required id="district_id" name="district_id" class="form-control">
                        <option value="">Select</option>
                        @forelse($districts as $district)
                            <option class="districts_of_city_{{$district->city_id}}" value="{{ $district->id }}">
                                {{ $district->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <input type="reset" class="btn default" value="Reset">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('sub_scripts')
    <script>

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
