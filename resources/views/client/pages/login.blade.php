@extends('layouts.client_home')
@section('title','Đăng Nhập')
@section('content')
		<!-- Start Banner Area -->
		<section class="banner-area organic-breadcrumb">
			<div class="container">
				<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
					<div class="col-first">
						<h1>Login/Register</h1>
						<nav class="d-flex align-items-center">
							<a href="{{ route('home') }}">Home<span class="lnr lnr-arrow-right"></span></a>
							<a href="{{ route('login') }}">Login/Register</a>
						</nav>
					</div>
				</div>
			</div>
		</section>
		<!-- End Banner Area -->

		<!--================Login Box Area =================-->
		<section class="login_box_area section_gap">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="login_box_img">
							<img class="img-fluid" src="{{ asset('assets/img/login.jpg') }}" alt="">
							<div class="hover">
								<h4>New to our website?</h4>
								<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
								<a class="primary-btn" href="{{ route('register') }}">Create an Account</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="login_form_inner">
							<h3>Log in to enter</h3>
							
							@if(session('success'))
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									{{ session('success') }}
									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								</div>
							@endif

							@if(session('error'))
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									{{ session('error') }}
									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								</div>
							@endif

							<form class="row login_form" action="{{ route('login') }}" method="POST" id="contactForm" novalidate="novalidate">
								@csrf
								<div class="col-md-12 form-group">
									<input type="email" class="form-control @error('email') is-invalid @enderror" 
										   id="email" name="email" value="{{ old('email') }}" 
										   placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
									@error('email')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-12 form-group">
									<input type="password" class="form-control @error('password') is-invalid @enderror" 
										   id="password" name="password" 
										   placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
									@error('password')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-12 form-group">
									<div class="creat_account">
										<input type="checkbox" id="remember" name="remember">
										<label for="remember">Keep me logged in</label>
									</div>
								</div>
								<div class="col-md-12 form-group">
									<button type="submit" value="submit" class="primary-btn">Log In</button>
									<a href="{{ route('register') }}">Don't have an account? Register</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--================End Login Box Area =================-->

		<!-- start footer Area -->
		


		

	</html>
@endsection