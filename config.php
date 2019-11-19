<?php
/* config.php */

define('DEBUG', FALSE);
define('DB_DEBUG', FALSE);
$connection = mysqli_connect('localhost', 'root', '', 'collegeeventwebsite');
$session_seconds = 14400; // 4 hours
$initial_list_keys = array('user_id', 'event_id', 'rso_id', 'university_id', 'comment_id');
/* custom session timeout - from https://solutionfactor.net/blog/2014/02/08/implementing-session-timeout-with-php/ */
ini_set('session.gc_maxlifetime', $session_seconds);
ini_set('session.cookie_lifetime', $session_seconds);
session_start();

if(!DB_DEBUG) {

  error_reporting(E_ERROR);
  ini_set('display_errors', 0);

} else {

  error_reporting(E_ALL);
  ini_set('display_errors', 1);
}
$geo = getGeo();
$time = $_SERVER['REQUEST_TIME'];


$timeout_duration = 1800;
$timeout_duration = $session_seconds; // 4 hours

if (isset($_SESSION['LAST_ACTIVITY']) &&
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}


$_SESSION['LAST_ACTIVITY'] = $time;
if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(), $_COOKIE[session_name()], time() + $session_seconds);
}
$prepopulate_setting = TRUE;

define('TAMU_API', '89851dfbcf3044b2927af764535069d3');



// Texas A&M Geocoding API Key 89851dfbcf3044b2927af764535069d3
