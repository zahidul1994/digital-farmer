@extends('backend.layouts.master')
@section('title', 'Update User')
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-4">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">User Update</p>
                            <a href="{{route(Request::segment(1).'.users.index')}}" class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        @include('partial.formerror')
                      
                        {!! Form::model($user, [
                            'route' => [Request::segment(1) . '.users.update', $user->id],
                            'method' => 'PATCH',
                            'files' => true,
                        ]) !!}
                        @include('backend.common.users.form')
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Update</button>
                                </div>
                                {!! Form::close() !!}
                            </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
<!-- CKEditor init -->
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>
<script>
      $('.select2').select2();

      image.onchange = evt => {
  const [file] = image.files
  if (file) {
    imageShow.src = URL.createObjectURL(file)
  }
}    

  </script>

@endpush

