<?php
include('functions.php');
include('connect.php');
$script_name = get_script_name();
$css_path = get_css_path_name_depend_on($script_name);
$aside_path = get_aside_path_depend_on($script_name);
$image_path = get_image_path_depend_on($script_name);
?>

<!DOCTYPE html>
<html lang="en">


  <?php include('head.php') ?>
  <body>
    <?php include('nav.php') ?>
    <div class="main-content">
      <?php include('aside.php') ?>

      <div class="container">
        <section>
          <?php 
    
            $result = get_blogs_from($database);
            $size = count($result);
            if($size > 0)
              echo_blogs($result, $database);
            else echo_you_have_no_blogs();
          ?>
          
          
        </section>
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
function get_blogs_from($database) {
  $sql = 
    'SELECT blog_id, blog_title, blog_text, blog_filename_image,
    blog_time, categories FROM blog 
    WHERE blog_id = ' . $_COOKIE['user-id'];
    $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}
function echo_blogs($result, $database) {
  foreach($result as $blog_data) {
    $user_name = get_user_name_by($blog_data['user-id']);
    get_box_user_information_html($database, $user_name);
    get_blog_title_text($blog_data["blog_title"], $blog_data["blog_text"]) ;
      if(is_there_blog_img($blog_data['blog_blog_filename_image'])) {
        echo "<img src='../images/blog_images/".$blog_data['blog_blog_filename_image'].".jpg' alt='' class='blog-img'> " ;
      }
    echo "</div> " ;
    get_comment_like_buttons($blog_data["blog_id"]);
  }
}
function get_user_name_by($id) {
  $sql = 
    'SELECT user_name FROM user 
    WHERE id = '.$id;
  $statement = $database->prepare($sql);
  $statement->execute();
  $data = $statement->fetchAll(PDO::FETCH_COLUMN,0);
  if($data == null) return null;
  return $data[0];
}
function get_box_user_information_html($database, $user_name) {
  echo "<div class='box'> " ;
    echo "<div class='user-information'> " ;
      echo "<div class='content'>" ;
        echo "<img src='images/profile_images/".echo_user_profile_pic($database).".png' alt='' class ='circled-img'>" ;
        echo "<span class='user-name'> $user_name </span> " ;
      echo "</div>" ;
}
function get_blog_title_text($title, $text) {
  echo "<div class='content'> ";
  echo "</div> " ;
  echo "<h2 id='blog-title'>$title/h2> " ;
  echo "<p id='blog-text'>$text</p> " ;
}
function is_there_blog_img($blog_pic) {
  if($blog_pic == null) {
    return false ; 
  }
  return true ;
}
function get_comment_like_buttons($blog_id) {
  echo "<div class='buttons'>";
    echo "<a href='comment.php?blog-id=$blog_id' title='comment'>";
      echo "<i class='fa-regular fa-comment'></i>";
      echo "<span>comment</span>";
    echo "</a>";
      echo "<a href='like.php'>";
        echo "<i class='fa-regular fa-thumbs-up'></i>";
        echo "<span>like</span>";
    echo "</a>";
  echo "</div>";
}
function echo_you_have_no_blogs() {
  echo "<div class='box error'>";
    echo "<span class='error-message'>You doesn't have Any blogs yet !!</span>";
    echo "<span class='make-blog'>add blog now <i class='fa-solid fa-arrow-down'></i></span>";
    echo "<a href='add_blog.php'>Add blog</a>";
  echo "</div>";
}