<?php 
include('php/functions.php');
include('php/connect.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);

?>

<!DOCTYPE html>
<html lang="en">
  <?php include('head.php') ?>
<body>
  <div class="main-content">
    <div class="blog-section">
      
      <?php 

        $blog_content = get_blog($database) ;
        echo_blog($blog_content, $database) 
      ?>
    </div>
    <div class='comment-section'>
      <div class='comment'>
        <?php

          $comment_content = get_comments_from($database);
          echo_comments($comment_content)
        ?>
        <img src='../images/profile_images/' alt=''>
        <p class='comment-text'></p>
        <a href='replay.php?comment-id=?' title='replay'><i class='fa-regular fa-comment-dots'></i></a>
      <!-- //todo if there a reply then make three dots 
        -->
      </div>
      <div class='write-comment'>
        <form action='' method='post'>
          <img src='../images/profile_images/<?php echo_user_profile_pic($database) ?>.png' alt=''>
          <input type='text' name='comment=text' >
          <button type='submit' name='comment'>
            <i class='fa-solid fa-paper-plane'></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

<?php 

function get_blog($database) {
  $sql = 
    'SELECT blog_id, blog_title, blog_text, blog_filename_image,
    blog_time, categories FROM blog WHERE blog_id='. $_GET['blog-id'];
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
}

function echo_blog($blog_data, $database) {

  get_box_user_information_html($database);
  get_blog_title_text($blog_data["blog_title"], $blog_data["blog_text"]) ;
    if(is_there_blog_img($blog_data['blog_blog_filename_image'])) {
      echo "<img src='images/blog_images/".$blog_data['blog_blog_filename_image'].".jpg' alt='' class='blog-img'> " ;
    }
  echo "</div> " ;
  get_comment_like_buttons($blog_data["blog_id"]);
}
function get_comments_from($database) {
  $sql = 
    'SELECT u.user_filename_profile, c.comment_id, c.blog_id, c.user_id, c.comment_time, c.comment_text 
    FROM comment as c
    INNER JOIN user as u 
    ON u.user_id = c.user_id
    WHERE blog_id='. $_GET['blog-id'] .'ORDER BY comment_time';
  $statement = $database->prepare($sql);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_DEFAULT);
} 
function echo_comments($comments) {
  foreach($comments as $comment) {
    echo "<img src='../images/profile_images/'".echo_comment_user_profile_pic($comment['user_filename_profile']).".png alt=''>";
        echo "<p class='comment-text'>".$comment['comment_text']."</p>";
         //todo if there a reply then make three dots 
        echo "<a href='replay.php?comment-id='".$comment['comment_id']." title='replay'><i class='fa-regular fa-comment-dots'></i></a>";
  }
}
function echo_comment_user_profile_pic($profile_pic_name) {
  if($profile_pic_name!= null) {
    return $profile_pic_name;
  } else {
    return 'default';
  }
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

