<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" sizes="16x16" href="{{ asset('Escudo_UNE.png') }}">
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="https://apps.une.edu.pe/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://apps.une.edu.pe/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="https://apps.une.edu.pe/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css"
        href="https://apps.une.edu.pe/assets/bootstrap-fileupload/bootstrap-fileupload.css" />

    <!--dynamic table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link href="https://apps.une.edu.pe/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://apps.une.edu.pe/assets/data-tables/DT_bootstrap.css" />
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" /> <!--NEW-->

    <!--right slidebar-->
    <link href="https://apps.une.edu.pe/css/slidebars.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="https://apps.une.edu.pe/css/style.css" rel="stylesheet">
    <link href="https://apps.une.edu.pe/css/style-responsive.css" rel="stylesheet" />

    <!--bootstrap switcher-->
    <link rel="stylesheet" type="text/css"
        href="https://apps.une.edu.pe/assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css" />
    <!-- switchery-->
    <link rel="stylesheet" type="text/css" href="https://apps.une.edu.pe/assets/switchery/switchery.css" />

    <!-- alert toastr.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- alert sweetalert2.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.17/sweetalert2.min.css"
        integrity="sha512-CJ5goVzT/8VLx0FE2KJwDxA7C6gVMkIGKDx31a84D7P4V3lOVJlGUhC2mEqmMHOFotYv4O0nqAOD0sEzsaLMBg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://apps.une.edu.pe/css/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!--CSS MENU DINAMICO-->
    <link href="https://apps.une.edu.pe/assets/back/css/intranetune.css" rel="stylesheet">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" href="https://apps.une.edu.pe/css/jquery.steps.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    {{-- Nuevo --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    @stack('styles')
</head>

<body>
    <div id="app">
        <div class="container mt-5">
            @yield('content')
        </div>
    </div>

    <script src="https://apps.une.edu.pe/js/jquery.js"></script>
    <!-- script src="js/jquery-ui-1.9.2.custom.min.js"></script> -->
    <script src="https://apps.une.edu.pe/js/bootstrap.bundle.min.js"></script>
    <script class="include" type="text/javascript" src="https://apps.une.edu.pe/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="https://apps.une.edu.pe/js/jquery.scrollTo.min.js"></script>
    <script src="https://apps.une.edu.pe/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!-- <script type="text/javascript" language="javascript" src="assets/advanced-datatable/media/js/jquery.dataTables.js">
    </script>
    <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script> -->
    <script src="https://apps.une.edu.pe/js/respond.min.js"></script>
    <script type="text/javascript" src="https://apps.une.edu.pe/assets/bootstrap-fileupload/bootstrap-fileupload.js">
    </script>

    <script type="text/javascript" src="https://apps.une.edu.pe/assets/bootstrap-inputmask/bootstrap-inputmask.js"></script>

    <!--right slidebar-->
    <script src="https://apps.une.edu.pe/js/slidebars.min.js"></script>

    <!--dynamic table initialization -->
    <script src="https://apps.une.edu.pe/js/dynamic_table_init.js"></script>

    <!--common script for all pages-->
    <script src="https://apps.une.edu.pe/js/common-scripts.js"></script>

    <!--bootstrap-switch-->
    <script src="https://apps.une.edu.pe/assets/bootstrap-switch/static/js/bootstrap-switch.js"></script>

    <!--bootstrap-switch-->
    <script src="https://apps.une.edu.pe/assets/switchery/switchery.js"></script>

    <!--Data tables Combinado-->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script> <!--NEW-->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> <!--NEW-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> <!--NEW-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> <!--NEW-->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> <!--NEW-->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script> <!--NEW-->

    <script type="text/javascript" src="https://apps.une.edu.pe/assets/data-tables/DT_bootstrap.js"></script>

    <!--Plugins -->
    <script src="https://apps.une.edu.pe/assets/back/libs/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="https://apps.une.edu.pe/assets/back/libs/jquery-validation/dist/localization/messages_es.js"></script>
    <!--Custom JavaScript -->
    <script src="https://apps.une.edu.pe/assets/back/js/intranetune.js"></script>

    <!--script for this page-->
    <script src="https://apps.une.edu.pe/js/form-validation-script.js"></script>

    <!-- alert toastr.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://apps.une.edu.pe/assets/jquery.stepy.js"></script>
    <script type="text/javascript" src="https://apps.une.edu.pe/js/jquery-ui.min.js"></script>
    <!-- alert sweetalert2.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.17/sweetalert2.min.js"
        integrity="sha512-Kyb4n9EVHqUml4QZsvtNk6NDNGO3+Ta1757DSJqpxe7uJlHX1dgpQ6Sk77OGoYA4zl7QXcOK1AlWf8P61lSLfQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://apps.une.edu.pe/js/jquery.steps.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://apps.une.edu.pe/assets/ckeditor/ckeditor.js"></script>



    {{-- Nuevo --}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    @stack('scripts')
</body>

</html>
