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
    <script>
    let swalInterval = null;

    window.addEventListener('swal', (e) => {
        const payload = e?.detail ?? {};
        const first = Array.isArray(payload) ? (payload[0] ?? {}) : payload;
        const action = first?.action;
        
        if (action === 'start') {
        // Si une ancienne popup existe, on nettoie d'abord
        try {
            if (typeof Swal.isVisible === 'function' && Swal.isVisible()) Swal.close();
        } catch (_) {}
        if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

        Swal.fire({
            icon: first.icon || 'info',
            title: first.title || 'Vérification…',
            html: `
            <div>
                <div>Merci de confirmer votre paiement en tapant *105# .</div>
                <div>Temps restant : <b id="swal-remaining"></b> s</div>
            </div>
            `,
            timer: Number(first.timer) || 30000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
            const html = Swal.getHtmlContainer();
            const $b = html ? html.querySelector('#swal-remaining') : null;

            // Par sécurité: clear avant de recréer
            if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

            swalInterval = setInterval(() => {
                const left = typeof Swal.getTimerLeft === 'function' ? Swal.getTimerLeft() : null;
                if (left != null && $b) $b.textContent = Math.ceil(left / 1000);
            }, 100);
            },
            willClose: () => {
            if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }
            }
        });
        return;
        }

        if (action === 'status') {
        // 1) Stoppe TOUJOURS le timer et nettoie l’interval
        try {
            if (typeof Swal.isTimerRunning === 'function' && Swal.isTimerRunning()) {
            if (typeof Swal.stopTimer === 'function') Swal.stopTimer();
            }
        } catch (_) {}
        if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

        // 2) Si demandé, on ferme directement (utile pour "annulé")
        if (first.close === true) {
            try { if (typeof Swal.close === 'function') Swal.close(); } catch (_) {}
            return;
        }

        // 3) Sinon on met à jour la popup (sans timer)
        if (typeof Swal.isVisible === 'function' && Swal.isVisible()) {
            Swal.update({
            icon: first.icon || 'info',
            title: first.title || '',
            html: first.text ? `<p>${first.text}</p>` : '',
            showConfirmButton: true,
            timer: undefined,
            timerProgressBar: false,
            allowOutsideClick: true,
            allowEscapeKey: true,
            });
        } else {
            Swal.fire({
            icon: first.icon || 'info',
            title: first.title || '',
            text: first.text || '',
            showConfirmButton: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            });
        }
        }
    });
    </script>
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