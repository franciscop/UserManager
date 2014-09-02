User Manager
============

Status: unsupported. You are free to push requests or fork the project, but I will not keep writing code myself in the near future.

This project was created to get a secure, integrated and extensible PHP library for handling the users. It manages the users so you can focus on building your web page.

Table of contents

1. Requisites
2. Installation
3. Using it
4. Configuration
5. Personalization
6. Credit


Requisites
----------
- `PHP 5.3.7` or higher (because of password_compat)
- `MySQL`
- `jQuery`
- `password_compat` from http://github.com/ircmaxell/password_compat


Installation
------------

1. Include the folder wherever you want.

2. Access `install.php` from the browser. Follow the installation.

3. Include these two bits of PHP code in every page you want to use the module.

  - Include this file the first thing of every page, it should have the proper path to the `include.php` in the root of UserManager:

    `<? include "UserManager/include.php"; ?>`

  - Include this exact code as is the first bit of your <body> html tag. Don't worry, we already made sure it has the correct path:

    `<?php include $UMBody; ?>`


Use it
------

We did almost everything for you, but you still need to do a bit. You must include the buttons or links where you think they suit better inside your html structure. For your convenience, we created some classes to simplify this to you and here's the actions when clicked:

`UserButton`: When there's no user logged in, the text will be `log in`, and when there's a user logged in, it'll display `Profile`, and their corresponding actions.

`LoginButton`: The log in form appears.

`RegisterButton`: The registering form shows up.

`ProfileButton`: The user's main links appear, including a "LogoutButton".

`SettingsButton`: Edit the user's settings.

`EditButton`: Edit the user's profile data.

`LogoutButton`: The user logs out.

A couple of examples:

- `<button class = "UserButton"></button>`

- `<a class = "LoginButton">Log In</a>`


Advanced
-------------

To fine-tune your application, you can (and are encouraged to) modify these files:

`config.php`: after installation and change it to tailor your page better. You'll find more information there.

`style.css`: style the forms to better suit your page's style.


Credit
------

While this project is *so far* personal, to get here I found these answers useful:

- Bug found when doing a callback from an ajax call: [Popup window blocked in ajax success handler](http://stackoverflow.com/q/7059902)
- Solution for the ajax call: [Best way of dynamic script loading](http://stackoverflow.com/q/7111131)
- Due to microsoft's lack of documentation, I decided to use another library and found a very useful one: http://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html
- Decimals on <input> http://blog.isotoma.com/2012/03/html5-input-typenumber-and-decimalsfloats-in-chrome/


Faq
---


