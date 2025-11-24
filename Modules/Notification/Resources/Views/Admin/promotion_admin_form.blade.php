@extends('generic::layouts.form')
@section('breadcrumb')

@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="{{route('sendPromotionLetter')}}" class="form-horizontal" role="form"
          enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}
            <input type="hidden" value="{{$district_id}}" name="district_id">
            <div class="form-group">
                <label class="col-md-3 control-label">Promotion Letter</label>
                <div class="col-md-9">
                    <textarea id="promotion_letter" name="promotion_letter" class="form-control" required></textarea>
                </div>
            </div>

            <div class="form-group col-md-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr class="">
                        <th>Main Image</th>
                        <th>city/district</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Price</th>
                        <th>selected</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key=>$item)
                        <tr>
                            <td>
                                {{--<img width="100" src="{{$item->main_image}}"/>--}}
                            </td>
                            <td>{{$item->district->city->name.", ".$item->district->name}}</td>
                            <td>{{$item->item_type->parent->name.", ".$item->item_type->name}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{number_format($item->price)}} {{trans('global.le')}}</td>
                            <td class="selection-td"><input type="checkbox" name="chosen_item[]" value="{{$item->id}}">
                                <span style="color: #0ed20e;font-weight: bold;" class="hidden">selected</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-4 col-md-9">
                        <button class="btn yellow selection-done">Selection Done</button>
                        <button type="submit" class="btn green sorting-done hidden">Sorting Done</button>
                        {{--<input type="reset" class="btn default" value="Reset">--}}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section("sub_scripts")
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var fixHelperModified = function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width())
                });
                return $helper;
            },
            updateIndex = function (e, ui) {
                $('td.index', ui.item.parent()).each(function (i) {
                    $(this).html(i + 1);
                });
            };


        $(".selection-done").click(function (e) {
            e.preventDefault();
            $('table .selection-td input').each(function () {
                if (!$(this).is(":checked")) {
                    $(this).parent().closest('tr').remove();
                }
            });
            $(".selection-done").addClass('hidden');
            $(".sorting-done").removeClass('hidden');
            $('table .selection-td input').addClass('hidden');
            $('table .selection-td span').removeClass('hidden');
            $('table tbody tr').css("cursor", "pointer");

            $("table tbody").sortable({
                helper: fixHelperModified,
                stop: updateIndex
            }).disableSelection();
        });
    </script>
@stop