@extends('backend.layouts.adminmaster')
@section('title', 'Create Mso')
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
                        <p class="mb-0">Mso Create</p>
                        <a href="{{route(Request::segment(1).'.mso.index')}}" class="btn btn-primary btn-sm ms-auto"><i
                                class="fa fa-backward"> </i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('partial.formerror')
                        {!! Form::open(['route' => Request::segment(1) . '.mso.store', 'method' => 'POST', 'files' =>
                        true]) !!}

                        @include('backend.admin.mso.form')
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Submit</button>
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

//branch_id change
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


//   $('#thana').change(function(){
//     $('#area').empty();
//  var district = $('#district').val();
//  var thana = $(this).val();
//  $.ajax({
//         type: "POST",
//         url: url + '/get-area-info',
//         dataType: "JSON",
//         data:{
//             district:district,
//             thana:thana
//         },
//         success:function(data) {
//        if(data){
//        $.each(data, function(key, value){
//              $('#area').append('<option value="'+value.area+'">' + value.area +'</option>');
//                     });
//                 }
               
//             },
//     });
//     });



});


</script>

@endpush