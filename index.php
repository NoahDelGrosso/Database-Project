<?php
/*
rsoms
config.php
inc/ :
lib.php (library of functions)
models/
model.php (main model)
userModel.php (user class)
universityModel.php (university class)
rsoModel.php (rso class)
eventModel.php (event class)
commentModel.php (comment class)

controllers/
userController.php
universityController.php
commentController.php
rsoController.php
eventController.php

Models do database storage.
Controllers do logic on data taken from the database
Views present UI

url structure:
index.php?type=user&op=delete&id=8
*/
require_once 'inc/lib.php';
require_once 'models/model.php';
require_once 'config.php'; // connect to db



if(!empty($_GET['type'])) $type = $_GET['type'];
else $type = FALSE;
if(!empty($_GET['id'])) $id = intval($_GET['id']);
else $id = FALSE;
if(!empty($_GET['op'])) $op = $_GET['op'];
else $op = FALSE;
$page_title = '';
$error_string = '';
$message_string = '';
$current_user = array();
if(isset($_SESSION['user_id'])) {
  $current_user = get_user_info();
} else {
  if (!empty($type) && $type <> 'user') {
    header('Location: index.php');
    exit();
  }
  if (!empty($op) && !in_array($op, array('login', 'create')) ) {
    header('Location: index.php');
    exit();
  }

}

switch($type) {

  case 'user':
  include 'models/userModel.php';
  include 'controllers/userController.php';
  break;

  case 'university':
  include 'models/universityModel.php';
  include 'controllers/universityController.php';
  break;

  case 'rso':
  include 'models/rsoModel.php';
  include 'controllers/rsoController.php';
  break;

  case 'event':
  include 'models/eventModel.php';
  include 'controllers/eventController.php';
  break;

  case FALSE:
  default:
  view_homepage();

}

include 'inc/default_funcs.php';
switch($op) {
  case 'create':
  rsoms_create();
  break;

  case 'update':
  rsoms_update($id);
  break;

  case 'delete':
  rsoms_delete($id);
  break;

  case 'edit':
  rsoms_edit($id);
  break;

  case 'display':
  rsoms_display($id);
  break;

  case 'list':
  rsoms_list();
  break;

  case 'join':
  rsoms_join($id);
  break;

  case 'leave':
  rsoms_leave($id);
  break;

  case 'login':
  rsoms_login();
  break;

  case 'logout':
  rsoms_logout();
  break;

  case 'approve':
  rsoms_approve();
  break;

  case 'test':
  rsoms_test();
  break;

  case 'test2':
  rsoms_test2();
  break;
}
