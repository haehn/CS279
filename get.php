<?php

// we define a valid entry point
define('__MINDMARGIN_ENTRY_POINT__', 666);


// include the configuration
require_once('config.inc.php');

require_once('mapper.class.php');

// the models
require_once('user.model.php');


// we need a type
if (isset($_GET['type'])) {

  $type = $_GET['type'];

} else if (isset($_POST['type'])) {

  $type = $_POST['type'];

}

if (!isset($type)) {

  die('we need a type');

}

$commentMapper = new Mapper($type);

if (isset($_GET["id"])) {

  $id = $_GET["id"];

} else if (isset($_POST["id"])) {

  $id = $_POST["id"];

} else {
  
  $id = -1;

}


$commentResult = $commentMapper->get($id);


echo json_encode($commentResult[$type]);

?>