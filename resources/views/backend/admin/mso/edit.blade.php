@extends('backend.layouts.adminmaster')
@section('title', 'Update Mso')
@push('css')
    <link href="{{ asset('backend/assets/select2/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-selection__choice {
            background-color: var(--bs-gray-200);
            border: none !important;
            font-size: 12px;
            font-size: 0.85rem !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header pb-4">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Mso Update</p>
                            <a href="{{route(Request::segment(1).'.mso.index')}}" class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        @include('partial.formerror')
                      
                        {!! Form::model($admin, [
                            'route' => [Request::segment(1) . '.mso.update', $admin->id],
                            'method' => 'PATCH',
                            'files' => true,
                        ]) !!}
                       
<div class="row">

    <div class="form-group col-md-6">
        <label for="name" class="form-control-label">Full Name (Owner) * </label>
        {!! Form::text('name', null, ['id' => 'name','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('name')) <span class="text-danger alert">{{ $errors->first('name') }}</span> @endif
    </div>
  <div class="form-group col-md-6">
        <label for="phone" class="form-control-label">phone * </label>
        {!! Form::tel('phone',null, ['id' => 'phone','class' => 'form-control','required'
        ]) !!}
        @if ($errors->has('phone')) <span class="text-danger alert">{{ $errors->first('phone') }}</span> @endif
    </div>
    <div class="input-group">
        <span class="input-group-btn">
            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Image (Profile)  [300*300]
            </a>
        </span>
        {!! Form::text('image', null, ['id' => 'thumbnail', 'class' => 'form-control', 'readonly','required','style'=>'height:40px']) !!}
        @if ($errors->has('image'))
        <span class="text-danger alert">{{ $errors->first('image') }}</span>
        @endif
        <img id="holder" style="margin-top:15px;max-height:100px;">
    </div>
    
    <div class="form-group col-md-6">
        <label for="email" class="form-control-label">Email * </label>
        {!! Form::email('email', null, ['id' => 'email','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('email')) <span class="text-danger alert">{{ $errors->first('email') }}</span> @endif
    </div>
    @if (Request::segment(3)=='create')
    <div class="form-group col-md-6">
        <label for="password" class="form-control-label">Password </label>
        {!! Form::password('password', ['id' => 'password','class' => 'form-control'
        ]) !!}
        @if ($errors->has('password')) <span class="text-danger alert">{{ $errors->first('password') }}</span> @endif
    </div>
@else
    <div class="form-group col-md-6">
        <label for="password" class="form-control-label">Password </label>
        {!! Form::password('password', ['id' => 'password','class' => 'form-control'
        ]) !!}
        @if ($errors->has('password')) <span class="text-danger alert">{{ $errors->first('password') }}</span> @endif
    </div>
@endif
    
    <div class="form-group col-md-6">
        <label for="gender" class="form-control-label">Gender * </label>
        {!! Form::select('gender',['Male'=>'Male','Female'=>'Female','Other'=>'Other'], null, ['id' => 'gender','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('gender')) <span class="text-danger alert">{{ $errors->first('gender') }}</span> @endif
    </div>
   
    <div class="form-group col-md-6">
        <label for="staff_quantity" class="form-control-label">Staff Quantity (Can Add Staff) * </label>
        {!! Form::select('staff_quantity',['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], null, ['id' => 'staff_quantity','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('gender')) <span class="text-danger alert">{{ $errors->first('gender') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="division" class="form-control-label">Select Division * </label>
        {!! Form::select('division',Helper::getDivisiontPluck(), null, ['id' => 'division','class'
        => 'form-control select2','required','placeholder'=>'Select One'
        ]) !!}
        @if ($errors->has('division')) <span class="text-danger alert">{{ $errors->first('division') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="district" class="form-control-label">Select District * </label>
        {!! Form::select('district',[], null, ['id' => 'district','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('district')) <span class="text-danger alert">{{ $errors->first('district') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="thana" class="form-control-label">Select Thana * </label>
        {!! Form::select('thana',[], null, ['id' => 'thana','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('thana')) <span class="text-danger alert">{{ $errors->first('thana') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="area" class="form-control-label">Type Area * </label>
        {!! Form::text('area', null, ['id' => 'area','class'
        => 'form-control','required',
        ]) !!}
        {{-- {!! Form::select('area',[], null, ['id' => 'area','class'
        => 'form-control areaselect','required',
        ]) !!} --}}
        @if ($errors->has('area')) <span class="text-danger alert">{{ $errors->first('area') }}</span> @endif
    </div>
   
    <div class="form-group col-md-6">
        <label for="company_name" class="form-control-label">Company Name * </label>
        {!! Form::text('company_name', null, ['id' => 'company_name','class' => 'form-control','required',
        ]) !!}
      @if ($errors->has('company_name')) <span class="text-danger alert">{{ $errors->first('company_name') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="nid_number" class="form-control-label">Nid Number * </label>
        {!! Form::number('nid_number', null, ['id' => 'nid_number','class' => 'form-control','required',
        ]) !!}
      @if ($errors->has('nid_number')) <span class="text-danger alert">{{ $errors->first('nid_number') }}</span> @endif
    </div>
    
    <div class="form-group">
        <label for="company_address" class="form-control-label">Company Address * </label>
        {!! Form::textarea('company_address', null, ['id' => 'company_address','class' =>
        "form-control",'required','rows'=>2
        ]) !!}
        @if ($errors->has('company_address')) <span class="text-danger alert">{{ $errors->first('company_address') }}</span> @endif
    </div>
  
    @if (Request::segment(3)=='create')
    <div class="form-group col-md-6">
        <label for="roles" class="form-control-label">Select Role * </label>
        {!! Form::select('roles',$roles,null, ['id' => 'roles','class' => 'form-control select2','placeholder'=>'Select
        Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @else
    
    <div class="form-group col-md-6">
        <label for="roles" class="form-control-label">Update Role * </label>
        {!! Form::select('roles',$roles, $userRole, ['id' => 'roles','class' => 'form-control
        select2','placeholder'=>'Select Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @endif
<div class="form-group col-md-6">
        <label for="status" class="form-control-label">Status * </label>
        {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control select2','required'
        ]) !!}
        @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
    </div>
</div>
</div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Update</button>
                                </div>
                                {!! Form::close() !!}
                            </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
<!-- CKEditor init -->
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
     $('.select2').select2();
      $(".areaselect").select2({
  tags: true
});
$('#lfm').filemanager('filemanager');

$(document).ready(function () {
    var division = '{{$admin->division}}';
    var district = '{{$admin->district}}';
    var thana = '{{$admin->thana}}';
    $.ajax({
        type: "GET",
        url: url + '/get-district-info/'+division,
        dataType: "JSON",
        success:function(data) {
         if(data){
                  $.each(data.district, function(key, value){
                    if(value.id==district){
                        $('#district').append('<option value="'+value.district+'" selected>' + value.district + ' </option>');
                    }else{
                        $('#district').append('<option value="'+value.district+'">' + value.district +'</option>');
                    }

                    });
                }

            },
      });
     

 $.ajax({
        type: "GET",
        url: url + '/get-thana-info/'+district,
        dataType: "JSON",
        success:function(data) {
         if(data){
          
                  $.each(data.thana, function(key, value){
                    if(value.id==thana){
                        $('#thana').append('<option value="'+value.thana+'" selected>' + value.thana + ' </option>');
                    }else{
                       $('#thana').append('<option value="'+value.thana+'">' + value.thana +'</option>');
                    }
                    });
                }

            },
    });
    // for district show 
    $('#division').change(function(){
        $('#district').empty();
        $('#thana').empty();
       
    $.ajax({
        type: "GET",
        url: url + '/get-district-info/'+$('#division').val(),
        dataType: "JSON",
        success:function(data) {
         if(data){
            $('#district').append('<option value="">Select One</option>');
                  $.each(data.district, function(key, value){
                       $('#district').append('<option value="'+value.district+'">' + value.district +'</option>');

                    });
                }

            },
      });
    });

//district change
$('#district').change(function(){
  $('#thana').empty();
 
 var district = $(this).val();
 $.ajax({
        type: "GET",
        url: url + '/get-thana-info/'+district,
        dataType: "JSON",
        success:function(data) {
         if(data){
            $('#thana').append('<option value="">Select One</option>');
                  $.each(data.thana, function(key, value){
                       $('#thana').append('<option value="'+value.thana+'">' + value.thana +'</option>');

                    });
                }

            },
    });
    $('.select2').select2();
   });
  });




  </script>

@endpush

