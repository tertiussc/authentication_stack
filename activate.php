<?php include("includes/header.php") ?>
<?php include("includes/navbar.php") ?>
<!-- Index Page Start -->
<main class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <?php
      display_message();
      activate_user();
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <h1 class="display-5 text-center mt-3">Activate your account</h1>
      <form method="post">
        <div class="d-grid">
          <button type="submit" name="activate_user" class="btn btn-outline-success btn-lg my-5">Activate</button>
        </div>
      </form>

    </div>

  </div>

</main>


<?php include("includes/footer.php") ?>