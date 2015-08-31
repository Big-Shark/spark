<!-- Laravel Spark Globals -->
<script>
    // Laravel CSRF Token
    window.CSRF_TOKEN = '{{ csrf_token() }}';

    // Current User ID
    window.USER_ID = {!! Auth::user() ? Auth::id() : 'null' !!};

    // Current Team ID
    @if (Auth::user() && Spark::usingTeams() && Auth::user()->hasTeams())
        window.CURRENT_TEAM_ID = {{ Auth::user()->currentTeam->id }};
    @else
        window.CURRENT_TEAM_ID = null;
    @endif
</script>
