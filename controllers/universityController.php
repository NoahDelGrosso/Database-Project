<?php
/* ----- universityController.php  ----- */
$university = new University;
$mainObject = $university;
include 'models/userModel.php';

function rsoms_edit($id) {
  global $connection, $event, $message_string, $error_string, $page_title, $mainObject, $rso;
  $form_fields = $mainObject->form_fields;
  $page_title = 'Edit University';
  if(!isset($_SESSION['user_role']) OR $_SESSION['user_role'] < 2) {
    unset($form_fields['event_type']['options'][0]);
  }
  user_permission();
  if(isset($_POST['submit'])) {
    $valid = check_empty2($form_fields, null, array());
    if(!$valid['flag']) {
      print "Error error error";
      print_r($valid);
      view_error_output('All text fields must be entered');
      foreach($valid['errorKeys'] as $k=>$v){
        view_error_output('Need input for field '.$v);
      }
      $data = $_POST;
      view_edit_university_form($id, $data, $form_fields);

      return;
    } else {

      $data = populate_data();

      $matches = array();
      $point_match = preg_match('#^\s*(-?[0-9]+\.[0-9]+)\s*,\s*(-?[0-9]+\.[0-9]+)\s*$#', $_POST['university_point'], $matches);
      if(!$point_match) {

        view_error_output($_POST['university_point'].'Point must be in format like 24.21,59.12');
        view_edit_university_form($id, $data);
        return;
      } else {
      $data['university_point'] = array();
      $data['university_point']['type'] = 'point';
      $data['university_point']['latitude'] = $matches[1];
      $data['university_point']['longitude'] = $matches[2];
    }


      $result = $mainObject->update($id, $data);
      if($result) $message_string = 'You successfully edited university, back to <a href="./">home</a>';
      else $error_string = 'Problem in updating university';
      view_generic_page();
    }
  } else {
    $mainObject->config['select'] = '*, ST_X(university_point) as latitude, ST_Y(university_point) as longitude ';
    $data = $mainObject->get($id);

    if(TRUE) {
      if(!empty($data['latitude']) && !empty($data['longitude'])) {
        $data['university_point'] = array();
        $data['university_point']['type'] = 'point';
        $data['university_point']['latitude'] = $data['latitude'];
        $data['university_point']['longitude'] = $data['longitude'];
      }
    } else {
      $data['event_point'] = array();
      $data['event_point']['type'] = 'point';
      $data['event_point']['latitude'] = $matches[1];
      $data['event_point']['longitude'] = $matches[2];
    }

    view_edit_university_form($id, $data, $form_fields);
  }
}

function rsoms_display($id) {
  global $mainObject;
  $mainObject->config['select'] = '*, ST_X(university_point) as latitude, ST_Y(university_point) as longitude ';
  $universityRow = $mainObject->get($id);
  view_generic_object($universityRow, 'Display Individual University');
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
    $limit = FALSE;
    $ops = array('display',
      array('permission' => 'rso_creator', 'name'=> 'edit'),
      array('yes_row' => 'rso_membership_id', 'name' => 'leave'),
      array('no_row' => 'rso_membership_id', 'name' => 'join'));
    $params = array('limit'=>$limit, 'joins'=>$joins, 'ops' => $ops, 'where' => $where);
    $mainObject->config['list'] = $params;
    $rows = $mainObject->list();
    $content = view_list($mainObject, $rows);
    view_generic_page($content);

}


function view_edit_university_form($id, $data = FALSE, $form_fields) {
  global  $page_title, $mainObject;
  if(!$data) $data = $_POST;
  $form_fields = $mainObject->form_fields;
  $content = site_head();
  $content .= view_auto_form2('index.php?type=university&op=edit&id='.$id, $form_fields, $data);
  $content .= site_foot();
  print $content;
}
