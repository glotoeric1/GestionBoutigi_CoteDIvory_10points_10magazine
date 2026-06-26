@php
    $entreprise = 'App - Boutiqi';
    $logo = asset('backend/images/default_logo.jpeg');
    $setting = \App\Models\settings::first();

    if (!empty($setting)) {
        $entreprise = $setting->app_name;
        $logo = $setting->logo;
        $title = $setting->title;
        $emailApi = $setting->email;
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Boutigi | {{ $entreprise }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
</head>

<body class="p-3">
    <div class="wrpper p-2">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wraper mb-5">
            <!-- Content Header (Page header) -->
            {{-- <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12 text-center">
                            <h1>Fichier introuvable</h1>
                        </div>
                        
                    </div>
                </div><!-- /.container-fluid -->
            </section> --}}

            <!-- Main content -->
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-info">Info</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-info"></i> Oops!</h3>
                        <p>
                            Le fichier ou le document que vous cherchez est indisponible pour le moment.
                        </p>
                    </div>
                </div>
                <!-- /.error-page -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <hr>
        <footer class="main-foote">
            <div class="float-right d-none d-sm-block">
                <b>Version 3.0</b>
            </div>
            <strong>Copyright &copy; SKill Codiing </strong>
        </footer>

    </div>
    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
</body>

</html>
