Slim Framework for Legacy PHP's API

## Sample Usage

- SlimacyFramework/
- public/
    + .htaccess
    + app/
        + conf/
        + controllers/
        + models/
    + index.php

### .htaccess

~~~.htaccess
RewriteEngine On
# except for existing file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php
~~~

### index.php

~~~index.php
// Load SlimacyFramework
ini_set('include_path', dirname(__FILE__) . '/../SlimacyFramework/library');
require_once 'Slimacy/Front.php';
require_once 'Slimacy/Registry.php';

// for develop
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Load ini
Slimacy_Registry::setFromIni(dirname(__FILE__) . '/app/conf/settings.ini');

// Dispatch
Slimacy_Front::run(dirname(__FILE__));
~~~
