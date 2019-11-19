# locations-json
This plugin makes use of WP-CLI to create a JSON file of location post types to facilitate geolocation. One key feature is that it makes it possible to schedule task.

## Usage
From within WP instance `wp process_locations_json`


## Petcurean International
(using the international branch)
this contains reference to wpml to create json files for each language, using the wp-cli the $_SERVER array doesn't exist

hack solution, temporarily add ```$_SERVER['SERVER_NAME'] = 'petcurean.international';```  to /srv/www/petcurean_i18n/releases/20190809/config/environments/production.php
