<?php $__env->startSection('title','Đăng Nhập'); ?>
<?php $__env->startSection('content'); ?>
		<!-- Start Banner Area -->
		<section class="banner-area organic-breadcrumb">
			<div class="container">
				<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
					<div class="col-first">
						<h1>Login/Register</h1>
						<nav class="d-flex align-items-center">
							<a href="<?php echo e(route('home')); ?>">Home<span class="lnr lnr-arrow-right"></span></a>
							<a href="<?php echo e(route('login')); ?>">Login/Register</a>
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
							<img class="img-fluid" src="<?php echo e(asset('assets/img/login.jpg')); ?>" alt="">
							<div class="hover">
								<h4>New to our website?</h4>
								<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
								<a class="primary-btn" href="<?php echo e(route('register')); ?>">Create an Account</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="login_form_inner">
							<h3>Log in to enter</h3>
							
							<?php if(session('success')): ?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?php echo e(session('success')); ?>

									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								</div>
							<?php endif; ?>

							<?php if(session('error')): ?>
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<?php echo e(session('error')); ?>

									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								</div>
							<?php endif; ?>

							<form class="row login_form" action="<?php echo e(route('login')); ?>" method="POST" id="contactForm" novalidate="novalidate">
								<?php echo csrf_field(); ?>
								<div class="col-md-12 form-group">
									<input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="email" name="email" value="<?php echo e(old('email')); ?>" 
										   placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
									<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<div class="invalid-feedback"><?php echo e($message); ?></div>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<div class="col-md-12 form-group">
									<input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="password" name="password" 
										   placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
									<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<div class="invalid-feedback"><?php echo e($message); ?></div>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<div class="col-md-12 form-group">
									<div class="creat_account">
										<input type="checkbox" id="remember" name="remember">
										<label for="remember">Keep me logged in</label>
									</div>
								</div>
								<div class="col-md-12 form-group">
									<button type="submit" value="submit" class="primary-btn">Log In</button>
									<a href="<?php echo e(route('register')); ?>">Don't have an account? Register</a>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/client/pages/login.blade.php ENDPATH**/ ?>