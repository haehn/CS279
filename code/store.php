<?php

// we define a valid entry point
define('__MINDMARGIN_ENTRY_POINT__', 666);


// include the configuration
require_once('config.inc.php');

require_once('mapper.class.php');

// the models
require_once('comment.model.php');
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


// we need some data
if (isset($_GET['data'])) {

  $data = base64_decode($_GET['data']);

} else if (isset($_POST['data'])) {

  $data = base64_decode($_POST['data']);

}

if (!isset($data)) {

  die('we need some data');

}

// here we have some data and a type (for sure)
$generic_object = json_decode($data);

// create an object based on the type
$real_object = new $type();

// check if an id was set. if yes, this is an updating task
if (isset($generic_object->id)) {

  $real_objects = Mapper::getStatic($type, $generic_object->id);

  $real_object = $real_objects[$type][0];

  $id = $generic_object->id;

}

// loop through all properties of the JSON object
foreach($generic_object as $key => $value) {

  if ($key == '_classname') continue;
  if ($key == 'id') continue;
  if ($key == 'ip') continue;
  if ($key == 'browser') continue;

  $real_object->$key = $value;

}

if (isset($id)) {

  // update the existing object
  Mapper::update($real_object, $id);

} else {

  // store the new object in the database
  $id = Mapper::add($real_object);
  $real_object->id = $id;

  $real_object->_classname = $type;

}

echo json_encode($real_object);


?>