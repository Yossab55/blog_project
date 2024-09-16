<?php
include('functions.php');
include("connect.php");
$script_name = get_script_name();
$css_path = get_css_path_name_depend_on($script_name);


$is_error = false ;
$log_out_email_error = false;
$log_out_password_error = false;
$message_is_error = "";
$index_and_boolean_email = [];
$index_and_boolean_password = [];
$logout_true_or_false = is_log_out();
if( !(isset($_POST['eye'])) || is_null($_POST['eye']) ) {
  $password_or_text = 'password';
}
if(check_request_post()) {
  if(is_it_login()) {
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
        if($index_and_boolean_email[1]) {
          if($passwords[$index_and_boolean_email[0]] === $_POST['password']) {
            $id = get_user_id($database,$index_and_boolean_email[0]);
            setcookie('user-id', $id, time() + strtotime('+1 month'), '/', null, false, true);
            header('Location: ../index.php');
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
  if(is_log_out()) {
    if(is_user_email_error($database)) {
      $log_out_email_error = true; 
      is_error_true($is_error);
      $message_is_error = error_message_email_wrong();
    } else if(is_user_password_error($database)) {
      $log_out_password_error = true;
      is_error_true($is_error);
      $message_is_error = error_message_password_wrong();
    } else {
      setcookie('user-id', $id, time() - 100, '/', null, false, true);
      echo "<script>window.close();</script>";
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
          <h2> <?php echo $message_is_error  ?> </h2>
          <span><a href="<?php ($logout_true_or_false) ? 'login.php?act="out"':'signup.php' ?>">
            <?php
            
              if($logout_true_or_false)
                echo 'try again';
              else echo 'sign-up and get an account';
              ?>
          </a></span>
        </div>
      </div>
    <?php } ?>
    <!-- end error section -->
    
    <!-- start welcome section -->
    <div class="container welcome">
      <h2 class="welcome">Bloger</h2>
      <span class="quote">
        <?php
          if($logout_true_or_false) 
            echo_bye_message();
          else echo_hello_message();
        ?>
      </span>
    </div>
    <!-- end welcome section -->
    
    <!-- start form section -->
    <div class="container ">
      <div class="get-information">
        <h2>
          <?php 
            if($logout_true_or_false) 
              echo "logout";
            else echo 'login';
          ?>
        </h2>
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
          <input 
          type="submit" 
          name= "submit"
          value="<?php echo ($logout_true_or_false) ? 'logout': 'login' ?>" >
        </form>
        <div class="questions">
          <span class="sign-up"><a href="<?php echo ($logout_true_or_false) ? '../index.php': 'signup.php'?>">
            <?php 
              if($logout_true_or_false) 
                echo "Go back to home page";
              else echo "you don't have an account (sign_in) ?";
            ?>
            
          </a></span>
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

function check_request_post() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['submit']) && !(is_null($_POST['submit'])))
    return true ;
  }
  return false;
}
function is_it_login() {
  if($_POST['submit'] === 'login')
    return true;
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
function get_user_id($database, $index) {
  $sql = 
    'SELECT user_id FROM user ';
  $statement = $database->prepare($sql);
  $statement->execute();
  $ids = $statement->fetchAll(PDO::FETCH_COLUMN, 0);
  return $ids[$index];
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
// start log out action 
function is_log_out() {
  if(isset($_GET['act']) && ! (is_null($_GET))) 
    return true;
  return false ;
}
function echo_hello_message() {
  echo 'where you write anything';
}
function echo_bye_message() {
  echo 'Nice to meet you , come back again';
}
function is_user_email_error($database) {
  if($_POST['email'] === get_email($database))
    return false;
  return true;
}
function get_email($database) {
  $sql = 
    'SELECT user_email FROM user WHERE user_id = ' . $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);
  if($result == null) 
    return null;
  return $result[0];
}
function is_user_password_error($database) {
  if($_POST['password'] === get_password($database))
    return false;
  return true;
}
function get_password($database) {
  $sql = 
    'SELECT user_password FROM user WHERE user_id = ' . $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);
  if($result == null) 
    return null;
  return $result[0];
}
function error_message_email_wrong() {
  return "your email is wrong";
}