@if (Spark::isDisplayingSettingsScreen())
	{{-- This Dropdown Is For Spark Settings Sreens - Vue Based --}}
	@include('spark::nav.spark.dropdown')
@else
	{{-- This Dropdown Is For Other App Screens - Blade Based --}}
	@include('spark::nav.app.dropdown')
@endif
