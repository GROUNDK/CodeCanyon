<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $general->sitename($page_title) }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/fontawesome-all.min.css') }}">
    <!-- nice-select css -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/nice-select.css') }}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/bootstrap.min.css') }}">
    <!-- swipper css link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/swiper.min.css') }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ getImage('assets/images/logoIcon/favicon.png') }}"/>
    <!-- icon css -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/themify.css') }}">

    <!-- animate.css -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/animate.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/style.css') }}">
    @stack('style-lib')

    <link rel="stylesheet" type="text/css" href="{{ asset($activeTemplateTrue ."css/color.php?color1=$general->base_color&color2=$general->secondary_color") }}">

    @include('partials.seo')

    @stack('style')
</head>
<body>
    @include($activeTemplate .'partials.preloader')
    @include($activeTemplate .'partials.header')
    @php
        $banner = getContent('banner.content', true)->data_values;
    @endphp

    @yield('content')
    @include($activeTemplate .'partials.footer')

<!-- jquery -->
<script src="{{asset($activeTemplateTrue.'/js/jquery-3.3.1.min.js')}}"></script>
<!-- migarate-jquery -->
<script src="{{asset($activeTemplateTrue.'/js/jquery-migrate-3.0.0.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset($activeTemplateTrue.'/js/bootstrap.min.js')}}"></script>
<!-- nice-select js-->
<script src="{{asset($activeTemplateTrue.'/js/jquery.nice-select.js')}}"></script>
<!-- swipper js -->
<script src="{{asset($activeTemplateTrue.'/js/swiper.min.js')}}"></script>
<!--plugin js-->
<script src="{{asset($activeTemplateTrue.'/js/plugin.js')}}"></script>
<!-- wow js file -->
<script src="{{asset($activeTemplateTrue.'/js/wow.min.js')}}"></script>
<!-- main -->
<script src="{{asset($activeTemplateTrue.'/js/main.js')}}"></script>



@stack('script-lib')
    @include($activeTemplate.'partials.notify')
@stack('script')

</body>
</html>
