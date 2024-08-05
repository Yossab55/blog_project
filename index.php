<?php
$script_name = basename($_SERVER['PHP_SELF'], ".php")  ;
include('php/head.php');
?>
<!DOCTYPE html>
<html lang="en">

  <body>
    <div class="container welcome">
      <h2 class="welcome">Bloger</h2>
      <span class="quote">where you write anything</span>
    </div>
    <div class="container ">
      <div class="get-information">
        <h2>login</h2>
        <form action="" method="post">
          <label for="email">enter your email</label>
          <input type="email" name="email" id="email">
          <label for="pass">enter your password</label>
          <div class="password">
            <input type="password" name="password" id="pass" >
            <button type="submit">
              <?php 
              if(true)
              echo "<i class='fa-solid fa-eye-slash'></i>" ;
              else echo "<i class='fa-solid fa-eye'></i>";
              ?>
            </button>
          </div>
          <input type="submit" name="login" value="submit">
        </form>
        <div class="questions">
          <span class="have-account"><a href="">you already have an account?</a></span>
          <span class="forget-password"><a href=""> did you forget your password?</a></span>
        </div>
      </div>
    </div>
  </body>
</html>