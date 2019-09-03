# wp-autoload
Simple Autoloader for WordPress. WP Autoload is maintained by [Make it WorkPress](https://www.makeitworkpress.com/wordpress-solutions/scripts/wp-autoload/).

## Introduction
This is a php script that makes your PHP classes load automatically when they are initiated. 
Thus, you do not need to require them manually.

Usage of the autoloader script is pretty simple.
* Paste this file in the root of your WordPress plugin or theme. 
* Namespace PHP classes according to their folder structure. 
* Classes are now automatically required when they are initiated.
* Use one class per file!

## Using WP Autoload
Now, let's say you are building a new WordPress theme. Within this theme, you are using a specific folder named classes to store all your classes. For this example, the theme has two classes which are placed in the following folders and a functions.php in root.
- functions.php
- classes\theme.php
- classes\models\model.php

In the functions.php, we include the autoloader and call our classes:
```php
spl_autoload_register( function($classname) {
    
    $class      = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
    $classes    = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php'; 
    
    if( file_exists($classes) ) {
        require_once( $classes );
    }    
} );

$theme = new Theme();
$model = new Models\Model.php

```
Because we have indicated the 'classes' folder in our autoloader, it will automatically require classes from within the classes folder and we don't have to require them manually.

The theme class is not namespaced, as it is in the root of our classes folder and the autoloader starts loading from that. It my look like this (for starters):
```php
<?php
class Theme {
  public function __construct() {
  }
}
```

The model class is namespaced, because it is in a subfolder. The model.php file may look like this: 
```php
<?php
namespace Models;
use WP_Query as WP_Query;

class Model {
  public function __construct() {
  }
}
```
Please also note that if we want to use classes from WordPress or other applications, we have to indicate them at the top of the document using the ``use`` clausule as in the example of model.php.

## Using WP Autoload with Composer
We may add components to our WordPress theme or plugin using composer. Simply said, composer is a kind of package maneger which you can use to register and add new modules to your code.

For example, we may want to include the code from a public repository in one of the themes we are developing. For example, the repository [we build for adding custom fields](https://github.com/makeitworkpress/wp-custom-fields). 

Now we can do that by placing a composer.json file in the root of our theme. The file may look like this:
```
{
    "name": "makeitworkpress/waterfall",
    "description": "Waterfall is a theme with extensive customizer options and very easy to modify or extend for developers.",
    "repositories": [              
        {
            "type": "vcs",
            "url": "https://github.com/makeitworkpress/wp-custom-fields.git"
        }       
    ],
    "require": {
        "php": ">=7.0",
        "makeitworkpress/wp-custom-fields": "dev-master",
    },
    "homepage": "https://github.com/makeitworkpress/our-new-theme/",
    "authors": [ 
        {
            "name": "Make it WorkPress",
            "email": "hello@makeitworkpress.com",
            "homepage": "https://www.makeitworkpress.com"

        } 
    ]          
}
```

If we now go our terminal to the root folder of the theme we're developing. we can use ``composer update --prefer-dist`` to include this repository. The --prefer-dist will ignore all the GIT related files for the given repositories and prefer a distribution version. Unless you want to contribute to the repository, this is the preferred way to include extra modules. By default, composer places all the files within a vendor folder.

Now in our functions.php, we may add the following autloader:

```php
spl_autoload_register( function($classname) {
    
    $class      = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
    $classes    = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php';
    
    $vendor     = str_replace( 'makeitworkpress' . DIRECTORY_SEPARATOR, '', $class );
    $vendor     = 'makeitworkpress' . DIRECTORY_SEPARATOR . preg_replace( '/\//', '/src/', $vendor, 1 ); // Replace the first slash for the src folder
    $vendors    = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $vendor . '.php';

    if( file_exists($classes) ) {
        require_once( $classes );
    } elseif( file_exists($vendors) ) {
        require_once( $vendors );    
    }
   
} );
```

This will both autoload any class called from the classes folder, if properly namespaced and also all the modules added by composer. Because we're using components from makeitworkpress, these are placed within the vendor/makeitworkpress folder.

Please note that composer also adds their own autoloading functions in the respective vendor folders. You also may require these and use them to autoload components.
