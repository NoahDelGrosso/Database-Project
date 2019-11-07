<?php
/* models/userModel.php */

class User extends Model {
//private $tablename = 'users';

function __construct() {
  $this->tablename = 'users';
  $this->id_name = 'user_id';
  //die('done');
}

function login() {
  global $error_string, $message_string, $connection;
  $tablename = $this->tablename;
  $id_name = $this->id_name;
  $sql = 'SELECT '.$id_name.' FROM '.$tablename.' WHERE email = "'.mysqli_real_escape_string($connection, $_POST['email']).'"'
    .' AND password = "'.mysqli_real_escape_string($connection, $_POST['password']).'";';
  if(DEBUG) print $sql;
  $result = mysqli_query($connection, $sql);
  if(!$result) {
    $error_string = 'Login error';
    return FALSE;
  } else {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $row['user_id'];
    $message_string = 'You are now logged in';
    return $row['user_id'];
  }
}


function test() {
  $check = $this->tablename;
  die($check);
}

}
