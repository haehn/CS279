var MM = MM || {};

MM.read_comments = function() {
  
  // read all comments
  comments = [];
  responses = {};
  popular_comments = [];
  regular_comments = [];
  DB.get(new Comment(), function(res) {

    res = JSON.parse(res);

    var number_of_comments = res.length;
    var comments_parsed = 0;

    // loop through existing comments
    for (var c in res) {

      c = res[c];
       
      if (c.parent_id != "-1") {

        if (typeof responses[c.parent_id] == 'undefined') {
          responses[c.parent_id] = [];
        }

        responses[c.parent_id].push(c);

        continue;       

      } else {
        comments.push(c);
      }

    }

    // calculate metric
    for (c in comments) {

      // if upvotes exceed downvotes, then only count upvotes
      // also show comments which are less than 1 hour old
      c = comments[c];

      var up = parseInt(c.upvotes,10);
      var down = parseInt(c.downvotes,10);

      var m = 0;

      if (up > down) {
        m += up;
      } else {
        m = up*1.3 - down;
      }

      if (Math.abs(new Date() - new Date(c.timestamp)) < 3600000) {
        m += 9;
      }

      c.metric = m;

    }

    // console.log(comments);

    MM.max_comment_x = 0;

    // check which are popular
    for (c in comments) {    

      c = comments[c];

      if (c.metric > 8) {
        popular_comments.push(c);
      } else {
        regular_comments.push(c);
      }

    }

    // order popular comments by y
    popular_comments.sort(function(a,b) {

      return a.y - b.y;

    });

    popular_comments.sort(function(a,b) {


      // prefer newer comments
      var time_difference = new Date(a.timestamp) - new Date(b.timestamp);

      var c_height = $('#existing_comment').height();
      var current_y = parseInt(a.y,10);
      var previous_y = parseInt(b.y,10);
      var overlap = current_y < ( previous_y + c_height/2 + 10);

      // console.log('o',overlap, time_difference);

      if (time_difference < 0 && overlap) {
        // b is newer and there is an overlap

        // then put b in front of a
        // console.log('UES')
        return b.y - a.y;

      } else {

        return a.y - b.y;

      }

    });


    copy_popular_comments = popular_comments.slice();

    continue_again = false;


    // display comments
    for (x in popular_comments) {

      // console.log(x);

      // if (continue_again) {
      //   continue_again = false;
      //   console.log('continuing again');
      //   continue;
      // }

      c = popular_comments[x];

      // check if there is an overlap
      // if (c.y <= c2[0].y-$('#existing_comment').height()/2 && c.y <= c2[0].y+$('#existing_comment').height()/2) {
      if (x>0) {
        var c_height = $('#existing_comment').height();
        var current_y = parseInt(popular_comments[x].y,10);
        var previous_y = parseInt(popular_comments[x-1].y,10);

        //console.log('checking',popular_comments[x][0].id,copy_popular_comments[x-1][0].id)

        // check if we have the same, then dont do anything
        if (popular_comments[x].id != popular_comments[x-1].id) {

          // console.log('not the same');

          if (current_y < ( previous_y + c_height/2 + 10) ) {
          // if (current_y - previous_y < c_height)  {                  

            // yes

            // move the current comment to regular_comments
            regular_comments.push(c);

            // and remove it from popular comments
            copy_popular_comments.splice(x, 1);

            //console.log(c.y, c2[0].y);
            // console.log('FOUND OVERLAP',current_y, previous_y)

            continue_again = true;
            // skip this guy
            continue;


          }

        }

      }

      var new_div = $('#existing_comment').clone();
      new_div.children('.comment_head').children('.username').html(c.username);
      new_div.attr('title', c.timestamp);
      new_div.children('.comment_body').html(c.text);
      new_div.children('.comment_footer').children('.upvotes').html(c.upvotes);
      new_div.children('.comment_footer').children('.downvotes').html(c.downvotes);
      new_div.attr('id', 'comment-'+c.id);
      new_div.show();
      new_div.addClass('small_text');
      new_div.addClass('comment-del');
      new_div.css('top',c.y-$('#existing_comment').height()/2 - $('#timer').height());
      // new_div.css('left',c.x);

      new_div.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,c));
      new_div.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,c));

      $('#hot').append(new_div);

      var new_line = $('#new_comment_line').clone();
      // new_line.css('top',$(window).scrollTop()+parseInt(c.y,10)+$('#existing_comment').height()/2);
      // var adjusted_y = c.y/615 * $(window).height();
      new_line.css('top',parseInt(c.y,10) - $('#timer').height());
      // new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
      new_line.css('left',0);
      new_line.css('width',parseInt($(content).css('margin'),10)+20);
      new_line.addClass('comment-del');
      new_line.attr('id', 'comment-line-'+c.id);

      $('#mind_margin').append(new_line);
      new_line.show();


      new_div.children('.comment_footer').on('click', MM.expand_replies.bind(this,new_div));
      new_div.children('.comment_footer').children('.actions').show();


      // check if we have responses
      if (typeof responses[parseInt(c.id,10)] != 'undefined') {

            for (r in responses[c.id]) {

              r = responses[c.id][r];

              // then add all responses to some container
              var new_div2 = $('#existing_comment_response').clone();
              new_div2.children('.comment_head').children('.username').html(r.username);
              new_div2.attr('title', r.timestamp);
              new_div2.children('.comment_body').html(r.text);
              new_div2.children('.comment_footer').children('.upvotes').html(r.upvotes);
              new_div2.children('.comment_footer').children('.downvotes').html(r.downvotes);
              new_div2.attr('id', 'comment-'+r.id);
              new_div2.hide();
              new_div2.addClass('small_text');
              // new_div.addClass('comment-del');
              // new_div.css('top',c.y-$('#existing_comment').height()/2);
              // new_div.css('left',c.x);

              new_div2.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,r));
              new_div2.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,r));

              new_div.append(new_div2);



            }

      }


      var new_div3 = $('#new_response').clone();
      new_div3.addClass('small_text');
      new_div3.attr('id', 'new_response-'+c.id);
      new_div.append(new_div3);      

    } // popular comments






    // order regular comments by y
    regular_comments.sort(function(a,b) {

      return a.y - b.y;

    });

    var left = 0;
    for (x in regular_comments) {

      c = regular_comments[x];

      // check if there is an overlap
      // if (c.y <= c2[0].y-$('#existing_comment').height()/2 && c.y <= c2[0].y+$('#existing_comment').height()/2) {
      if (x>0) {
        var c_height = $('#existing_comment').height();
        var current_y = parseInt(regular_comments[x].y,10);
        var previous_y = parseInt(regular_comments[x-1].y,10);

        if (current_y < ( previous_y + c_height/2 + 10) ) {
        // if (current_y - previous_y < c_height)  {                  

          // yes
          left += $('#existing_comment').width() + 30; 
          //console.log(c.y, c2[0].y);
          console.log('FOUND OVERLAP',current_y, previous_y)

          MM.max_comment_x = Math.max(MM.max_comment_x, left + $('#existing_comment').width());

        } else {
          left = 0;
        }
      }

      var new_div = $('#existing_comment').clone();
      new_div.children('.comment_head').children('.username').html(c.username);
      new_div.attr('title', c.timestamp);
      new_div.children('.comment_body').html(c.text);
      new_div.children('.comment_footer').children('.upvotes').html(c.upvotes);
      new_div.children('.comment_footer').children('.downvotes').html(c.downvotes);
      new_div.attr('id', 'comment-'+c.id);
      new_div.addClass('small_text');
      new_div.show();
      new_div.css('top',c.y-$('#existing_comment').height()/2 - $('#timer').height());
      // var adjusted_y = c.y/615 * $(window).height();
      // new_line.css('top',parseInt(c.y,10) - $('#existing_comment').height()/2-$('#timer').height());      
      new_div.css('margin-left', left);
      new_div.addClass('comment-del');
      // new_div.css('left',c.x);

      new_div.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,c));
      new_div.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,c));

      $('#cold').append(new_div);

      var new_line = $('#new_comment_line').clone();
      new_line.css('top',parseInt(c.y,10) - $('#timer').height());
      // new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
      new_line.css('left',-parseFloat($('#content').css('margin'),10));
      new_line.css('width',parseInt($(content).css('margin'),10)+parseInt($('#cold').css('margin-left'),10)+left+20);
      new_line.attr('id', 'comment-line-'+c.id);
      new_line.addClass('comment-del');
      $('#mind_margin').append(new_line);
      new_line.show();              

      new_div.children('.comment_footer').on('click', MM.expand_replies.bind(this,new_div));
      new_div.children('.comment_footer').children('.actions').show();

      // check if we have responses
      if (typeof responses[parseInt(c.id,10)] != 'undefined') {
            
            for (r in responses[c.id]) {

              r = responses[c.id][r];

              // then add all responses to some container
              var new_div2 = $('#existing_comment_response').clone();
              new_div2.children('.comment_head').children('.username').html(r.username);
              new_div2.attr('title', r.timestamp);
              new_div2.children('.comment_body').html(r.text);
              new_div2.children('.comment_footer').children('.upvotes').html(r.upvotes);
              new_div2.children('.comment_footer').children('.downvotes').html(r.downvotes);
              new_div2.attr('id', 'comment-'+r.id);
              new_div2.hide();
              new_div2.addClass('small_text');
              // new_div.addClass('comment-del');
              // new_div.css('top',c.y-$('#existing_comment').height()/2);
              // new_div.css('left',c.x);

              new_div2.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,r));
              new_div2.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,r));

              new_div.append(new_div2);



            }

      }

      var new_div3 = $('#new_response').clone();
      new_div3.addClass('small_text');
      new_div3.attr('id', 'new_response-'+c.id);

      new_div.append(new_div3);


    } // regular comments

    $('#cold').css('width',MM.max_comment_x+500);

    MM.setup_slider();            


  }); // db

}

MM.setup_slider = function() {

  MM.scrollbar = $( ".scroll-bar" ).slider({
    slide: function( event, ui ) {

      $('#mind_margin').scrollLeft(MM.max_comment_x/100 * ui.value);

    }
  });


}

MM.upvote = function(c) {

  c.upvotes = parseInt(c.upvotes,10) + 1;
  c._classname = 'comment';
  delete c.metric;

  DB.store(c, function() {

    //MM.create_ui();


    $('#comment-'+c.id).children('.comment_footer').children('.upvotes').html(c.upvotes);


    $.ajax({url:'../log.php?what='+'UPVOTE, USER_ID: '+USER_ID+' USERNAME: '+MM.user+' COMMENT_ID: '+c.id+' MINDMARGIN'}).done(function() {});




  });

  return false;

}

MM.downvote = function(c) {

  c.downvotes = parseInt(c.downvotes,10) + 1;
  c._classname = 'comment';
  delete c.metric;

  DB.store(c, function() {

    //MM.create_ui();

    $('#comment-'+c.id).children('.comment_footer').children('.downvotes').html(c.downvotes);


    $.ajax({url:'../log.php?what='+'DOWNVOTE, USER_ID: '+USER_ID+' USERNAME: '+MM.user+' COMMENT_ID: '+c.id+' MINDMARGIN'}).done(function() {});



  });

  return false;

}

MM.takeover_sidescroll = function() {

    $('#mind_margin').bind('mousewheel', function(e){

        if(e.originalEvent.wheelDelta /120 > 0) {
            $('#mind_margin').scrollLeft($('#mind_margin').scrollLeft()-30);
        }
        else{
            $('#mind_margin').scrollLeft($('#mind_margin').scrollLeft()+30);
        }

        $('.scroll-bar').slider('value', $('#mind_margin').scrollLeft()/MM.max_comment_x*100);

        return false;

    });

}


MM.expand_replies = function(div) {

  if (div.css('z-index') == '10000') {

    div.css('height','150px');

    div.css('z-index','6000');    

    div.children('.comment_response').hide();

    div.children('.new_response').hide();

    div.children('.comment_footer').children('.actions').html('v');

  } else {

    div.css('height', 'auto'); 

    div.css('z-index','10000');

    div.children('.comment_response').show();

    div.children('.new_response').show();

    div.children('.comment_footer').children('.actions').html('^');

  }

}

MM.done = function() {

  if (parseFloat($('.finish_btn').css('opacity'),10) < 1) {
    return;
  }

  $.ajax({url:'../log.php?what='+'END EXPERIMENT, USER_ID: '+USER_ID+' USERNAME: '+MM.user+' MINDMARGIN'}).done(function() {

    //console.log('test');
    TIME_LEFT = myCountdown1.J - Date.now();
    window.location.replace("../index.html?q&userid="+USER_ID+'&timeleft='+TIME_LEFT);

  });

}

MM.share = function(e) {
  e.stopPropagation();

  // open original article

  window.open('http://www.thecrimson.com/column/the-red-line/article/2013/10/23/dont-teach-for-america/');

}