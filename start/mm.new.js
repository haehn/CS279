var MM = MM || {};



MM.submit_new_comment = function() {


  var o = new Comment();

  o.text = $('#new_comment_text').val();

  o.parent_id = -1;

  o.userid = USER_ID;

  o.x = 0;
  o.y = 0;

  o.timestamp = new Date(new Date().getTime() - 300*60000);

  o.username = MM.user;

  DB.store(o, function(res) { 

    MM.create_ui();

    var c = JSON.parse(res);

    $.ajax({url:'../log.php?what='+'NEW COMMENT, USER_ID: '+USER_ID+' USERNAME: '+MM.user+' COMMENT_ID: '+c.id+' REGULAR'}).done(function() {});

  })

}


MM.submit_response = function(id) {


  var parent_id = parseInt($(id).parent().attr('id').split('-')[1],10);


  var o = new Comment();

  o.text = $(id).parent().children('textarea').val();

  o.parent_id = parent_id;

  o.userid = USER_ID;

  o.timestamp = new Date(new Date().getTime() - 300*60000);

  o.username = MM.user;

  DB.store(o, function(res) { 

    MM.create_ui();

    var c = JSON.parse(res);

    $.ajax({url:'../log.php?what='+'NEW REPLY, USER_ID: '+USER_ID+' USERNAME: '+MM.user+' COMMENT_ID: '+c.id+' PARENT_ID: '+c.parent_id+' REGULAR'}).done(function() {});


  })


}

MM.create_ui = function() {

      // recreate UI
      $('.comment-del').remove();

      MM.read_comments();

      $('#new_comment_text').val('');

}