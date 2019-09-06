<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME','eKantor Application')}}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset('images/logo.png')}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset('template/bsbm/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset('template/bsbm/plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset('template/bsbm/plugins/animate-css/animate.css')}}" rel="stylesheet" />

    <link href="{{asset('template/bsbm/plugins/dropzone/dropzone.css')}}" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{asset('template/bsbm/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="{{asset('template/bsbm/plugins/morrisjs/morris.css')}}" rel="stylesheet" />

    <!-- Sweet Alert Css -->
    <link href="{{asset('template/bsbm/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="{{asset('template/bsbm/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">

    <!-- Custom Css -->
    <link href="{{asset('template/bsbm/css/style.css')}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset('template/bsbm/css/themes/all-themes.css')}}" rel="stylesheet" />

    <style type="text/css">
    .invalid-feedback {
        color: red;
    }
    .dropdown-menu ul.menu li {
        list-style-type: none;
    }
    body {
        background:#FFF;
    }
    </style>
</head>

<body class="theme-red">

    <section class="content" style="margin: 0px;">
        @yield('content')
    </section>

    <!-- Jquery Core Js -->
    <script src="{{asset('template/bsbm/plugins/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset('template/bsbm/plugins/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Bootstrap Select Css -->
    <link href="{{asset('template/bsbm/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('template/bsbm/plugins/node-waves/waves.js')}}"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="{{asset('template/bsbm/plugins/jquery-validation/jquery.validate.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{asset('template/bsbm/js/admin.js')}}"></script>
    <script src="{{asset('template/bsbm/js/pages/forms/form-validation.js')}}"></script>

    @yield('script')
</body>

</html>