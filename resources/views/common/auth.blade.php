<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} @yield('title')</title>

		<link rel="icon" type="image/x-icon" href="{{ asset('favicon-16x16.png') }}">
        <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

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
				<div class="col mt-5">
					@yield('content')
				</div>
			</div>
		</div>
    </body>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/vue/vue.global.js') }}"></script>

    @yield('script')
</html>
