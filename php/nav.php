<nav>
    <div class="section-1">
      <img src="<?php echo $image_path ?>site_images/logo.png" alt="Bloger" class="circled-img">
      <form action="" method="post">
        <label >
          <input type="text" name="search"
          value='<?php if(isset($_GET['search'])) echo $_GET['search'] ?>' >
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </label>
      </form>
    </div>
    <div class="section-3">
      <a href="<?php echo $aside_path ?>blog.php">
        <img 
          src="<?php echo $image_path ?>profile_images/<?php echo echo_user_profile_pic($database)?>.png" 
          alt="your photo" 
          class="circled-img"
          title= 'Home Page'>
      </a>
    </div>
  </nav>