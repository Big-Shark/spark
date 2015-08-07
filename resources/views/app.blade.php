<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href='//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    @yield('styles', '')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.8/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.10/vue-resource.min.js"></script>

    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = "{{ csrf_token() }}";
    </script>

    @if (Auth::user())
        <script>
            var USER_ID = {{ Auth::id() }};

            @if (Laravel\Spark\Spark::usingTeams() && Auth::user()->hasTeams())
                var CURRENT_TEAM_ID = {{ Auth::user()->currentTeam->id }};
            @else
                var CURRENT_TEAM_ID = null;
            @endif
        </script>
    @endif

    @yield('scripts', '')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="spark-app">
        <!-- Navigation -->
        @include('spark::nav')

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('spark::footer')

        <!-- Footer Scripts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

        @yield('scripts.footer', '')

        <script src="/js/app.js"></script>
    </script>
</body>
</html>
