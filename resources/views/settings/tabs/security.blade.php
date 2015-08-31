<spark-settings-security-screen inline-template>
	<div id="spark-settings-security-screen">
		<!-- Update Password -->
		@include('spark::settings.tabs.security.password')

		<!-- Two-Factor Authentication -->
		@include('spark::settings.tabs.security.two-factor')
	</div>
</spark-settings-security-screen>
