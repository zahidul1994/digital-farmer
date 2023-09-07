@extends('backend.layouts.master')
@section('title', 'Supplier Due List')
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
                                <h5 class="mb-0">All Supplier Due</h5>
                                <p class="text-sm mb-0">
                                    Supplier Due data.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route(Request::segment(1) . '.supplier-due.create') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp;Pay Supplier Due</a>
                                    
                               
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="">
                            <table id="datatable-basic" class="table table-flush" id="products-list">
                                <thead class="thead-light">
                                    <tr class="align-left">
                                        <th>Sl</th>
                                        <th>Date Time</th>
                                        <th>Invoice</th>
                                        <th>Creator</th>
                                        <th>Supplier</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                       <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th>Sl</th>
                                        <th>Date Time</th>
                                        <th>Invoice</th>
                                        <th>Creator</th>
                                        <th>Supplier</th>
                                        <th style="text-align: left"></th>
                                        <th style="text-align: left"></th>
                                       
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
    <script src="{{asset('backend/assets/js/plugins/datatables/sum.js')}}"></script>
   
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
                   
                    ajax: "{{ route(Request::segment(1) . '.supplier-due.index') }}",
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
                            data: 'invoice_no',
                            name: 'invoice_no'
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            data: 'supplier.supplier_name',
                            name: 'supplier.supplier_name'
                        },
                        {
                            data: 'paid',
                            name: 'paid'
                        },
                       
                        {
                            data: 'due',
                            name: 'due'
                        },
                       
                       
                        
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: true
                        },
                    ], "footerCallback": function(row, data) {
                    var api = this.api(),
                        data;
                    $(api.column(5, {
                        page: ''
                    }).footer()).html('' + api.column(5, {
                        page: ''
                    }).data().sum());
                    $(api.column(6, {
                        page: ''
                    }).footer()).html('' + api.column(6, {
                        page: ''
                    }).data().sum());

                },
                });
            });

            
        </script>
    @endpush
