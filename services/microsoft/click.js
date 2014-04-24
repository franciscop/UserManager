// Handle the login. In this case, we'll make a call to our PHP verification url
$.ajax({
  type: 'POST',
  url: document.URL, // This is a URL on your website.
  data: { 'service_connect': '1', 'service': 'microsoft', 'redirect': url },
  dataType: 'json',
  success: function(data) {
    console.log('Answer retrieved. Status: ' + data.status + '.');
    // The user needs to connect fb to our app, then redirect them to fb's connection page
    if (data.status == "connect")
      window.location.href = data.url;
    
    // The user is logged in with the service, reload page.
    else if (data.status == "logged") {
      window.location.href = 'http://' + window.location.hostname + window.location.pathname;
      }
    
    else if (data.status == "error") {
      alert(data.message);
      }
    }
  });
