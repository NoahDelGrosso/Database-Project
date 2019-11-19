<?php

class Event extends Model {

function __construct() {
  global $current_user;

  $rso = new Rso();
  $this->tablename = 'events';
  $this->id_name = 'event_id';
  $this->title_name = 'event_name';

  if(empty($_SESSION['user_id'])) $user_id = 0;
  else $user_id = $_SESSION['user_id'];
  $this->form_fields = array('event_name',

  'event_date' => array('name' => 'event_date', 'type' => 'date'),
  'event_phone',
  'event_contact_email',

  'rso_id' =>array('name' => 'rso_id', 'type' => 'db_simple', 'object' => $rso,
    'initial_option' => array('key' => '0', 'human_text' => 'none'),
    'where' => 'user_id = "'.$user_id.'" AND rso_official="1"',
    'This option is only for events of type RSO'),
  'event_description' => array('name' => 'event_description',
    'type' => 'text'),
  'event_address' => array('name' => 'event_address', 'type' => 'text'),
  'event_point' => array('name' => 'event_point', 'type'=> 'point', 'description' => 'Format: XX.X, XX.X [ like 28.5,-72.1]'),

  'event_zipcode',
  'event_type' => array('name'=>'event_type',
      'type' => 'select',
      'options' => array('0' => 'rso event', '1' => 'private event', '2' => 'public event'),
      'description' => 'Type of event can not be edited after creation',
    ),
  );
  if(!empty($_GET['rso'])) {
    $this->form_fields['event_type'] = array('name'=> 'event_type', 'type' => 'hidden', 'value' => '0');
  } else {
    unset($this->form_fields['event_type']['options'][0]);
    unset($this->form_fields['rso_id']);
  }
  $config = array();
  $config['limit'] = array('event_name', 'event_date', 'event_description', 'event_address', 'latitude', 'longitude', 'distance', 'event_type', 'university_name', 'university_picture', 'rso_name', 'name');
  $config['select'] = '*, a.event_id AS listfunc_id, a.university_id AS uni, ST_X(event_point) as latitude, ST_Y(event_point) as longitude, '
  .'ROUND(ST_Distance(@thislocation, event_point), 2) AS distance';

  $join = array('table' => 'universities u', 'on' => 'a.university_id = u.university_id');
  $joins[] = $join;
  $join = array('table' => 'rsos r', 'on' => 'a.rso_id = r.rso_id');
  $joins[] = $join;
  $join = array('table' => 'users us', 'on' => 'a.user_id = us.user_id');
  $joins[] = $join;
  $config['joins'] = $joins;
  if(!empty($_SESSION['user_id']) && ($_SESSION['user_role'] < 4)) {

    $event_permissions = array_merge($current_user['event_member'], $current_user['event_creator']);
    $event_perm_string = implode(',', array_unique($event_permissions));
    $rso_permissions = array_merge($current_user['rso_member'], $current_user['rso_creator']);
    $rso_perm_string = implode(',', array_unique($rso_permissions));
    $where = ' a.event_type = "2" ';
    if(!empty($event_perm_string)) $where .= ' OR a.event_id IN ('.$event_perm_string.') ';
    if(!empty($rso_perm_string)) $where .= ' OR a.rso_id IN ('.$rso_perm_string.')  ';
    $where .= ' OR (a.event_type="1" AND a.university_id="'.$_SESSION['user_university_id'].'")';

  } else {
    if(empty($_SESSION['user_role'])) $where = ' a.event_type = "2"';
  }
  $config['where'] = $where;
  $contentFuncs = array('event_type' => array('function' => 'return_event_type', 'argument' => FALSE),
    'university_picture' => array('function' => 'return_picture', 'argument' => NULL));
  $config['contentFuncs'] = $contentFuncs;
  if(!empty($_SESSION['user_id'])){
  $ops = array('display',
    array('permission' => 'event_creator', 'name'=> 'edit'),
    array('permission' => 'university_creator', 'name' => 'approve'),
    array('yes_row' => 'event_subscriptions_id', 'name' => 'leave'),
    array('no_row' => 'event_subscriptions_id', 'name' => 'join'));
  } else {
    $ops = array('display');
  }
  $config['ops'] = $ops;
  $config['orderBy'] = ' distance';
  if(!empty($_POST['zip'])) {
    $zipArray = getZipGeo(intval($_POST['zip']));
    $latitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Latitude'];
    $longitude = $zipArray['OutputGeocodes'][0]['OutputGeocode']['Longitude'];

  } else {
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
  }

  $config['preQuery'] = 'SET @thislocation = POINT('.$latitude.','.$longitude.')';
  $config['buffer'] = TRUE;
  $this->config['main'] = $config;

  
}

}
