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
 * Special autoload callback function. Allows us to skip most uses of include/require/etc..
 * 
 * @param string $class_name The name of the class to load.
 * @return void
 */
function curator_autoload($class_name)
{
	$class_file_name = strtolower($class_name).'.php';
	
	if( file_exists(ROOT_DIR.DS.'Curator'.DS.'Library'.DS.$class_file_name) === true ) {
		require_once(ROOT_DIR.DS.'Curator'.DS.'Library'.DS.$class_file_name);
		return;
	}
}

// register our callback
spl_autoload_register('curator_autoload');
