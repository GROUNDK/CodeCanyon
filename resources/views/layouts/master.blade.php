<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __($general->sitename($page_title ?? '')) }}</title>
    <!-- site favicon -->
    <link rel="shortcut icon" type="image/png" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/all_vendors/css/magnific-popup.css') }}">

    <!-- bootstrap 4  -->
    <link rel="stylesheet" href="{{ asset('assets/all_vendors/css/vendor/bootstrap.min.css') }}">
    <!-- bootstrap toggle css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/bootstrap-toggle.min.css')}}">
    <!-- fontawesome 5  -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/all.min.css')}}">
    <!-- line-awesome webfont -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/line-awesome.min.css')}}">

    @stack('style-lib')

    <!-- custom select box css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/nice-select.css')}}">
    <!-- select 2 css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/select2.min.css')}}">
    <!-- data table css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/datatables.min.css')}}">
    <!-- jvectormap css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/jquery-jvectormap-2.0.5.css')}}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/datepicker.min.css')}}">
    <!-- timepicky for time picker css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/jquery-timepicky.css')}}">
    <!-- bootstrap-clockpicker css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/bootstrap-clockpicker.min.css')}}">
    <!-- bootstrap-pincode css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/vendor/bootstrap-pincode-input.css')}}">
    <!-- dashdoard main css -->
    <link rel="stylesheet" href="{{asset('assets/all_vendors/css/app.css')}}">

    @stack('style')
</head>
<body>

@yield('content')


<!-- jQuery library -->
<script src="{{asset('assets/all_vendors/js/vendor/jquery-3.5.1.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset('assets/all_vendors/js/vendor/bootstrap.bundle.min.js')}}"></script>
<!-- bootstrap-toggle js -->
<script src="{{asset('assets/all_vendors/js/vendor/bootstrap-toggle.min.js')}}"></script>

<!-- slimscroll js for custom scrollbar -->
<script src="{{asset('assets/all_vendors/js/vendor/jquery.slimscroll.min.js')}}"></script>
<!-- custom select box js -->
<script src="{{asset('assets/all_vendors/js/vendor/jquery.nice-select.min.js')}}"></script>


@include('partials.notify')
@stack('script-lib')

<script src="{{ asset('assets/all_vendors/js/nicEdit.js') }}"></script>

<!-- seldct 2 js -->
<script src="{{asset('assets/all_vendors/js/vendor/select2.min.js')}}"></script>
<!-- data-table js -->
<script src="{{asset('assets/all_vendors/js/vendor/datatables.min.js')}}"></script>
<!-- main js -->
<script src="{{asset('assets/all_vendors/js/app.js')}}"></script>

<!-- magnific popup css -->
<script src="{{ asset('assets/all_vendors/js/jquery.magnific-popup.min.js') }}"></script>

{{-- LOAD NIC EDIT --}}
<script>
    (function($){
        "use strict";
        bkLib.onDomLoaded(function() {
            $( ".nicEdit" ).each(function( index ) {
                $(this).attr("id","nicEditor"+index);
                new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
            });
        });
    })(jQuery);
</script>


@stack('script')


</body>
</html>
