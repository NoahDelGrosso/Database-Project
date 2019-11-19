<?php
/* models/userModel.php */

class User extends Model {


function __construct() {
  $this->tablename = 'users';
  $this->id_name = 'user_id';
  $this->title_name = 'name';

}

function login($auto_login = FALSE) {
  global $error_string, $message_string, $connection;

  $tablename = $this->tablename;
  $id_name = $this->id_name;
  $sql = 'SELECT '.$id_name.', role, name, email, role_2, university_id FROM '.$tablename.' WHERE email = "'.mysqli_real_escape_string($connection, $_POST['email']).'"'
    .' AND password = "'.mysqli_real_escape_string($connection, $_POST['password']).'";';
  if(DB_DEBUG) print $sql;
  $result = mysqli_query($connection, $sql);
  if(!$result) {
    $error_string = 'Login error';
    return FALSE;
  } else {
    $row = mysqli_fetch_assoc($result);
    if(empty($row['user_id'])) {
      $error_string = 'Login error';
      return FALSE;
    }

    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_role'] = $row['role'];
    $_SESSION['user_name'] = $row['name'];
    $_SESSION['user_email'] = $row['email'];
    $_SESSION['user_university_id'] = $row['university_id'];
    $_SESSION['user_role2'] = $row['role_2'];

    $message_string = 'You are now logged in';
    $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
    $geo = getGeo();
    
    return $row['user_id'];
  }
}


function test() {
  $check = $this->tablename;
  die($check);
}

}
