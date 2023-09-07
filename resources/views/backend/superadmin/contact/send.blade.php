@extends('layouts.superadmin')
@section('title','Contact Email Seding list')
@section('page-style')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css" rel="stylesheet">

@endsection
@section('content')   
            <div class="main-cards">
              

                <div class="cards tooltips-card">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('superadmin/createemail')}}"
                            class="btn btn-primary w-100 mb-3">Compose</a>
                            <div class="card mb-3" style="">
                                <div class="card-header">
                                    Folders
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item mail-menu"><a href="{{url('superadmin/emaillist')}}"><i class="fas fa-inbox"></i> Inbox</a></li>
                                    <li class="list-group-item mail-menu"><a href="{{url('superadmin/sendingmaillist')}}"><i class="far fa-envelope"></i> Sent</a></li>
                                    <li class="list-group-item mail-menu"><a href="#"><i class="fas fa-file-alt"></i> Drafts</a></li>
                                    <li class="list-group-item mail-menu"><a href="#"><i class="fas fa-filter"></i> Junk</a></li>
                                    <li class="list-group-item mail-menu"><a href="#"><i class="far fa-trash-alt"></i> Trash</a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="progress-main-box shadow-sm">
                                <div class="d-flex progress-head">
                                    <div class="mb-0 w-50 align-self-center">
                                        Inbox

                                    </div>
                                    <div class="mb-0 w-50 align-self-center">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search Mail" aria-label="Recipient's username" aria-describedby="button-addon2">
                                            <button class="btn btn-primary mb-0" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex progress-head">
                                    <div class="mb-0 w-50 align-self-center">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination mail-option">
                                                <li class="page-item"><a class="page-link" href="#">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                                                        </div>

                                                    </a></li>
                                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-trash-alt"></i></a></li>
                                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-reply"></i></a></li>
                                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-share"></i></a></li>
                                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-sync-alt"></i></a></li>
                                            </ul>
                                        </nav>

                                    </div>
                                    <div class="mb-0 w-50 align-self-center">
                                        <nav aria-label="Page navigation example" class="float-end">
                                            <ul class="pagination mail-option">
                                                <li class="page-item"><a class="page-link" href="#">
                                                        <i class="fas fa-angle-left"></i>

                                                    </a></li>

                                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a>

                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>

                                <div class="progress-box px-0 pb-3">
                                   <div class="inbox-table-wrapper">
                                        <table class="table table-striped inbox-table mb-0">

                                        <tbody>
                                            @if(count($contactus)>0)

                                            @foreach ($contactus as $contact)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                                                    </div>
                                                </th>

                                                <td>
                                                    <a href="{{url('superadmin/replymail/'.$contact->id) }}"> {{$contact->name}}</a>
                                                </td>
                                                <td> <b>{{$contact->subject}}</b> </td>
                                                <td>
                                                    <small>{{$contact->created_at->diffForHumans()}}</small>
                                                </td>
                                            </tr>
                                            @endforeach
                                          @else
                                        <h6 class="center-align">No Results Found</h6>
                                           @endif

                                        </tbody>
                                    </table>
                                       
                                   </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

@endsection

@section('page-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function() {
    });
</script>
@endsection
           