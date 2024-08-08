<?php

$script_name = basename($_SERVER['PHP_SELF'], ".php")  ;
$password_or_text = 'password';
$is_error = false ;
$message_is_error = "";
if( !(isset($_POST['eye'])) || is_null($_POST['eye']) ) {
  $password_or_text = 'password';
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['eye']) && !(is_null($_POST['eye']))) {
    if($_POST['eye'] === 'password') $password_or_text = 'text' ;
    else $password_or_text = 'password';
  }
  if(check_request_submit()) {
    $result = get_data_user();
    if($result == null) {
      $is_error= true;
      $message_is_error = "you don't have an account " ;
    }
  }
} 

?>
<!DOCTYPE html>
<html lang="en">
  <?php include('php/head.php'); ?>
  <body>
    <!-- start error section -->
    <?php 
      if($is_error) { ?>
      <div class="container">
        <div class="over-lay"></div>
        <div class="error">
          <h2> <?php echo $message_is_error ; ?> </h2>
          <span><a href="">sign-up and get an account</a></span>
        </div>
      </div>
    <?php } ?>
    <!-- end error section -->
    
    <!-- start welcome section -->
    <div class="container welcome">
      <h2 class="welcome">Bloger</h2>
      <span class="quote">where you write anything</span>
    </div>
    <!-- end welcome section -->
    
    <!-- start form section -->
    <div class="container ">
      <div class="get-information">
        <h2>login</h2>
        <form action="" method="post">
          <label for="email">enter your email</label>
          <input 
          type="email" 
          name="email" 
          id="email"
          value = '<?php echo (check_request_eye_to_put_data()) ? $_POST['email'] : "" ?>'
          >
          <label for="pass">enter your password</label>
          <div class="password">
            <input 
            type="<?Php echo $password_or_text ?>" 
            name="password" 
            id="pass" 
            value='<?php echo (check_request_eye_to_put_data()) ? $_POST['password'] : "" ?>'
            >
            <button type="submit" name='eye' value='<?php echo $password_or_text ?>'>
              <?php 
              if($password_or_text == "password")
              echo "<i class='fa-solid fa-eye-slash'></i>" ;
              else echo "<i class='fa-solid fa-eye'></i>";
              ?>
            </button>
          </div>
          <input type="submit" name="login" value="submit">
        </form>
        <div class="questions">
          <span class="sign-up"><a href="">you don't have an account (sign_in) ?</a></span>
          <span class="forget-password"><a href=""> did you forget your password?</a></span>
        </div>
      </div>
    </div>
    <!-- end form section -->

  </body>
</html>


<?php
// function section 
function check_request_eye_to_put_data() {
  if($_SERVER['REQUEST_METHOD'] ==='POST') {
    if(isset($_POST['eye']) )
    return true ;
  }
  return false;
}
function check_request_submit() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['login']) && !(is_null($_POST)))
    return true ;
  }
  return false;
}
function get_data_user() {
  include("php/connect.php");
  $sql = "SELECT  user_email, user_password FROM User";
  $statement = $database->prepare($sql) ;
  $statement->execute();
  return  $statement->fetch(PDO:: FETCH_ASSOC);
}
