<?php
include('php/functions.php');

$script_name = get_script_name();
if(! isset($_COOKIE['user-id']) || (is_null($_COOKIE))) {
    header('Location: php/login.php');
}
$css_path = get_css_path_name_depend_on($script_name);
$aside_path = get_aside_path_depend_on($script_name);
$image_path = get_image_path_depend_on($script_name);

//todo buttons like add friend there is no request for them 
//* like and friend should be done by fetch 🥹
include('php/connect.php');
?>


<!DOCTYPE html>
<html lang="en">
<?php include('php/head.php'); ?>
<body>
  
  <?php include('php/nav.php')?>
  <div class="main-content">
    <?php include('php/aside.php')?>
    
    <div class="container">
      <section>
        <?php 
        if(check_request_method_get()) {
          if(check_request_search()) {
            $search_blogs = get_search_blog($database);
            if(count($search_blogs) > 0 ) {
              echo_blogs($search_blogs, $database, false);
            } else {
              echo_there_is_no_blog_found();
            }
          }
        } else {
          $result = get_friend_blogs($database);
          if(is_user_have_friend($result)) {
            echo_blogs($result, $database, false);
          } else {
            $random_blogs = get_random_blogs($database);
            if(count($random_blogs) > 0) {
              echo_blogs($random_blogs, $database, true);
            } else {

            }
          }
        }
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
function get_friend_blogs($database) {
    $sql = 
      'SELECT f.friend_id, b.blog_id, b.blog_title, b.blog_text, b.blog_filename_image,
      b.blog_time, b.categories
      FROM  userfriend as f
      INNER JOIN userfriend as u 
      on  u.user_id = f.friend_id 
      INNER JOIN blog as b 
      on b.user_id = f.friend_id 
      WHERE f.friend_id =' . $_COOKIE['user-id'] .' 
      ORDER BY b.blog_time';
    $statement = $database->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_DEFAULT);
}
function get_random_blogs($database) {
  $sql = 
    'SELECT blog_id, blog_title, blog_text, blog_filename_image,
    blog_time, categories FROM blog '. make_where_statement($database) 
    .' AND NOT user_id = '. $_COOKIE['user-id']. ' ORDER BY blog_time' ;
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
}
function is_user_have_friend($result) {
  if($result === null) {
    return false ;
  } 
  return true ;
}
function echo_blogs($result, $database, $add_content_button) {
  foreach($result as $blog_data) {
    $user_name = get_user_name_by($blog_data['user-id']);
    get_box_user_information_html($database, $user_name, $blog_data['categories']);
    if($add_content_button) {
      add_content_button();
    }
    get_blog_title_text($blog_data["blog_title"], $blog_data["blog_text"]) ;
      if(is_there_blog_img($blog_data['blog_blog_filename_image'])) {
        echo "<img src='images/blog_images/".$blog_data['blog_blog_filename_image'].".jpg' alt='' class='blog-img'> " ;
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
function get_box_user_information_html($database, $user_name, $categories) {
  echo "<div class='box'> " ;
    echo "<div class='user-information'> " ;
      echo "<div class='content'>" ;
        echo "<img src='images/profile_images/".echo_user_profile_pic($database).".png' alt='' class ='circled-img'>" ;
        echo "<span class='user-name'> $user_name </span> " ;
        if(! is_categories_null($categories))
        echo "<span class='categories'>".make_categories($categories)."</span>";
      echo "</div>" ;
}
function add_content_button() {
      echo "<form action= 'index.php' method= 'get'>" ;
        echo "<button type='submit' name='add-content' value='add-friend'>" ;
          echo "<span class='button'>Add Contact </span>" ;
          echo "<i class='fa-solid fa-plus'></i>" ;
        echo "</button>" ;
      echo "</form>" ;
}
function is_categories_null($categories) {
  if($categories === null) return true;
  return false;
}
function make_categories($categories) {
  $result = implode(" ", explode($categories, '/'));
  return $result;
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
    echo "<a href='php/comment.php?blog-id=$blog_id' title='comment'>";
      echo "<i class='fa-regular fa-comment'></i>";
      echo "<span>comment</span>";
    echo "</a>";
      echo "<a href='php/like.php'>";
        echo "<i class='fa-regular fa-thumbs-up'></i>";
        echo "<span>like</span>";
    echo "</a>";
  echo "</div>";
}
function make_where_statement($database) {
  $ids = get_friends_id($database);
  $statement = '';
  foreach($ids as $id ) {
    $statement .= "NOT user_id =". $id ." AND " ;
  }
  return $statement;
}
function get_friends_id($database) {
  $sql = 
    'SELECT friend_id 
    FROM userfriend 
    WHERE  user_id='. $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
}
function there_is_no_blogs() {
  echo "<div class='box'>";
    echo "<p>There is no blog is the site be the first one</p>";
    echo "<a href='php/add_blog.php'> create your blog</a>";
  echo "</div>";
}
//search bar 
function check_request_method_get() {
  if($_SERVER['REQUEST_METHOD'] === 'GET') 
    return true;
  return false;
}
function check_request_search() {
  if(isset($_GET['search']) && ! (is_null($_GET))) 
    return true;
  return false;
}
function get_search_blog($database) {
  $sql = 
    'SELECT blog_id, blog_title, blog_text, blog_filename_image,
    blog_time, categories FROM blog LIKE "%'.$_GET['search'].'%"';
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll();
}
function echo_there_is_no_blog_found() {
  echo "<div class='box;>";
    echo "<p>There is no blog found</p>";
  echo "</div>";
}