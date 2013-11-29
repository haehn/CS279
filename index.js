submit_demographics = function() {

  var u = new User();

  for (var a in u) {
    // if (a == '_classname' || a =='nickname') continue;

    if ($('#demographics .content input[name="'+a+'"]').length == 0) {
      console.log('skipping', a);
      continue;
    }

    u[a] = $('#demographics .content input[name="'+a+'"]:checked').val();

    if (typeof u[a] == 'undefined') {

      alert('Please answer all questions.');
      return false;

    }

  }

  DB.store(u, function(e) {

    USER_ID = JSON.parse(e).id;

    $('#demographics').hide();$('#instructions').show();

  });
  
}

submit_instructions = function() {

  var nickname_choice = $('#instructions .content input[name="nickname"]:checked').val();

  if (typeof nickname_choice == 'undefined') {
    alert('Please choose a nickname.');
    return false;
  }

  var nickname = 'anonymous';

  if (nickname_choice == 'custom') {

    if ($('#nickname').val().trim() == '') {
      alert('Please choose a nickname.');
      return false;
    }

    nickname = $('#nickname').val().trim();

  } 

  var u = new User();
  u.id = USER_ID;

  DB.get(u, function(r) {

    u = JSON.parse(r)[0];

    u._classname = 'user';
    u.nickname = nickname;


    // and store it again
    DB.store(u, function() {

      NICKNAME = nickname;

      init_experiment();

    })

  });

}

submit_q = function() {

  var u = new User();
  u.id = USER_ID;

  DB.get(u, function(r) {

    u = JSON.parse(r)[0];

    u._classname = 'user';

    // attach all the questionnaire properties.
    for (var a in u) {
      // if (a == '_classname' || a =='nickname') continue;

      // if ($('#questionnaire .content input[name="'+a+'"]').length == 0 && 
      //   $('#'+a).length == 0 && 
      //   $('#'+a).parent().parent().parent().attr('id') == 'questionnaire') {
      //   console.log('skipping', a);
      //   continue;
      // }

      var skipped = ['prototype', 'id', 'age', 'gender', 'concentration', 'handed', 'reading', 'nickname', '_classname', 'timeleft'];

      if (skipped.indexOf(a) != -1) {
        continue;
      }

      u[a] = $('#questionnaire .content input[name="'+a+'"]:checked').val();

      // special cases for text fields
      //   support_point_1
      //   support_point_2
      //   your_stance
      //   take_from_comments
      //   one_adjective
      //   another_adjective
      //   comments
      var special = ['support_point_1', 'support_point_2', 'one_adjective', 'another_adjective'];

      if (special.indexOf(a) != -1) {
        u[a] = $('#questionnaire .content input[name="'+a+'"]').val();
      }

      var special2 = ['take_from_comments', 'comments'];
      if (special2.indexOf(a) != -1) {
        u[a] = $('#'+a).val();
      }

      var special3 = ['your_stance'];
      if (special3.indexOf(a) != -1) {
        u[a] = $('#'+a).slider('value');
      }

      console.log(a, u[a]);

      if (typeof u[a] == 'undefined') {

        alert('Please answer all questions.');
        return false;

      }

    }    

    u.timeleft = TIME_LEFT;
    u.prototype = PROTOTYPE;

    // and store it again
    DB.store(u, function() {

      $('#questionnaire').hide();$('#finish').show();

    })

  });

}