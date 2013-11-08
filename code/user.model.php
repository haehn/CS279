<?php

require_once 'object.model.php';


class User extends Object {

  public $id = 0;

  public $username = '';

  public $browser = '';

  public function __construct() {

    // we need to assign these values in the contructor
    $this->browser = $_SERVER['HTTP_USER_AGENT'];
  }


}


?>