<html>
<head>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript'>

window.onload = function() {

  var hover_trigger = null;
  var new_comment_box_shown = false;

  window.document.body.onmousemove = function(e) {

    if (hover_trigger) {
      // $('#new_comment').hide();
      clearTimeout(hover_trigger);
    }

    hover_trigger = setTimeout(function() {
      $('#new_comment').css('top',$(window).scrollTop()+e.y);
      $('#new_comment').show();
      $('#new_comment_line').css('top',$(window).scrollTop()+e.y);
      $('#new_comment_line').css('left',e.x);
      $('#new_comment_line').css('width',parseFloat($('#new_comment').css('left'),10)-e.x);
      $('#new_comment_line').show();
      new_comment_box_shown = true;
    }, 1000);

  }




}


</script>

</head>
<body>
<div style='float:left;'>
<?php

  include($_GET['url']);

?>
</div>
<div id='mind_margin' style='top:0px;right:0px;width:30px;position:fixed;height:100%;background-color:black;'>
Test1123
</div>
<div id='new_comment' style='position:absolute;left:60%;display:none;'>
<textarea style='width:300px;height:150px' autofocus></textarea>
<button id='submit' style='float:left;margin-right:0px;'>Add new comment</button>
</div>
<div id='new_comment_line' style='position:absolute;height:1px;width:100px;display:none;border-top:1px dotted;color:red;'>
</div>
</body>