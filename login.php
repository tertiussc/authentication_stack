<?php include_once("includes/header.php") ?>
<?php include_once("includes/navbar.php") ?>
<?php
// PHP code

?>

<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3 mt-5">
			<?php display_message(); ?>
			<?php var_dump($_SESSION);?>
			<?php validate_login(); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card panel-login">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<a href="login.php" class="text-reset text-decoration-none fw-bold" id="login-form-link">Login</a>
						</div>
						<div class="col-6">
							<a href="register.php" id="" class="text-reset text-decoration-none">Register</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<form id="login-form" method="post" role="form" style="display: block;">
								<div class="mb-3">
									<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" required>
								</div>
								<div class="mb-3">
									<input type="password" name="password" id="login-
										password" tabindex="2" class="form-control" placeholder="Password" required>
								</div>
								<div class="mb-3 form-check">
									<input type="checkbox" tabindex="3" class="form-check-input" name="remember" id="remember">
									<label for="remember" class="form-check-label"> Remember Me</label>
								</div>
								<div class="mb-3">
									<div class="row">
										<div class="col-6 offset-3 d-grid">
											<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="btn btn-success" value="Log In">
										</div>
									</div>
								</div>
								<div class="mb-3">
									<div class="row">
										<div class="col-lg-12">
											<div class="text-center">
												<a href="recover.php" tabindex="5" class="text-reset text-decoration-none fst-italic">Forgot Password?</a>
											</div>
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