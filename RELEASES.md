Releases
========

This file exists with the purpose of keeping track of the releases.

0.5 *Future*
 - `index.php`, the center of user management for "the admin".
 - Allow connecting services that don't give the email (twitter)
 - Reset password
 - Avoid using exceptions for control flow

0.4 January 1st, 2014 [Current]
 - `install.php` completed.
 - Full code refactory.
 - All the calls go through the main `include.php`
 - `request.php` handles all [possible] actions to be performed.
 - `Visitor` class handles the current visitor, including blocking.
 - Generate the forms automatically from array.
 - Validate the forms automatically from array.
 - `Blocked` and `Attack` exception classes created to block users.
 - `Post`, `Session` and `Cookie` classes simplify the code.
 - Routing of services is done in PHP, not in javascript (calls go to `init.php`).
 - Bug fix: confirmation email sent.
 - Bug fix: google redirects
 - Bug fix: error logs scrambled all around

0.3 September 20th, 2013
 - Four services available: Persona, Facebook, Gmail and Hotmail.

0.2 Early in September 2013
 - First service integrated: [Persona](https://login.persona.org/).
 - 

0.1. Early in 2013 with some code from 2012
 - Hashing algorithm
 - Login/Register/Logout
