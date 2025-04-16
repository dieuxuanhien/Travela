<head>
    <meta charset="utf-8">
    <title>Travela - Tourism Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
{{--    @vite(['resources/css/bootstrap.min.css', 'resources/css/style.css'])--}}
    <link rel="icon" href="{{asset('/frontend/images/logo2.png')}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/frontend/css/base.css')}}?v={{time()}}" rel="stylesheet">
    <link href="{{asset('/frontend/css/bootstrap.min.css')}}?v={{time()}}" rel="stylesheet">
    <link href="{{asset('/frontend/css/style.css')}}?v={{time()}}" rel="stylesheet">
    <link href="{{asset('/lib/fontawesome/css/all.css')}}?v={{time()}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('lib/slick/slick/slick.css')}}?v={{time()}}"/>
{{--    // Add the new slick-theme.css if you want the default styling--}}
    <link rel="stylesheet" type="text/css" href="{{asset('lib/slick/slick/slick-theme.css')}}?v={{time()}}"/>

</head>
