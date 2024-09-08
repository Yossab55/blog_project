<?php 
include('functions.php');
include('connect.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);
$is_error = false;
if(is_request_method_post()) {
  if(is_request_from_add_comment())  {
    if(!  is_new_comment_null()) {
      add_comment_to($database);
      header("Location: comment.php?blog-id=".$_GET['blog-id']);
    } else {  
      $is_error = true;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('head.php') ?>
<body>
  <div class="main-content">
    <div class="blog-and-old-comment">
      <div class="blog-section">
        <?php 
          $blog_content = get_blog($database) ;
          if(isset($blog_content) && ! (is_null($blog_content))) 
            echo_blog($blog_content, $database);
        ?>
      </div>
      <div class='comment-section'>
          <?php
            $comment_content = get_comments_from($database);
            if(count($comment_content) !== 0) {
              echo_comments($comment_content);
            } else {
              echo "<div class='comment no-comment'>";
                echo "<p>There is no comment!!, be the first one</p>";
              echo "</div>";
            }
          ?>
      </div>
      <div class='write-comment'>
        <img src='../images/profile_images/<?php echo_user_profile_pic($database) ?>.png' alt='' class='circled-img'>
        <form action='' method='post'>
          <?php
            if($is_error) 
              echo "<span class = 'error'>". error_message_comment() ."</span>" ;
          ?>
          <input type='text' name='comment-text' >
          <button type='submit' name='comment' value='add-comment'>
            <i class='fa-solid fa-paper-plane'></i>
          </button>
        </form>
      </div>
  </div>
  
</body>
</html>

<?php 

function get_blog($database) {
  $sql = 'SELECT * FROM blog WHERE blog_id='. $_GET['blog-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  $data = $statement->fetchAll(PDO::FETCH_DEFAULT);
  if(count($data) === 0) return null;
  return $data[0];
}
function echo_blog($blog_content, $database) {
  get_box_user_information_html($database);
  get_blog_title_text($blog_content["blog_title"], $blog_content["blog_text"]) ;
    if(is_there_blog_img($blog_content['blog_blog_filename_image'])) {
      echo "<img src='../images/blog_images/".$blog_content['blog_blog_filename_image'].".jpg' alt='' class='blog-img'> " ;
    }
  echo "</div> " ;
}
function get_box_user_information_html($database) {
  echo "<div class='box'> " ;
    echo "<div class='user-information'> " ;
      echo "<div class='information'>" ;
        echo "<img src='images/profile_images/".echo_user_profile_pic($database).".png' alt='' class ='circled-img'>" ;
        echo "<span class='user-name'>".echo_user_name($database)." </span> " ;
      echo "</div>" ;
}
function echo_user_profile_pic($database) {
  $profile_pic_name = get_user_profile_pic_from($database);
  if($profile_pic_name!= null) {
    echo $profile_pic_name;
  } else {
    echo 'default';
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
function echo_user_name($database) {
  $user_name = get_user_name_from($database);
  return $user_name;
}
function get_user_name_from($database) {
  $sql = 'SELECT user_name FROM user WHERE user_id='. $_COOKIE['user-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  $data = $statement->fetchAll(PDO::FETCH_COLUMN,0);
  return $data[0];
}
function get_blog_title_text($title, $text) {
  echo "<div class='content'> ";
  echo "<h2 id='blog-title'>$title/h2> " ;
  echo "<p id='blog-text'>$text</p> " ;
}
function is_there_blog_img($blog_pic) {
  if($blog_pic == null) {
    return false ; 
  }
  return true ;
}
function get_comments_from($database) {
  $sql = 
    'SELECT u.user_filename_profile, c.comment_id, c.blog_id, c.user_id, c.comment_time, c.comment_text 
    FROM comment as c
    INNER JOIN user as u 
    ON u.user_id = c.user_id
    WHERE blog_id ='. $_GET['blog-id'] .' ORDER BY comment_time';
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
} 
function echo_comments($comments) {
  foreach($comments as $comment) {
    echo "<div class='comment'>" ;
      echo "<img src='../images/profile_images/'".echo_comment_user_profile_pic($comment['user_filename_profile']).".png' alt=''>";
        echo "<p class='comment-text'>".$comment['comment_text']."</p>";
        echo "<a href='replay.php?comment-id='".$comment['comment_id']."' title='replay'><i class='fa-regular fa-comment-dots'></i></a>";
    echo "</div>" ;
  }
}
function echo_comment_user_profile_pic($profile_pic_name) {
  if($profile_pic_name!= null) {
    return $profile_pic_name;
  } else {
    return 'default';
  }
}
function is_request_method_post() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    return true;
  }
  return false;
}
function is_request_from_add_comment() {
  if(isset($_POST['add-comment']) && ! (is_null($_POST['add-comment']))) {
    return true;
  }
  return false;
}
function is_new_comment_null() {
  if(is_null($_POST['comment-text'])) {
    return true;
  }
  return false;
}
function add_comment_to($database) {
  $data = [
    'comment_id' => get_id_for_new_comment_from($database),
    'blog_id' => $_GET['blog-id'],
    'user_id' => $_COOKIE['user-id'],
    'comment_time' => "CURRENT_TIMESTAMP",
    'comment_text' => $_POST['comment-text']
  ];
  $sql= 
    'INSERT INTO comment VALUES(:comment_id, :blog_id, :user_id, :comment_time, :comment_text)' ;
  $statement = $database->prepare($sql);
  $statement->execute($data);
}
function get_id_for_new_comment_from($database) {
  $sql = 'SELECT comment_increment FROM increment' ;
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll();
  if($result === null) {
    insert_one_into_increment($database);
    return 1;
  } else {
    $result[1][0] ++ ;
    update_id_from_column_increment($database, $result[1][0]);
    return $result[1][0];
  }
}
function insert_one_into_increment($database) {
  $sql = 'INSERT INTO increment VALUES (?, ?)';
  $statement = $database->prepare($sql);
  $statement->execute([1,null]);
}
function update_id_from_column_increment($database, $number_after_increment) {
  $sql = 'UPDATE increment SET id_increment = ?';
  $statement = $database->prepare($sql)->execute([$number_after_increment]);
}
function error_message_comment() {
  return "comment can't be empty";
}