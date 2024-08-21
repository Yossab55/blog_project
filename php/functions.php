<?php 

function get_script_name() {
  $result = basename($_SERVER['PHP_SELF'], '.php');
  return ($result == 'index') ? 
    'main' : $result;
}

function get_path_name_depend_on($script_name) {
  if($script_name == 'main') 
    return $path = 'css/' ;
  else return $path = '../css/'; 
}