<?php
/* userController.php */

//global $user;
$user = new User();
include 'models/universityModel.php';

function rsoms_create() {
  global $connection, $user;
  $superAdminFlag = FALSE;
  /* debugging code */
  //$test = new User();
  //$test->test();
  if(isset($_POST['submit'])) {
    // validate user
    /* Note: we will use checkbox for permission to make user Superadmin if necessary */
    $toValidate = array('name', 'email', 'password', 'university');
    $valid = check_empty($toValidate);
    if(!$valid) {
      view_error_output('All text fields must be entered');
      view_create_user_form();
      return;
    }
    $query = 'SELECT university_id FROM universities WHERE university_name = "'.mysqli_real_escape_string($connection, $_POST['university']).'"';
    print $query;
    $result = mysqli_query($connection, $query);
    if(!$result) {
      print mysqli_error($connection);
    }
    $university_id = '';
    while($row = mysqli_fetch_assoc($result)) {
      print '<pre>'; print_r($row); print '</pre>';
      $university_id = $row['university_id'];
    }
    //$row = mysqli_fetch_row($result);
    if(!empty($university_id)) {
        // nothing here to do (yet)
    } else {
      if(empty($_POST['permission'])) {
        $valid = FALSE;
        view_error_output('You can only register for a new university if you are willing to be Superadmin');
        view_create_user_form();
        return;
      } else {
        $superAdminFlag = TRUE;
        view_message_output('System will make you SuperAdmin');
        $university = new University();
        $data = array('university_name' => $_POST['university']);
        $university_id = $university->create($data);
        /*$sql = 'INSERT INTO universities (university_name) '
        .'VALUES ("'.mysqli_real_escape_string($connection, $_POST['university']).'")';
        $result = mysqli_query($connection, $sql);
        if(!$result) {
          print mysqli_error($connection);
        }*/
        //$university_id = mysqli_insert_id($connection);
        /* code for inserting university row, must set $university_id = new row id
        with mysqli_insert_id($connection) */

      }
    }
    if($valid) {
      $data = $_POST;
      unset($data['permission']);
      unset($data['submit']);
      unset($data['university']);
      // 0 = student; 1 = admin; 2 = superAdmin
      if($superAdminFlag) $data['role'] = 2;
      else $data['role'] = 0;
      $data['university_id'] = $university_id;
      $user_id = $user->create($data);
      if($superAdminFlag) {
        $where = array('fieldname' => 'university_id', 'value' => $university_id);
        $data = array('university_superadmin' => $user_id);
        $university->update($where, $data);
        view_user_page();
      }
    } else {
      //view_error_output('All text fields must be entered');
      view_create_user_form();
      return;
    }

  } else {
    view_create_user_form();

  }
}

function rsoms_update($id) {

}

function rsoms_delete($id) {

}

function rsoms_display($id) {

}

function rsoms_list() {

}

function rsoms_login() {
  global $user, $error_string;
    if(isset($_POST['submit'])) {
      $user_id = $user->login();
      if($user_id) {
        view_user_page();
      } else {
        view_user_login();
      }
    } else {
      if(isset($_SESSION['user_id'])) {
        view_user_page();
      } else {
        view_user_login();
      }
    }
}

function rsoms_logout() {
  unset($_SESSION['user_id']);
  header('Location: index.php');
}

/* view functions */
/*
function view_user_logout() {
  header('Location: index.php');
}*/

function view_user_login() {
  global $page_title;
  $page_title = 'Log in';
  print_r($_SESSION);
  $content = site_head();
  $content .= '<form action="index.php?type=user&op=login" method="post">';
  $content .= '<label for="email">Email:</label><input  name="email" id="email">';
  $content .= '<label for="password">Password:</label><input type="password" id="password" name="password">';
  $content .= '<input type="submit" name="submit" value="Submit">';
  $content .= '</form>';
  $content .= site_foot();
  print $content;
}

function view_create_user_form() {
  global $page_title; // $error_string;
  $page_title = 'Create User';
  //die('stop on '.__LINE__);
  $content = '';
  $content .= site_head();
  //$content .= $error_string;
  $content .= '<form action="index.php?type=user&op=create" method="post">';
  $content .= '<label for="name">Name</label><input id="name" name="name">';
  $content .= '<label for="email">Email</label><input id="email" name="email">';
  $content .= '<label for="password">Password</label><input id="password" name="password">';
  $content .= '<label for="university">University</label><input id="university" name="university">';
  $content .= '<label for="permission">Are you willing to be Superadmin if you are the first student of your university?</label>'
    .'<input id="permission" name="permission" type="checkbox">';
  $content .= '<input type="submit" name="submit" value="Submit">';
  $content .= '</form>';

  $content .= site_foot();
  print $content;
}

function view_user_page() {
  global $page_title;
  $page_title = 'User Page';
  $content = site_head();
  if($_SESSION['user_id']) {
    $content .= 'Hello User #'.htmlspecialchars($_SESSION['user_id']);
    $content .= '<a href="index.php?type=user&op=logout">Log out</a>';
  }
  else {
    $content .=  'Please <a href="index.php?type=user&op=login">Log in</a>';
  }
  $content .= site_foot();
  print $content;
}
