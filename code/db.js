DB = {};

// stores an object in our database using the store.php AJAX call
DB.store = function(o, callback) {
  
  // JSON stringify the object
  var json = JSON.stringify(o);

  // and encode it
  var base64_json = btoa(json);

  // now send the JSON get request
  $.ajax({

    url: 'store.php?type=' + o._classname + '&data=' + base64_json

  }).done(callback);

};

// grabs an object from the database using the get.php AJAX call
DB.get = function(o, callback) {

  // grab the JSON request
  $.ajax({

    url: 'get.php?type=' + o._classname

  }).done(callback);

};