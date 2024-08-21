<?php
include('php/functions.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);
?>


<!DOCTYPE html>
<html lang="en">
<?php include('php/head.php'); ?>
<body>
  <nav>
    <div class="section-1">
      <img src="images/site_images/logo.png" alt="Bloger" class="circled-img">
      <form action="" method="post">
        <label >
          <input type="text" name="categories" >
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </label>
      </form>
    </div>
    <div class="section-3">
      <!-- //todo there should be echo profile pic if there is no pic but a default photo 
      -->
      <a href="php/home.php">
        <img src="images/profile_images/default.png" alt="your photo" class="circled-img">
      </a>
    </div>
  </nav>
</body>
</html>


