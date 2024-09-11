<?php
include('functions.php');
include('connect.php');
$script_name = get_script_name();
$css_path = get_css_path_name_depend_on($script_name);
$aside_path = get_aside_path_depend_on($script_name);

$is_error_text = false;
if(is_request_method_post()) {
  if(is_request_about_add_blog()) {
    if(is_input_null($_POST['text'])) {
      $is_error_text = true;
    } else {
      insert_value_in($database);
      header('Location: ../index.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('head.php')?>
  <body>
    <div class="main-content">
      <?php include('aside.php') ?>
      <div class="container">
        <div class="hello">
          <h2>Hello, create your Blog Here</h2>
        </div>
        <form action="" method="post" enctype='multipart/form-data'>
          <div class="texts">
            <label for="title">blog title</label>
            <input type="text" name="title" id="title">
            <label for="text">blog text</label>
            <textarea name="text" id="text"></textarea>
            <?php 
              if($is_error_text) {
                echo_message_error();
              }
            ?>
          </div>
          <input type="file" name="blog-img" id="file" accept='image/*'>
          <label for="file"><span>Upload img</span></label>
          <?php echo_categories($database) ?>
          <button type="submit" name='submit' value='add-blog'>
            <i class="fa-solid fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </body>
</html>

<?php
function echo_categories($database) {
  echo "<div class='checkboxes'>";
  $categories = get_categories_from($database);
  $counter = 0;
  foreach($categories as $category) {
    echo "<label>";
      echo "<span>$category</span>";
      echo "<input type='checkbox' name='cat[$counter]' value='$category'>";
    echo "</label>";
    $counter++;
  }
  echo "</div>";
}
function get_categories_from($database) {
  $sql=
    'SELECT category_name from category';
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);
  return $result;
}
function is_request_method_post() {
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    return true;
  }
  return false;
}
function is_request_about_add_blog() {
  if(isset($_POST['submit']) && ! (is_null($_POST['submit']))) {
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
function echo_message_error() {
  echo "<div class='error'>";
    echo "<span>blog text can't be empty!!</span>";
  echo "</div>";
}
function insert_value_in($database) {
  $data = [
    'user_id' => $_COOKIE['user-id'],
    "blog_title" => is_input_null($_POST['title']) ? $_POST['title'] : null,
    "blog_text" => $_POST['text'],
    "blog_filename_image" => change_img_name(),
    "blog_time" => 'CURRENT_TIMESTAMP',
    "categories" => (is_there_categories()) ? make_string_categories() : null
  ];
  $sql = 
    'INSERT INTO blog VALUES(:user_id, :blog_title, :blog_text, :blog_filename_image
    :blog_time, : categories)' ;
  $statement = $database->prepare($sql);
  $statement->execute($data);
  upload_img_in_blog_folder();
}
function change_img_name() {
  $time = time();
  $rand = rand(10000, 99999);
  $extension = strtolower(pathinfo($_FILES['blog-img']['name'], PATHINFO_EXTENSION));
  $newName = $time . $rand . "." . $extension;
  $_FILES['blog-img']['name'] = $newName;
  return $newName;
}
function upload_img_in_blog_folder() {
  $file_path = '../images/blog_images/' . basename($_FILES['blog-img']['name']);
  move_uploaded_file($_FILES['blog-img']['tmp_name'], $file_path);
}
function is_there_categories() {
  if(isset($_POST['categories']) && !(is_null($_POST['categories']))) 
    return true;
  return false;
}
function make_string_categories() {
  $result = "";
  foreach($_POST['categories'] as $category) {
    $result .= $category .'/';
  }
  return $result;
}