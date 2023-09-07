@extends('backend.layouts.writer')
@section('title', 'Notification List')
@section('content')

<div class="container-fluid py-4">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Notification Information</p>
                                <button
                            class="btn btn-danger btn-sm ms-auto"  id="deleteNotification">Delete Notification</button>
                            </div>
                        </div>
                        
                
                        <!-- /.card-header -->
                        <div class="card-body">
                           
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                        aria-selected="true">User</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Read</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-contact" type="button" role="tab"
                                        aria-controls="nav-contact" aria-selected="false">UnRead</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered table-striped data-table">
                                            <thead>

                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>User Name</td>
                                                    <td>{{ Auth::user()->name }} </td>

                                                </tr>
                                                <tr>
                                                    <td>User Type</td>
                                                    <td>{{Auth::user()->user_type }} </td>
                                                </tr>
                                                <tr>
                                                    <td>User Email</td>
                                                    <td>{{Auth::user()->email }} </td>
                                                </tr>
                                                <tr>
                                                    <td>User Phone</td>
                                                    <td>{{Auth::user()->phone }} </td>
                                                </tr>
                                                <tr>
                                                    <td>Zip Code</td>
                                                    <td>{{ @Auth::user()->zip_code }} </td>
                                                </tr>

                                            </tbody>
                                            <tfoot>

                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    @if (auth()->user()->readnotifications->isNotEmpty())
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered table-striped data-table">
                                            <thead>
                                                <tr>
                                                    <th>#Id</th>
                                                    <th>Date Time</th>
                                                    <th>Message</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach (auth()->user()->readnotifications as $notification)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }} </td>
                                                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                                                    <td> {!! $notification->data['data'] !!} </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>

                                            </tfoot>

                                        </table>
                                        @else
                                        <div>
                                            <h5 class="text-center">No Notification Found</h5>
                                        </div>
                                        @endif
                                    </div>
                                    

                                </div>
                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                aria-labelledby="nav-contact-tab">
                                @if (auth()->user()->unreadnotifications->isNotEmpty())
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped data-table">
                                        <thead>
                                            <tr>
                                                <th>#Id</th>
                                                <th>Date Time</th>
                                                <th>Message</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach (auth()->user()->unreadnotifications as $notification)
                                            <tr>
                                                <td>{{ $loop->index + 1 }} </td>
                                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                                                <td> {!! $notification->data['data'] !!} </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>

                                        </tfoot>

                                    </table>
                                    @else
                                    <div>
                                        <h5 class="text-center">No Notification Found</h5>
                                    </div>
                                    @endif
                                </div>
                                

                            </div>
                                <!-- /.card-body -->
                               
                            </div>
                            <!-- /.card -->
                           
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
    </section>
</div>
@endsection
@push('js')

<script>
    $(document).ready(function () {
        $('#deleteNotification').click(function (e) { 
            if (!confirm('Are You Sure?')) return;
            '{{auth()->user()->notifications()->delete()}}';
            toastr.success('success', 'Notification Delete successfully');
            location.reload();
        });
    });
</script>

@endpush