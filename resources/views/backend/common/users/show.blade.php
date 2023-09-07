@extends('backend.layouts.master')
@section('title', 'User Show')
@push('css')

<link href="{{ asset('lightbox/css/lightbox.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- 2nd style start -->
                    <div class="container">
                        <div class="row align-items-center flex-row-reverse">
                            <div class="col-lg-8">
                                <div class="about-text go-to">
                                    <h3 class="dark-color">Permission Of {{@$userDetails->name}} </h3>
                                    <p>@foreach ($userDetails->getAllPermissions() as $permission)
                                        <span class="badge badge-primary"> {{($permission->name)}} </span>
                                    @endforeach</p>
                                    <div class="row about-list">

                                        <div class="col-md-6">
                                            <div class="media">
                                                <label>Phone</label>
                                                <p>{{$userDetails->phone}}</p>
                                            </div>
                                            <div class="media">
                                                <label>Employee Type</label>
                                                <p>{{$userDetails->user_type}}</p>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="media">
                                                <label>E-mail</label>
                                                <p>{{$userDetails->email}}</p>
                                            </div>

                                            <div class="media">
                                                <label>Role</label>
                                                @php
                                                 $rolename=$userDetails->roles()->pluck('name')
                                                
                                                @endphp
                                                
                                                <p>{{rtrim(@$rolename[0], @Auth::id())}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="about-avatar">
                                    
                                    <img class="img-fluid img-thumbnail"
                                        src="{{@$userDetails->image}}"
                                        title="{{@$userDetails->name}}" alt="{{@$userDetails->name}}">
                                </div>
                                <div class="text-center">
                                    <h3 class="mt-4 text-uppercase">{{@$userDetails->name}}</h3>
                                   <a role="button" class="btn bg-gradient-info" href="{{route(request()->segment(1) . '.users.edit', (encrypt(@$userDetails->id)))}}"  >
                                       Edit
                                    </a>
                                    <a role="button" class="btn bg-gradient-primary" href="{{url(request()->segment(1) . '/admin-login-as-employee', (encrypt(@$userDetails->id)))}}" >
                                       Login As  {{@$userDetails->name}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 2nd style End -->
                    <!-- Modal Show Start for NID  -->
                    <!-- Button trigger modal -->


                    <!-- Modal -->
                    
                    <!-- Modal Show End for NID  -->
                </div>
            </div>
        </div>
    </div>
    @endsection
    @push('js')
    <script src="{{ asset('lightbox/js/lightbox.js') }}"></script>
    <script>
        $(document).ready(function() {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            });
       });
         
    </script>
    @endpush