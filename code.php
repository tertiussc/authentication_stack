<?php include("includes/header.php"); ?>
<?php
// PHP code
// assign a value to $code to populate the form
$email = isset($_GET['email']) ? $email = $_GET['email'] : $email = '';
$code = isset($_GET['code']) ? $code = $_GET['code'] : $code = '';

?>
<main class="container">
	<div class="row">
		<div class="container">
			<div class="col-md-6 offset-md-3 mt-5">

				<?php
				display_message(); // Check for messages first
				var_dump($_POST);
				validate_code();
				?>
			</div>
			<div class="col-md-6 offset-md-3">

				<div class="card border-success">
					<div class="card-body">
						<div class="row">
							<div class="text-center">
								<h2>Validate Reset</h2>
							</div>
							<form id="register-form" method="POST" role="form" autocomplete="off">
								<div class="mb-3">
									<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Enter email address" autocomplete="off" required value="<?= $email ?>" />
								</div>
								<div class="mb-3">
									<input type="text" name="code" id="code" tabindex="1" class="form-control" placeholder="Enter validation code" autocomplete="off" required value="<?= $code ?>" />
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-md-3 offset-md-2">
											<input type="submit" value="Cancel" name="code-cancel" id="code-cancel" tabindex="2" class="form-control btn btn-danger" />
										</div>
										<div class="col-md-3 offset-md-2">
											<input type="hidden" class="hide" name="token" id="token" value="<?php echo token_generator(); ?>">
											<input type="submit" value="submit" name="code-submit" id="code-submit" tabindex="2" class="form-control btn btn-success" />
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
</main>

<?php include_once("includes/footer.php") ?>