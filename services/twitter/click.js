// Handle the login. In this case, we'll make a call to our PHP verification url
alert("Not yet!");
/*
$.ajax({
  type: 'POST',
  url: twitterVerify, // This is a URL on your website.
  data: { url: 'http://' + window.location.hostname + window.location.pathname },
  success: function(jsondata) {
    data = JSON.parse(jsondata);
    // The user needs to connect fb to our app, then redirect them to fb's connection page
    if (data.status == "connect")
      window.location.href = data.url;
    
    // The user is logged in with the service, reload page.
    else if (data.status == "logged") {
      window.location.href = 'http://' + window.location.hostname + window.location.pathname;
      }
    else {
      alert("Error");
      }
    }
  });
/* */
