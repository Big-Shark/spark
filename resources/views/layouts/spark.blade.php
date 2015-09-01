<!DOCTYPE html>
<html lang="en">
<head>
    @include('spark::layouts.common.head')
</head>
<body>
    <div id="spark-app" v-cloak>
        <!-- Navigation -->
        @include('spark::nav')

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('spark::common.footer')

        <!-- JavaScript Application -->
        <script src="/js/app.js"></script>
    </div>
</body>
</html>
