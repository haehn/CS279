<?php

require_once 'object.model.php';


class Comment extends Object {

  public $text = '';

  public $parent_id = 0;

  public $x = 0;

  public $y = 0;

  public $timestamp = '';

}


?>