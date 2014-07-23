<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Base</title>
    {{ HTML::script('assets/js/jquery.js') }}
    {{ HTML::script('assets/js/modernizr.js') }}
    {{ HTML::script('assets/js/detectizr.js') }}
    {{ HTML::script('assets/js/scripts.js') }}
    <script type="text/javascript">
        var APP_URI = '{{ URL::to('/'); }}';
    </script>
</head>
<body>
    @yield('content')
    @include('includes.facebook')
</body>
</html>