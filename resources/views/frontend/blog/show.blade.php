@extends('layouts.app')
@push('css')

<style>
    .blod-info {
        display: flex
    }

    .blod-info li {
        padding-right: 20px;
        list-style: none;
        color: #aaa;
        display: flex;
        align-items: center
    }

    .blod-info li svg {
        margin-right: 5px
    }

    .blod-info li a {
        color: #aaa
    }

    .blod-info li a:hover {
        color: #00f
    }

    #social-links {
        display: inline-block
    }

    #social-links ul {
        margin-bottom: 0 !important;
        padding-left: 0 !important;
        margin-right: 10px
    }

    #social-links ul li {
        list-style: none
    }

    #social-links ul li a span {
        font-size: 22px;
        color: #0d6efd
    }



    .cke_editable {
        font-size: 16px;
        line-height: 1.6;
        word-wrap: break-word
    }

    blockquote {
        font-style: italic;
        font-family: Georgia, Times, "Times New Roman", serif;
        padding: 2px 0;
        border-style: solid;
        border-color: #ccc;
        border-width: 0
    }

    .cke_contents_ltr blockquote {
        padding-left: 20px;
        padding-right: 8px;
        border-left-width: 5px
    }

    .cke_contents_rtl blockquote {
        padding-left: 8px;
        padding-right: 20px;
        border-right-width: 5px
    }

    a {
        color: #0782c1
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 400 !important;
        line-height: 1.2 !important
    }

    hr {
        border: 0;
        border-top: 1px solid #ccc
    }

    img.right {
        border: 1px solid #ccc;
        float: right;
        margin-left: 15px;
        padding: 5px
    }

    img.left {
        border: 1px solid #ccc;
        float: left;
        margin-right: 15px;
        padding: 5px
    }

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        -moz-tab-size: 4;
        tab-size: 4
    }

    .marker {
        background-color: #ff0 !important
    }

    span[lang] {
        font-style: italic
    }

    figure {
        text-align: center;
        border: solid 1px #ccc;
        border-radius: 2px;
        background: rgba(0, 0, 0, .05);
        padding: 10px;
        margin: 10px 20px;
        display: inline-block
    }

    figure>figcaption {
        text-align: center;
        display: block
    }
</style>
@endpush
@section('content')
<section class="main-block">
        
        <div class="container">
            <nav aria-label="breadcrumb" style="font-size:12px">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{url('/blog')}}">Rehab Blog</a></li>
                  <li class="breadcrumb-item"><a href="{{url('blog',@$blogDetails->slug)}}"> {{@$blogDetails->title}}</a></li>
                </ol>
            </nav>
            <div class="main-blog">
                <div class="row  shadow-lg p-3 mb-1 bg-body rounded">
                    <div class="col-md-12">
    
                        <h1 class="text-success blod" style="font-size: 55px;
                        font-family: bold;"> {{@$blogDetails->title}}</h1>

    
                        <ul class="blod-info ps-0">
                            <li>
                                <span class="icon-calendar"></span> &nbsp;
                                {{ date('d-M-Y', strtotime(@$blogDetails->created_at)) }}
                            </li> 
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{url('blog',@$blogDetails->slug)}}" class="social-button " id=""><span class="icon-social-facebook"></span></a></li>
                                <li><a href="https://twitter.com/intent/tweet?text={{@$blogDetails->title}}&amp;url={{url('blog',@$blogDetails->slug)}}" class="social-button " id=""><span class="icon-social-twitter"></span></a></li>
                                <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url('blog',@$blogDetails->slug)}}&amp;title={{@$blogDetails->title}}&amp;summary={{@$blogDetails->title}}" class="social-button " id=""><span class="icon-social-linkedin"></span></a></li>
                                
                            </ul>
                          <img src="{{ url(@$blogDetails->image) }}"
                            alt="{{ @$blogDetails->title }}"
                            class="img-fluid rounded mx-auto d-block mb-1"> 
                        
                        <div class="cke_editable mt-1"> {!! @$blogDetails->long_description !!}</div>
                    </div>
                  </div>
                  </div>
                 
           
             <div class="fb-comments" data-href="{{Request::url()}}" data-width="100%" data-numposts="5"></div>
             <span id="fb-root"></span>
            </div>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="styled-heading">
                                <h3>Rehab Center Latest Blog </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach (Helper::frontBlog() as $blog)
                            
                        <div class="col-md-3 featured-responsive">
                            <div class="featured-place-wrap">
                                <a href="{{url('blog',@$blog->slug)}}">
                                    <img src="{{ asset(@$blog->image)}}" alt="{{Str::words(@$blog->title,8)}}" class="img-fluid img-thumbnail rounded">
                                   
                                    <div class="featured-title-box">
                                        <h6>{{Str::words(@$blog->title,8)}} </h6>
                                        <ul>
                                            <li><span class="icon-calendar"></span>
                                                {{ date('d-M-Y h:iA', strtotime(@$blog->created_at)) }}
                                            </li>
                                            
                                        </ul>
                                        <p>{{Str::words(@$blog->short_description,20)}}</p> 
                                    </div>
                                   
                                </a>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="featured-btn-wrap">
                                <a href="{{url('blog')}}" class="btn btn-danger">VIEW ALL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </section>

@endsection
@push('js')
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v13.0&appId=209201484717774&autoLogAppEvents=1"
    nonce="iyZZ3sn3">
</script>
<script>
    $(document).ready(function () {
        $(".cke_editable img").each(function(i) {
                $(this).addClass("img-fluid");
                   
            });

    });
</script>

@endpush