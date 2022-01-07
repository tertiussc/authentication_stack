<?php include_once("includes/header.php") ?>
<?php include_once("includes/navbar.php") ?>
<?php
// Initialize values
$first_name = (isset($first_name)) ? $first_name : '';
?>
<div class="container">
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<?php validate_user_registration(); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 offset-md-3 mt-5">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<a href="login.php" class="text-reset text-decoration-none">Login</a>
						</div>
						<div class="col-6">
							<a href="register.php" class="text-reset text-decoration-none fw-bold" id="">Register</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<form id="register-form" method="post" role="form">

								<div class="mb-3">
									<input type="text" name="first_name" id="first_name" tabindex="1" class="form-control" placeholder="First Name" value="<?= $first_name;?>" required>
								</div>
								<div class="mb-3">
									<input type="text" name="last_name" id="register_last_name" tabindex="1" class="form-control" placeholder="Last Name" value="" required>
								</div>
								<div class="mb-3">
									<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>
								</div>
								<div class="mb-3">
									<input type="email" name="email" id="register_email" tabindex="1" class="form-control" placeholder="Email Address" value="" required>
								</div>
								<div class="mb-3">
									<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
								</div>
								<div class="mb-3">
									<input type="password" name="confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" required>
								</div>
								<div class="mb-3">
									<div class="row">
										<div class="col-6 offset-3 d-grid">
											<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn-success" value="Register Now">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once("includes/footer.php") ?>