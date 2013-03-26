dev2
====
Due to school, I haven't been updating controllers to utilize the framework, just been manipulating the framework. 
Which also means a strong lack of commentary, my apologies.

--Needs a .htaccess, which I didn't include
RewriteEngine on
RewriteRule ^dev2/([0-9a-zA-Z_/]+)$ /dev2/index.php?loc=$1


Folder Setup:
- Install: contains a small install script for setting up the database and saving the info in the config file.
- Application: contains controllers, models, views, system files, and classes(which are resources used by the system but not controllers).
- Assets: Files used by the web. E.g., css, javsscript, images, etc.
- *** Most directories contain a "OLD" folder which are files from previous versions that have not been updated to most recent framework***

The Flow:
- URL's that follow: dev2/controllername/action
  I.e., dev2/auth/logout will go to application/controllers/auth.php and call the function "logout()".
  I know it used to check for a list of safe controllers saved in the dB, not sure that is right now.

- URL: site.com/dev2/
  Will automatically load handler.php, which normally be the index of the site for users.
