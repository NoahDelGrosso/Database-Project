<?php

/*models/model.php */

class Model {
  private static $tablename;
  private static $id_name;

function __construct() {

}

  function create($data) {
    global $connection;
    $tablename = $this->tablename;
    $query = 'INSERT INTO '.$tablename.' (';
      foreach($data as $k => $v) {
        //if($k == 'submit') continue;
        $query .= mysqli_real_escape_string($connection, $k).',';
      }
    $query = rtrim($query, ',');
    $query .= ') VALUES (';
    foreach($data as $k => $v) {
      //if($k == 'submit') continue;
      $query .= '"'.mysqli_real_escape_string($connection, $v).'",';
    }
    $query = rtrim($query, ',');
    $query .= ')';
  print $query;
  $result = mysqli_query($connection, $query);
  if(!$result) {
    print mysqli_error($connection);
  }
  return mysqli_insert_id($connection);
}
  function get($id) {
    global $connection;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    $sql = 'SELECT * WHERE '.$id_name.' = ".intval($id)."';
    $result = mysqli_query($connection, $sql);
    if($result) {
      $row = mysqli_fetch_assoc($result);
      return $row;
    } else {
      if(DEBUG) print msyqli_error($connection);
    }
  }

  function update($where, $data) {
    global $connection;
    $tablename = $this->tablename;
    $sql = 'UPDATE '.$tablename // WHERE '.$where['fieldname'].' = '.mysqli_real_escape_string($connection, $where['value'])
      .' SET ';
    foreach($data as $k => $v) {
      $sql .= $k.'="'.mysqli_real_escape_string($connection, $v).'"';
    }
    $sql .= ' WHERE '.$where['fieldname'].' = '.mysqli_real_escape_string($connection, $where['value']);
    $result = mysqli_query($connection, $sql);
    if(!$result) {
      print mysqli_error($connection);
    }
  }

  //}



}
