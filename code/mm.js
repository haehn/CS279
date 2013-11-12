var MM = MM || {};

MM.read_comments = function() {
  
  // read all comments
  comments = [];
  popular_comments = [];
  regular_comments = [];
  DB.get(new Comment(), function(res) {

    res = JSON.parse(res);

    var number_of_comments = res.length;
    var comments_parsed = 0;

    // loop through existing comments
    for (var c in res) {

      c = res[c];

      u = new User()
      u.id = c.user_id

      DB.get(u, function(c,res) {

        u = JSON.parse(res)[0];

        comments.push([c,u]);

        comments_parsed++;

        if (comments_parsed == number_of_comments) {

            // calculate metric
            for (c in comments) {

              // if upvotes exceed downvotes, then only count upvotes
              // also show comments which are less than 1 hour old
              c = comments[c];

              var up = parseInt(c[0].upvotes,10);
              var down = parseInt(c[0].downvotes,10);

              var m = 0;

              if (up > down) {
                m += up;
              } else {
                m = up*1.3 - down;
              }

              if (Math.abs(new Date() - new Date(c[0].timestamp)) < 3600000) {
                m += 9;
              }

              c[0].metric = m;

            }

            // console.log(comments);

            MM.max_comment_x = 0;

            // check which are popular
            for (c in comments) {    

              c = comments[c];

              if (c[0].metric > 8) {
                popular_comments.push(c);
              } else {
                regular_comments.push(c);
              }

            }

            // order popular comments by y
            popular_comments.sort(function(a,b) {

              return a[0].y - b[0].y;

            });

            popular_comments.sort(function(a,b) {


              // prefer newer comments
              var time_difference = new Date(a[0].timestamp) - new Date(b[0].timestamp);

              var c_height = $('#existing_comment').height();
              var current_y = parseInt(a[0].y,10);
              var previous_y = parseInt(b[0].y,10);
              var overlap = current_y < ( previous_y + c_height/2 + 10);

              // console.log('o',overlap, time_difference);

              if (time_difference < 0 && overlap) {
                // b is newer and there is an overlap

                // then put b in front of a
                // console.log('UES')
                return b[0].y - a[0].y;

              } else {

                return a[0].y - b[0].y;

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
              // if (c[0].y <= c2[0].y-$('#existing_comment').height()/2 && c[0].y <= c2[0].y+$('#existing_comment').height()/2) {
              if (x>0) {
                var c_height = $('#existing_comment').height();
                var current_y = parseInt(popular_comments[x][0].y,10);
                var previous_y = parseInt(popular_comments[x-1][0].y,10);

                //console.log('checking',popular_comments[x][0].id,copy_popular_comments[x-1][0].id)

                // check if we have the same, then dont do anything
                if (popular_comments[x][0].id != popular_comments[x-1][0].id) {

                  // console.log('not the same');

                  if (current_y < ( previous_y + c_height/2 + 10) ) {
                  // if (current_y - previous_y < c_height)  {                  

                    // yes

                    // move the current comment to regular_comments
                    regular_comments.push(c);

                    // and remove it from popular comments
                    copy_popular_comments.splice(x, 1);

                    //console.log(c[0].y, c2[0].y);
                    // console.log('FOUND OVERLAP',current_y, previous_y)

                    continue_again = true;
                    // skip this guy
                    continue;


                  }

                }
              }

              var new_div = $('#existing_comment').clone();
              new_div.children('.comment_head').children('.username').html(c[1].username);
              new_div.attr('title', c[0].timestamp);
              new_div.children('.comment_body').html(c[0].text);
              new_div.children('.comment_footer').children('.upvotes').html(c[0].upvotes);
              new_div.children('.comment_footer').children('.downvotes').html(c[0].downvotes);
              new_div.attr('id', 'comment-'+c[0].id);
              new_div.show();
              new_div.addClass('small_text');
              new_div.addClass('comment-del');
              new_div.css('top',c[0].y-$('#existing_comment').height()/2);
              // new_div.css('left',c[0].x);

              new_div.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,c[0]));
              new_div.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,c[0]));

              $('#hot').append(new_div);

              var new_line = $('#new_comment_line').clone();
              // new_line.css('top',$(window).scrollTop()+parseInt(c[0].y,10)+$('#existing_comment').height()/2);
              new_line.css('top',parseInt(c[0].y,10));
              // new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
              new_line.css('left',0);
              new_line.css('width',parseInt($(content).css('margin'),10)+20);
              new_line.addClass('comment-del');
              new_line.attr('id', 'comment-line-'+c[0].id);

              $('#mind_margin').append(new_line);
              new_line.show();


            }






            // order regular comments by y
            regular_comments.sort(function(a,b) {

              return a[0].y - b[0].y;

            });

            var left = 0;
            for (x in regular_comments) {

              c = regular_comments[x];

              // check if there is an overlap
              // if (c[0].y <= c2[0].y-$('#existing_comment').height()/2 && c[0].y <= c2[0].y+$('#existing_comment').height()/2) {
              if (x>0) {
                var c_height = $('#existing_comment').height();
                var current_y = parseInt(regular_comments[x][0].y,10);
                var previous_y = parseInt(regular_comments[x-1][0].y,10);

                if (current_y < ( previous_y + c_height/2 + 10) ) {
                // if (current_y - previous_y < c_height)  {                  

                  // yes
                  left += $('#existing_comment').width() + 30; 
                  //console.log(c[0].y, c2[0].y);
                  console.log('FOUND OVERLAP',current_y, previous_y)

                  MM.max_comment_x = Math.max(MM.max_comment_x, left + $('#existing_comment').width());

                } else {
                  left = 0;
                }
              }

              var new_div = $('#existing_comment').clone();
              new_div.children('.comment_head').children('.username').html(c[1].username);
              new_div.attr('title', c[0].timestamp);
              new_div.children('.comment_body').html(c[0].text);
              new_div.children('.comment_footer').children('.upvotes').html(c[0].upvotes);
              new_div.children('.comment_footer').children('.downvotes').html(c[0].downvotes);
              new_div.attr('id', 'comment-'+c[0].id);
              new_div.addClass('small_text');
              new_div.show();
              new_div.css('top',c[0].y-$('#existing_comment').height()/2);
              new_div.css('margin-left', left);
              new_div.addClass('comment-del');
              // new_div.css('left',c[0].x);

              new_div.children('.comment_footer').children('.upvoteimg').on('click', MM.upvote.bind(this,c[0]));
              new_div.children('.comment_footer').children('.downvoteimg').on('click', MM.downvote.bind(this,c[0]));

              $('#cold').append(new_div);

              var new_line = $('#new_comment_line').clone();
              new_line.css('top',parseInt(c[0].y,10));
              // new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
              new_line.css('left',-parseFloat($('#content').css('margin'),10));
              new_line.css('width',parseInt($(content).css('margin'),10)+parseInt($('#cold').css('margin-left'),10)+left+20);
              new_line.attr('id', 'comment-line-'+c[0].id);
              new_line.addClass('comment-del');
              $('#mind_margin').append(new_line);
              new_line.show();              

            }


            console.log('mm', MM.max_comment_x);
            $('#cold').css('width',MM.max_comment_x+500);

            MM.setup_slider();            


        }

      }.bind(this,c));

    } // for



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

    MM.create_ui();

  });

}

MM.downvote = function(c) {

  c.downvotes = parseInt(c.downvotes,10) + 1;
  c._classname = 'comment';
  delete c.metric;

  DB.store(c, function() {

    MM.create_ui();

  });

}

MM.takeover_sidescroll = function() {

    $('#mind_margin').bind('mousewheel', function(e){

        if(e.originalEvent.wheelDelta /120 > 0) {
            $('#mind_margin').scrollLeft($('#mind_margin').scrollLeft()-30);
        }
        else{
            $('#mind_margin').scrollLeft($('#mind_margin').scrollLeft()+30);
        }
        console.log($('#mind_margin').scrollLeft(),MM.max_comment_x,$('#mind_margin').scrollLeft()/MM.max_comment_x*100);
        
        $('.scroll-bar').slider('value', $('#mind_margin').scrollLeft()/MM.max_comment_x*100);

        return false;

    });

}