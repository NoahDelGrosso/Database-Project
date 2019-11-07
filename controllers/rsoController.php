<?php

$rso = new Rso;
include 'models/userModel.php';
function rsoms_create() {
  global $connection, $rso, $message_string;
  $form_fields = array('rso_name');
  if(isset($_POST['submit'])) {
    $valid = check_empty($form_fields);
    if(!$valid) {
      view_error_output('All text fields must be entered');
      $form = view_auto_form('index.php?type=rso&op=create', $fields);
      print $form;
      return;
    } else {
      $user = new User();
      $userRow = $user->get($_SESSION['user_id']);
      $university_id = $userRow['university_id'];
      $rso_email = preg_replace('#.*@(.*)$#', '$1', $userRow['email']);
      $data = array('rso_name' => $_POST['rso_name'], 'university_id' => $university_id, 'rso_email' => $rso_email);
      $rso_id = $rso->create($data);
      $message_string = 'You successfully created rso number <a href="index.php?type=rso&op=display&id="'.$rso_id.'">'.$rso_id.'</a>';
      view_generic_page();
    }
  } else {

    $form = view_auto_form('index.php?type=rso&op=create', $fields);
    print $form;


  }

}
