@extends('backend.layouts.master')
@section('title', 'Update Equipment Provider')
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-4">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Equipment Provider Update</p>
                            <a href="{{ route(Request::segment(1) . '.video-uploads.index') }}"
                                class="btn btn-primary btn-sm ms-auto">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('partial.formerror')
                            {!! Form::model($equipment, [
                                'route' => [Request::segment(1) . '.video-uploads.update', $equipment->id],
                                'method' => 'PATCH',
                                'files' => true,
                            ]) !!}
                            @include('backend.common.equipment_providers.form')
                           
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
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>
<!-- CKEditor init -->
<script src="https://cdn.ckeditor.com/4.21.0/basic/ckeditor.js"></script>
<script src="{{ asset('backend/assets/js/ckeditor-jquery.js') }}"></script>
<script>
    $('.select2').select2();
    $(document).ready(function () {
    var division = '{{$equipment->division}}';
    var district = '{{$equipment->district}}';
    var thana = '{{$equipment->thana}}';
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