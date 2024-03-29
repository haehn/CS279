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
<script type='text/javascript' src='countdown.js'></script>
<script type='text/javascript'>

window.onload = function() {

  // grab username
  var config = UTIL.fetch_args();
  var username = config.username;
  if (typeof username == 'undefined') {
    username = 'Anonymous';
  }
  USER_ID = config.userid;

  MM.user = (username);

  // fix layout
  $('#right').height(window.document.height);
  $('#mind_margin').height(window.document.height);

  // ready for new comments
  MM.observe_interaction();

  // read and show old comments
  MM.read_comments();

  MM.takeover_sidescroll();

  $('#mind_margin').css('height', $('#content').height() + 200 + 'px');

  // make info hide under comments
  $('#info').css('z-index', 5000000);
  setTimeout($('#info').css('z-index', 500),4000);

  setTimeout(function() {

    $('.finish_btn').addClass('finish_btn_active');

  }, 2*60000);

  $('#share').on('click', MM.share);

  $('#right').css('width', $(window).width() - 581 + 'px');
  $('#mind_margin').css('left', '581px');
  $('#mind_margin').css('width', $(window).width()-581 + 'px');

  $('.scroll-bar').css('width', $(window).width()-620 + 'px');

}


</script>

</head>
<body>

<div id='timer'>
  <script type='text/javascript'>myCountdown1 = new Countdown({width:100, height:40, padding:0.6,time:10*60, rangeHi:"minute",onComplete  : MM.done});</script> <button class='finish_btn' onclick='MM.done();'>Finish reading > </button>
</div>

<div id='left' style='float:left;width:581px;height:1686px;'>

  <div id='content'>

<div id='share' style='position:absolute;left:441px'><button id='share_btn'> > Share Original Article</button></div>

  <?php

    include("article1.txt");

  ?>
  </div>
</div>

<div id='right' style='float:left;width:50%;height:100%;overflow:hidden'>


  <div id='mind_margin'>

    <div id='hot'>
      <div id='info'>
        <div id='info-title'><span id='info-firsthalf'>Mind</span><span id='info-secondhalf'>Margin</span></div>
        <div id='info-desc'>click the text to comment</div>
      </div>
    </div>
    <div id='cold'></div>

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




<!-- NEW COMMENT -->
<div id='new_comment'>
  <textarea id='new_comment_text' autofocus></textarea>
  <button id='new_comment_submit' class='new_comment_submit' onclick='MM.submit_new_comment();'>Add new comment</button>
</div>  
<div id='new_comment_line' class='comment_line'></div>

   <div class="scroll-bar-wrap ui-widget-content ui-corner-bottom" style='width:45%'>
      <div class="scroll-bar" style="position: fixed;left: 601px;bottom: 1%;z-index: 100000;"></div>
    </div>

</body>