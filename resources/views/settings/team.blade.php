@extends('spark::app')

@section('content')
<!-- Team Settings Dashboard -->
<div class="container spark-screen">
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
										<i class="fa fa-btn {{ $tab->icon }}"></i>&nbsp;{{ $tab->name }}
									</a>
								</li>
							@endforeach
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
