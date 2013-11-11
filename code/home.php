<html>
<head>
<link href="style.css" rel="stylesheet"/>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='comment.model.js'></script>
<script type='text/javascript' src='user.model.js'></script>
<script type='text/javascript' src='db.js'></script>
<script type='text/javascript' src='mm.js'></script>
<script type='text/javascript' src='mm.new.js'></script>
<script type='text/javascript' src='util.js'></script>
<script type='text/javascript'>

window.onload = function() {

  // grab username
  var config = UTIL.fetch_args();
  var username = config.username;
  if (typeof username == 'undefined') {
    username = 'Anonymous';
  }
  // and store it
  var u = new User();
  u.username = username;
  DB.store(u, function(res) {

    MM.user = (JSON.parse(res));

    // fix layout
    $('#right').height(window.document.height);
    $('#mind_margin').height(window.document.height);

    // ready for new comments
    MM.observe_interaction();

    // read and show old comments
    MM.read_comments();

  });

}


</script>

</head>
<body>

<div id='left' style='float:left;width:50%;height:100%;'>
  <div id='content'>
  <?php

    include("article1.txt");

  ?>
  </div>
</div>

<div id='right' style='float:left;width:50%;height:100%;overflow:hidden'>

  <div id='mind_margin'>

    <div id='hot'><small><b>Popular Comments</b></small></div>
    <div id='cold'></div>

    <!-- COMMENT TEMPLATE -->
    <div id='existing_comment' class='comment'>
      <div class='comment_head'>
        <span class='username'>User123</span>  
        <span class='date'>01/01/1970</span>
      </div>
      <div class='comment_body'></div>
      <div class='comment_footer'>
        <span class='upvotes'>4<img height="10" src="http://en.clipart-fr.com/data/clipart/arrows/arrow_0047.gif"></span>
        <span class='downvotes'>2<img height="10" src="http://en.clipart-fr.com/data/clipart/arrows/arrow_0041.gif"></span>
        <span class='actions'>Reply</span>  
      </div>
    </div>

  </div>

</div>




<!-- NEW COMMENT -->
<div id='new_comment'>
  <textarea id='new_comment_text' autofocus></textarea>
  <button id='new_comment_submit' onclick='MM.submit_new_comment();'>Add new comment</button>
</div>  
<div id='new_comment_line' class='comment_line'>
</div>

</body>