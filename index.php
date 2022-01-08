<?php include("includes/header.php") ?>
<?php include("includes/navbar.php") ?>
<!-- Index Page Start -->
<main class="container">
  <h1>Index Page</h1>
  <?php display_message(); ?>
  <?php 
  $sql = "SELECT * FROM users";

  $result = query($sql);

  confirm($result);

  $users = fetch_array($result);
  echo "There are " . row_count($result) . " row(s) of data. <br>";
  echo $users['username'];
 

  ?>
</main>


<?php include("includes/footer.php") ?>