@extends('backend.layouts.master')
@section('title', 'Video Upload')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

    <link href="{{ asset('lightbox/css/lightbox.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-4">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Video Uploads</h5>
                                <p class="text-sm mb-0">
                                    Video Upload data.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route(Request::segment(1) . '.video-uploads.create') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New Video Upload</a>
                                    
                               
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="">
                            <table id="datatable-basic" class="table table-flush">
                                <thead class="thead-light">
                                    <tr class="align-left">
                                        <th>Sl</th>
                                        <th>Date</th>
                                        <th>Uploader</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th>Sl</th>
                                        <th>Date</th>
                                        <th>Uploader</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
    @push('js')
    <script src="{{ asset('lightbox/js/lightbox.js') }}"></script> 
    <script src="{{asset('backend/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
   
    
  
  
  <script>
            $(document).ready(function() {
                lightbox.option({
                    'resizeDuration': 200,
                    'wrapAround': true
                });

                $('#datatable-basic').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    dom: 'Bflrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    language: {
                        'paginate': {
                            'previous': '<strong><<strong>',
                            'next': '<strong>><strong>'
                        }
                    },
                   
                    ajax: "{{ route(Request::segment(1) . '.video-uploads.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        
                        {
                            data: 'text_title',
                            name: 'text_title'
                        },
                        
                        
                        {
                            data: 'status'
                        },
                        
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: true
                        },
                    ]
                });
            });

            function updateStatus(el) {
                if (el.checked) {
                    var status = 1;
                } else {
                    var status = 0;
                }
                $.post("{{ route(Request::segment(1) . '.videoUploadStatus') }}", {
                        _token: '{{ csrf_token() }}',
                        id: el.value,
                        status: status
                    },
                    function(data) {
                        if (data == 1) {
                            toastr.success('success', 'Video Upload Status updated successfully');
                        } else {
                            toastr.danger('danger', 'Something went wrong');
                        }
                    });
            }
        </script>
    @endpush
