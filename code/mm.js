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

            console.log(comments);

            // check which are popular
            for (c in comments) {    

              c = comments[c];

              if (c[0].metric > 8) {
                popular_comments.push(c);
              } else {
                regular_comments.push(c);
              }

            }

            // display comments
            for (c in popular_comments) {

              c = popular_comments[c];

              var new_div = $('#existing_comment').clone();
              new_div.children('.comment_head').children('.username').html(c[1].username);
              new_div.children('.comment_head').children('.date').html(c[0].timestamp);
              new_div.children('.comment_body').html(c[0].text);
              new_div.children('.comment_footer').children('.upvotes').html(c[0].upvotes);
              new_div.children('.comment_footer').children('.downvotes').html(c[0].downvotes);
              new_div.attr('id', 'comment-'+c[0].id);
              new_div.show();
              new_div.addClass('small_text');
              new_div.css('top',c[0].y-$('#existing_comment').height()/2);
              // new_div.css('left',c[0].x);

              $('#hot').append(new_div);

              var new_line = $('#new_comment_line').clone();
              // new_line.css('top',$(window).scrollTop()+parseInt(c[0].y,10)+$('#existing_comment').height()/2);
              new_line.css('top',parseInt(c[0].y,10));
              new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
              new_line.css('width',parseInt($(content).css('margin'),10));
              new_line.attr('id', 'comment-line-'+c[0].id);
              $('body').append(new_line);
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
                  left += $('#existing_comment').width() + 10; 
                  //console.log(c[0].y, c2[0].y);
                  console.log('FOUND OVERLAP',current_y, previous_y)

                } else {
                  left = 0;
                }
              }

              var new_div = $('#existing_comment').clone();
              new_div.children('.comment_head').children('.username').html(c[1].username);
              new_div.children('.comment_head').children('.date').html(c[0].timestamp);
              new_div.children('.comment_body').html(c[0].text + ' ' + c[0].y + ' ' + left);
              new_div.children('.comment_footer').children('.upvotes').html(c[0].upvotes);
              new_div.children('.comment_footer').children('.downvotes').html(c[0].downvotes);
              new_div.attr('id', 'comment-'+c[0].id);
              new_div.addClass('small_text');
              new_div.show();
              new_div.css('top',c[0].y-$('#existing_comment').height()/2);
              new_div.css('margin-left', left);
              // new_div.css('left',c[0].x);

              $('#cold').append(new_div);

              var new_line = $('#new_comment_line').clone();
              new_line.css('top',parseInt(c[0].y,10));
              new_line.css('left',$(window).width()/2-parseFloat($('#content').css('margin'),10));
              new_line.css('width',parseInt($(content).css('margin'),10)+parseInt($('#cold').css('margin-left'),10)+left);
              new_line.attr('id', 'comment-line-'+c[0].id);
              $('body').append(new_line);
              new_line.show();              

            }


        }

      }.bind(this,c));

    } // for

  }); // db

}