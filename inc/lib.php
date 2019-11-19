<?php

// inc/lib.php

// view / templating functions
function site_head() {
  global $page_title, $error_string, $message_string;

  $content = '<!doctype html>
  <html lang="en">
  <head>
  <title>'.$page_title.'</title>
  <link href="https://fonts.googleapis.com/css?family=Bitter&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Archivo+Narrow&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  </head>
  <body><div id="main"><nav id="topNav"><ul>';

  if(isset($_SESSION['user_id'])) {
    $content .= '<li>Welcome '.htmlspecialchars($_SESSION['user_name']).' in '.$_SESSION['city'].'</li><li><a href="./">home</a></li><li><a href="index.php?type=user&op=logout">logout</a></li>';
  } else {
    $content .= '<li><a href="./">home</a></li><li><a href="index.php?type=user&op=login">login</a></li>';
    $content .= '<li><a href="index.php?type=user&op=create">register user account</a></li>';
  }
  $content .='</nav>';
  $content .= crud_menu();
  $content .= "\n".'<h1>'.$page_title.'</h1>';
  if(!empty($error_string)) $content .= '<div id="errors">'.$error_string.'</div>';
  if(!empty($message_string)) $content .= '<div id="messages">'.$message_string.'</div>';
  @ob_end_clean();
  return $content;
}

function site_foot() {
  $content = '</div></body>';
  return $content;
}

function check_empty($toValidate, $toCheck = null) {
  if(empty($toCheck)) {
    $toCheck = $_POST;
  }
  $flag = TRUE;
  foreach($toValidate as $k => $v) {
    if(empty($toCheck[$v])) {
      $flag = FALSE;
    }
  }
  return $flag;
}

function check_empty2($toValidate, $toCheck = null, $okEmpty = false) {
  if(empty($toCheck)) {
    $toCheck = $_POST;
  }
  if(!$okEmpty) {
    $okEmpty = array();
  }
  $valid = array();
  $valid['errorKeys'] = array();
  $flag = TRUE;
  foreach($toValidate as $k => $v) {
    if(is_array($v)) {
      if(empty($toCheck[$v['name']])) {
        if(in_array($v['name'], $okEmpty)) continue;
        $flag = FALSE;
        $valid['errorKeys'][] = $v['name'];
      }
    } else {
      if(empty($toCheck[$v])) {
        if(in_array($v, $okEmpty)) continue;
        $flag = FALSE;
        $valid['errorKeys'][] = $v;
      }
    }
  }
  $valid['flag'] = $flag;
  return $valid;
}

function populate_data($input = false) {
  $return = array();
  if(!$input) $input = $_POST;
  foreach($input as $k => $v) {
    $return[$k] = $v;
  }
  return $return;
}



function singularize($string){
  return rtrim($string, 's');
}

function humanize($string, $tablename=FALSE) {
  if($tablename) {
    $tablename = rtrim($tablename, 's');
    $string = str_replace($tablename, '', $string);
  }
  return str_replace('_', ' ', $string);
}

function view_generic_page($string = '', $exit = FALSE) {
  global $error_string, $message_string;
  $content = site_head();
  $content .= $string;
  $content .= site_foot();
  print $content;
  if($exit) exit();
}

function view_error_output($string) {
  global $error_string;
  $error_string .= '<span class="error">'. $string . '</span>';
}

function view_message_output($string) {
  global $message_string;
  $message_string .= '<span class="message">'. $string . '</span>';
}

function view_homepage() {
  global $mainObject, $event, $rso, $university;

  require_once 'models/eventModel.php';
  require_once 'controllers/eventController.php';
  rsoms_test2();
}

function view_list($object, $rows){
  global $connection, $initial_list_keys;

  $tablename = $object->tablename;
  $id_name = $object->id_name;
  $title_name = $object->title_name;

  $object_list_keys = array();
  foreach($initial_list_keys as $v) {
    if($v == $id_name) $object_list_keys[$v] = 'listfunc_id';
    else $object_list_keys[$v] = $v;
  }

  if(isset($object->config['list'])){
    extract($object->config['list']);
  }
  if(empty($skip)) $skip = array();
  if(empty($ops)) $ops = array();
  if(empty($contentFuncs)) $contentFuncs = array();

  $content = '<table class="t'.$tablename.'">';
  $i = 0;
  if(is_array($limit)) {
    $newLimit = array();
    foreach($limit as $k=>$v) {
      $newLimit[$v] = $v;
    }
    $limit = $newLimit;
  }

  foreach($rows as $row) {
    if(is_array($limit)) {
      $tableRow = $limit;
    } else {
      $tableRow = $row;
    }
    $content .= "\n".'<tr class="r'.($i%2).'">';
    if($i==0) {
      foreach($tableRow as $k=>$v) {
        if(in_array($k, $skip)) continue;
        $human = humanize($k, $tablename);
        $content .= '<th>'.mysqli_real_escape_string($connection, $human).'</th>';
      }
      if(is_array($ops)) {
        foreach($ops as $v) {
          if(is_array($v)) $content .= '<th>'.$v['name'].'</th>';
          else $content .= '<th>'.$v.'</th>';
        }
      }
      $i++;
      $content .= '</tr>'."\n".'<tr class="r'.($i%2).'">';
    }
    foreach($tableRow as $k=>$v) {
      if(in_array($k, $skip)) continue;
      $cell_content = mysqli_real_escape_string($connection, $row[$k]);
      if(!empty($contentFuncs[$k])) {
        $function = $contentFuncs[$k]['function'];

        $cell_content = $function($cell_content, $contentFuncs[$k]['argument']);
      }
      $content .= '<td>'.$cell_content.'</td>';
    }

    if(is_array($ops)) {
      foreach($ops as $v) {
        if(is_array($v)) {
          if(isset($v['yes_row'])) {
            if(!empty($row[$v['yes_row']])) {
              $content .= '<td><a href="index.php?type='.singularize($tablename).'&op='.$v['name'].'&id='.intval($row['listfunc_id']).'">'.$v['name'].'</a></td>';
            } else $content .= '<td></td>';
          } elseif(isset($v['no_row'])){
            if(empty($row[$v['no_row']])) {
              $content .= '<td><a href="index.php?type='.singularize($tablename).'&op='.$v['name'].'&id='.intval($row['listfunc_id']).'">'.$v['name'].'</a></td>';
            } else $content .= '<td></td>';
          } elseif(isset($v['permission'])) {

            $vals = array();
            foreach($object_list_keys as $kk => $vv) {
              if(empty($row[$vv])) $vals[$kk] = 0;
              else $vals[$kk] = $row[$vv];
            }
            if(isset($_SESSION['user_role'])) $user_role = $_SESSION['user_role'];
            else $user_role = 0;

            $check = check_permission($v['permission'], $vals['university_id'], $vals['rso_id'], $vals['event_id'], $user_role);
            if($check) $content .= '<td><a href="index.php?type='.singularize($tablename).'&op='.$v['name'].'&id='.intval($row['listfunc_id']).'">'.$v['name'].'</a></td>';
            else $content .= '<td></td>';
          }
        } else {
          $content .= '<td><a href="index.php?type='.singularize($tablename).'&op='.$v.'&id='.intval($row['listfunc_id']).'">'.$v.'</a></td>';
        }
      }
    }
    $i++;
    $content .= '</tr>';
  }
  $content .= '</table>';
  return $content;
}



function view_generic_object($row, $title = FALSE, $page_content = '') {
  global $page_title, $mainObject;
  if($title) $page_title = $title;
  $content = site_head();
  if(!empty($mainObject->config['main'])) extract($mainObject->config['main']);
  if(empty($limit)) {
     $limit = array();
   }
  if(empty($contentFuncs)) $contentFuncs = array();
  foreach($row as $k => $v) {
    if(!in_array($k, $limit)) continue;
    $value = $v;
    if(isset($contentFuncs[$k])) {
      $func = $contentFuncs[$k]['function'];
      $content .= '<div><span class="key">'. str_replace('_', ' ', $k).':</span> '.$func($v, $contentFuncs[$k]['argument']).'</div>';
    } else $content .= '<div><span class="key">'. str_replace('_', ' ', $k).':</span> '.htmlspecialchars($v).'</div>';
  }
  $content .= $page_content;
  $content .= site_foot();
  print $content;
}

function view_auto_form($action, $fields) {
  $content = '<form action="'.$action.'" method="post">';
  foreach($fields as $k=>$v) {
    $machine_name = $v;
    $human_name = str_replace('_', ' ', $v);
    $content .= '<label for="'.$machine_name.'">'.$human_name.'</label><input name="'.$v.'" id="'.$v.'">';
  }
  $content .= '<input type="submit" name="submit" value="Submit">';
  $content .= '</form>';
  $content .= '</content>';
  return $content;
}

function view_auto_form2($action, $fields, $values = FALSE) {
  global $prepopulate_setting;
  if($values === FALSE) $values = array();
  $content = '<form action="'.$action.'" method="post">';
  foreach($fields as $k=>$v) {

    $machine_name = $v;
    $human_name = str_replace('_', ' ', $v);
    if(!is_array($v)) {
      $content .= '<label for="'.$machine_name.'">'.$human_name.'</label><input name="'.$v.'" id="'.$v.'"'.form_values($v, 'text', $values).'>';
    } else {
      $machine_name = $v['name'];
      $human_name = humanize($machine_name);
      switch($v['type']) {
        case 'standard':

          $content .= '<label for="'.$machine_name.'">'.$human_name.'</label><input name="'.$v['name'].'" id="'.$v['name'].'"'.form_values($v['name'], 'text', $values).'>';
          break;
        case 'db_simple':
          $content .= "\n".'<label for="'.$machine_name.'">'.$human_name.'</label><select name="'.$v['name'].'" id="'.$v['name'].'">';
          $optionObject = $v['object'];
          if(!empty($v['where'])) $options = $optionObject->select_options($v['where']);
          else $options = $optionObject->select_options();
          if(!empty($v['initial_option'])) {
            array_unshift($options, $v['initial_option']);
          }

          foreach($options as $kk=>$vv) {
            $content .= "\n\t".'<option value="'.$vv['key'].'" '.select_values($v['name'], $vv['key'], $values).'>'.$vv['human_text'].'</option>';
          }
          $content .= '</select>';
        break;
        case 'select':
        $options = $v['options'];
        $content .=  "\n".'<label for="'.$machine_name.'">'.$human_name.'</label><select name="'.$v['name'].'" id="'.$v['name'].'">';
        foreach($options as $kk => $vv) {
          $content .= "\n\t".'<option value="'.$kk.'"';
          if(isset($_GET['prepopulate_'.$v['name']]) && ($_GET['prepopulate_'.$v['name']] == $kk)) {
            $content .= ' selected="selected" ';
          }
          $content .= select_values($v['name'], $kk, $values);
          $content .= '>'.$vv.'</option>';
        }
        $content .= '</select>';
        break;
        case 'text':
          $content .= "\n".'<label for="'.$machine_name.'">'.$human_name.'</label><textarea name="'.$v['name']
            .'">'.form_values($v['name'], 'textarea', $values).'</textarea>';
          break;
      //}
        case 'date':
          $content .= "\n".'<label for='.$machine_name.'">'.$human_name.'</label><input name="'.$v['name'].'" id="'.$v['name'].'"'
            .' type="datetime-local" '.form_values($v['name'], 'datetime-local', $values).'>';
          break;
        //}
        case 'point':
          $content .= "\n".'<label for='.$machine_name.'">'.$human_name.'</label><input name="'.$v['name'].'" id="'.$v['name'].'"'
           .' type="text" '.form_values($v['name'], 'point', $values).'>';
          break;
        case 'hidden':
          $content .= "\n".'<input name="'.$v['name'].'" id="'.$v['name'].'"'
           .' type="hidden" '.form_values($v['name'], 'hidden', $values).'>';
    }
    if(isset($v['description'])) {
      $content .= '<div class="description">'.$v['description'].'</div>';
    }
  }
}
  $content .= '<input type="submit" name="submit" value="Submit">';
  $content .= '</form>';
  $content .= '</content>';
  return $content;
}

function select_values($name, $valueOption, $values=FALSE, $selected="selected"){
  global $prepopulate_setting;
  if($values===FALSE) $values = array();
  if(!isset($valueOption)) error_exit('No valueOption set');
  if(isset($values[$name]) && $values[$name] == $valueOption) return ' '.$selected.'="'.$selected.'" ';
  if($prepopulate_setting) return select_prepopulate($name, $valueOption, $selected);
  return '';
}

function form_values($name, $type=FALSE, $values=FALSE) {
  global $prepopulate_setting, $prepopulate_values;
  if($values === FALSE) $values = array();
  switch ($type){
    case 'text':
    case 'hidden':

      if(isset($values[$name])) return ' value="'.htmlspecialchars($values[$name]).'" ';
      if(!empty($prepopulate_setting)) {

        return value_prepopulate($name);
      }
      return '';
      break;
    case 'textarea':
      if(isset($values[$name])) return htmlspecialchars($values[$name]);
      if(!empty($prepopulate_setting)) return htmlspecialchars($prepopulate_values[$name]);
    case 'datetime-local':

      if(isset($values[$name])) return ' value="'.date('Y-m-d\TH:i:s', strtotime($values[$name])).'" ';
    case 'point':
      if(isset($values[$name]['latitude'])){
        return ' value="'.htmlspecialchars($values[$name]['latitude'].','.htmlspecialchars($values[$name]['longitude'])).'" ';
      }
  }
}

function select_prepopulate($name, $valueOption, $selected) {
  global $prepopulate_setting, $prepopulate_values;
  if(empty($prepopulate_setting)) error_exit();
  if(isset($prepulate_values[$name]) && $prepopulate_values[$name] == $valueOption) return ' '.$selected.'="'.$selected.'" ';
  return '';
}

function value_prepopulate($name) {
  global $prepopulate_values, $prepopulate_setting;
  if(empty($prepopulate_setting)) error_exit(__FILE__.__LINE__);
  $content = '';
  if(is_array($prepopulate_values) && isset($prepopulate_values[$name])) {
    $content .= ' value="';
    $content .= htmlspecialchars($prepopulate_values[$name]);
    $content .= '" ';
  }
  return $content;
}

function error_exit($string) {
  if(DEBUG) {
    $string .= '<br>'.print_r(debug_backtrace(), TRUE);
    die($string);
  }
  else die('Operation not permitted');
}



function user_permission($role=0, $criteria = false, $die=FALSE) {
  if($role == 4) return;
  if($role == 0) {
    if(!isset($_SESSION['user_id'])) {
      if($die) die('You must be logged in for this functionality - <a href="index.php?type=user&op=login">Log in</a>');
      else return FALSE;
    }
  }

}

function check_permission($permission_name, $university_id = 0, $rso_id = 0, $event_id = 0, $role = 0, $creator_id = 0) {
  global $current_user;
  if(empty($_SESSION['user_id'])) return FALSE;
  if(!is_array($permission_name)) {
    $permission_name = array($permission_name);
  }
  foreach($permission_name as $v) {
    switch($v) {
      case 'university_member':
        if($university_id != $_SESSION['user_university_id']) return FALSE;
        break;
      case 'university_creator':
        if($university_id != $_SESSION['user_university_id']) return FALSE;
        if(empty($_SESSION['user_role2'])) return FALSE;
        break;
      case 'rso_member':
        if(!in_array($rso_id, $current_user['rso_member'])) return FALSE;
        break;
      case 'rso_creator':
        if(!in_array($rso_id, $current_user['rso_creator'])) return FALSE;
        break;
      case 'event_member':
        if(!in_array($event_id, $current_user['event_member'])) return FALSE;
        break;
      case 'event_creator':
        if(!in_array($event_id, $current_user['event_creator'])) return FALSE;
        break;
      case 'role_check':
        if($_SESSION['user_role'] < $role) return FALSE;
        break;

    }
  }
  return TRUE;
}

function crud_menu() {
  $cm = "\n".'<nav class="crud_menu"><ul>';
  $items = array();
  if(empty($_SESSION['user_id'])) {

  } else {
    $items[] = array('event', 'create', 'Create public / private event');
    $items[] = array('rso', 'list', 'Join a Registered Student Oranization [RSO]');
    $items[] = array('rso', 'create', 'Create RSO');
    if(!empty($_SESSION['user_role'])) {
      $items[] = array('event', 'create', 'Create RSO Event', '&rso=rso' );

      if($_SESSION['user_role'] > 3) {
        $items[] = array('user', 'list', 'List Users');
      }
    }
    if(!empty($_SESSION['user_role2'])) {
      $items[] = array('university', 'edit', 'Edit University Profile', '&id='.$_SESSION['user_university_id']);
    }
  }
  foreach($items as $v) {
    $cm .= "\n\t".'<li><a href="index.php?type='.$v[0].'&op='.$v[1];
    if(!empty($v[3])) $cm .= $v[3];
    $cm .= '">'.$v[2].'</a></li>';
  }

  $cm .= '</ul><div class="clearDiv"></div></nav>';
  return $cm;
}

function get_user_info() {

  $unionArray = array('rso_id AS data_result, "rso_member" as source FROM rso_memberships',
  'event_id, "event_member" as source FROM event_subscriptions',
  'rso_id, "rso_creator" as source FROM rsos',
  'event_id, "event_creator" as source FROM events');
  foreach($unionArray as $v){
    if(!isset($unionSql)) {
      $unionSql = '';
    } else $unionSql .= "\n UNION ALL \n";
    $unionSql .= 'SELECT '.$v.' WHERE user_id = "'.intval($_SESSION['user_id']).'" ';
  }
  $unionSql .= ';';

  $modelObj = new Model();
  $result = $modelObj->send_query($unionSql);
  $rows = array();
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $user_info = array('event_member'=>array(), 'event_creator'=>array(), 'rso_member'=>array(), 'rso_creator'=>array());
  foreach($rows as $v) {
    if(!isset($user_info[$v['source']])) $user_info[$v['source']] = array();
    $user_info[$v['source']][]= $v['data_result'];
  }
  return $user_info;

}

function getGeoAPI(){
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://freegeoip.app/json/",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
		"accept: application/json",
		"content-type: application/json"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  if(DEBUG) echo __FILE__.__LINE__." cURL Error #:" . $err;
	} else {
	  return json_decode($response, TRUE);
	}
}

function getGeo() {
	if(isset($_SESSION['geo'])) {
		return ($_SESSION['geo']);
	} else {
		$geo = getGeoAPI();
    $_SESSION['latitude'] = $geo['latitude'];
    $_SESSION['longitude'] = $geo['longitude'];
    $_SESSION['city'] = $geo['city'];
		return $geo;
	}
}


function getZipGeo($zip) {
  $curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL =>
    "http://geoservices.tamu.edu/Services/Geocode/WebService/GeocoderWebServiceHttpNonParsed_V04_01.aspx?format=json&version=4.01&apiKey=".TAMU_API."&zip=".$zip,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
		"accept: application/json",
		"content-type: application/json"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  return json_decode($response, TRUE);
	}

}

function return_picture($url, $null) {
  return '<img class="return_picture" src="'.$url.'">';
}
