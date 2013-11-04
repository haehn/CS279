<?php

// we define a valid entry point
define('__MINDMARGIN_ENTRY_POINT__', 666);


// include the configuration
require_once('config.inc.php');

require_once('mapper.class.php');

// the models
require_once('comment.model.php');

$commentMapper = new Mapper('Comment');
$commentResult = $commentMapper->get();


echo json_encode($commentResult['Comment']);

?>