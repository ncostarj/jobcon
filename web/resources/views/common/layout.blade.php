<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="refresh" content="3600" >
        <title>{{ config('app.name') }} @yield('title')</title>

		<link rel="icon" type="image/x-icon" href="{{ asset('favicon-16x16.png') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
		<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/calendars/calendar-1/assets/css/calendar-1.css">

		<style type="text/css">
			.closed {
				width: 100px !important;
			}
		</style>
		@yield('style')
    </head>

    <body class="" data-bs-theme="dark">

		<div class="container-fluid">
			<div class="row">

				<div class="menu col-2 closed">
					@include('common.menu')
				</div>

				<div class="col">
					@yield('content')
				</div>
			</div>
		</div>
    </body>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/vue/vue.global.js') }}"></script>
	<script type="text/javascript">
		// const toastLiveExample = document.getElementById('liveToast')
		// if (toastTrigger) {
		// 	const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
		// 	toastTrigger.addEventListener('click', () => {
		// 		toastBootstrap.show();
		// 	 })
		// }

		function toggleMenu() {
			let menu = document.querySelector('.menu');

			if(menu.classList.value.includes('closed')) {
				menu.classList.remove('closed');
			} else {
				menu.classList.add('closed');
			}
		}
	</script>

    @yield('script')
</html>
