DB = {};

// stores an object in our database using the record.php AJAX call
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