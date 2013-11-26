submit_demographics = function() {

  var u = new User();

  for (var a in u) {
    if (a == '_classname' || a =='nickname') continue;

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