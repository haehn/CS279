<html>
<head>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='comment.model.js'></script>
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

      var icon = document.createElement('div');
      icon.id = 'comment-'+c.id;
      icon.innerHTML = 'C!';
      window.document.body.appendChild(icon);
      $('#'+icon.id).css('height', '10px');
      $('#'+icon.id).css('width', '10px');
      $('#'+icon.id).css('position', 'fixed');
      $('#'+icon.id).css('color', 'red');
      $('#'+icon.id).css('top', c.y);
      $('#'+icon.id).css('right', '5px');

    }

  });

  $('#new_comment')[0].onmousemove = function(e) {

    clearTimeout(hover_trigger);

    return false;

  }


  $('#external_website')[0].onmousemove = function(e) {
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


function bubbleIframeMouseMove(iframe){
    // Save any previous onmousemove handler
    var existingOnMouseMove = iframe.contentWindow.onmousemove;

    // Attach a new onmousemove listener
    iframe.contentWindow.onmousemove = function(e){
        // Fire any existing onmousemove listener 
        if(existingOnMouseMove) existingOnMouseMove(e);

        // Create a new event for the this window
        var evt = document.createEvent("MouseEvents");

        // We'll need this to offset the mouse move appropriately
        var boundingClientRect = iframe.getBoundingClientRect();

        // Initialize the event, copying exiting event values
        // for the most part
        evt.initMouseEvent( 
            "mousemove", 
            true, // bubbles
            false, // not cancelable 
            window,
            e.detail,
            e.screenX,
            e.screenY, 
            e.clientX + boundingClientRect.left, 
            e.clientY + boundingClientRect.top, 
            e.ctrlKey, 
            e.altKey,
            e.shiftKey, 
            e.metaKey,
            e.button, 
            null // no related element
        );

        // Dispatch the mousemove event on the iframe element
        iframe.dispatchEvent(evt);
    };
}

// Get the iframe element we want to track mouse movements on
var myIframe = document.getElementById("myIFrame");

// Run it through the function to setup bubbling
//bubbleIframeMouseMove(myIframe);

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
<div id='external_website' style='float:left;width:100%;height:100%;'>
<!-- <iframe id='myIFrame' style='width:100%;height:100%;' src='http://monster.krash.net/d/CS279/code/proxy.php?url=<?php echo $_GET["url"]; ?>'></iframe> -->

<?php

  include($_GET["url"]);

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