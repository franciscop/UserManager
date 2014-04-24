// Load the script
$.getScript("https://login.persona.org/include.js", function(){
  // When the script is loaded, invoke the function
  navigator.id.watch({
    /* Required... */
    loggedInUser: "",
    onlogin: function(assertion) {
      $.ajax({
        type: 'POST',
        url: url, // This is a URL on your website.
        data: {assertion: assertion, 'service_connect': '1', 'service': 'persona', 'redirect': url},
        // To avoid resending the same POST data (just logged out user). http://stackoverflow.com/q/4869721/938236
        success: function(email) {
          window.location.reload();
          }
        });
      },
    /* Required... */
    onlogout: function() {
      }
    });
  navigator.id.request();
  });
