First of all,make sure your server supports PHP.

## Simple Installation ##

  * Get code from here and then upload to your server.
  * If OS of your server is Unix like OS, please run
```
chmod 0777 data
chmod 0777 sites/default
```
  * Copy `sites/default/default.config.php` to `sites/default/config.php`,and make sure the file is writable by the web server.
  * Refresh your application page , follow the tips to install it.
  * Remove or rename the file `includes/install.php` after installation finished.
  * Please protect your data especially your server is not Apache.

## Multisite Installation ##

  * Create a new subdirectory of the 'sites' directory with the name of your new site
  * Copy the file `sites/default/default.config.php` into the subdirectory you created in the previous step. Rename the new file to `config.php`.
  * Adjust the permissions of the new site directory, and grant write permissions on the configuration file
  * In a Web browser, navigate to the URL of the new site and continue with the standard installation procedure.