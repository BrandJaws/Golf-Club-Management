<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Dashboard - Home</title>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- for ios 7 style, multi-resolution icon of 152x152 -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-barstyle"
	content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Flatkit">
<!-- for Chrome on Android, multi-resolution icon of 196x196 -->
<meta name="mobile-web-app-capable" content="yes">
@include ('admin.__partials.css-assets')
<script src="{{asset('/libs/jquery/jquery/dist/jquery.js')}}"></script>
<script src="{{asset('/libs/vuejs/vue.js')}}"></script>
</head>
<body>
	<div class="app" id="app">

		<!-- ############ LAYOUT START-->

		<!-- aside -->
		@include('admin.__partials.left-nav')
		<!-- / -->

		<!-- content -->
		@yield('main')

		<!-- / -->
	</div>
	@include ('admin.__partials.js-assets')
</body>
</html>
