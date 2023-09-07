@extends('backend.layouts.master')
@section('title', 'Create Page')
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
                            <p class="mb-0">Page Create</p>
                            <a href="{{ route(Request::segment(1) . '.pages.index') }}"
                                class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('partial.formerror')
                            {!! Form::open(['route' => Request::segment(1) . '.pages.store', 'method' => 'POST', 'files' => true]) !!}
                            @include('backend.superadmin.pages.form')
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Submit</button>
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
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js"></script>
<script>
      $('.select2').select2();
$('#lfm').filemanager('filemanager');
    var route_prefix = "/filemanager";
        $('textarea[name=bn_description]').ckeditor({
          height: 300,
          filebrowserImageBrowseUrl: route_prefix + '?type=Images',
          filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
          filebrowserBrowseUrl: route_prefix + '?type=Files',
          filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}',
            extraAllowedContent: 'a[rel]',
             extraPlugins: 'embed,autoembed,uicolor,colorbutton,colordialog,font',
            autoEmbed_widget:'customEmbed',
            format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
            
            allowedContent:true
        });
        $('textarea[name=header_description]').ckeditor({
          height: 300,
          filebrowserImageBrowseUrl: route_prefix + '?type=Images',
          filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
          filebrowserBrowseUrl: route_prefix + '?type=Files',
          filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}',
            extraAllowedContent: 'a[rel]',
             extraPlugins: 'embed,autoembed,uicolor,colorbutton,colordialog,font',
            autoEmbed_widget:'customEmbed',
            format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
            
            allowedContent:true
        });
        $('textarea[name=footer_description]').ckeditor({
          height: 300,
          filebrowserImageBrowseUrl: route_prefix + '?type=Images',
          filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
          filebrowserBrowseUrl: route_prefix + '?type=Files',
          filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}',
            extraAllowedContent: 'a[rel]',
             extraPlugins: 'embed,autoembed,uicolor,colorbutton,colordialog,font',
            autoEmbed_widget:'customEmbed',
            format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
            
            allowedContent:true
        });
        $("#page_name").keyup(function() {
            var Text = $("#page_name").val();
                Text = Text.toLowerCase();
                var regExp = /\s+/g;
                Text = Text.replace(regExp, '-');
         $("#slug").val(Text);
        })

  </script>

@endpush

