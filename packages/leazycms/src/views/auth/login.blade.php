<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('backend/css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Masuk - {{get_option('site_title')}}</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta property="og:title" content="Masuk - {{get_option('site_title')?? 'Web Title'}}" />
<meta property="og:image" content="{{url(get_option('icon')??noimage())}}" />
<meta property="og:site_name" content="{{get_option('site_title') ?? 'Web Title'}}" />
<meta property="og:description" content="Masuk Sebagai Admin / Operator" />
<style>
body {
    background-image: linear-gradient(to right bottom, #00963d, #008162, #006972, #005068, #1c374c, #1c3245, #1c2e3d, #1b2936, #003443, #004045, #004a39, #005221);
}

</style>

  </head>
  <body>

    <section class="login-content" >
      <div class="login-box" style=background:transparent;box-shadow:none;width:100%">

        <form method="POST"  style="width:300px;margin-left:auto;margin-right:auto"  action="{{route('login.submit') }}">
          @csrf
          <center>
            <img height="80" src="{{!empty(get_option('logo')) ? url(get_option('logo')) : noimage()}}">
            <br>
            <br>
            <h4 class="text-white">Masuk</h4>

            @if(get_option('site_maintenance')=='Y')
            <p class="badge badge-danger">Modus Perbaikan Aktif</p>
            @endif
          </center>
                @if (session()->has('error'))
                <div class="alert alert-dismissible alert-danger">
                  <button class="close" type="button" data-dismiss="alert">×</button>
                  {{session()->get('error')}}
                </div>
                @endif

          <div class="form-group">
            <label class="control-label" style="color:#f5f5f5">Nama Pengguna</label>
                <input id="username" placeholder="Username" type="text" class="form-control form-control-lg " name="username" required autocomplete="username" autofocus>
          </div>
          <div class="form-group">
            <label class="control-label" style="color:#f5f5f5">Kata Sandi</label>
                <input id="password" placeholder="*****" type="password" class="form-control form-control-lg " name="password" required autocomplete="current-password" autofocus>
          </div>
          <div class="form-group">
            <img src="{{ $captcha }}" alt=""> <input type="text" name="captcha" placeholder="Masukkan Kode..." required  style="border:none;float:right;height: 40px;">
          </div>

          <div class="form-group btn-container">
            <button class="btn btn-warning btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>MASUK</button>
          </div>

        </form>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{url('backend/js/popper.min.js')}}"></script>
    <script src="{{url('backend/js/bootstrap.min.js')}}"></script>
    <script src="{{url('backend/js/main.js')}}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{url('backend/js/plugins/pace.min.js')}}"></script>
    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>
