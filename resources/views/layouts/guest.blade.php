<!doctype html>
<html lang="en">
<head>
    @php
        // récupère la valeur fournie par la section 'bg_img' sinon fallback
        $bg = $bg_img;
        // si $bg commence par http(s) ou / on l'utilise tel quel sinon on passe par asset()
        $bgUrl = $bg ? (\Illuminate\Support\Str::startsWith($bg, ['http://','https://','/']) ? $bg : asset($bg)) : asset('admin/assets/images/bg.jpg');
        // Dégradé : du noir transparent (en haut) au noir plus opaque (en bas). Ajuste les couleurs/alphas si besoin.
        $gradient = 'linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.55))';
    @endphp

    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CAP CONGO ESTHETIQUE" name="description" />
    <meta content="mikangamani nsimba" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/logo-dark.png') }}">

    
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
    <style>
        .authentication-bg {
            /* $gradient doit contenir quelque chose comme: linear-gradient(90deg, rgba(0,0,0,0.5), rgba(0,0,0,0.2)) */
            background-repeat: no-repeat;
            min-height: 100vh;        /* plus flexible que height:100vh */
            background-size: cover;
            background-position: center;
            background-image: url('{{ asset($bg) }}');
        }
    </style>
    @livewireStyles()
</head>

<body class="relative h-screen w-screen overflow-hidden">
   


     <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            {{ $slot }}
        </div>
    </div>

    @include('sweetalert::alert')
    @include('sweetalert2::index')
    @livewireScripts()
    @vite('resources/js/app.js')
    @stack('scripts')

   <!-- JAVASCRIPT -->
    <script src="/admin/assets/libs/jquery/jquery.min.js"></script>
    <script src="/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/admin/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/admin/assets/libs/node-waves/waves.min.js"></script>

    <script src="/admin/assets/js/app.js"></script>

</body>

</html>
