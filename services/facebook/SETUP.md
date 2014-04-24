These are the steps to set up the facebook login in your page.

If you have already installed user manager from `install.php`:

1. Go to https://developers.facebook.com/
2. Click "Apps" in the top bar.
3. Click "Create new App" in the dropdown menu that will be shown.
4. Fill the popup that will be shown.
  - Name of the app: `appname`. Example: `Francisco Presencia website`
  - Namespace: choose what you prefer.
  - Category:  choose one appropriate for your website. Example: `Productivity`
5. Click on "Create Application" on the bottom-right of the popup.
6. Fill in the captcha (if any) and submit it.
7. Copy the `App ID` in the field `appId` into the array `$facebook` inside `config.php`.
8. Copy the `App secret` in the field `secret` into the same array from last point.
9. Go to *Settings* on the left column.
10. Click on *+ Add platform*.
11. Select *Website* from the popup.
12. Fill in the required fields.
  - Site URL: your main page **you should have user manager here**. Example: `http://francisco.io/`
  - Mobile site URL: the version of your site for facebook or the main one if there's none. Example: `http://francisco.io/`
  - Accept Mobile Web Payments: your call. Example: `no`
13. Save changes.
14. Open any page where you're including User manager and test it.

If you are in the user manager installer and it requires the two fields:
1. Do steps 1-6 from the above guide.
2. Copy the `App ID` from facebook's page and paste it inside `App ID` in the installer.
3. Copy the `App secret` from facebook's page and paste it inside `App secret` in the installer.
4. Follow steps 10-14 from the above guide.
