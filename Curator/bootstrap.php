<?php
/**
 * Bootstrap, the Go Getter.
 * 
 * The bootstrap does the nitty gritty, bare-bones stuff. Without which, we are nothing.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://creativecommons.org/licenses/by-sa/3.0/
 * @package      Curator
 * @subpackage   Core
 */

/**
 * Defines the absolute path to the `Curator/` directory.
 */
if( !defined('CURATOR_DIR') ) {
	define('CURATOR_DIR', ROOT_DIR.DS.'Curator');
}

/**
 * Defines the absolute path to the `Curator/Configs/` directory.
 */
if( !defined('CURATOR_CONFIGS_DIR') ) {
	define('CURATOR_CONFIGS_DIR', CURATOR_DIR.DS.'Configs');
}

/**
 * Defines the absolute path to the `Curator/Library` directory.
 */
if( !defined('CURATOR_LIBRARY_DIR') ) {
	define('CURATOR_LIBRARY_DIR', CURATOR_DIR.DS.'Library');
}

/**
 * Defines the absolute path to the `Curator/Skeleton` directory.
 */
if( !defined('CURATOR_SKELETON_DIR') ) {
	define('CURATOR_SKELETON_DIR', CURATOR_DIR.DS.'Skeleton');
}

/**
 * Defines the absolute path to the `Curator/Temporary` directory.
 */
if( !defined('CURATOR_TEMPORARY_DIR') ) {
	define('CURATOR_TEMPORARY_DIR', CURATOR_DIR.DS.'Temporary');
}

/**
 * Defines the absolute path to the `Vendor` directory.
 */
if( !defined('CURATOR_VENDOR_DIR') ) {
	define('CURATOR_VENDOR_DIR', CURATOR_DIR.DS.'Vendor');
}

/**
 * Special autoload callback function. Allows us to skip most uses of include/require/etc..
 * 
 * @param string $class_name The name of the class to load.
 * @return void
 */
function curator_autoload($class_name)
{
	$class_file_name = strtolower($class_name).'.php';
	
	if( file_exists(CURATOR_LIBRARY_DIR.DS.$class_file_name) === true ) {
		require_once(CURATOR_LIBRARY_DIR.DS.$class_file_name);
		return;
	}
}

// register our callback
spl_autoload_register('curator_autoload');
