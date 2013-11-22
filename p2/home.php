<html>
<head>
<link href="style.css" rel="stylesheet"/>
<link href="jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='jquery-ui-1.10.3.custom.min.js'></script>
<script type='text/javascript' src='comment.model.js'></script>

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

  MM.user = (username);

  // ready for new comments
  //MM.observe_interaction();

  // read and show old comments
  MM.read_comments();

}


</script>

</head>
<body>
<div id='page'>
  <div id='left' style='float:left;width:50%;'>
    <div id='content'>
    <?php

      include("article1.txt");

    ?>
    </div>
  </div>

  <div id='bottom' style='width:50%;'>

  <!-- NEW COMMENT -->
  <div id='new_comment' style='float:left;height:200px'>
    <textarea id='new_comment_text' autofocus></textarea>
    <button id='new_comment_submit' onclick='MM.submit_new_comment();'>Add new comment</button>
  </div> 

  </div>
</div>


    <!-- COMMENT TEMPLATE -->
    <div id='existing_comment' class='comment'>
      <div class='comment_head'>
        <span class='username'>User123</span>  
      </div>
      <div class='comment_body'></div>
      <div class='comment_footer'>
        <span class='upvotes'>4</span> <img class='upvoteimg' height="10" style='cursor:pointer;' src="arrow_0047.gif">
        <span class='downvotes'>2</span> <img class='downvoteimg' height="10" style='cursor:pointer;' src="arrow_0041.gif">
        <span class='actions' style='display:none' >v</span>  
      </div>
    </div>
    <div id='existing_comment_response' class='comment_response'>
      <div class='comment_head'>
        <span class='username'>User123</span>  
      </div>
      <div class='comment_body'></div>
      <div class='comment_footer'>
        <span class='upvotes'>4</span> <img class='upvoteimg' height="10" style='cursor:pointer;' src="arrow_0047.gif">
        <span class='downvotes'>2</span> <img class='downvoteimg' height="10" style='cursor:pointer;' src="arrow_0041.gif">
        <span class='actions' style='display:none' >v</span>  
      </div>
    </div>
    <div id='new_response' class='new_response'>
      <textarea class='new_response_text' autofocus></textarea>
      <button class='new_comment_submit' onclick='MM.submit_response(this);'>Add new reply</button>
    </div>
  </div>

</div>






</body>