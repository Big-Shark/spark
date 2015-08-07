@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	<script>
		TEAM_ID = {{ $team->id }};
	</script>

	@include('spark::scripts.common')

	<script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@append

<!-- Footer Scripts (Components) -->
@section('scripts.footer.components')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
	</script>
@append

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/settings/team.js')) !!}
	</script>
@append

<!-- Main Content -->
@section('content')
<div id="spark-team-settings-screen" class="container spark-screen">
	<div class="row">
		<!-- Tabs -->
		<div class="col-md-4">
			<div class="panel panel-default panel-flush">
				<div class="panel-heading">
					Team Settings ({{ $team->name }})
				</div>

				<div class="panel-body">
					<div class="spark-settings-tabs">
						<ul class="nav spark-settings-tabs-stacked" role="tablist">
							@foreach (Laravel\Spark\Spark::teamSettingsTabs()->displayable($team, Auth::user()) as $tab)
								<li role="presentation"{!! $tab->key === $activeTab ? ' class="active"' : '' !!}>
									<a href="#{{ $tab->key }}" aria-controls="{{ $tab->key }}" role="tab" data-toggle="tab">
										<i class="fa fa-btn fa-fw {{ $tab->icon }}"></i>&nbsp;{{ $tab->name }}
									</a>
								</li>
							@endforeach

							<li role="presentation" role="tab">
								<a href="/settings?tab=teams">
									<i class="fa fa-btn fa-fw fa-search"></i>&nbsp;<strong>View All Teams</strong>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab Panes -->
		<div class="col-md-8">
			<div class="tab-content">
				@foreach (Laravel\Spark\Spark::teamSettingsTabs()->displayable($team, Auth::user()) as $tab)
					<div role="tabpanel" class="tab-pane{{ $tab->key == $activeTab ? ' active' : '' }}" id="{{ $tab->key }}">
						@include($tab->view)
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
