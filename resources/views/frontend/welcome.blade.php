@extends('layouts.app')
@push('css')

@endpush
@section('content')
<section class="home_main_sec align-items-center d-flex" id="home">
    <div class="container">
        <div class="row">
			@include('partial.laravelmessage')
            <div class="col-lg-12">
                <div class="header_content text-center">
                    <p class="small_title mb-0">Wellcome</p>
                    <h1 class="text-capitalize mt-4 mb-0 mx-auto">A Powerfull Management  tools </h1>
                    <p class="text-muted mt-4 mx-auto sub_title">If you want to keep yourself and agency updated with time then this software is for you. </p>
                   
                    <div class="scroll_btn down_scroll text-center mt-5">
                        <a href="#features" class="text-white"><i class="mdi mdi-arrow-down mr-3"></i></a>
                    </div>
                </div>
                <div class="home_mockup mx-auto">
                    <img src="{{ @Helper::getSlider()->image}}" alt="{{ @Helper::getSlider()->link_text}}" class="img-fluid mx-auto d-block rounded">
                </div>
            </div>
        </div>
    </div>
    <div class="curv-img">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
            <path fill="#fff" d="M3000,0v185.4H0V0c496.4,115.6,996.4,173.4,1500,173.4S2503.6,115.6,3000,0z"></path>
          </svg>
    </div>
</section>
<!-- End Home -->

<!-- Start Logo -->
<section class="section mt-5">
    <div class="container">
        <div class="row">
			@foreach ($companies as $company)
            <div class="col-lg-3">
                <div class="client_logo mt-3">
					<img src="{{ asset(@$company->company_logo) }}" alt="logo" class="img-fluid mx-auto d-block">
                </div>
            </div>
			@endforeach
        </div>
    </div>
</section>
	

	<!-- End Logo -->

	<!-- Start Section Divider -->
	<section class="sec_divider mx-auto"></section>
	<!-- End Section Divider -->

	<!-- Start Features -->
	<section class="section" id="features">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title mx-auto text-center">	
						<h2 class=""><span></span>Explore about our awesome features</h2>
						<p class="text-muted mt-3 mx-auto">Software has  recruitment services for skilled manpower, labor, industrial staff, and semi-skilled labor in various sectors.</p>
					</div>
				</div>
			</div>
			<div class="row mt-5 align-items-center">
                <div class="col-lg-6">
                    <div class="content_features mx-auto mt-3">
                        <p class="content_small_title mb-0 text-uppercase"><span></span>Multiple Shop</p>
                        <h3 class="content_main_title mb-0 mt-4">It’s everything you’ll ever need.</h3>
                        <p class="text-muted mt-4">
							Skilled manpower recruitment services
							Labor recruitment services
							Industrial staff recruitment services
							Semi-skilled labor recruitment services
							Offshore / onshore recruitment services
						.</p>
                        <div class="mt-4 pt-3">
                            <a href="#" class="btn btn-custom btn-rounded">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="side_img_content mt-3">
                        <img src="{{ asset('frontend/images/features-one.png')}}" alt="com" class="img-fluid mx-auto d-block">
                    </div>
                </div>
            </div>
            <div class="features_divider"></div>
            <div class="row mt-3 align-items-center">
                <div class="col-lg-6">
                    <div class="side_img_content mt-3">
                        <img src="{{ asset('frontend/images/features-two.png')}}" alt="com" class="img-fluid mx-auto d-block">
                    </div>
                </div>
                <div class="col-lg-6">
                	<div class="content_features mx-auto mt-3">
                        <p class="content_small_title mb-0 text-uppercase"><span></span>User Interface</p>
                        <h3 class="content_main_title mb-0 mt-4">Unlimited features awaiting for you.</h3>
                        <p class="text-muted mt-4">Create custom landing pages with Landik that converts more visitors than any website. With lots of unique blocks, you can easily build a page without coding.</p>
                        <div class="mt-4 pt-3">
                            <a href="#" class="btn btn-custom btn-rounded">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</section>
	<!-- End Features -->

	<!-- Start Services -->
	<section class="bg-custom section">
		<div class="curv_top">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
				<path fill="#fff" d="M3000,185.4V0H0v185.4C496.4,69.8,996.4,12,1500,12S2503.6,69.8,3000,185.4z"></path>
			</svg>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title_bg pb-5 mx-auto text-center">	
						<h2><span></span>What We do</h2>
						<p class="mt-3 mx-auto">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis eum modi, adipisci facilis.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Services -->

	<!-- Start Services -->
	<section class="ser_sec">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="ser_bor_boxes mt-3 d-flex">
						<div class="ser_icon">
							<img src="{{ asset('frontend/images/icon/pen&ruller.svg')}}" alt="icon" height="44px;" class="me-4">
						</div>
						<div class="ser_content">
							<h4>Well Documented</h4>
							<p class="text-muted mb-0 mt-3">Ned ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ser_bor_boxes mt-3 d-flex">
						<div class="ser_icon">
							<img src="{{ asset('frontend/images/icon/code.svg')}}" alt="icon" height="44px;" class="me-4">
						</div>
						<div class="ser_content">
							<h4>Highly Customizable</h4>
							<p class="text-muted mb-0 mt-3">The point of using Lorem Ipsum is has a normal distribution of letters, as opposed to using.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-lg-6">
					<div class="ser_bor_boxes mt-3 d-flex">
						<div class="ser_icon">
							<img src="{{ asset('frontend/images/icon/headphones.svg')}}" alt="icon" height="44px;" class="me-4">
						</div>
						<div class="ser_content">
							<h4>24/7 Support</h4>
							<p class="text-muted mb-0 mt-3">There are many variations of but the majority have suffered alteration in some form.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ser_bor_boxes mt-3 d-flex">
						<div class="ser_icon">
							<img src="{{ asset('frontend/images/icon/groupchat.svg')}}" alt="icon" height="44px;" class="me-4">
						</div>
						<div class="ser_content">
							<h4>Community</h4>
							<p class="text-muted mb-0 mt-3">If you are going to use a Ipsum, you need to be sure there isn't anything in the middle of text.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Services -->
	
	<!-- Start Funfact -->
	<section class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title mx-auto text-center">	
						<h2 class=""><span></span>Hosts like you, worldwide</h2>
						<p class="text-muted mt-3 mx-auto">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis eum modi, adipisci facilis.</p>
					</div>
				</div>
			</div>
			<div class="row mt-5 fun_bg" id="counter">
				<div class="col-lg-3">
					<div class="fun_box text-center mt-3">
						<div class="fun_icon">
							<i class="mdi mdi-star"></i>
						</div>
						<div class="fun_detail mt-4">
							<h3 class="fw-bold mb-2"><span class="count" data-count="{{$companies->avg('rating')}}"></span><span>/5</span></h3>
							<p class="text-muted mb-0">Average Ratings</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="fun_box text-center mt-3">
						<div class="fun_icon">
							<i class="mdi mdi-account"></i>
						</div>
						<div class="fun_detail mt-4">
							<h3 class="fw-bold mb-2"><span class="count" data-count="{{$companies->count('id')}}"></span><span>k+</span></h3>
							<p class="text-muted mb-0">Total Agency</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="fun_box text-center mt-3">
						<div class="fun_icon">
							<i class="mdi mdi-home"></i>
						</div>
						<div class="fun_detail mt-4">
							<h3 class="fw-bold mb-2"><span class="count" data-count="0">0</span><span>k+</span></h3>
							<p class="text-muted mb-0">Total Shop</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="fun_box text-center mt-3">
						<div class="fun_icon">
							<i class="mdi mdi-apple-keyboard-command"></i>
						</div>
						<div class="fun_detail mt-4">
							<h3 class="fw-bold mb-2"><span class="count" data-count="4.9">0</span><span>M</span></h3>
							<p class="text-muted mb-0">Total Downloads</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Funfact -->

	<!-- Start Cta -->
	<section class="section">
		<div class="container">
			<div class="row cta_section">
				<div class="col-lg-12">
					<div class="cta_content text-center">
						<h1 class="fw-bold text-white mx-auto mb-0">We’re Ready for a A Metting </h1>
						<p class="mx-auto mt-4">if you want to make some differnt actibute to other agency company just contact Us </p>
						<div class="mt-4 pt-2">
							<a href="#contact" class="btn btn-white">Start Trial Free</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
	<!-- End Cta -->

	<!-- Start How It Work -->
	<section class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title mx-auto text-center">	
						<h2 class=""><span></span>How easy is it to get started?</h2>
						<p class="text-muted mt-3 mx-auto">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis eum modi, adipisci facilis.</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-lg-4">
					<div class="mt-3 hit_box text-center px-4">
						<div class="hit_icon mb-4">
							<p class="mb-0 text-uppercase">Step 1</p>
						</div>
						<div class="hit_detail mt-4">
							<h4 class="">Design & Devloping</h4>
							<p class="text-muted mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="mt-3 hit_box text-center px-4">
						<div class="hit_icon mb-4">
							<p class="mb-0 text-uppercase">Step 2</p>
						</div>
						<div class="hit_detail mt-4">
							<h4 class="">Testing Project</h4>
							<p class="text-muted mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="mt-3 hit_box text-center px-4">
						<div class="hit_icon mb-4">
							<p class="mb-0 text-uppercase">Step 3</p>
						</div>
						<div class="hit_detail mt-4">
							<h4 class="">Publisher Project</h4>
							<p class="text-muted mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End How It Work -->

	<!-- Start Client -->
	<section class="section bg-light" id="client">
		<div class="curv_top">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
				<path fill="#fff" d="M3000,185.4V0H0v185.4C496.4,69.8,996.4,12,1500,12S2503.6,69.8,3000,185.4z"></path>
			</svg>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title mx-auto text-center">	
						<h2 class=""><span></span>Oliver is loved by users</h2>
						<p class="text-muted mt-3 mx-auto">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veritatis eum modi, adipisci facilis.</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-lg-12">
					<div class="oliver_client position-relative">
						@foreach ($companies as $company)
							
						
						<div>
							<div class="client_box">
								<div class="client_img_rate d-flex align-items-center">
									<div class="client_img">
										<img src="{{asset($company->image)}}" alt="testimonial" class="img-fluid rounded-circle mx-auto">
									</div>
									<div class="ms-3 client_review">
										<div class="name_client">
											<p class="mb-0"><span class="fw-bold">{{$company->name}}</span>, {{$company->company_name}}</p>
										</div>
										@for ($i = 0; $i < round($company->rating); $i++)
										<i class="mdi mdi-star text-custom"></i>
										@endfor
										@for ($i = 0; $i <5-(round($company->rating)); $i++)
										<i class="mdi mdi-star text-muted"></i>
										@endfor
									</div>
								</div>
								<div class="client_review_des mt-4">
									<p class="text-muted mb-0">{{$company->comment}}</p>
								</div>
							</div>
						</div>		
						@endforeach
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Client -->

	<!-- Start Price -->
	<section class="section" id="price">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title mx-auto text-center">	
						<h2 class=""><span></span>Find a Plan that works for you</h2>
						<p class="text-muted mt-3 mx-auto">if you use to free for one month. type i use free in contact us body</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-lg-4">
					<div class="price_box mt-3 text-center">
						<div class="price_name">
							<h2 class="fw-bold">Basic</h2>
							<p class="text-muted mt-4">Start For Small Agency.</p>
						</div>
						<div class="price_btn mt-5">
							<a href="#" class="btn btn-custom">Choose Now</a>
						</div>
						<div class="plan_price mt-5">
							<h1 class="fw-bold"><sub class="fw-normal">BDT/</sub>1000</h1>
							<p class="text-muted mb-0">TK  Monthly .</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="price_box active mt-3 text-center">
						<div class="price_name">
							<h2 class="fw-bold">Professional</h2>
							<p class="text-muted mt-4">For Mid Level Agency.</p>
						</div>
						<div class="price_btn mt-5">
							<a href="#" class="btn btn-custom">Choose Now</a>
						</div>
						<div class="plan_price mt-5">
							<h1 class="fw-bold"><sub class="fw-normal">BDT/</sub>10000</h1>
							<p class="text-muted mb-0">TK  Yearly.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="price_box mt-3 text-center">
						<div class="price_name">
							<h2 class="fw-bold">Ultimate</h2>
							<p class="text-muted mt-4">For Big Level Agency</p>
						</div>
						<div class="price_btn mt-5">
							<a href="#" class="btn btn-custom">Choose Now</a>
						</div>
						<div class="plan_price mt-5">
							<h1 class="fw-bold"><sub class="fw-normal">BDT/</sub>120000</h1>
							<p class="text-muted mb-0">TK  Lifetime.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Price -->

	<!-- Start Contact -->
	<section class="section bg-custom">
		<div class="curv_top">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
				<path fill="#fff" d="M3000,185.4V0H0v185.4C496.4,69.8,996.4,12,1500,12S2503.6,69.8,3000,185.4z"></path>
			</svg>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sec_main_head_title_bg pb-5 mx-auto text-center">	
						<h2><span></span>Contact our sales team</h2>
						<p class="mt-3 mx-auto">Have a Agency Company or Empoloyee. Please Contact Us For Our Help support</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Contact -->

	<!-- Start Contact Form -->
	<section class="contact_sec" id="contact">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="contact_form mx-auto p-4">
						{!! Form::open(array('url' => 'contact','method'=>'POST')) !!}
						    <div class="row">
						        <div class="col-lg-6">
						            <div class="form-group mt-3">
						                <label>Name</label>
										{!!Form::text('name',@Auth::user()->name, array('id'=>'name','required','class'=>'form-control'))!!}
                                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
						                
						            </div>
						        </div>
						        <div class="col-lg-6">
						            <div class="form-group mt-3">
						                <label>Email address</label>
						                {!!Form::email('email',@Auth::user()->email, array('id'=>'email','required','class'=>'form-control'))!!}
                                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
						            </div>
						        </div>
						        <div class="col-lg-12">
						            <div class="form-group mt-3">
						                <label>Subject</label>
										{!!Form::text('subject',null, array('id'=>'subject','class'=>'form-control'))!!}
                                        @if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
						            </div>
						        </div>
						    </div>
						    <div class="row">
						        <div class="col-lg-12">
						            <div class="form-group mt-3">
						                <label>Message</label>
										{!!Form::textarea('message',null, array('id'=>'message','required','class'=>'form-control'))!!}
						            </div>
						        </div>
						    </div>
						    <div class="row">
						        <div class="col-lg-12 mt-4 text-end">
						            <input type="submit" class="btn btn-custom" value="Send Message" />
						        </div>
						    </div>
						 {!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Contact Form -->

	<!-- Start Contact Detail -->
	<section class="section contact_detail_bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="contact_detail mt-3 text-center">
						<div class="contact_icon">
							<i class="mdi mdi-email"></i>
						</div>
						<div class="contact_name mt-3">
							<h5 class="fw-bold mb-2">Email</h5>
							<p class="text-muted">{{@Helper::setting()->email}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact_detail mt-3 text-center">
						<div class="contact_icon">
							<i class="mdi mdi-phone"></i>
						</div>
						<div class="contact_name mt-3">
							<h5 class="fw-bold mb-2">Telephone</h5>
							<p class="text-muted">+ {{@Helper::setting()->phone}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact_detail mt-3 text-center">
						<div class="contact_icon">
							<i class="mdi mdi-watch"></i>
						</div>
						<div class="contact_name mt-3">
							<h5 class="fw-bold mb-2">Business Hours</h5>
							<p class="text-muted mb-0">Sunday To Friday</p>
							<p class="text-muted">10:00am To 6:300pm (BDT)</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Contact Detail -->

@endsection
@push('js')


@endpush