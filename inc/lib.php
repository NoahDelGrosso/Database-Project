<?php

/* inc/lib.php */

/* view / templating functions */
function site_head() {
  global $page_title, $error_string, $message_string;
  $content = '<html lang="en">
  <head>
  <title>'.$page_title.'</title>
  <link rel="stylesheet" href="css/styles.css">
  </head>
  <body><div id="main"><h1>'.$page_title.'</h1>';
  $content .= $error_string.$message_string;
  return $content;
}

function site_foot() {
  $content = '</div></body>';
  return $content;
}

function check_empty($toValidate, $toCheck = null) {
  if(empty($toCheck)) {
    $toCheck = $_POST;
  }
  $flag = TRUE;
  foreach($toValidate as $k => $v) {
    if(empty($toCheck[$v])) {
      $flag = FALSE;
    }
  }
  return $flag;
}

/* templating functions */

function view_generic_page($string = '') {
  global $error_string, $message_string;
  $content = site_head();
  $content .= $string;
  $content .= site_foot();
  print $content;
}

function view_error_output($string) {
  global $error_string;
  $error_string .= '<span class="error">'. $string . '</span>';
}

function view_message_output($string) {
  global $message_string;
  $message_string .= '<span class="message">'. $string . '</span>';
}

function view_homepage() {

}

function view_auto_form($action, $fields) {
  $content = '<form action="'.$action.'" method="post">';
  foreach($fields as $k=>$v) {
    $machine_name = str_replace(' ', '_', $v);
    $content .= '<label for="'.$machine_name.'">'.$v.'</label><input name="'.$v.'" id="'.$v.'">';
  }
  $content .= '<input type="submit" name="submit" value="Submit">';
  $content .= '</content>';
  return $content;
}
