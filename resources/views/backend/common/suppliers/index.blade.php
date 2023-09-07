@extends('backend.layouts.master')
@section('title', 'Supplier')
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
                                <h5 class="mb-0">All Supplier</h5>
                                <p class="text-sm mb-0">
                                    Supplier  data.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route(Request::segment(1) . '.suppliers.create') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New Supplier</a>

                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-0">
                            <div class="table-responsive">
                                <table id="datatable-basic" class="table table-flush" id="products-list">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl</th>
                                            <th>Created By</th>
                                            <th>Supplier</th>
                                            <th>Phone</th>
                                            <th>Payment</th>
                                             <th>Due</th>
                                             <th>D OR P</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Created By</th>
                                            <th>Supplier</th>
                                            <th>Phone</th>
                                            <th style="text-align: left">Payment</th>
                                             <th style="text-align: left">Due</th>
                                             <th style="text-align: left">D OR P</th>
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
<script src="{{asset('backend/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
   
    <script>
        $(document).ready(function() {
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
               
                ajax: "{{ route(Request::segment(1) . '.suppliers.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'supplier_phone',
                        name: 'supplier_phone'
                    },
                    {
                        data: 'paid_total',
                        name: 'paid_total'
                    },
                    {
                        data: 'due_total',
                        name: 'due_total'
                    },
                    {
                        data: 'due_or_paid',
                        name: 'due_or_paid'
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
            $.post("{{ route(Request::segment(1) . '.supplierStatus') }}", {
                    _token: '{{ csrf_token() }}',
                    id: el.value,
                    status: status
                },
                function(data) {
                    if (data == 1) {
                        toastr.success('success', 'Supplier Status updated successfully');
                    } else {
                        toastr.danger('danger', 'Something went wrong');
                    }
                });
        }
    </script>
@endpush
