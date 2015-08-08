<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="//fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    @yield('styles', '')

    <!-- CSRF Token -->
    <script>
        var CSRF_TOKEN = '{{ csrf_token() }}';
        var USER_ID = {!! Auth::user() ? Auth::id() : 'null' !!};
    </script>

    <script>
        @if (Auth::user() && Laravel\Spark\Spark::usingTeams() && Auth::user()->hasTeams())
            var CURRENT_TEAM_ID = {{ Auth::user()->currentTeam->id }};
        @else
            var CURRENT_TEAM_ID = null;
        @endif
    </script>

    @yield('scripts', '')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="spark-app" v-cloak>
        <!-- Navigation -->
        @include('spark::nav')

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('spark::footer')

        <!-- JavaScript Application -->
        <script src="/js/app.js"></script>
</body>
</html>
