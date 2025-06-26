@extends('layouts.client_home')
@section('title','Đăng Ký')
@section('content')
		<!-- Start Banner Area -->
		<section class="banner-area organic-breadcrumb">
			<div class="container">
				<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
					<div class="col-first">
						<h1>Register</h1>
						<nav class="d-flex align-items-center">
							<a href="{{ route('home') }}">Home<span class="lnr lnr-arrow-right"></span></a>
							<a href="{{ route('register') }}">Register</a>
						</nav>
					</div>
				</div>
			</div>
		</section>
		<!-- End Banner Area -->

		<!--================Register Box Area =================-->
		<section class="login_box_area section_gap">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="login_box_img">
							<img class="img-fluid" src="{{ asset('assets/img/login.jpg') }}" alt="">
							<div class="hover">
								<h4>Already have an account?</h4>
								<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
								<a class="primary-btn" href="{{ route('login') }}">Login</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="login_form_inner">
							<h3>Create Account</h3>
							
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

							<form class="row login_form" action="{{ route('register') }}" method="POST" id="contactForm" novalidate="novalidate">
								@csrf
								<div class="col-md-6 form-group">
									<input type="text" class="form-control @error('name') is-invalid @enderror" 
										   id="name" name="name" value="{{ old('name') }}" 
										   placeholder="Full Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Full Name'" required>
									@error('name')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6 form-group">
									<input type="text" class="form-control @error('account_name') is-invalid @enderror" 
										   id="account_name" name="account_name" value="{{ old('account_name') }}" 
										   placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" required>
									@error('account_name')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6 form-group">
									<input type="email" class="form-control @error('email') is-invalid @enderror" 
										   id="email" name="email" value="{{ old('email') }}" 
										   placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" required>
									@error('email')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6 form-group">
									<input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
										   id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
										   placeholder="Phone Number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
									@error('phone_number')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6 form-group">
									<input type="password" class="form-control @error('password') is-invalid @enderror" 
										   id="password" name="password" 
										   placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required>
									@error('password')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6 form-group">
									<input type="password" class="form-control" 
										   id="password_confirmation" name="password_confirmation" 
										   placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'" required>
								</div>
								<div class="col-md-12 form-group">
									<button type="submit" value="submit" class="primary-btn">Create Account</button>
									<a href="{{ route('login') }}">Already have an account? Login</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--================End Register Box Area =================-->

		<!-- start footer Area -->
		


		

	</html>
@endsection
