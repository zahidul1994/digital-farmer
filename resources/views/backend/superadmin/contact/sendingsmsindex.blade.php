@extends('layouts.superadmin')
@section('title','Sending SMS')
@section('page-style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

{{-- main page content --}}

@section('content')
<div class="main-cards">

    <div class="cards tooltips-card">
        <div class="row">
            <div class="col-md-3">

                <div class="card mb-3" style="">
                    <div class="card-header">
                        Folders
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item mail-menu"><a href="{{url('superadmin/emaillist')}}"><i
                                    class="fas fa-inbox"></i> Inbox</a></li>
                        <li class="list-group-item mail-menu"><a href="#"><i class="far fa-envelope"></i> Sent</a></li>
                        <li class="list-group-item mail-menu"><a href="{{url('superadmin/draftsmaillist')}}"><i
                                    class="fas fa-file-alt"></i> Drafts</a></li>
                        <li class="list-group-item mail-menu"><a href="#"><i class="fas fa-filter"></i> Junk</a></li>


                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="progress-main-box shadow-sm">
                    <div class="col-md-9">
                        <div class="progress-main-box shadow-sm">
                            <div class="progress-head">
                                <div class="mb-0 align-self-center">
                                    Sending SMS >>>>>>>  <a target="download" href="https://www.sohibd.com/storage/smssendign.xlsx">Demo <i class="fa fa-download"></i></a></div>
                            </div>
                            @include('partial.formerror')
                            {!! Form::open(array('url' => 'superadmin/sendingsms','method'=>'POST','files'=>true )) !!}
                            <div class="progress-box">
                                <div class="mb-3">
                                    {!!Form::label('smsapi',' Select SMS API * ')!!}
                                    {!!Form::select('smsapi',$smsapi,'SohiBdSMSAPI',
                                    array('id'=>'smsapi','required','class'=>'form-control select2'))!!}


                                </div>
                                <div class="mb-3">
                                    {!!Form::label('phone','Enter Your Phone or Upload excel file')!!}
                                    {!!Form::select('phone[]',[],null,
                                    array('id'=>'phone','class'=>'form-control select2','multiple'=>true))!!}

                                </div>
                                
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload Your Excel </label>
                                    <input class="form-control" type="file" name="excelfile" id="formFile">
                                  </div>
                                <div class="mb-3">
                                    {!!Form::label('message',' Enter Your Text * ')!!}
                                    {!!Form::textarea('message',null,
                                    array('id'=>'message','required','class'=>'form-control','rows'=>2))!!}
                                </div>
                            </div>

                            <div class="d-flex px-3">

                                <div class="mb-0 w-100 align-self-center  mb-5">
                                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i>
                                        Send</button>
                                </div>
                            </div>


                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
</div>

@endsection

@section('page-script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(".select2").select2({
  tags: true
});


</script>

@endsection