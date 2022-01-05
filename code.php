<?php include("includes/header.php"); ?>

<main class="container">
	<div class="row">
		<div class="container">
			<div class="col-lg-8 offset-lg-2 mt-5">
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					We have a sent a security code to your email <span>@edwin@email.com</span>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
			<div class="col-lg-8 offset-lg-2">
				<div class="alert-placeholder">
				</div>
				<div class="card border-success">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="text-center">
									<h2><b> Enter Code</b></h2>
								</div>
								<form id="register-form" method="post" role="form" autocomplete="off">
									<div class="mb-3 form-group">
										<input type="text" name="code" id="code" tabindex="1" class="form-control" placeholder="Enter your secure code here" value="" autocomplete="off" required />
									</div>
									<div class="form-group">
										<div class="row">

											<div class="col-lg-3 offset-lg-2 col-md-3 offset-md-2">
												<input type="submit" name="code-cancel" id="code-cancel" tabindex="2" class="form-control btn btn-danger" value="Cancel" />

											</div>
											<div class="col-lg-3 offset-lg-2 col-md-3 offset-md-2">
												<input type="submit" name="code-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="Continue" />
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
	</div>
</main>

<?php include_once("includes/footer.php") ?>