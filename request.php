<?php

// Find if there was any action to be done with the data provided
// Importance of requests: post > session > cookie (there're no get requests)
//
// Note to self: no need for elses since each action returns something
//
// Description of the action to be performed in the /actions/
// log_user_out: delete all the data related to the user, as well as the device
// edit_user_data: check that there's a user and allow a profile edition
// log_user_in: check submitted values against database
// register_with_form: check that the fields are valid and register the user
// register_with_service: verify that the user data is correct and register him/her with the service
// verify_service: check that the service also verifies the user in the server-side
// service_is_verified: need to check whether the user is already in the database (login) or not (register)
// retrieve_user: there's already a logged in user
// login_with_cookie: need to verify email and token in the cookies
// null: there was nothing requested
function request($Post, $Session, $Cookie)
  {
  // The user is trying to log out
  if ($Post->logout)
    {
    return "log_user_out";
    }
  
  // The user wants to edit the profile
  if ($Post->edit)
    {
    return "edit_user_data";
    }
  
  // Someone is trying to log in
  if ($Post->login)
    {
    return "log_user_in";
    }
  
  // Someone is trying to log in
  if ($Post->recover)
    {
    return "recover_password";
    }
  
  // Someone is trying to register
  if ($Post->register)
    {
    return "register_with_form";
    }
  
  // First time the user attempts to connect with a service
  // This should be vefore register_with_service in case someone clicked
  // a service but later wants to register with another service
  if ($Post->service_connect)
    {
    return "service_connect";
    }
  
  // Someone is trying to register with a service
  if ($Post->service && $Session->service_email)
    {
    return "register_with_service";
    }
  
  // The service returned green light
  if ($Session->service_connect)
    {
    return "verify_service";
    }
  
  // The user just landed here after confirming in the service
  if ($Session->service_email)
    {
    return "service_is_verified";
    }
  
  // There's already a user logged in. Simply retrieve the user
  if ($Session->email)
    {
    return "retrieve_user";
    }
  
  // There's apparently a user in the cookies
  if ($Cookie->email)
    {
    return "login_with_cookie";
    }
  
  // If nothing from the above was submitted
  return null;
  }
