<?php
//todo you need to make add content button in the blogs if the user doesn't have friend 
include('php/functions.php');
include('php/connect.php');
$script_name = get_script_name();
$path = get_path_name_depend_on($script_name);
setcookie('user-id',1 ,time() + strtotime('+1 year'), '/');
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
      <?php //blog_structure_html($database) ?>
      <div class='box'> 
    <div class='user-information'> 
      <div class="content">
        <img src='images/profile_images/default.png' alt='' class ='circled-img'> 
        <span class='user-name'>user name </span> 
      </div>
      <form action= 'index.php' method= 'get'>
        <button type='submit' name='add-content' value='?'>
          <span class='button'>Add Contact </span>
          <i class='fa-solid fa-plus'></i>
        </button>
      </form>
    </div> 
    <h2 id='blog-title'>Hello world</h2> 
    <p id='blog-text'>I'm a new user in bloger, I'm trying to learn how to make a nodes</p> 
    <img src='images/blog_images/test.jpg' alt='' class='blog-img'> 
  </div> 
    </section>
  </div>
</body>
</html>

<?php
function is_user_have_friend_blogs($database) {
  $result = get_blogs($database);
  if($result === null) {
    //todo there should be function to get random blogs from user 
  } else {
    //todo function to get all user blogs and sort by date !!
  }
}
function blog_structure_html($database) {
  echo "<div class='box'> " ;
    echo "<div class='user-information'> " ;
      echo "<div class='content'>" ;
        echo "<img src='images/profile_images/".echo_user_profile_pic($database).".png' alt='' class ='circled-img'>" ;
        echo "<span class='user-name'>user name </span> " ;
      echo "</div>" ;
      //todo if he is a friend from the first why i but a button 
      echo "<form action= 'index.php' method= 'get'>" ;
        echo "<button type='submit' name='add-content' value='?'>" ;
          echo "<span class='button'>Add Contact </span>" ;
          echo "<i class='fa-solid fa-plus'></i>" ;
        echo "</button>" ;
      echo "</form>" ;
    echo "</div> " ;
    echo "<h2 id='blog-title'>Hello world</h2> " ;
    echo "<p id='blog-text'>I'm a new user in bloger, I'm trying to learn how to make a nodes</p> " ;
    //todo it should be blog_images instead of profile images and there si no default 
    // if there is no image so don't put an image 
    echo "<img src='images/blog_images/test.jpg' alt='' class='blog-img'> " ;
  echo "</div> " ;
}
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

function is_there_a_blog_in($database) {
  $data = get_blogs($database);
  if($data == null) return false ;
  return true ;
}
function get_blogs($database) {
  // * done 
  //this sql statment is wrong you will need to make selection with user friend then take user friend 
  // then take user friend id which is user id  and get there blogs first then sort is by the date . 
  //? i think this is the right we will find in the near future 
    $sql = 
      'SELECT f.friend_id, b.blog_title, b.blog_text, b.blog_filename_image,
      b.blog_time, b.categories
      FROM  userfriend as f
      INNER JOIN blog as b 
      on b.blog_id = f.friend_id 
      WHERE f.friend_id =' . $_COOKIE['user-id'];
    $statement = $database->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_DEFAULT);
}
