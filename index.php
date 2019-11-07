<?php
/******************
rsoms (registered student organization management system)
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
5 relational tables

controllers/
userController.php
universityController.php

MODELS do our basic database storage.
CONTROLLERS do logic on the data gotten from the database
VIEWS present interface to user

CRUD type controller [ model view controller - mvc ]
- create
- update
- delete

url structure:
index.php?type=user&op=delete&id=8

*******************/

include 'config.php'; // connect to db
include 'inc/lib.php';
include 'models/model.php';

if(!empty($_GET['type'])) $type = $_GET['type']; //\
else $type = FALSE;
// For actual deployment, filter with regexes to check for all lowercase
if(!empty($_GET['id'])) $id = intval($_GET['id']);
else $id = FALSE;
if(!empty($_GET['op'])) $op = $_GET['op'];
else $op = FALSE;
$page_title = '';
$error_string = '';
$message_string = '';

// load model classes and controller functions.
switch($type) {
  case FALSE:
  print 'display homepage func';
  print '<a href="index.php?type=user&op=create">Create User</a>';
  break;

  case 'user':
  include 'models/userModel.php';
  include 'controllers/userController.php'; // controller files also include views functionality
  break;

  case 'university':
  include 'models/universityModel.php';
  include 'models/universityController.php';
  break;

  case 'rso':
  include 'models/rsoModel.php';
  include 'models/rsoModelController.php';
  break;

}

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

  case 'display':
  rsoms_display($id);
  break;

  case 'list':
  rsoms_list();
  break;

  case 'login':
  rsoms_login();
  break;

  case 'logout':
  rsoms_logout();
  break;
}
