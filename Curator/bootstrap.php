<?php
/**
 * Bootstrap, the Go Getter.
 * 
 * The bootstrap does the nitty gritty, bare-bones stuff. Without which, we are nothing.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   Core
 */

namespace Curator;

if( !defined('CURATOR_VERSION') ) {
	define('CURATOR_VERSION', '0.1 α');
}

include_once('Library/exceptions.php');
require_once('Console/CommandLine.php');

/**
 * Special autoload callback function. Allows us to skip most uses of include/require/etc..
 * 
 * @param string $class_name The name of the class to load.
 * @return void
 */
function curator_autoload($class_name)
{
	$class_parts = explode('\\', $class_name);
	
	$class_file_name = array_pop($class_parts);
	$class_file_name = strtolower($class_file_name).'.php';
	
	if( file_exists(ROOT_DIR.DS.'Curator'.DS.'Library'.DS.$class_file_name) === true ) {
		require_once(ROOT_DIR.DS.'Curator'.DS.'Library'.DS.$class_file_name);
		return;
	}
}

// register our callback
spl_autoload_register('Curator\curator_autoload');
