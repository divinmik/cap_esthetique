<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>{{ ($title_page) ? $title_page:'' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CAP CONGO ESTHETIQUE" name="description" />
    <meta content="mikangamani nsimba" name="author" />
    <!-- App favicon -->
    <link rel="shortcut PNG" href="/admin/assets/images/logo-dark.png">

    
    <link href="/admin/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        <!-- buttons datatable -->
        <link href="/admin/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable -->
        <link href="/admin/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

        
        <!-- dark layout js -->
        <script src="/admin/assets/js/pages/layout.js"></script>

        <!-- Bootstrap Css -->
        <link href="/admin/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- simplebar css -->
        <link href="/admin/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">
        <!-- App Css-->
        <link href="/admin/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    @livewireStyles()
   
</head>

<body>

<div id="layout-wrapper">

    
    <!-- Start topbar -->
   @include('components.layouts.topbar')
    <!-- End topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    @include('components.layouts.siderbar')
    <!-- Left Sidebar End -->


    <!-- Start right Content here -->

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                           <h4 class="fs-16 fw-semibold mb-1 mb-md-2">Salut, <span class="text-primary">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/dashboard">CAP ESTHETIQUE</a></li>
                                    <li class="breadcrumb-item active">{{ ($title_page) ? $title_page:'' }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $slot }}
            </div>
            <!-- end container-fluid -->
        </div>
        <!-- End Page-content -->

        @include("components.layouts.footer")

    </div>
    <!-- end main content-->
</div>
<!-- end layout-wrapper -->


<div class="custom-setting bg-primary pe-0 d-flex flex-column rounded-start">
    <button type="button" class="btn btn-wide border-0 text-white fs-20 avatar-sm rounded-end-0" id="light-dark-mode">
        <i class="mdi mdi-brightness-7 align-middle"></i>
        <i class="mdi mdi-white-balance-sunny align-middle"></i>
    </button>
    <button type="button" class="btn btn-wide border-0 text-white fs-20 avatar-sm" data-toggle="fullscreen">
        <i class="mdi mdi-arrow-expand-all align-middle"></i>
    </button>
</div>




    @include('sweetalert::alert')
    @include('sweetalert2::index')
    @livewireScripts()
    @vite('resources/js/app.js')
    @stack('scripts')
     <script src="/admin/assets/libs/jquery/jquery.min.js"></script>
        <script src="/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/admin/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/admin/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/admin/assets/libs/node-waves/waves.min.js"></script>

        <!-- Required datatable js -->
        <script src="/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/admin/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

        <!-- buttons examples -->
        <script src="/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="/admin/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="/admin/assets/libs/jszip/jszip.min.js"></script>
        <script src="/admin/assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="/admin/assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <!-- Responsive examples -->
        <script src="/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/admin/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>

        <!-- Datatable init js -->
        <script src="/admin/assets/js/pages/datatables-extension.init.js"></script>

        <script src="/admin/assets/js/app.js"></script>

</body>
</html>