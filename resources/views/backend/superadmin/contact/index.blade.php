@extends('backend.layouts.master')
@section('title', 'Contact')
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
                        <a class="nav-link text-body d-flex align-items-center" href="{{ route(Request::segment(1) . '.contacts.create') }}">
                            <i class="ni ni-send  me-2 text-dark opacity-6"></i>
                            <span class="text-sm">Compose</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-body d-flex align-items-center active" href="{{ route(Request::segment(1) . '.contacts.index') }}">
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
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mail-option">
                               
                                <li class="page-item"><button class="page-link" id="deleteSelect"><i
                                            class="fas fa-trash-alt"></i></button></li>
                                <li class="page-item"><a class="page-link" href="#"><i
                                            class="fas fa-reply"></i></a></li>
                                <li class="page-item"><a class="page-link" href="#"><i
                                            class="fas fa-share"></i></a></li>
                                <li class="page-item"><a class="page-link" href="#"><i
                                            class="fas fa-sync-alt"></i></a></li>
                            </ul>
                        </nav>                 
                   
                </div>
                <div class="card-body pt-0">
                    <table class="table table-striped inbox-table mb-0">

                        <tbody>
                            @if (count($contactus) > 0)

                            @foreach ($contactus as $contact)
                            <tr>
                                <th scope="row">
                                    <div class="form-check mb-0">
                                        {{ Form::checkbox('checkvalue', $contact->id, false, ['class' =>
                                        'form-check-input group_1', 'id' => 'flexCheckDefault']) }}
                                    </div>
                                </th>

                                <td>
                                    <a href="{{route(Request::segment(1).'.contacts.edit', $contact->id) }}">
                                        {{ $contact->name }}</a>
                                </td>
                                <td> <b>{{ $contact->subject }}</b> </td>
                                <td>
                                    <small>{{ $contact->created_at->diffForHumans() }}</small>
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
@endsection

@push('js')
<script src="{{ asset('backend/assets/js/plugins/sweetalert.min.js') }}"></script>
<script>
  $(document).ready(function() {
        $(document).on('click', '#deleteSelect', function() {
            if (!confirm('Are You Delete All Selected Email ?')) return;
            var idsvalue = [];
            $.each($("input[name='checkvalue']:checked"), function() {
                idsvalue.push($(this).val());
            });
            $info_url = url + '/superadmin/deleteallemail';
            $.ajax({
                url: $info_url,
                method: "POST",
                data: {
                    'ids': idsvalue
                },
                success: function(data) {
                    if (data) {
                        // console.log(idsvalue);
                        Swal.fire({
                            icon: 'success',
                            title: "All Email Delete Successfully",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        location.reload();

                    }
                },
                error: function(data) {
                    Swal.fire({
                            icon: 'warning',
                            title: "Select Minimum One Email",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                }
            });
        });
    });

</script>
@endpush