// DRAGBY jQuery plugin
// Make the main form to be draggable from the h2
(function ($, window) {
  $.fn.dragby = function(handler) {
    // Variables that will be needed
    var box = this;
    var boxpos;
    var mouse;
    
    $(handler).mousedown(function(event){
      box.addClass("dragby");
      boxpos = box.offset();
      mouse = event;
      // Avoid selecting text
      event.preventDefault();
      });
    
    $(window).on("mousemove", function(event){
      if($('.dragby').length) {
        box.offset({
          top:  boxpos.top + event.pageY - mouse.pageY,
          left: boxpos.left + event.pageX - mouse.pageX
          });
        }
      });
        
    $(window).mouseup(function(event){
      box.removeClass("dragby");
      // Avoid any link/anything that could be there
      event.preventDefault();
      });
    
    return this;
    }
  }(jQuery, window));
// END OF PLUGIN

// Facebook bug + fix + source: http://stackoverflow.com/a/13933967
if (window.location.hash == '#_=_') {
  window.location.hash = ''; // for older browsers, leaves a # behind
  history.pushState('', document.title, window.location.pathname); // nice and clean
  }








$(document).ready(function(){
  
  
  
  // Handle the services's Logo clicks
  var loaded = new Array();
  // When a service is clicked
  $(".services img").click(function(){
    // Save which service was clicked
    var serviceclicked = $(this).attr("data-service");
    
    // Replace the current icon for the loading one
    $(this).attr("src", folder + "/services/loading.gif");
    
    // Avoid multiple loading when clicking several times
    if (loaded.indexOf() == -1) {
      // Load the needed external script. Note how this is only loaded when the user clicks the desired service
      $.getScript(folder + "/services/" + serviceclicked + "/click.js", function(){
        });
      }
    loaded.push(serviceclicked);
    });
  
  
  
  
  
  
  
  
  // Handle the closing of the popup
  $(document).mouseup(function (e) {
    var forms = $("#userForms");
    var close = $("#userForms nav .close");
    
    if (close.is(e.target) ||   // If the clicked button was the close one or
        !forms.is(e.target) && // if the target of the click isn't the container nor
        forms.has(e.target).length === 0 && // a descendant of the container and
        forms.css('display') == 'block') // the container is shown
      {
      // Hide everything
      forms.fadeOut(500, function(){
        $(this).hide().children("div").hide();
        });
      }
    });
  
  
  // Show the form box with the appropriate form inside
  function showform(name) {
    // If the forms are hidden
    if ($("#userForms:hidden").length !== 0) {
      // Make sure the content is also hidden
      $("#userForms > div").hide();
      // And display it
      $("#userForms").fadeIn(500);
      // With the appropriate child
      $("." + name).show();
      }
    
    // If they're already shown and they have something visible inside
    else if ($('#userForms > div:visible').length !== 0) {
      // Hide it
      $("#userForms > div").hide(500);
      // Display the new one
      $("." + name).show(500);
      }
    }
  // Needed because of this: http://stackoverflow.com/q/17830621
  window.showform = showform;
  
  // Complete the register with the email provided by the ajax call to verify.php
  function completeregister(email) {
    showform("register");
    emailparts = email.split('@');
    breakornot = email.length > 25 ? "<br>" : "";
    $("#userForms .register input[type=email]").parent().html(emailparts[0] + "<small>" + breakornot + "@" + emailparts[1] + "</small>");
    $("#userForms .register input[type=password]").parent().remove();
    $("input[name=register]").attr("name", "service");
    }
  // Needed because of this: http://stackoverflow.com/q/17830621
  window.completeregister = completeregister;
  
  
  
  $("#userForms").dragby("#userForms h2");
  
  // Differnet buttons that can be set-up and clicked
  $(".LoginButton").click(function(e){e.preventDefault(); showform("login")});
  $(".RegisterButton").click(function(e){e.preventDefault(); showform("register")});
  $(".ProfileButton").click(function(e){e.preventDefault(); showform("profile")});
  $(".EditButton").click(function(e){e.preventDefault(); showform("edit")});
  $(".SettingsButton").click(function(e){e.preventDefault(); showform("settings")});
  $(".ForgotButton").click(function(e){e.preventDefault(); showform("forgot")});
  $(".DeleteButton").click(function(e){e.preventDefault(); showform("delete")});
  
  // Set the text of all the UserButton. If there's no user, Log in
  var LoginButtonText = (typeof email == 'undefined') ? "Log In" : "Profile";
  $(".UserButton").html(LoginButtonText);
  
  // Display the appropriate form
  $(".UserButton").click(function(){
    var WhichForm = (typeof email == 'undefined') ? "login" : "profile";
    showform(WhichForm);
    });
  
  // Recover password
  $("#userForms .RecoverButton").click(function(e){
    e.preventDefault();
    
    var email = $("#userForms .forgot input[type='email']").val();
    $.ajax({
      type: "POST",
      url: document.URL,
      data: { 'email': email },
      dataType: 'json',
      success: function(data) {
        if (data.register == 1) {
          $("#recoverform").remove();
          $("#userForms .forgot .message").html("Confirmation email sent to <strong>" + email + "</strong>. ")
          $("#userForms .forgot .RecoverButton").html("Resend");
          }
        else {
          $("#userForms .forgot .message").html("Cannot find <strong>" + email + "</strong>.");
          $("#userForms .forgot .RecoverButton").html("Try again");
          }
        }
      }); // Close the ajax call
    }); // Close the $("RecoverButton").click();
  
  $(".PrivacyButton").click(function(){
    $(this).replaceWith("Test");
    });
  
  $(".ChangePassButton").click(function(){
    $(this).replaceWith("<input placeholder = '******'>");
    });
  }); // Close the $(document).ready(function(){
