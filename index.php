<?php
include('php/functions.php');
include('php/connect.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);
setcookie('user-id',1 ,time() + strtotime('+1 year'), '/');
//todo buttons comment and like add friend there is no request for them 
?>


<!DOCTYPE html>
<html lang="en">
<?php include('php/head.php'); ?>
<body>
  <nav>
    <div class="section-1">
      <img src="images/site_images/logo.png" alt="Bloger" class="circled-img">
      <form action="" method="post">
        <label >
          <input type="text" name="categories" >
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </label>
      </form>
    </div>
    <div class="section-3">
      <a href="php/home.php">
        <img 
          src="images/profile_images/<?php echo echo_user_profile_pic($database)?>.png" 
          alt="your photo" 
          class="circled-img"
          title= 'Home Page'>
      </a>
    </div>
  </nav>
  <div class="main-content">
    <aside>
      <ul class='page-links'>
        <!-- //todo there should be an id for the home page and blog page
          -->
        <li>
          <a href="php/home.php">
            <i class="fa-solid fa-house"></i>
            <span>home page</span>
          </a>
        </li>
        <li>
          <a href="php/blog.php">
            <i class="fa-solid fa-b fa-lg"></i>
            <span>Your blogs</span>
          </a>
        </li>
        <li>
          <a href="php/settings.php">
            <i class="fa-solid fa-gear"></i>
            <span>settings</span>
          </a>
        </li>
      </ul>
    </aside>
    <section>
      <?php 
        $result = get_friend_blogs($database);
        if(is_user_have_friend($result)) {
          echo_blogs($result, $database, false);
        } else {
          $random_blogs = get_random_blogs($database);
          echo_blogs($random_blogs, $database, true);
        }
      ?>
      <div class='box'> 
        <div class='user-information'> 
          <div class="information">
            <img src='images/profile_images/default.png' alt='' class ='circled-img'> 
            <span class='user-name'>user name </span> 
          </div>
          <form action= 'index.php' method= 'get'>
            <button type='submit' name='add-content' value='add-friend'>
              <span class='button'>Add Contact </span>
              <i class='fa-solid fa-plus'></i>
            </button>
          </form>
        </div> 
        <div class='content'>
          <h2 id='blog-title'>Hello world</h2> 
          <p id='blog-text'>I'm a new user in bloger, I'm trying to learn how to make a nodes</p> 
          <img src='images/blog_images/test.jpg' alt='' class='blog-img'> 
        </div>
        <div class='buttons'>
          <a href='php/comment.php?blog-id=1' title='comment'>
            <i class='fa-regular fa-comment'></i>
            <span>comment</span>
        </a>
          <a href='php/like.php'>
            <i class='fa-regular fa-thumbs-up'></i>
            <span>like</span>
        </a>
        </div>
      </div> 
    </section>
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
    get_box_user_information_html($database);
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
function get_box_user_information_html($database) {
  echo "<div class='box'> " ;
    echo "<div class='user-information'> " ;
      echo "<div class='content'>" ;
        echo "<img src='images/profile_images/".echo_user_profile_pic($database).".png' alt='' class ='circled-img'>" ;
        echo "<span class='user-name'>user name </span> " ;
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
