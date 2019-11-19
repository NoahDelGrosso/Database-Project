<?php

$rso = new Rso;
$mainObject = $rso;
include 'models/userModel.php';
function rsoms_create() {
  global $connection, $rso, $message_string, $error_string, $mainObject;
  user_permission();
  $form_fields = array('rso_name');
  if(isset($_POST['submit'])) {
    $valid = check_empty2($form_fields);
    if(!$valid) {
      view_error_output('All text fields must be entered');
      $form = view_auto_form('index.php?type=rso&op=create', $form_fields);
      print $form;
      return;
    } else {
      $user = new User();
      $userRow = $user->get($_SESSION['user_id']);
      $university_id = $userRow['university_id'];
      $rso_email = preg_replace('#.*@(.*)$#', '$1', $userRow['email']);
      $data = array('rso_name' => $_POST['rso_name'], 'user_id'=> $_SESSION['user_id'], 'university_id' => $university_id, 'rso_email' => $rso_email);
      $rso_id = $mainObject->create($data);
      if($rso_id) $message_string = 'You successfully created rso number <a href="index.php?type=rso&op=display&id='.$rso_id.'">'.$rso_id.'</a>';
      else $error_string = 'Problem in creating rso';
      view_generic_page();
    }
  } else {
    view_create_rso_form();


  }

}

function rsoms_display($id) {
  global $mainObject;
  $mainObject->config['get']['where'] = 'a.rso_id="'.$id.'"';
  $rsoRow = $mainObject->get($id);
  view_generic_object($rsoRow, 'Display Individual RSO');
}

function rsoms_list() {
    global $mainObject, $page_title;
    user_permission(0, false, true);
    $joins = array();
    $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
    $joins[] = $join;
    $join = array('table' => 'users us', 'on' => 'a.user_id = us.user_id');
    $joins[] = $join;
    if(!empty($_SESSION['user_id'])) {
      $join = array('table' => 'rso_memberships r', 'on' => 'a.rso_id = r.rso_id AND r.user_id = "'.intval($_SESSION['user_id']).'"');
      $joins[] = $join;
    }
    if($_SESSION['user_role'] < 4) $where = 'a.university_id = "'.$_SESSION['user_university_id'].'"';
    $limit = array('rso_name', 'rso_email', 'rso_official', 'university_name', 'name');
    $ops = array(

      array('yes_row' => 'rso_membership_id', 'name' => 'leave'),
      array('no_row' => 'rso_membership_id', 'name' => 'join'));
    $params = array('limit'=>$limit, 'joins'=>$joins, 'ops' => $ops, 'where' => $where);
    $mainObject->config['list'] = $params;

    $rows = $mainObject->list();
    $content = view_list($mainObject, $rows);
    view_generic_page($content);

}

function rsoms_join($id){
  global $mainObject, $connection, $message_string, $error_string;
  $sql = 'INSERT INTO rso_memberships (user_id, rso_id) VALUES ("'.intval($_SESSION['user_id']).'", "'.intval($id).'");';
  $result = $mainObject->send_query($sql);
  if($result) {
    $message_string .= '<br>You have subscribed / joined this RSO';
    update_rso_official($id);

    view_generic_page('<br>Back to <a href="index.php?type=rso&op=list">rso list</a>');

  }
}

function rsoms_leave($id){
  global $mainObject, $message_string, $error_string;
  $sql = 'DELETE FROM rso_memberships WHERE user_id = "'.intval($_SESSION['user_id']).'" AND rso_id = "'.intval($id).'";';
  $result = $mainObject->send_query($sql);
  if($result) {
    $message_string .= '<br>You have unsubscribed / left this RSO';
    update_rso_official($id);
    view_generic_page('<br>Back to <a href="index.php?type=rso&op=list">rso list</a>');
  }
}

function update_rso_official($id){
  global $mainObject;
  $sql = 'SELECT COUNT(*) FROM rso_memberships WHERE rso_id = "'.intval($id).'";';
  $result = $mainObject->send_query($sql);
  $row = mysqli_fetch_row($result);
  if($row[0] >= 5) {
    $sql = 'UPDATE rsos SET rso_official = "1" WHERE rso_id = "'.intval($id).'"';
  } else {
    $sql = 'UPDATE rsos SET rso_official = "0" WHERE rso_id = "'.intval($id).'"';
  }
  $result = $mainObject->send_query($sql);
  if($row[0] >= 5) {
    $sql = 'UPDATE users SET role ="1" WHERE role="0" AND user_id="'.$_SESSION['user_id'].'"';
  }
}



function view_create_rso_form() {
  global $page_title;
  $page_title = 'Create RSO';
  $form_fields = array('rso_name');
  $content = '';
  $content .= site_head();
  $content .= view_auto_form2('index.php?type=rso&op=create', $form_fields);
  $content .= site_foot();
  print $content;
}
