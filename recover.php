<?php include_once("includes/header.php") ?>
<?php include_once("includes/navbar.php") ?>
<main class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 mt-5">
			<div class="alert-placeholder">

			</div>
			<div class="card border-success">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="text-center">
								<h2><b class="$pink-500">Recover Password</b></h2>
							</div>
							<form id="register-form" method="post" role="form" autocomplete="off">
								<div class="mb-3">
									<label for="email" class="visually-hidden">Email Address</label>
									<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="" />
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 mt-1 mt-lg-0">
											<input type="submit" name="cancel-submit" id="cancel-submit" tabindex="2" class="form-control btn btn-danger" value="Cancel" />
										</div>
										<div class="col-lg-6 mt-1 mt-lg-0">
											<input type="submit" name="recover-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="Send Password Reset Link" />
										</div>
									</div>
								</div>
								<input type="hidden" class="hide" name="token" id="token" value="">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include_once("includes/footer.php") ?>