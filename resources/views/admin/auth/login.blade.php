<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<title>Golf - Login</title>
<meta name="description"
	content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- for ios 7 style, multi-resolution icon of 152x152 -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-barstyle"
	content="black-translucent">
<link rel="apple-touch-icon" href="{{asset('/assets/images/logo.png')}}">
<meta name="apple-mobile-web-app-title" content="Flatkit">
<!-- for Chrome on Android, multi-resolution icon of 196x196 -->
<meta name="mobile-web-app-capable" content="yes">
<link rel="shortcut icon" sizes="196x196" href="{{asset('/assets/images/logo.png')}}">

<!-- style -->
<link rel="stylesheet" href="{{asset('/assets/animate.css/animate.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/glyphicons/glyphicons.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/font-awesome/css/font-awesome.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/material-design-icons/material-design-icons.css')}}" type="text/css" />

<link rel="stylesheet" href="{{asset('/assets/bootstrap/dist/css/bootstrap.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/styles/app.min.css')}}">
<link rel="stylesheet" href="{{asset('/assets/styles/font.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('/assets/styles/custom-style.css')}}" type="text/css" />
</head>
<body>
	<div class="app" id="app">
		<div class="login-bg-main">
			<!-- ############ LAYOUT START-->
			<div class="center-block w-xxl w-auto-xs p-y-md mrg-t">
				<div class="p-a-md box-color r box-shadow-z1 text-color m-a">
					<div class="m-b text-center logo-login">
						<img src="{{asset('/images/logo.png')}}" alt="logo-login">
					</div>
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                        {{ csrf_field() }}
						<div class="md-form-group float-label {{ $errors->has('email') ? ' has-error' : '' }}">
							<input type="email" name="email" class="md-input" ng-model="user.email" value="{{ old('email') }}" required autofocus> 
							<label>Email</label> 
							@if ($errors->has('email')) 
							<span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> 
							@endif
						</div>
						<div class="md-form-group float-label {{ $errors->has('password') ? ' has-error' : '' }}">
							<input type="password" name="password" class="md-input" ng-model="user.password" required>
							<label>Password</label>
						</div>
						<div class="m-b-md">
							<label class="md-check"> <input type="checkbox">
								<i class="primary"></i> Keep me signed in
							</label>
						</div>
						<button type="submit" class="btn-def btn btn-block p-x-md">Sign in</button>
					</form>
				</div>
			</div>
			<!-- ############ LAYOUT END-->
		</div>
	</div>
	<script src="scripts/app.html.js"></script>
</body>

<!-- Mirrored from flatfull.com/themes/flatkit/html/signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Oct 2016 14:52:26 GMT -->
</html>
