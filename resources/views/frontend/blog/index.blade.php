@extends('layouts.app')
@push('css')


@endpush
@section('content')
<section class="main-block">
        
        <div class="container-fluid">
            <nav aria-label="breadcrumb" style="font-size:12px">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{url('/blog')}}">Rehab Blog</a></li>
                  
                </ol>
            </nav>
        
                        <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="styled-heading">
                                <h3>Rehab Center Latest Blog </h3>
                            </div>
                           
                            <form class="d-flex" action="{{url('/blog')}}" method="get" role="search">
                              <input class="form-control" name="q" required type="search" placeholder="Search" aria-label="Search">
                              <button class="btn btn-primary" aria-label="Left Align" type="submit"><span class="icon-arrow-right"></span>Search</button>
                          </form>
                         <br/>
                        </div>
                        
                    </div>
                    <div class="row">
                        @if($LatestBlog->isNotEmpty())
                        @foreach ($LatestBlog as $blog)
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
                        @else 
                        <div>
                            <h2 class="text-center">No Resule found</h2>
                        </div>
                    @endif
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="featured-btn-wrap">
                                {{@$LatestBlog->withQueryString()->onEachSide(1)->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </section>

@endsection
@push('js')

<script>
    
</script>


@endpush