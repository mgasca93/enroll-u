<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>App | @yield('title', 'Enroll-u')</title>

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

  @include('partials.header-dashboard')
  @include('partials.navbar-dashboard')

  <main id="main" class="main">
    @yield('content')
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="https://mariogasca.com/">Mario Gasca</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>