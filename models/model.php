<?php

/*models/model.php */

class Model {
  public $tablename;
  public $id_name;
  public $title_name;
  public $config;
  public $form_fields;
  public $id;
  public $listKeys;

function __construct() {

}

function create($data, $form_fields = FALSE) {
    global $connection;
    if(!$form_fields) $form_fields = array();
    $tablename = $this->tablename;
    $query = 'INSERT INTO '.$tablename.' (';
      foreach($data as $k => $v) {
        if($k == 'submit') continue;
        $query .= mysqli_real_escape_string($connection, $k).',';
      }
    $query = rtrim($query, ',');
    $query .= ') VALUES (';
    foreach($data as $k => $v) {
      if($k == 'submit') continue;
      if(is_array($v)) {
        switch($v['type']) {
          case 'point':
          $query .= ' POINT('.$v['latitude'].', '.$v['longitude'].'), ';
          break;
        }
      } else {
        $query .= '"'.mysqli_real_escape_string($connection, $v).'",';
      }
    }
    $query = rtrim($query, ',');
    $query .= ')';
  if(DB_DEBUG) print '<br>'.$query;
  $result = mysqli_query($connection, $query);
  if(!$result) {
    if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
    return FALSE;
  }
  $this->id = mysqli_insert_id($connection);
  return $this->id;
}

  function get($id) {
    global $connection, $mainObject;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    if(empty($mainObject->config['get']['where'])) {
      $mainObject->config['get']['where'] = ' '.$id_name.' = "'.$id.'"';
    }
    $sql = $this->build_sql('get');
    if(DB_DEBUG) print '<br>'.__FILE__.__LINE__.' '.$sql.'<br>';
    $result = mysqli_query($connection, $sql);
    if($result) {
      $row = mysqli_fetch_assoc($result);
      if(DB_DEBUG) {
        print '<br>'.__FILE__.__LINE__.'<pre>';
        print_r($row);
        print '</pre>';
      }
      $this->id = $row[$id_name];
      return $row;
    } else {
      if(DEBUG) print '<br>'.__FILE__.', '.__LINE__.': '.mysqli_error($connection);
    }
  }

  function update($where, $data) {
    global $connection;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    $sql = 'UPDATE '.$tablename
      .' SET ';
    foreach($data as $k => $v) {
      if($k == 'submit') continue;
      if(is_array($v)){
        switch($v['type']) {
          case 'point':
          $sql .= $k.'= POINT('.$v['latitude'].', '.$v['longitude'].'), ';
          break;
        }
      } else $sql .= $k.'="'.mysqli_real_escape_string($connection, $v).'", ';
    }
    $sql = rtrim($sql, ', ');
    $sql .= ' WHERE '.$id_name.' = "'.mysqli_real_escape_string($connection, $where).'"';


    if(DB_DEBUG) print '<br>'.$sql;
    $result = mysqli_query($connection, $sql);
    if(!$result) {
      if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
      return FALSE;
    }
        return $result;
  }

  function send_query($sql) {
    global $connection;
    if(DB_DEBUG) print '<br>'.$sql;
    $result = mysqli_query($connection, $sql);
    if(!$result) {
      if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
    }
    return $result;
  }


  function build_sql($type) {
    global $connection;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    $title_name = $this->title_name;
    $config = $this->config;
    $configArray = array();
    if(isset($config['main'])){
      foreach($config['main'] as $k => $v) {
          $configArray[$k] = $v;
        }
    }
    if(isset($config[$type])) {
      foreach($config[$type] as $k => $v) {
        $configArray[$k] = $v;
      }
    }

    extract($configArray);
    if(empty($skip)) $skip = array();
    if(empty($contentFuncs)) $contentFuncs = array();
    if(!empty($sql)){
      $sql = $sql;
    } else {
      if(!empty($select)) $sql = 'SELECT '.$select.' FROM '.$tablename.' a ';
      else $sql = 'SELECT *, a.'.$id_name.' AS listfunc_id FROM '.$tablename.' a ';
      if(isset($joins) && is_array($joins)) {
        if(!empty($joins['fulltext'])) {
          $sql .= ' '.$joins['fulltext'];
        } else {
          foreach ($joins as $k=>$v) {
            if(empty($v['type'])) {
              $sql .= ' LEFT JOIN ';
            } else $sql .= ' '.$v['type'].' ';
            $sql .= ' '.$v['table'].' ';
            $sql .= ' ON '.$v['on'];
          }
        }
      }
      if(!empty($where)) $sql .= ' WHERE '.$where.' ';
      if(!empty($whereAnd)) $sql .= ' AND '.$whereAnd.' ';
      if(!empty($orderBy)) {
        $sql .= ' ORDER BY '.$orderBy;
      }
      $sql .=';';
      if(DB_DEBUG) print '<br>'.__LINE__.': '.$sql;
    }

    if(!empty($preQuery)) {
      if(DB_DEBUG) print '<br>'.$preQuery;
      $result = mysqli_query($connection, $preQuery);
      if(!$result) {
        if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
      }
    }

    return $sql;
  }



  function list() {
    global $connection;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    $title_name = $this->title_name;
    $config = $this->config;

    $sql = $this->build_sql('list');
    $result = mysqli_query($connection, $sql);
    if(!$result) {
      if(DEBUG) print '<br>'.__FILE__.', '.__LINE__.': '.mysqli_error($connection);
    }

    $buffer = TRUE;
    if($buffer) {
      $rows = array();
      while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
      }
      return $rows;
    }

  }

  function select_options($where = FALSE) {
    global $connection;
    $tablename = $this->tablename;
    $id_name = $this->id_name;
    $title_name = $this->title_name;

    $sql = 'SELECT '.$id_name.', '.$title_name.' FROM '.$tablename;
    if($where) $sql .= ' WHERE '.$where;
    $sql .=';';
    if(DB_DEBUG) print '<br>'.$sql;
    $result = mysqli_query($connection, $sql);
    if(!$result) {
      if(DEBUG) print __FILE__.', '.__LINE__.': '.mysqli_error($connection);
    }
    $options = array();
    while($row = mysqli_fetch_assoc($result)){
      $options[] = array('key' => $row[$id_name], 'human_text' => $row[$title_name]);
    }
    return $options;
  }
}
