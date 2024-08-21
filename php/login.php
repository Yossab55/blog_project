<?php
include('../php/functions.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);

$is_error = false ;
$message_is_error = "";
$index_and_boolean_email = [];
$index_and_boolean_password = [];
if( !(isset($_POST['eye'])) || is_null($_POST['eye']) ) {
  $password_or_text = 'password';
}

if(check_request_post_login()) {
  include("php/connect.php");
  $emails = get_emails_user($database);
  $passwords = get_passwords_user($database);
  if($emails == null) {
    is_error_true($is_error);
    $message_is_error = error_message_no_account() ;
  } else {
      for($i = 0 ; $i < count($emails); $i++) {
        if(is_user_email_correct($emails[$i])) {
          $index_and_boolean_email[0] = $i;
          $index_and_boolean_email[1] = true;
          break ;
        }
      }
      $index_and_boolean_email[1] = false;
      if($index_and_boolean_email[1]) {
        if($passwords[$index_and_boolean_email[0]] === $_POST['password']) {
          $id = get_user_id($database,$index_and_boolean_email[0]);
          header('Location: ../index.php?id='.$id);
        } else {
          is_error_true($is_error);
          $message_is_error = error_message_no_account_or_email_wrong() ;
        }
      } else {
        is_error_true($is_error);
        $message_is_error = error_message_no_account_or_email_wrong() ;
      }
    }
  }


?>
<!DOCTYPE html>
<html lang="en">
  <?php include('head.php'); ?>
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
          <input type="submit" name="login" value="submit">
        </form>
        <div class="questions">
          <span class="sign-up"><a href="signup.php">you don't have an account (sign_in) ?</a></span>
          <span class="forget-password"><a href=""> did you forget your password?</a></span>
        </div>
      </div>
    </div>
    <!-- end form section -->
    <script src="../js/functions.js" type='module'></script>
    <script src="../js/login.js" type='module'></script>

  </body>
</html>


<?php
// function section

// check request section 

function check_request_post_login() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['login']) && !(is_null($_POST)))
    return true ;
  }
  return false;
}
// fetch data from database section
function get_emails_user($database) {
  $sql = "SELECT user_email FROM User";
  $statement = $database->prepare($sql) ;
  $statement->execute();
  return  $statement->fetchAll(PDO::FETCH_COLUMN,0);
}
function get_passwords_user($database) {
  $sql = "SELECT user_password FROM User";
  $statement = $database->prepare($sql) ;
  $statement->execute();
  return  $statement->fetchAll(PDO::FETCH_COLUMN,0);
}
function get_user_id($database,$index) {
  $sql = "SELECT user_id FROM User where id=".$index;
  $statement = $database->prepare($sql) ;
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_COLUMN,0);
  return  $result[0];
}

// check error data base section 
function is_user_email_correct($email) {
  if($_POST['email'] === $email) return true;
  return false ;
}
// set error message section
function is_error_true(&$is_error) {
  $is_error = true ;
}
function error_message_no_account() {
  return "You Don't Have An Account, Please Sign Up";
}
function error_message_no_account_or_email_wrong() {
  return "You Don't Have An Account or Your Email Is Wrong, Please Sign Up";
}
function error_message_password_wrong() {
  return "Your Password Is Wrong";
}