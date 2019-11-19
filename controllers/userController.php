<?php
/* userController.php */


$user = new User();
$mainObject = $user;
include 'models/universityModel.php';

function rsoms_create() {
  global $connection, $user;
  $superAdminFlag = FALSE;
  if(isset($_POST['submit'])) {
    // validate user
    // we will use checkbox for permission to make user Superadmin if necessary
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

    if(!empty($university_id)) {

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

      }
    }
    if($valid) {
      $data = $_POST;
      unset($data['permission']);
      unset($data['submit']);
      unset($data['university']);
      // 0 = student; 1 = admin; 2 = superAdmin
      if($superAdminFlag) $data['role_2'] = 2;
      else $data['role_2'] = 0;
      $data['role'] = 0;
      $data['university_id'] = $university_id;
      $user_id = $user->create($data);
      if(!$user_id) {
        view_error_output('Could not create user');
        view_generic_page('', TRUE);
      } else {
        $login_id = $user->login(TRUE);
        if($login_id <> $user_id) {
          view_error_output('Login Problem');
          view_generic_page('', TRUE);
        }
      }


      if($superAdminFlag) {
        $where = $university_id;
        $data = array('university_superadmin' => $user_id);
        $university->update($where, $data);
        header('Location:index.php?type=university&op=edit&id='.$university_id);

        view_user_page('Edit university <a href="index.php?type=university&op=edit&id='.$university_id.'">here</a>');
      } else {
        view_user_page();
      }
    } else {

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
  view_user_page($id);
}

function rsoms_list() {
  global $mainObject, $page_title;
  $page_title = 'List of Users';
  $limit = FALSE;
  $joins = array();
  $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
  $joins[] = $join;

  $params = array('limit' => $limit, 'joins' => $joins);
  $mainObject->config['list'] = $params;
  $rows = $mainObject->list();
  $content = view_list($mainObject, $rows);

  view_generic_page($content);
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

  session_unset();
  view_user_page();

}

function rsoms_goto_login() {
  header('Location: index.php?type=user&op=login');
}



function view_user_login() {
  global $page_title;
  $page_title = 'Log in';

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
  global $page_title;
  $page_title = 'Create User';

  $content = '';
  $content .= site_head();

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

function view_user_page($id=null, $html = FALSE) {
  global $page_title, $user;
  $page_title = 'User Page';
  if(!$id) {
    if(empty($_SESSION['user_id'])){

      $content = site_head();
      $content .=  'Please <a href="index.php?type=user&op=login">Log in</a>';
      $content .= site_foot();
      print $content;
    }
    else $id = $_SESSION['user_id'];
  }

  if(!empty($id)) {
    $user_content = $user->get($id);
    $logoutString = 'Hello User #'.htmlspecialchars($id).'<br><a href="index.php?type=user&op=logout">Log out</a>';
    view_generic_object($user_content, 'User Page', $logoutString);

  }
  else {
    $content = site_head();
    $content .=  'Please <a href="index.php?type=user&op=login">Log in</a>';
    $content .= site_foot();
    print $content;
  }

}
