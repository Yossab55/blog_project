<?php 

function get_script_name() {
  $result = basename($_SERVER['PHP_SELF'], '.php');
  return ($result == 'index') ? 
    'main' : $result;
}

function get_css_path_name_depend_on($script_name) {
  if($script_name == 'main') 
    return 'css/' ;
  else return  '../css/'; 
}
function get_aside_path_depend_on($script_name) {
  if($script_name == 'main')
    return 'php/';
  else return '';
}
function get_image_path_depend_on($script_name) {
  if($script_name == 'main')
    return 'images/';
  else return '../images/';
}