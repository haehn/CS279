var MM = MM || {};

MM.observe_interaction = function() {

  var hover_trigger = null;
  var new_comment_box_shown = false;

  $('#new_comment')[0].onmousemove = function(e) {

    clearTimeout(hover_trigger);

    return false;

  }


  $('#content')[0].onmousemove = function(e) {
    
    if (hover_trigger) {
      // $('#new_comment').hide();
      clearTimeout(hover_trigger);
    }

    hover_trigger = setTimeout(function() {
      $('#new_comment').css('top',$(window).scrollTop()+e.y-$('#new_comment_text').height()/2);
      $('#new_comment').show();
      $('#new_comment_line').css('top',$(window).scrollTop()+e.y);
      $('#new_comment_line').css('left',e.x);
      $('#new_comment_line').css('width',$(window).width()/2-e.x);
      $('#new_comment_line').show();
      new_comment_box_shown = true;
    }, 1000);

  }


  MM.submit_new_comment = function() {


    var o = new Comment();

    o.text = $('#new_comment_text').val();

    o.parent_id = -1;

    o.x = parseFloat($('#new_comment_line').css('left'),10);
    o.y = parseFloat($('#new_comment_line').css('top'),10);

    o.timestamp = new Date();

    o.user_id = MM.user.id;

    DB.store(o, function(res) { alert('stored'); })

  }
}