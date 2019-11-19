<?php

require_once 'models/userModel.php';
require_once 'models/universityModel.php';
require_once 'models/rsoModel.php';
$event = new Event();
global $mainObject, $rso;
$mainObject = $event;
$university = new University();



function rsoms_create() {
  global $connection, $event, $message_string, $error_string, $form_fields, $page_title, $mainObject, $rso, $prepopulate_values;
  $form_fields = $mainObject->form_fields;
  if(!isset($_SESSION['user_role']) OR $_SESSION['user_role'] < 2) {
    unset($form_fields['event_type']['options'][0]);
  }
  if(!empty($_GET['rso'])) $prepopulate_values['event_type'] = 0;
  $page_title = 'Create Event';
  if(!empty($_GET['rso'])) $page_title = 'Create RSO Event';
  user_permission();

  if(isset($_POST['submit'])) {

    $valid = check_empty2($form_fields, null, array('event_type', 'rso_id'));
    if(!$valid['flag']) {
      print "Error error error";
      print_r($valid);
      view_error_output('All text fields must be entered');
      foreach($valid['errorKeys'] as $k=>$v){
        view_error_output('Need input for field '.$v);
      }
      view_create_event_form();

      return;
    } else {
      $user = new User();
      $userRow = $user->get($_SESSION['user_id']);
      $university_id = $userRow['university_id'];
      $rso_email = preg_replace('#.*@(.*)$#', '$1', $userRow['email']);

      $data = populate_data();
      $data['event_email'] = $rso_email;
      unset($data['university_name']);
      $data['university_id'] = $university_id;
      $data['user_id'] = $_SESSION['user_id'];
      $matches = array();
      $point_match = preg_match('#^\s*(-?[0-9]+\.[0-9]+)\s*,\s*(-?[0-9]+\.[0-9]+)\s*$#', $_POST['event_point'], $matches);

      if(!$point_match) {

        view_error_output('Point must be in format like 24.21,59.12');
        view_create_event_form();
        return;
      }
      $data['event_point'] = array();
      $data['event_point']['type'] = 'point';
      $data['event_point']['latitude'] = $matches[1];
      $data['event_point']['longitude'] = $matches[2];

      $phpDate = strtotime($data['event_date']);
      $mysqlDate = date('Y-m-d H:i:s', $phpDate);
      $data['event_date'] = $mysqlDate;


      $event_id = $mainObject->create($data);

      if($event_id) $message_string = 'You successfully created event number <a href="index.php?type=event&op=display&id='.$event_id.'">'.$event_id.'</a>';
      else $error_string = 'Problem in creating event';
      view_generic_page();
    }
  } else {
    view_create_event_form();


  }
}

function rsoms_edit($id) {
  global $connection, $event, $message_string, $error_string, $form_fields, $page_title, $mainObject, $id, $rso;
  $form_fields = $mainObject->form_fields;
  $page_title = 'Edit Event';

  unset($form_fields['event_type']);
  unset($mainObject->form_fields);
  user_permission();
  if(isset($_POST['submit'])) {
    $valid = check_empty2($form_fields, null, array('event_type', 'rso_id'));
    if(!$valid['flag']) {
      print "Error error error";
      print_r($valid);
      view_error_output('All text fields must be entered');
      foreach($valid['errorKeys'] as $k=>$v){
        view_error_output('Need input for field '.$v);
      }
      $data = $_POST;
      view_edit_event_form($id, $data);

      return;
    } else {
      $user = new User();
      $userRow = $user->get($_SESSION['user_id']);
      $university_id = $userRow['university_id'];
      $rso_email = preg_replace('#.*@(.*)$#', '$1', $userRow['email']);

      $data = populate_data();
      $data['event_email'] = $rso_email;
      unset($data['university_name']);
      $data['university_id'] = $university_id;
      $data['user_id'] = $_SESSION['user_id'];
      $matches = array();
      $point_match = preg_match('#^\s*(-?[0-9]+\.[0-9]+)\s*,\s*(-?[0-9]+\.[0-9]+)\s*$#', $_POST['event_point'], $matches);
      if(!$point_match) {

        view_error_output('Point must be in format like 24.21,59.12');
        view_edit_event_form($id, $data);
        return;
      } else {
      $data['event_point'] = array();
      $data['event_point']['type'] = 'point';
      $data['event_point']['latitude'] = $matches[1];
      $data['event_point']['longitude'] = $matches[2];
    }

      $phpDate = strtotime($data['event_date']);
      $mysqlDate = date('Y-m-d H:i:s', $phpDate);
      $data['event_date'] = $mysqlDate;
      $result = $mainObject->update($id, $data);
      if($result) $message_string = 'You successfully edited event number <a href="index.php?type=event&op=display&id='.$id.'">'.$id.'</a>';
      else $error_string = 'Problem in creating event';
      view_generic_page();
    }
  } else {
    $mainObject->config['select'] = '*, ST_X(event_point) as latitude, ST_Y(event_point) as longitude ';
    $data = $mainObject->get($id);
    if(TRUE) {
      $data['event_point'] = array();
      $data['event_point']['type'] = 'point';
      $data['event_point']['latitude'] = $data['latitude'];
      $data['event_point']['longitude'] = $data['longitude'];
    } else {
      $data['event_point'] = array();
      $data['event_point']['type'] = 'point';
      $data['event_point']['latitude'] = $matches[1];
      $data['event_point']['longitude'] = $matches[2];
    }
    view_edit_event_form($id, $data);
  }
}

function rsoms_list() {
    global $mainObject, $page_title;
    if(!isset($_SESSION['user_role']) OR $_SESSION['user_role'] < 2) {
      unset($form_fields['event_type']['options'][0]);
    }
    $page_title = 'List of Events';

    $limit = array('event_name', 'event_date', 'event_description', 'event_address', 'latitude', 'longitude', 'event_type', 'university_name', 'rso_name');

    $content = '';

    $select = FALSE;
    $select = '*, a.event_id AS listfunc_id, ST_X(event_point) as latitude, ST_Y(event_point) as longitude ';

    $skip = FALSE;
    if(!empty($_SESSION['user_id'])) {
    $join = array('table' => 'event_subscriptions s', 'on' => 'a.event_id = s.event_id AND s.user_id = "'.intval($_SESSION['user_id']).'"');
    $joins[] = $join;
  } else {

  }

    $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
    $joins[] = $join;
    $join = array('table' => 'rsos r', 'on' => 'a.rso_id = r.rso_id');
    $joins[] = $join;
    $where = FALSE;
    $contentFuncs = array('event_type' => array('function' => 'return_event_type', 'argument' => FALSE));
    $orderBy = FALSE;
    $buffer = TRUE;

    $ops = array('display', 'edit',
    array('yes_row' => 'event_subscriptions_id', 'name' => 'leave'), array('no_row' => 'event_subscriptions_id', 'name' => 'join'));

    $params = array('limit' => $limit, 'joins' => $joins, 'ops' => $ops, 'where' => $where, 'select' => $select, 'sql' => FALSE, 'skip' => $skip,
      'contentFuncs' => $contentFuncs, 'orderBy' => $orderBy, 'buffer' => $buffer);
    $mainObject->config['list'] = $params;
    $rows = $mainObject->list();
    $content = view_list($mainObject, $rows, $params);

    view_generic_page($content);

}

function rsoms_display($id) {
  global $mainObject, $page_title, $bottom_content;
  require_once 'models/commentModel.php';
  require_once 'controllers/commentController.php';
  $mainObject->config['select'] = '*, ST_X(event_point) as latitude, ST_Y(event_point) as longitude ';
  $mainObject->config['get']['where'] = 'a.event_id="'.$id.'"';


  $row = $mainObject->get($id);
  if(DB_DEBUG) var_dump($row);
  $bottom_content = comment_create($id).comment_list($id);
  view_generic_object($row, $page_title, $bottom_content);
}

function rsoms_approve($id) {
  global $mainObject, $connection, $message_string;
  $sql = 'UPDATE events SET event_approved = "1" WHERE event_id="'.intval($id).'";';
  $result = $mainObject->send_query($sql);
  if($result) {
    $message_string = 'You have approved this event';
    view_generic_page('Back to <a href="index.php">home</a>');
  } else {
    if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
  }

}

function rsoms_join($id){
  global $mainObject, $connection, $message_string;
  $sql = 'INSERT INTO event_subscriptions (user_id, event_id) VALUES ("'.intval($_SESSION['user_id']).'", "'.intval($id).'");';
  $result = $mainObject->send_query($sql);
  if($result) {
    $message_string .= 'You have subscribed / joined this event';
    view_generic_page('Back to <a href="index.php">home</a>');
  }
}

function rsoms_leave($id){
  global $mainObject;
  $sql = 'DELETE FROM event_subscriptions WHERE user_id = "'.intval($_SESSION['user_id']).'" AND event_id = "'.intval($id).'";';
  $result = $mainObject->send_query($sql);
  if($result) {
    view_generic_page('You have left / unsubscribed from this event');
  }
}

function rsoms_test() {
  global $mainObject, $page_title, $university;
  $page_title = 'List of Events';

  $limit = array('event_name', 'event_date', 'event_description', 'event_address', 'latitude', 'longitude', 'distance', 'event_type', 'university_name', 'rso_name');

  $content = '';

  $select = FALSE;
  $select = '*, a.event_id AS listfunc_id, a.university_id AS uni, ST_X(event_point) as latitude, ST_Y(event_point) as longitude, '
  .'ROUND(ST_Distance(@thislocation, event_point), 2) AS distance';

  $skip = FALSE;

  if(!empty($_SESSION['user_id'])) {
  $join = array('table' => 'event_subscriptions s', 'on' => 'a.event_id = s.event_id AND s.user_id = "'.intval($_SESSION['user_id']).'"');
  $joins[] = $join;
} else {

}

  $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
  $joins[] = $join;
  $join = array('table' => 'rsos r', 'on' => 'a.rso_id = r.rso_id');
  $joins[] = $join;
  if(!empty($_POST['university'])) {
    $where = 'a.university_id="'.intval($_POST['university']).'"';
  } else $where = FALSE;
  $contentFuncs = array('event_type' => array('function' => 'return_event_type', 'argument' => FALSE));

  $ops = array('display', 'edit', array('yes_row' => 'event_subscriptions_id', 'name' => 'leave'), array('no_row' => 'event_subscriptions_id', 'name' => 'join'));

  if(!empty($_POST['zip'])) {
    $zipArray = getZipGeo(intval($_POST['zip']));
    $latitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Latitude'];
    $longitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Longitude'];

  } else {
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
  }

  $sql = 'SET @thislocation = POINT('.$latitude.','.$longitude.')';
  $set = $mainObject->send_query($sql);
  $orderBy = ' distance';

  $buffer = TRUE;

  $params = array('limit' => $limit, 'joins' => $joins, 'ops' => $ops, 'where' => $where, 'select' => $select, 'sql' => FALSE, 'skip' => $skip,
    'contentFuncs' => $contentFuncs, 'orderBy' => $orderBy, 'buffer' => $buffer);
  $mainObject->config['list'] = $params;
  $rows = $mainObject->list();
  $content = view_list($mainObject, $rows, $params);

  $formHTML = '<form id="topSearch" method="post" action="index.php?type=event&op=test">'
  .'<input name="zip" placeholder="zipcode" id="zip" ><select name="university"><option value="0"> --- </option>';
  $options = $university->select_options();
  foreach($options as $k => $v){
    $formHTML .= '<option value="'.$v['key'].'">'.$v['human_text'].'</option>';
  }
  $formHTML .= '</select><input name="submit" type="submit" value="Search"></form>';
  view_generic_page($formHTML.$content);
}


function rsoms_test2() {
  global $mainObject, $page_title, $university, $message_string, $error_string, $current_user;
  if(!empty($_SESSION['user_id'])) $page_title = 'List of Public Events and Private / RSO Events Available To You';
  else $page_title = 'List of Public Events';
  $limit = array('event_name', 'event_date', 'event_description', 'event_address', 'latitude', 'longitude', 'distance', 'event_type', 'university_name', 'rso_name');

  $content = '';

  $select = FALSE;
  $select = '*, a.event_id AS listfunc_id, a.university_id AS uni, ST_X(event_point) as latitude, ST_Y(event_point) as longitude, '
  .'ROUND(ST_Distance(@thislocation, event_point), 2) AS distance';

  $skip = FALSE;

  if(!empty($_SESSION['user_id'])) {
  $join = array('table' => 'event_subscriptions s', 'on' => 'a.event_id = s.event_id AND s.user_id = "'.intval($_SESSION['user_id']).'"');
  if(!empty($_POST['attending']) && $_POST['attending'] == 'attending') $join['type'] = 'INNER JOIN';
  $joins[] = $join;
} else {

}

  $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
  $joins[] = $join;
  $join = array('table' => 'rsos r', 'on' => 'a.rso_id = r.rso_id');
  $joins[] = $join;

  $contentFuncs = array('event_type' => array('function' => 'return_event_type', 'argument' => FALSE));

  if(!empty($_SESSION['user_id'])){
  $ops = array('display',
    array('permission' => 'event_creator', 'name'=> 'edit'),
    array('permission' => 'university_creator', 'name' => 'approve'),
    array('yes_row' => 'event_subscriptions_id', 'name' => 'leave'),
    array('no_row' => 'event_subscriptions_id', 'name' => 'join'));
  } else {
    $ops = array();
  }

  if(!empty($_POST['zip'])) {
    $zipArray = getZipGeo(intval($_POST['zip']));
    $latitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Latitude'];
    $longitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Longitude'];

  } else {
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
  }

  $sql = 'SET @thislocation = POINT('.$latitude.','.$longitude.')';

  $orderBy = ' distance';

  $buffer = TRUE;


  $mainObject->config['list'] = $mainObject->config['main'];
  if(!empty($_SESSION['user_id'])) {
      $join = array('table' => 'event_subscriptions s', 'on' => 'a.event_id = s.event_id AND s.user_id = "'.intval($_SESSION['user_id']).'"');
      $mainObject->config['list']['joins'][] = $join;
  }
  if(!empty($_POST['university'])) {
    $mainObject->config['list']['where'] = 'a.university_id="'.intval($_POST['university']).'"'
    .' AND a.event_type="2"';
    $message_string = 'Search result for university id '.intval($_POST['university']).' public events';
  }
  if(!empty($_POST['attending'])) {
    $mainObject->config['list']['joins'][3]['type'] = ' INNER JOIN ';
    $message_string = 'Events you are attending';
  }


  $rows = $mainObject->list();
  $content = view_list($mainObject, $rows);

  $formHTML = '<form id="topSearch" method="post" action="index.php?type=event&op=test2">'
  .'<input name="zip" placeholder="zipcode" id="zip" ><select name="university"><option value="0"> --- </option>';
  $options = $university->select_options();
  foreach($options as $k => $v){
    $formHTML .= '<option value="'.$v['key'].'">'.$v['human_text'].'</option>';
  }
  $formHTML .= '</select>';
  if(!empty($_SESSION['user_id'])) {
    $formHTML .= '<label for="attending">Limit to subscribed events </label><input name="attending" type="checkbox" value="attending" id="attending">';
  }

  $formHTML .= '<input name="submit" type="submit" value="Search"></form>';
  view_generic_page($formHTML.$content);
}


function view_create_event_form() {
  global $form_fields, $page_title;
  $content = site_head();
  if(isset($_GET['rso'])) $content .= view_auto_form2('index.php?type=event&op=create&rso=rso', $form_fields, $_POST);
  else $content .= view_auto_form2('index.php?type=event&op=create', $form_fields, $_POST);
  $content .= site_foot();
  print $content;
}

function view_edit_event_form($id, $data = FALSE) {
  global $form_fields, $page_title;
  if(!$data) $data = $_POST;
  $content = site_head();
  if(isset($_GET['rso'])) $content .= view_auto_form2('index.php?type=event&op=create&rso=rso', $form_fields, $_POST);
  else $content .= view_auto_form2('index.php?type=event&op=edit&id='.$id, $form_fields, $data);
  $content .= site_foot();
  print $content;
}


function return_event_type($int, $false){
  $event_types = array('0' => 'rso event', '1' => 'private event', '2' => 'public event');
  return $event_types[$int];
}
