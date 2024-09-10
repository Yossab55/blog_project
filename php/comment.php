<?php 
include('functions.php');
include('connect.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);
$is_error = false;
if(is_request_method_post()) {
  if(is_request_from_add_comment()) {
    if(!  is_new_comment_null()) {
      add_comment_to($database);
      header("Location: comment.php?blog-id=".$_GET['blog-id']);
    } else {  
      $is_error = true;
    }
  }
  if(is_request_from_add_replay()) {
    if(!  is_new_replay_null()) {
      add_replay_to($database);
      header("Location: comment.php?blog-id=".$_GET['blog-id']."&comment-id=".$_GET['comment-id']);
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
            if(is_comment()) {
              $comments_content = get_comments_from($database);
              if(count($comments_content) !== 0) {
                echo_comments($comments_content);
              } else {
                echo "<div class='comment no-comment'>";
                  echo "<p>There is no comment!!, be the first one</p>";
                echo "</div>";
              }
            }
            if(is_replay()) {
              $comment_content = get_comment_from($database);
              echo_comments($comment_content);
              $replays_content = get_replays($database);
              if(count($replays_content) !== 0) {
                echo_replays($database, $replays_content);
              } else {
              echo "<div class='comment no-comment'>";
                echo "<p>There is no replays!!, be the first one who reply</p>";
              echo "</div>";
              }
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
          <input type='text' name='<?php echo determine_comment_or_replay_text() ?>' >
          <button type='submit' name='comment' value='<?php echo determine_add_comment_or_replay() ?>'>
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
  $sql = 'SELECT u.user_name FROM user AS u
  WHERE u.user_id = (SELECT b.blog_id FROM blog as b 
  WHERE b.blog_id ='. $_GET['blog-id'] . ')';
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
        echo "<a href='comment.php?blog-id=".$_GET['blog-id']."&comment-id=".$comment['comment-id']."'
        title='replay'><i class='fa-regular fa-comment-dots'></i></a>";
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
function is_request_from_add_replay() {
  if(isset($_POST['add-replay']) && ! (is_null($_POST['add-replay']))) {
    return true;
  }
  return false;
}
function is_new_replay_null() {
  if(is_null($_POST['replay-text'])) {
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
    insert_one_comment_into_increment($database);
    return 1;
  } else {
    $result[0][1] ++ ;
    update_comment_from_column_increment($database, $result[0][1]);
    return $result[0][1];
  }
}
function insert_one_comment_into_increment($database) {
  $sql = 'INSERT INTO increment VALUES (?, ?, ?)';
  $statement = $database->prepare($sql);
  $statement->execute([null,1,null]);
}
function update_id_from_column_increment($database, $number_after_increment) {
  $sql = 'UPDATE increment SET id_increment = ?';
  $statement = $database->prepare($sql)->execute([$number_after_increment]);
}
function error_message_comment() {
  return "comment can't be empty";
}
function is_comment() {
  $query = explode('&',$_SERVER['QUERY_STRING']);
  $size = count($query);
  if($size === 1) {
    return true;
  }
  return false;
}
function is_replay() {
  $query = explode('&',$_SERVER['QUERY_STRING']);
  $size = count($query);
  if($size > 1) {
    return true;
  }
  return false;
}
function get_comment_from($database) {
  $sql = 
    'SELECT u.user_filename_profile, c.comment_id, c.blog_id, c.user_id, c.comment_text 
    FROM comment as c
    INNER JOIN user as u 
    ON u.user_id = c.user_id
    WHERE c.blog_id = '. $_GET['blog-id'] .' AND c.comment_id = '. $_GET['comment-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
}
function get_replays() {
  $sql = 
    'SELECT u.user_filename_profile, r.replay_text, r.replay_time 
    FROM replay as y
    INNER JOIN user as u
    ON u.user_id = r.user_id
    WHERE user_id ='.$_COOKIE['user-id'] .' ORDER BY r.replay_time';
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
}
function echo_replays($database, $replays_content) {
  foreach($replays_content as $replay) {
    echo "<div class='replay comment'>";
      echo "<img src='../images/profile_images/".echo_comment_user_profile_pic($comment['user_filename_profile']).".png' alt='' class='circled-img'>";
      echo "<p>".$replay['replay_text']."</p>";
    echo "</div>";
  }
}
function determine_add_comment_or_replay() {
  if(is_comment()) {
    return "add-comment";
  } else return "add-replay";
}
function determine_comment_or_replay_text() {
  if(is_comment()) {
    return "comment-text";
  } else return "replay-text";
}
function add_replay_to($database) {
  $data = [
    'replay_id' => get_id_for_new_replay_from($database),
    'user_id' => $_COOKIE['user-id'],
    'comment_id' => $_GET['comment-id'],
    'replay_time' => "CURRENT_TIMESTAMP",
    'replay_text' => $_POST['replay-text']
  ];
  $sql= 
    'INSERT INTO comment VALUES(:replay_id, :user_id, :comment_id, :replay_time, :replay_text)' ;
  $statement = $database->prepare($sql);
  $statement->execute($data);
}
function get_id_for_new_comment_from($database) {
  $sql = 'SELECT comment_increment FROM increment' ;
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll();
  if($result === null) {
    insert_one_replay_into_increment($database);
    return 1;
  } else {
    $result[0][2] ++ ;
    update_replay_from_column_increment($database, $result[0][2]);
    return $result[0][2];
  }
}
function insert_one_replay_into_increment($database) {
  $sql = 'INSERT INTO increment VALUES (?, ?, ?)';
  $statement = $database->prepare($sql);
  $statement->execute([null, null, 1]);
}
function update_replay_from_column_increment($database, $number_after_increment) {
  $sql = 'UPDATE increment SET replay_increment = ?';
  $statement = $database->prepare($sql)->execute([$number_after_increment]);
}