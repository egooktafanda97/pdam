<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PDAM Tirta Bening</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- plugin css -->
    <link href="{{ asset('admin-template/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('admin-template/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    
    <!-- Icons Css -->
    <link href="{{ asset('admin-template/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- App Css-->
    <link href="{{ asset('admin-template/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}">
    
    <!-- CSS Libraries -->
    @yield('library_style')

    <!-- Custom CSS -->
    @yield('custom_style')
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.header')
        
        <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- start page title -->
                <div class="page-title-box">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            @yield('breadcrumb')
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="container-fluid">
                    <div class="page-content-wrapper">
                        @yield('content')
                        {{ $slot ?? '' }}
                    </div>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @yield('modal')

    <!-- JAVASCRIPT -->
    <script src="{{ asset('admin-template/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/setup-ajax.js') }}"></script>

    <!-- JS Libraries -->
    @yield('library_script')

    <!-- App js -->
    <script src="{{ asset('admin-template/assets/js/app.js') }}"></script>
    
    @yield('custom_script')
</body>
</html>
