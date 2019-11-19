<?php

class Comment extends Model {

function __construct() {
  $this->tablename = 'comments';
  $this->id_name = 'comment_id';
  $this->title_name = 'comment_text';
}

}
