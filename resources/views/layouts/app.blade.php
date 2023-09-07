<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('backend/assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{ asset('backend/assets/img/agency.ico')}}">
	<meta property="og:site_name" content="Agency Management">
    <meta property="fb:app_id" content="209201484717774" />
    <link rel="canonical" href="{{ url()->current() }}">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--============================= Author:Zahidul & Tanzir Nur =============================-->
    <!--============================= Developer: zahidul1994@github.com =============================-->
    <!--============================= Email: mjahid1990@gmail.com =============================-->
    {!! SEO::generate() !!}
	{{-- @laravelPWA --}}
    <!-- Favicon Icon -->
     <!-- Font Awesome Icons -->
        <!-- Favicon Icon -->
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">

	<!-- Bootstrap Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css')}}">
<!-- Tiny Slider -->
    <link rel="stylesheet" href="{{ asset('frontend/css/prism.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/css/tiny-slider.css')}}">

	<!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css')}}">
	
    @stack('css')
</head>
<body>
	<!-- Start Navbar -->
	<nav class="navbar navbar-expand-lg custom-nav navbar-light fixed-top sticky">
	    <div class="container">
	        <a class="navbar-brand logo"  href="{{url('/')}}">
                <img src="{{@Helper::setting()->logo}}" alt="logo" width="80px" height="80px" class="img-fluid logo-dark">
            </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	            <span class="mdi mdi-menu"></span>
	        </button>
	        <div class="collapse navbar-collapse" id="navbarNav">
	            <ul class="navbar-nav ms-auto" id="main_nav">
	                <li class="nav-item">
	                    <a class="nav-link" href="#home">Home</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="#features">Features</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="#client">Client</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="#price">Price</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="#contact">Contact</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="{{url('login')}}">Login</a>
	                </li>
	            </ul>
	        </div>
	    </div>
	</nav>
	<!-- End Navbar -->
    @yield('content')


	<div class="footer_main_divider"></div>

	<!-- Start Footer -->
	<footer class="footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="footer_logo mt-3">
						<img src="{{@Helper::setting()->logo}}" alt="logo-dark" class="img-fluid d-block">
						<p class="text-muted mt-4">2018 © {{date('Y')}}  {{@Helper::setting()->title}}. Develop by <a href="{{('/')}}" target="_blank">SohiBD </a>.</p>
						<h5 class="fw-bold mt-4">Follow Us</h5>
						<ul class="list-inline fot_social mt-4">
							<li class="list-inline-item"><a href="{{@Helper::setting()->facebook}}" class="social-icon"><i class="mdi mdi-facebook"></i></a></li>
							<li class="list-inline-item"><a href="{{@Helper::setting()->twitter}}" class="social-icon"><i class="mdi mdi-twitter"></i></a></li>
							<li class="list-inline-item"><a href="{{@Helper::setting()->instagram}}" class="social-icon"><i class="mdi mdi-linkedin"></i></a></li>
							<li class="list-inline-item"><a href="{{@Helper::setting()->facebook}}" class="social-icon"><i class="mdi mdi-google-plus"></i></a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="footer_menu mt-3">
						<h5 class="fw-bold">Our Product</h5>
						<ul class="list-unstyled footer_menu_list mb-0 mt-4">
							<li><a href="#">Getbootstrap</a></li>
							<li><a href="#">Wordpress</a></li>
							<li><a href="#">Shopify</a></li>
							<li><a href="#">React</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="footer_menu mt-3">
						<h5 class="fw-bold">Company</h5>
						<ul class="list-unstyled footer_menu_list mb-0 mt-4">
							<li><a href="#">About</a></li>
							<li><a href="#">Terms</a></li>
							<li><a href="#">Privacy Policy</a></li>
							<li><a href="#">Careers</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="footer_menu mt-3">
						<h5 class="fw-bold">Services</h5>
						<ul class="list-unstyled footer_menu_list mb-0 mt-4">
							<li><a href="#">Documentation</a></li>
							<li><a href="#">Design</a></li>
							<li><a href="#">Themes</a></li>
							<li><a href="#">Illustrations</a></li>
							<li><a href="#">UI Kit</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="footer_menu mt-3">
						<h5 class="fw-bold">More</h5>
						<ul class="list-unstyled footer_menu_list mb-0 mt-4">
							<li><a href="#">Documentation</a></li>
							<li><a href="#">License</a></li>
							<li><a href="#">Changelog</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- End Footer -->
 <!-- Back To Top -->    
 <a href="#" class="back_top"><i class="mdi mdi-arrow-up"></i></a>


</div>

	<script src="{{ asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>
<!-- Tiny Slider Js -->
    <script src="{{ asset('frontend/js/tiny-slider.js')}}"></script>
<!-- Custom Js -->
	<script src="{{ asset('frontend/js/custom.js')}}"></script>
	<script src="{{ asset('frontend/js/counter.js')}}"></script>
   
		
	@stack('js')
   
</body>
</html>