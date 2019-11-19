<?php

if(!function_exists('rsoms_display')) {
  function rsoms_display($id) {
    global $mainObject, $page_title;

    $row = $mainObject->get($id);
    view_generic_object($row);
  }
}

if(!function_exists('rsoms_list')) {
  function rsoms_list() {
      global $mainObject, $page_title;
      $content = site_head();
      $content .= $mainObject->list();
      $content .= site_foot();
      print $content;
  }

}
