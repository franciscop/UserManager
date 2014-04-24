These are the steps to set up the google login in your page.


If you are in the user manager installer:

 1. Go to https://cloud.google.com/console in a new tab
 2. Click "Create project" button in red.
 3. A popup will appear. Fill in the name of the app and the project id.
 4. Click the button `create`. You will be redirected.
 5. Go to the left menu and click `APIs and auth`.
 6. In the same menu, click the newly shown `Credentials.
 7. Click `Create new client id` big button in red.
 8. Fill the popup that will be shown.
    - Application type: `Web application`.
    - Authorized Javascript origins: your webpage's root. Example: `http://francisco.io/`.
    - Authorized redirect URI: your main page, where **you should have user manager**. Example: `http://francisco.io/`.
 9. At this point, another table will appear, `Client ID for web application`.
10. Copy the `Client ID` from google's page into `Client ID` of the installer.
11. Copy the `Client secret` from google's page into `Client secret` of the installer.
12. Copy the `Email address` from google's page into `Email adress` of the installer.


If you want to simply modify the config.php
1. Do steps 1-9 from the above guide.
2. Copy the 10, 11 and 12 in their respective fields inside the `$google` array. Names are VERY descriptive.
