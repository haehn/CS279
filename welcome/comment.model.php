<?php

require_once 'object.model.php';


class Comment extends Object {

  public $text = '';

  public $parent_id = 0;

  public $x = 0;

  public $y = 0;

  public $timestamp = '';

  public $upvotes = 0;

  public $downvotes = 0;

  public $username = '';


  public $browser = '';

  public $userid = -1;

  public function __construct() {

    // we need to assign these values in the contructor
    $this->browser = $_SERVER['HTTP_USER_AGENT'];
  }



}


?>