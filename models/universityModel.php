<?php
/* models/universityModel.php */

class University extends Model {
//private static $tablename = 'universities';
function __construct() {
  $this->tablename = 'universities';
  //die('done');
}


function get($id) {

  return $university;
}

function search($array) {

  return $universities;
}

}
