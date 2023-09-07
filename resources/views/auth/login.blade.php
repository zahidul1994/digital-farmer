
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('backend/assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{ asset('backend/assets/img/agency.ico')}}">
  <title>
    Login
  </title>
     @laravelPWA
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('backend/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('backend/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('backend/assets/css/argon-dashboard.css?v=2.0.5') }}" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content main-content-bg mt-0">
    <div class="page-header min-vh-100" style="background-image: url('{{@Helper::getSlider()->image}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-7">
            <div class="card border-0 mb-0">
              <div class="card-header bg-transparent">
                <h5 class="text-dark text-center mt-2 mb-3">Sign in</h5>
                
              </div>
              <div class="card-body px-lg-5 pt-0">
              @include('partial.formerror')
                <form role="form" class="text-start" method="post" action="{{ route('login') }}">
                    @csrf
                  <div class="mb-3">
                    <input type="email" class="form-control" value="{{ old('email') }}" required  name="email" placeholder="Email" aria-label="Email">
                  </div>
                 
                  <div class="mb-3">
                    <input type="password" class="form-control"  name="password" placeholder="Password" aria-label="Password">
                  </div>
                 
                  <div class="mb-3">
                    <div class="row">
                      <div class="col-md-4 col-md-text-end mt-2 shadow rounded"> <span id="firstVal" class="form-controll"></span>+<span id="secondVal"> </span>  =  </div>
                      <div class="col-md-8"> <input type="text" required class="form-control" id="sumValue"></div>
                    </div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me </label>   &nbsp;
                    
                  </div>
                  <a href="{{route('password.request')}}"> Forgot Password</a>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100 my-4 mb-2" disabled id="submit">Sign in</button>
                  </div>
                  <div class="mb-2 position-relative text-center">
                    <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                      or
                    </p>
                  </div>
                  
                  <div class="card-footer text-center pt-0 px-sm-4 px-1">
                    <p class="mb-4 mx-auto">
                     Need  account? 
                      <a href="https://agency.sohibd.com/#contact" class="text-primary font-weight-bold">Contact US</a>
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->

  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="{{ asset('backend/assets/js/jquery-3.6.3.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <!-- Kanban scripts -->
  <script src="{{ asset('backend/assets/js/plugins/dragula/dragula.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/plugins/jkanban/jkanban.js') }}"></script>
  <script>
 document.addEventListener('contextmenu', event => event.preventDefault());
           // for first value 
             var firstnum = Math.floor(1 + (Math.random() * Math.pow(8, 1)));
            $('#firstVal').html(firstnum);
               
             // for second value 
             var secondnum = Math.floor(1 + (Math.random() * Math.pow(8, 1)));
            $('#secondVal').html(secondnum);
          var sumValue=firstnum+secondnum;
            $('#sumValue').on('keyup keypress', function(e) {
                typeVal=$(this).val();
                if(sumValue==typeVal){
              $('#submit').prop("disabled", false);
                }
                else{
                    $('#submit').prop("disabled", true);
                }
            ;
        });

   
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('backend/assets/js/argon-dashboard.min.js?v=2.0.5') }}"></script>
</body>

</html>
