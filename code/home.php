<html>
<head>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='comment.model.js'></script>
<script type='text/javascript' src='user.model.js'></script>
<script type='text/javascript' src='db.js'></script>
<script type='text/javascript'>

window.onload = function() {

  var hover_trigger = null;
  var new_comment_box_shown = false;


  // read all comments
  DB.get(new Comment(), function(res) {

    res = JSON.parse(res);

    // loop through existing comments
    for (var c in res) {

      c = res[c];

      u = new User()
      u.id = c.user_id

      DB.get(u, function(res) {

        u = JSON.parse(res)[0];

        console.log(u);

        var comment = document.createElement('div');
        comment.id = 'comment-'+c.id;
        comment.innerHTML = '<b>' + u.username + '</b>'
        comment.innerHTML += c.text

        window.document.body.appendChild(comment);

      });

    }

  });

  $('#new_comment')[0].onmousemove = function(e) {

    clearTimeout(hover_trigger);

    return false;

  }


  $('#content')[0].onmousemove = function(e) {
    console.log('test');
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
<body style='margin:0px'>
<div id='content' style='border:1px solid black;margin:50px;padding:30px;float:left;width:50%;'>
<?php

  include("article1.txt");

?>
</div>
<div id='mind_margin' style='float:left;width:40%'>

<div id='hot' style='float:left;width:50%'><small><b>Popular Comments</b></small></div>
<div id='cold' style='float:left;width:50%'></div>

</div>
<div id='new_comment' style='position:absolute;width:300px;right:50px;display:none;'>
<textarea id='new_comment_text' style='width:300px;height:150px' autofocus></textarea>
<button id='new_comment_submit' style='float:left;margin-right:0px;' onclick='new_comment();'>Add new comment</button>
</div>
<div id='existing_comment'>
  <span id='username'>User123</span>
  <span id='comment'></span>
  <span id='upvotes'></span>
  <span id='downvotes'></span>
  <span id='actions'>Reply</span>
</div>
<div id='new_comment_line' style='position:absolute;height:1px;width:100px;display:none;border-top:1px dotted;color:red;'>
</div>
</body>