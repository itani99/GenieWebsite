<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <!-- Styles -->
    <link href="{{asset('css/materialize.min.css')}}" rel="stylesheet">

    @stack('css')

</head>
<body>


<div id="app">
    <nav class="black">
        <div class="nav-wrapper">
            <a href="#!" class="brand-logo">Logo</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="">Register</a></li>
                <li><a href="">Abouts us</a></li>
                <li><a href="">Our Team</a></li>
                <li><a href="">Contact us</a></li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li><a href="">Register</a></li>
        <li><a href="">Register</a></li>
        <li><a href="">Register</a></li>
        <li><a href="">Register</a></li>
    </ul>
        @yield('content')
    </div>
@stack('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="/public/js/materialize.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $('.sidenav').sidenav();
    });


</script>
</body>
</html>
