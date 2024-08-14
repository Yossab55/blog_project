<?php
$script_name = basename($_SERVER['PHP_SELF'], ".php")  ;

// get_id_for_new_user($database);
if(check_request_submit()) {
  $size = count($_POST);
  $start_count = 0 ;
  $data_is_null = [];
  foreach($_POST as $data) {
    if($data == null) $data_is_null[$start_count] = false ;
    else $data_is_null[$start_count] = true ;
    $start_count++ ;
  }
  if(is_all_null($data_is_null)) {
    //todo i want to but every input is null an error message like a span on the month input


    break ;
  }
  include('connect.php');
  //todo insert values in user table and that's it good luck don't 
  //note don't forget to but id user also have a good time brother
}

?>
<!DOCTYPE html>
<html lang="en">
  <?php include("head.php"); ?> 
  <body>
      <div class="container">
        <div class="sign-up">
          <div class="information-section">
            <img src="../images/site_images/logo.png" >
            <div class="title">
              <h2>Welcome To Bloger </h2>
              <p>join our community</p>
            </div>
            <div class="form-section">
              <form action="" method="post">
                <label for="f-name">first name</label>
                <input type="text" name="first-name" id="f-name">
                <label for="l-name">last name</label>
                <input type="text" name="last-name" id="l-name">
                <label for="email">email</label>
                <input type="email" name="email" id="email">
                <!-- I'm seeing wether let the user write his birth day or choose it  -->
                <?php /*
                <select size="1" name="day">
                  <option value="01" label="1st">1st</option>
                  <option value="02" label="2nd">2nd</option>
                  <option value="03" label="3rd">3rd</option>
                  <option value="04" label="4th">4th</option>
                  <option value="05" label="5th">5th</option>
                  <option value="06" label="6th">6th</option>
                  <option value="07" label="7th">7th</option>
                  <option value="08" label="8th">8th</option>
                  <option value="09" label="9th">9th</option>
                  <option value="10" label="10th">10th</option>
                  <option value="11" label="11th">11th</option>
                  <option value="12" label="12th">12th</option>
                  <option value="13" label="13th">13th</option>
                  <option value="14" label="14th">14th</option>
                  <option value="15" label="15th">15th</option>
                  <option value="16" label="16th">16th</option>
                  <option value="17" label="17th">17th</option>
                  <option value="18" label="18th">18th</option>
                  <option value="19" label="19th">19th</option>
                  <option value="20" label="20th">20th</option>
                  <option value="21" label="21st">21st</option>
                  <option value="22" label="22nd">22nd</option>
                  <option value="23" label="23rd">23rd</option>
                  <option value="24" label="24th">24th</option>
                  <option value="25" label="25th">25th</option>
                  <option value="26" label="26th">26th</option>
                  <option value="27" label="27th">27th</option>
                  <option value="28" label="28th">28th</option>
                  <option value="29" label="29th">29th</option>
                  <option value="30" label="30th">30th</option>
                  <option value="31" label="31st">31st</option>
                </select>
                <select size="1" name="month">
                  <option value="01" label="Jan">Jan</option>
                  <option value="02" label="Feb">Feb</option>
                  <option value="03" label="Mar">Mar</option>
                  <option value="04" label="Apr">Apr</option>
                  <option value="05" label="May">May</option>
                  <option value="06" label="Jun">Jun</option>
                  <option value="07" label="Jul">Jul</option>
                  <option value="08" label="Aug">Aug</option>
                  <option value="09" label="Sep">Sep</option>
                  <option value="10" label="Oct">Oct</option>
                  <option value="11" label="Nov">Nov</option>
                  <option value="12" label="Dec">Dec</option>
                </select>
                <select size="1" name="year">
                  <option value="1950" label="1950">1950</option>
                  <option value="1951" label="1951">1951</option>
                  <option value="1952" label="1952">1952</option>
                  <option value="1953" label="1953">1953</option>
                  <option value="1954" label="1954">1954</option>
                  <option value="1955" label="1955">1955</option>
                  <option value="1956" label="1956">1956</option>
                  <option value="1957" label="1957">1957</option>
                  <option value="1958" label="1958">1958</option>
                  <option value="1959" label="1959">1959</option>
                  <option value="1960" label="1960">1960</option>
                  <option value="1961" label="1961">1961</option>
                  <option value="1962" label="1962">1962</option>
                  <option value="1963" label="1963">1963</option>
                  <option value="1964" label="1964">1964</option>
                  <option value="1965" label="1965">1965</option>
                  <option value="1966" label="1966">1966</option>
                  <option value="1967" label="1967">1967</option>
                  <option value="1968" label="1968">1968</option>
                  <option value="1969" label="1969">1969</option>
                  <option value="1970" label="1970">1970</option>
                  <option value="1971" label="1971">1971</option>
                  <option value="1972" label="1972">1972</option>
                  <option value="1973" label="1973">1973</option>
                  <option value="1974" label="1974">1974</option>
                  <option value="1975" label="1975">1975</option>
                  <option value="1976" label="1976">1976</option>
                  <option value="1977" label="1977">1977</option>
                  <option value="1978" label="1978">1978</option>
                  <option value="1979" label="1979">1979</option>
                  <option value="1980" label="1980">1980</option>
                  <option value="1981" label="1981">1981</option>
                  <option value="1982" label="1982">1982</option>
                  <option value="1983" label="1983">1983</option>
                  <option value="1984" label="1984">1984</option>
                  <option value="1985" label="1985">1985</option>
                  <option value="1986" label="1986">1986</option>
                  <option value="1987" label="1987">1987</option>
                  <option value="1988" label="1988">1988</option>
                  <option value="1989" label="1989">1989</option>
                  <option value="1990" label="1990">1990</option>
                  <option value="1991" label="1991">1991</option>
                  <option value="1992" label="1992">1992</option>
                  <option value="1993" label="1993">1993</option>
                  <option value="1994" label="1994">1994</option>
                  <option value="1995" label="1995">1995</option>
                  <option value="1996" label="1996">1996</option>
                  <option value="1997" label="1997">1997</option>
                  <option value="1998" label="1998">1998</option>
                  <option value="1999" label="1999">1999</option>
                  <option value="2000" label="2000">2000</option>
                  <option value="2001" label="2001">2001</option>
                  <option value="2002" label="2002">2002</option>
                  <option value="2003" label="2003">2003</option>
                  <option value="2004" label="2004">2004</option>
                  <option value="2005" label="2005">2005</option>
                  <option value="2006" label="2006">2006</option>
                  <option value="2007" label="2007">2007</option>
                  <option value="2008" label="2008">2008</option>
                  <option value="2009" label="2009">2009</option>
                  <option value="2010" label="2010">2010</option>
                  <option value="2011" label="2011">2011</option>
                  <option value="2012" label="2012">2012</option>
                  <option value="2013" label="2013">2013</option>
                  <option value="2014" label="2014">2014</option>
                  <option value="2015" label="2015">2015</option>
                </select>
                */ ?>
                <label for="dob">date of birth</label>
                <input type="text" name="date-of-birth" id="dob" placeholder='DD/MM/YYYY'>
                <span class="dmy">day, month, year</span>
                <label for="pass">
                  <span>password</span>
                  <button type="button" name='eye' id= 'button_eye'>
                  <i class='fa-solid fa-eye-slash'></i> 
                  </button>
                </label>
                <input type="password" name="password" id="pass" >
                <button type="submit" value='signup'>
                  Sign Up
                </button>
              </form>
            </div>
            </div>
            
          <div class="image-section">
            <img src="../images/site_images/signup.png" >
          </div>
        </div>
      </div>
    </div>
    <script src="../js/functions.js" type='module'></script>
    <script src="../js/signup.js" type='module'></script>
  </body>
</html>


<?php
function check_request_submit() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!(is_null($_POST)))
    return true ;
  }
  return false;
}
function is_all_null($data_is_null) {
  foreach($data_is_null as $boolean) {
    if($boolean) continue ;
    else return false ;
  }
  return true ;
}
function get_id_for_new_user($database) {
  $sql = 'SELECT id_increment FROM increment' ;
  $statement = $database->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll();
  if($result[0][0] === null) {
    insert_one_into_increment($database);
    return 1;
  } else {
    $result[0][0] ++ ;
    update_id_from_column_increment($database, $result[0][0]);
    return $result[0][0];
  }
}

function update_id_from_column_increment($database, $number_after_increment) {
  $sql = 'UPDATE increment SET id_increment = ?';
  $statement = $database->prepare($sql)->execute([$number_after_increment]);
}
function insert_one_into_increment($database) {
  $sql = 'INSERT INTO increment VALUES (?, ?)';
  $statement = $database->prepare($sql);
  $statement->execute([1,1]);
}