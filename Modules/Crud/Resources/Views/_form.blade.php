<div class="form-body">
    {{csrf_field()}}
    @php $__name = 'module' @endphp
    <div class="form-group">
        <label class="col-md-3 control-label">{{$__name}} Name</label>
        <div class="col-md-9">
            <input value="{{ old('name', $$__name->name) }}"
                   name="name" type="text" class="form-control">
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
