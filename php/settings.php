<?php
include('functions.php');
include('connect.php');
$script_name = get_script_name();
$css_path = get_css_path_name_depend_on($script_name);
$aside_path = get_aside_path_depend_on($script_name);
$image_path = get_image_path_depend_on($script_name);
$error_first_name = false;
$upload_img = true;
if(is_request_method_post()) {
  if(is_request_about_change_name()) {
    if(is_input_null($_POST['first-name']))
      $error_first_name = true;
    else {
      update_user_name_in($database);
      header("Location: settings.php");
    }
  }
  if(is_request_about_change_img()) {
    $img_name = change_img_name();
    if(! is_it_image()) $upload_img = false;
    if(! is_it_new_image()) {
      unlink_the_old_img() 
    }
    if($upload_img) {
      upload_img_in_profile_folder();
      insert_img_in($database, $img_name);
      header("Location: settings.php");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('head.php') ?>
  <body>
    <?php include('nav.php') ?>

    <div class="main-content">
      <?php include('aside.php') ?>
      <div class="container">
        <div class="grid">
          <div class="box">
            <h3>Change Your profile Image</h3>
            <form action="" method="_POST" enctype='multipart/form-data'>
              <input type="file" name="user-img" id="file" accept='image/*'>
              <label for="file"><span> Upload new image </span></label>
              <button type="submit" name="change-pic">change</button>
            </form>
          </div>
          <div class="box">
            <h3>Change Your Name </h3>
            <form action="" method="post" >
              <label for="first-name">First name</label>
              <input type="text" name="first-name" id='first-name'>
              <label for="last-name">last name</label>
              <input type="text" name="last-name" id='last-name'>
              <button type="submit" name="change-name">change</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<?php

function echo_user_profile_pic($database) {
  $profile_pic_name = get_user_profile_pic_from($database);
  if($profile_pic_name!= null) {
    return $profile_pic_name;
  } else {
    return 'default';
  }
}
function get_user_profile_pic_from($database) {
  $sql = 'SELECT user_filename_profile FROM user WHERE user_id='. $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  $data = $statement->fetchAll(PDO::FETCH_COLUMN,0);
  if($data == null) return null;
  return $data[0];
}
function is_request_method_post() {
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    return true;
  }
  return false;
}
function is_request_about_change_img() {
  if(isset($_POST['change-pic']) && ! (is_null($_POST['change-pic']))) {
    return true;
  }
  return false;
}
function is_request_about_change_name() {
  if(isset($_POST['change-name']) && ! (is_null($_POST['change-name']))) {
    return true;
  }
  return false;
}
function is_input_null($string) {
  if(check_input_null(explode(' ', $string)) === '') return true;
  return false;
}
function check_input_null($chars) {
  $result = "";
  foreach($chars as $char) {
    if($char === ' ') continue;
    $result .= $char;
  }
  return $result;
}
function update_user_name_in($database) {
  $data = ['user_name' => make_name()];
  $sql = 
    'UPDATE TABLE user SET user_name = :user_name WHERE user_id = ' . $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute($data);
}
function make_name() {
  if(is_input_null($_POST['last-name'])) {
    return $_POST['firs-name'];
  }
  return $_POST['firs-name'] . " " . $_POST['last-name'];
}
function change_img_name() {
  $new_name = "user-img-". $_COOKIE['user-id'];
  // $_FILES['user-img']['name'] = $new_name;
  // return $newName;
}
function is_it_img() {
  if($_FILES['user-img']['size'] > 0) return true;
  return false;
}
function is_it_new_image() {
  $dir = "../images/profile_images/*";
  $images = glob($dir);
  foreach($images as $img) {
    if($img == $_FILES['user-img']['name'])
    return false;
  }
  return true;
}
function unlink_the_old_img() {
  $dir = "../images/profile_images/". $_FILES['user-img']['name'];
  unlink($dir);
}
function upload_img_in_profile_folder() {
  $file_path = '../images/profile_images/' . basename($_FILES['user-img']['name']);
  move_uploaded_file($_FILES['user-img']['tmp_name'], $file_path);
}
function insert_img_in($database, $img_name) {
  $data = ['user_profile' => $img_name];
  $sql =  
    'UPDATE TABLE user SET user_filename_profile = :user_profile WHERE user_id = ' . $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute($data);
}