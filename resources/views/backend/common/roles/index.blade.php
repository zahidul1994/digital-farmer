@extends('backend.layouts.master')
@section('title', 'Roles')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

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
                                <h5 class="mb-0">All Roles</h5>
                                <p class="text-sm mb-0">
                                     data.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{route(Request::segment(1).'.roles.create')}}"
                                    class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New Role</a>
                                 
                            </div>
                        </div>
                      </div>
                        <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <table id="datatable-basic" class="table table-flush" id="products-list">
                                <thead class="thead-light">
                                    <tr>
                                    <th>Sl</th>
                                    <th>Role Name</th>
                                    <th>Permission</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Role Name</th>
                                        <th>Permission</th>
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
    <script src="{{asset('backend/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
   
     <script>
        $(document).ready(function () {
       
            $('#datatable-basic').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: 'Bflrtip',
                lengthMenu :
                [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                language: {
                    'paginate': {
                        'previous': '<strong><<strong>',
                        'next': '<strong>><strong>'
                    }
                },
                
                ajax: "{{ route(Request::segment(1).'.roles.index') }}",
                columns: [
                    {data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                   },
                    {data: 'name', name: 'name'},
                    {data: 'permission',render: function(data, type) { 
                    return $("<p/>").html(data).text();}},
                    {data: 'action', name: 'action', orderable: false, searchable: true},
                ]
            });

            

        });
        

    </script>
    @endpush