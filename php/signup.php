<?php
include('../php/functions.php');
$script_name = get_script_name();
$css_path = get_path_name_depend_on($script_name);

$error_exist = false ;
$keys_data_from_post = array_keys($_POST);
$counter_data = 0 ;
$array_data_name_error = [];
if(check_request_submit()) {
  
  foreach($keys_data_from_post as $key) {

    if($_POST[$key] == null) {
      $error_exist = true ;
      $array_data_name_error[$counter_data] = [true, join(" ",explode('-', $key))];
    } else $array_data_name_error[$counter_data] = [false, join(" ",explode('-', $key))];
    $counter_data++ ;
  }

  if(! $error_exist) {
    include('connect.php');
    insert_data_in_user_table($database) ;
    set_id_in_cookie($database);
    header('location: ../index.php');
  }
  
}

?>
<!DOCTYPE html>
<html lang="en">
  <?php include("head.php"); ?> 
  <body>
      <div class="container">
        <div class="sign-up">
          <div class="information-section">
            <img src="../images/site_images/logo.png" >
            <div class="title">
              <h2>Welcome To Bloger </h2>
              <p>join our community</p>
            </div>
            <div class="form-section">
              <form action="" method="post">
                <label for="f-name">first name</label>
                <?php 
                  if(! (isset($array_data_name_error))&& $array_data_name_error[0][0] == true  ) 
                  echo put_message_error_html_element($array_data_name_error[0][1]);
                ?>
                <input type="text" name="first-name" id="f-name">
                <label for="l-name">last name</label>
                <input type="text" name="last-name" id="l-name">
                <?php 
                  if(! (isset($array_data_name_error))&& $array_data_name_error[1][0] == true  ) 
                  echo put_message_error_html_element($array_data_name_error[1][1]);
                ?>
                <label for="email">email</label>
                <input type="email" name="email" id="email">                
                <?php 
                  if(! (isset($array_data_name_error))&& $array_data_name_error[2][0] == true  ) 
                  echo put_message_error_html_element($array_data_name_error[2][1]);
                ?>
                <label for="dob">date of birth</label>
                <input type="text" name="date-of-birth" id="dob" placeholder='YYYY/MM/DD'>
                <span class="dmy">Year, Month, Day</span>
                <?php 
                  if(! (isset($array_data_name_error))&& $array_data_name_error[3][0] == true  ) 
                  echo put_message_error_html_element($array_data_name_error[3][1]);
                ?>
                <label for="pass">
                  <span>password</span>
                  <button type="button" name='eye' id= 'button_eye'>
                  <i class='fa-solid fa-eye-slash'></i> 
                  </button>
                </label>
                <input type="password" name="password" id="pass" >
                <?php 
                  if(! (isset($array_data_name_error))&& $array_data_name_error[4][0] == true  ) 
                  echo put_message_error_html_element($array_data_name_error[4][1]);
                ?>
                <button type="submit" value='signup' id='signup'>
                  Sign Up
                </button>
              </form>
            </div>
            </div>
            
          <div class="image-section">
            <img src="../images/site_images/signup.png" >
          </div>
        </div>
      </div>
    </div>
    <script src="../js/functions.js" type='module'></script>
    <script src="../js/signup.js" type='module'></script>
  </body>
</html>


<?php

function check_request_submit() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!(is_null($_POST)))
    return true ;
  }
  return false;
}
function insert_data_in($database) {
  $data = [
    'full_name' => $_POST['first-name'] . " " . $_POST['last-name'],
    'pass' => $_POST['password'],
    'email' => $_POST['email'],
    'date_of_birth' => get_date()
  ];
  $sql = 'INSERT INTO user (user_name, user_password, user_email, user_date_of_birth)
  VALUES (:full_name, :pass, :email, :date_of_birth)';
  $statement = $database->prepare($sql);
  $statement->execute($data);
}
function set_id_in_cookie($database) {
  setcookie('user-id', get_id_for_new_user($database),time() + strtotime('+1 year'), '/');
}
get_id_for_new_user($database) {
  $sql = 
    'SELECT user_id FROM user';
  $statement = $database->prepare($sql);
  $statement->execute();
  $data = $statement->fetchAll(PDO::FETCH_COLUMN, 0);
  $count = count($data);
  return $data[$count - 1];
}
function get_date() {
  $part = explode('/',$_POST['date-of-birth']);
  $result = $part[0] . '-' . $part[1] . '-' .$part[2];
  return $result ;
}
function put_message_error_html_element($data) {
  return "<span class='error'> $data can't be empty!!</span>";
}