@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listMember') }}">Members</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
     <div class="form-body">
        {{csrf_field()}}
    
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Name</label>
    <div class="col-md-9">
        <input id="name" value="{{ old('name', $member->name) }}"
               name="name" type="text" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Phone</label>
    <div class="col-md-9">
        <input id="phone" value="{{ old('phone', $member->phone) }}"
               name="phone" type="text" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Address</label>
    <div class="col-md-9">
        <input id="address" value="{{ old('address', $member->address) }}"
               name="address" type="text" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Email</label>
    <div class="col-md-9">
        <input id="email" value="{{ old('email', $member->email) }}"
               name="email" type="email" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Age</label>
    <div class="col-md-9">
        <input id="age" value="{{ old('age', $member->age) }}" step="any"
               name="age" type="number" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Age</label>
    <div class="col-md-9">
        <input id="age" value="{{ old('age', $member->age) }}" step="any"
               name="age" type="number" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Age</label>
    <div class="col-md-9">
        <input id="age" value="{{ old('age', $member->age) }}" step="any"
               name="age" type="number" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Image</label>
    <div class="col-md-9">
        <input id="image" value="{{ old('image', $member->image) }}"
               name="image" type="text" class="form-control" >
    </div>
</div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Gender</label>

    <div class="col-md-9">
        <div class="mt-radio-list">
            <label class="mt-radio mt-radio-outline">
                <input id="gender" value="0"
                       {{ old('gender', $member->gender)==0?'checked':'' }}
                       name="gender" type="radio" >Option 1
                <span></span>
            </label>
            <label class="mt-radio mt-radio-outline">
                <input id="gender" value="1"
                       {{ old('gender', $member->gender)==1?'checked':'' }}
                       name="gender" type="radio" >Option 2
                <span></span>
            </label>
        </div>
    </div>
</div>

            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Birthday</label>
    <div class="col-md-9">
        <input id="birthday" value="{{ old('birthday', $member->birthday) }}"
               name="birthday" type="text" class="form-control" >
    </div>
</div>
            

    <div class="form-group col-md-6" style="clear:both;">
        <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
        <div class="col-md-9">
            <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="hidden" name="deleted_at" value="">
                    <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                            {{ $member->trashed()?'checked':'' }}>
                    <span></span>
                </label>
            </div>
        </div>

    </div>

    <div class="form-actions" style="clear:both;">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn green">{{trans('admin.submit')}}</button>
                <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
            </div>
        </div>
    </div>
    </div>
    </form>
@endsection
