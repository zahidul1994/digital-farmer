@extends('backend.layouts.master')
@section('title', 'Send New Mail')
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
<div class="container-fluid my-5 py-2">
    <div class="row mb-5">
        <div class="col-lg-3">
            <div class="card position-sticky top-1">
                <ul class="nav flex-column bg-white border-radius-lg p-3">
                    <li class="nav-item">
                        <a class="nav-link text-body d-flex align-items-center active" href="{{ route(Request::segment(1) . '.contacts.create') }}">
                            <i class="ni ni-send  me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Compose</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-body d-flex align-items-center" href="{{ route(Request::segment(1) . '.contacts.index') }}">
                            <i class="ni ni-email-83 me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Inbox</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-body d-flex align-items-center" href="{{ route(Request::segment(1) . '.contacts.index') }}">
                            <i class="ni ni-atom me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Sent</span>
                        </a>
                    </li>


                    <li class="nav-item pt-2">
                        <a class="nav-link text-body d-flex align-items-center"  href="{{ route(Request::segment(1) . '.contacts.index') }}">
                            <i class="ni ni-watch-time me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Trash</span>
                        </a>
                    </li>

                    <li class="nav-item pt-2">
                        <a class="nav-link text-body d-flex align-items-center"  href="{{ route(Request::segment(1) . '.contacts.index') }}">
                            <i class="ni ni-settings-gear-65 me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Junk</span>
                        </a>
                    </li>

                   


                </ul>
            </div>
        </div>
        <div class="col-lg-9 mt-lg-0 mt-4">
            <div class="card mt-4" id="password">
                <div class="card-header pb-4">
                    <h5>Email List</h5>
                </div>
                  <div class="mb-0 w-50 align-self-center">
                    Email Sending >>>>>>>
                         
                </div>
                <div class="card-body pt-0">
                    {!! Form::open(array('url' => 'superadmin/contacts','method'=>'POST','files'=>true )) !!}

                                        
                    <div class="form-input">
                        <div class="mb-3">
                                <label for="email">Email</label>  
                                {!!Form::select('email[]',$Contact,null, array('id'=>'email','required','class'=>'form-control select2','multiple'=>true))!!}
                                                
                        </div>
                             <div class="mb-3">
                                <label for="email">Subject</label>  
                                {!!Form::text('subject',null, array('id'=>'subject','required','class'=>'form-control','placeholder'=>'Subject:'))!!}
                                                
                        </div>
                          
                             <div class="mb-3">
                                <label for="email">Name </label>  
                                {!!Form::text('name','Hello', array('id'=>'name','required','class'=>'form-control','placeholder'=>'Converson Hello or Hi'))!!}
                                                
                        </div>
                          
                        {!!Form::textarea('message',null, array('id'=>'message','required','class'=>'ckeditor'))!!}
                      
                  
                          <div class="d-flex pt-3">
                        
                        <div class="mb-0 w-100 align-self-center text-end">
                          
                            <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                        </div>
                    </div>
                   

                    </div>
                    {!! Form::close() !!}
                </div>

            </div>






        </div>
    </div>

</div>
@endsection

@push('js')
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js"></script>
 <script>
      $(".select2").select2({
  tags: true
});
var route_prefix = "/filemanager";
   $('textarea[name=reply]').ckeditor({
     height: 300,
     filebrowserImageBrowseUrl: route_prefix + '?type=Images',
     filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
     filebrowserBrowseUrl: route_prefix + '?type=Files',
     filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
     
   });
</script>
@endpush