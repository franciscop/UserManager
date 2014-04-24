Adding new services
===================

Privacy protection
------------------

None of the services will load any of their pages prior user clicking on any of its logos. This has some disadvantages. However, we think that it has an overall improvement, as it also speeds up page loads. Read `How are we protecting your privacy` below.

Develop a new Service
---------------------

You will need to create this folder:
- `/services/YOURSERVICE`: We suggest copying a folder, renaming it and working on that.

That folder should contain these files:
- `click.js`: As its name suggests, this will be called when the user clicks the service's logo. If you're going to do a pure php call, you can simply copy Facebook's one.
- `verify.php`: Return `$Logged = 1;` and a valid `$Email` if user is verified and `$Logged = null;` and `loginUrl` if user is not verified.
- `logo.svg`: The logo to display for log in.
- `logo.png`: *opt* fallback for the logo. Default size should be 55x55.

Besides, it should also contain these files before `install.php` is executed.
- `install.php`: Contains an array with the needed files with the keys being the required data and the values the name for the installer as:
    
    $service = array("keyId" => "Key id", "secret" => "App secret");
    
- `SETUP.md`: A file with the steps to set up the service. How to get the appId, app_secret, where to do it and other steps go here.

How services work
-----------------

This is the tipical flow of a service:

1. Click on the service's logo for the first time.
2. Action `service_connect.php` is called, which calls `verify.php`.
3. `verify.php` sets up `$_SESSION['service']` and if it sets:
  - Email: set up `$_SESSION['service_email']` and go to step 8.
  - Nothing: set up `$_SESSION['service_connect']` and go to next step.
4. Redirection to the service's log in page
5. Service redirects to previous page.
6. Action `verify_service.php` is triggered, which calls again `verify.php`.
7. If the service returns:
  - Email: Set up `$_SESSION['service_email']`.
  - Nothing: Throw exception; user must be already logged in
8. Action `service_is_verified.php` is called and it attempts to find user in db.
  - Found: `$_SESSION['email']` is set. Go to step 10.
  - Not found: Register prompt is shown with the service email. Go to next step.
9. User is registered with the email set in the `$_SESSION['service_email']`.
10. Delete all `$_SESSION` data except the email.

How are we protecting your privacy?
-----------------------------------
- Some services just *cannot* return anything the first call for verification, like Facebook. Furthermore, you cannot set up the url for redirection in `click.js` since it needs PHP to generate it and we won't load any Service's php until the user has requested it. So this is the flow for that case:
1. User clicks on the service's logo and click.js is retrieved and executed.
2. Javascript calls verify.php, which can only return the login `url`.
3. Only THEN javascript can load that url for login with facebook.

On the other hand a bad privacy scheme (that we do NOT implement) would be:
1. Facebook's login url is fetched in every normal call and sets up `url` in the logo.
2. User clicks on the service's logo `<a href = "badprivacyurl">` and goes to the page for login with facebook.

Besides of the privacy enhancement, it also improves speed for regular users, while slowing slightly at the moment of logging in with that Service by making one small json call.

FAQ (for those developing services)
-----------------------------------

- Why don't you integrate `click.js` into the main javascript.js?
- There's the need for `click.js` since some services can make the first contact with javascript, like `Persona`, so it is there to allow for further personalization. Read the `click.js` from `Persona` to see what it means. However, for full PHP login, we recommend copying Facebook's one.
