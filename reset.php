<?php include_once("includes/header.php") ?>
<?php include_once("includes/navbar.php") ?>

<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3 mt-5">
			<div class="card panel-login">
				<div class="card-header">
					<div class="row">

						<div class="col-12">
							<h3>Reset Password</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<form id="register-form" method="post" role="form">

								<div class="mb-3">
									<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
								</div>
								<div class="mb-3">
									<input type="password" name="confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" required>
								</div>
								<div class="mb-3">
									<div class="row">
										<div class="col-6 offset-3">
											<input type="submit" name="reset-password-submit" id="reset-password-submit" tabindex="4" class="form-control btn btn-outline-secondary" value="Reset Password">
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