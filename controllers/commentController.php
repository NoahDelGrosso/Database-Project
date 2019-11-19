<?php
/* controllers/commentController.php */
$comment = new Comment();
global $commentObject;
$commentObject = $comment;
function comment_create($id) {
  global $connection, $commentObject, $message_string, $error_string;
  $form_fields = array('comment_text'=> array('name'=>'comment_text', 'type' => 'text'));
  if(isset($_POST['submit'])) {
    $valid = check_empty2($form_fields);
    if(!$valid) {
      view_error_output('All text fields must be entered');
      $form = view_comment_form('index.php?type=event&op=display&id='.$id, $form_fields);
      return $form;
      
  } else {
    $data = $_POST;
    $data['event_id'] = $id;
    $data['user_id'] = $_SESSION['user_id'];
    var_dump($commentObject);
    $comment_id = $commentObject->create($data);
    if($comment_id) $message_string = 'You successfully added a comment <a href="index.php?type=event&op=display&id='.$id.'">Click to Refresh</a>';
    else $error_string = 'Problem in creating rso';

  }
} else {
  return view_comment_form('index.php?type=event&op=display&id='.$id, $form_fields);
}
}

function comment_list($id) {
  global $commentObject, $page_title;
  $join = array('table' => 'users us', 'on' => 'a.user_id = us.user_id');
  $joins[] = $join;
  $limit = array('name', 'comment_text');
  $ops = FALSE;
  $skip = FALSE;
  $contentFuncs = FALSE;
  $orderBy = FALSE;
  $buffer = FALSE;
  $where = ' a.event_id="'.$id.'"';
  $params = array('limit' => $limit, 'joins' => $joins, 'ops' => $ops, 'where' => $where, 'select' => FALSE, 'sql' => FALSE, 'skip' => $skip,
    'contentFuncs' => $contentFuncs, 'orderBy' => $orderBy, 'buffer' => $buffer);
  $commentObject->config['list'] = $params;
  $rows = $commentObject->list();
  $content = view_list($commentObject, $rows, $params);
  return $content;
}

/* ------ view / templating functions ----*/

function view_comment_form($action, $form_fields) {

  $content = '';
  $content .= view_auto_form2($action, $form_fields);
  return $content;

}
