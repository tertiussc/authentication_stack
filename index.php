<?php include("includes/header.php") ?>
<?php include("includes/navbar.php") ?>
<!-- Index Page Start -->
<main class="container">
  <h1>Index Page</h1>
  <?php display_message(); ?>
  <?php 
  
  if (logged_in()){
    echo "Logged In";
  }
  echo "<br>";
  var_dump($_SESSION);

  ?>
</main>


<?php include("includes/footer.php") ?>