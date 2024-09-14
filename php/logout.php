<?php

include('functions.php');
include('connect.php');
$script_name = get_script_name();
$css_path = get_css_path_name_depend_on($script_name);
?>

html lang="en">
  <?php include('head.php'); ?>
  <body>
    
    <!-- start form section -->
    <div class="container ">
      <div class="get-information">
        <h2>logout</h2>
        <form action="" method="post">
          <label for="email">enter your email</label>
          <input 
          type="email" 
          name="email" 
          id="email"
          >
          <label for="pass">enter your password</label>
          <div class="password">
            <input 
            type="password" 
            name="password" 
            id="pass" 
            >
            <button type="button" name='eye' id= 'button_eye'>
              <i class='fa-solid fa-eye-slash'></i> 
            </button>
          </div>
          <input type="submit" name="logout" value="submit">
        </form>
        <div class="questions">
          <span class="sign-up"><a href="../index.php">Go back to home page</a></span>
          <span class="forget-password"><a href=""> did you forget your password?</a></span>
        </div>
      </div>
    </div>


  </body>
</html>
