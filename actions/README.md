Possible actions
================

The different actions that can be taken are all in this folder.

These are the returned from `request()`:
- `log_user_out.php`: delete all the data related to the user, as well as the device
- `edit_user_data`: check that there's a user and allow a profile edition
- `log_user_in.php`: check submitted values against database
- `register_with_form.php`: check that the fields are valid and register the user
- `register_with_service.php`: verify that the user data is correct and register him/her with the service
- `verify_service.php`: check that the service also verifies the user in the server-side
- `service_is_verified.php`: need to check whether the user is already in the database (login) or not (register)
- `retrieve_user.php`: fetch the user in $User
- `login_with_cookie.php`: need to verify email and token in the cookies

The secondary actions are in /secondary/ folder:
- `delete_data`: delete the cookies, session, etc from the current visitor
