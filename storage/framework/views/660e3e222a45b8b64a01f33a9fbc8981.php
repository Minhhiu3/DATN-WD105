<?php $__env->startSection('title','Đăng Ký'); ?>
<?php $__env->startSection('content'); ?>
		<!-- Start Banner Area -->
		<section class="banner-area organic-breadcrumb">
			<div class="container">
				<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
					<div class="col-first">
						<h1>Register</h1>
						<nav class="d-flex align-items-center">
							<a href="<?php echo e(route('home')); ?>">Home<span class="lnr lnr-arrow-right"></span></a>
							<a href="<?php echo e(route('register')); ?>">Register</a>
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
							<img class="img-fluid" src="<?php echo e(asset('assets/img/login.jpg')); ?>" alt="">
							<div class="hover">
								<h4>Already have an account?</h4>
								<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
								<a class="primary-btn" href="<?php echo e(route('login')); ?>">Login</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="login_form_inner">
							<h3>Create Account</h3>
							
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

							<form class="row login_form" action="<?php echo e(route('register')); ?>" method="POST" id="contactForm" novalidate="novalidate">
								<?php echo csrf_field(); ?>
								<div class="col-md-6 form-group">
									<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="name" name="name" value="<?php echo e(old('name')); ?>" 
										   placeholder="Full Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Full Name'" required>
									<?php $__errorArgs = ['name'];
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
								<div class="col-md-6 form-group">
									<input type="text" class="form-control <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="account_name" name="account_name" value="<?php echo e(old('account_name')); ?>" 
										   placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" required>
									<?php $__errorArgs = ['account_name'];
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
								<div class="col-md-6 form-group">
									<input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="email" name="email" value="<?php echo e(old('email')); ?>" 
										   placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" required>
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
								<div class="col-md-6 form-group">
									<input type="text" class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="phone_number" name="phone_number" value="<?php echo e(old('phone_number')); ?>" 
										   placeholder="Phone Number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
									<?php $__errorArgs = ['phone_number'];
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
								<div class="col-md-6 form-group">
									<input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										   id="password" name="password" 
										   placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required>
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
								<div class="col-md-6 form-group">
									<input type="password" class="form-control" 
										   id="password_confirmation" name="password_confirmation" 
										   placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'" required>
								</div>
								<div class="col-md-12 form-group">
									<button type="submit" value="submit" class="primary-btn">Create Account</button>
									<a href="<?php echo e(route('login')); ?>">Already have an account? Login</a>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/client/pages/register.blade.php ENDPATH**/ ?>