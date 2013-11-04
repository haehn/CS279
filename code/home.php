<html>
<head>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='comment.model.js'></script>
<script type='text/javascript' src='db.js'></script>
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
      $('#new_comment').css('top',$(window).scrollTop()+e.y-$('#new_comment_text').height()/2);
      $('#new_comment').show();
      $('#new_comment_line').css('top',$(window).scrollTop()+e.y);
      $('#new_comment_line').css('left',e.x);
      $('#new_comment_line').css('width',$(window).width()-e.x-$('#new_comment').width()-parseFloat($('#new_comment').css('right')));
      $('#new_comment_line').show();
      new_comment_box_shown = true;
    }, 1000);

  }


  new_comment = function() {

    console.log('yes');

    var o = new Comment();

    o.text = $('#new_comment_text').val();

    o.parent_id = -1;

    o.x = parseFloat($('#new_comment_line').css('left'),10);
    o.y = parseFloat($('#new_comment_line').css('top'),10);

    o.timestamp = new Date();

    DB.store(o, function() { alert('stored'); })

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
<div id='new_comment' style='position:absolute;width:300px;right:50px;display:none;'>
<textarea id='new_comment_text' style='width:300px;height:150px' autofocus></textarea>
<button id='new_comment_submit' style='float:left;margin-right:0px;' onclick='new_comment();'>Add new comment</button>
</div>
<div id='new_comment_line' style='position:absolute;height:1px;width:100px;display:none;border-top:1px dotted;color:red;'>
</div>
</body>