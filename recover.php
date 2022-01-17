<?php include_once("includes/header.php") ?>
<?php include_once("includes/navbar.php") ?>
<?php
// PHP COde

?>
<main class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 my-5">
			<?php display_message(); ?>
			<?php recover_password(); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="card border-success">
				<div class="card-body">
					<div class="text-center">
						<h2><b class="lead">Recover Password</b></h2>
					</div>
					<form id="register-form" method="post" role="form" autocomplete="off">
						<div class="mb-3">
							<label for="email" class="visually-hidden">Email Address</label>
							<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" />
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6 mt-1 mt-lg-0">
									<a href="login.php" class="form-control btn btn-danger">Cancel</a>
								</div>
								<div class="col-lg-6 mt-1 mt-lg-0">
									<input type="submit" name="recover-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="Send Password Reset Link" />
								</div>
							</div>
						</div>
						<input type="hidden" class="hide" name="token" id="token" value="<?php echo token_generator(); ?>">
					</form>
				</div>
			</div>
		</div>

	</div>


</main>
<?php include_once("includes/footer.php") ?>