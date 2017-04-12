<?php
/**
 * Registers the autoloader for classes
 *
 * @author	Michiel Tramper - https://www.michieltramper.com
 * @todo	Make the autoloader comply with the coding standards of WordPress
 */
spl_autoload_register( function($classname) {

    $class     = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
    
    $file_path = get_template_directory() . DIRECTORY_SEPARATOR . $class . '.php';
    
    if ( file_exists( $file_path ) )
        require_once $file_path;
  
} );
