#!/usr/bin/env php
<?php
/**
 * Curator, the Face.
 * 
 * Curator is our core front-end to the user.
 * 
 * @author Quentin Hill <quentin@quentinhill.com>
 * @package curator
 * @subpackage bin
 */

/**
 * Defines shorthand notation for DIRECTORY_SEPARATOR.
 */
if( !defined('DS') ) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Defines the absolute path to the root of our project.
 */
if( !defined('ROOT_DIR') ) {
	define('ROOT_DIR', dirname(dirname(__FILE__)));
}

// Load the bootstrap.
require_once(ROOT_DIR.DS.'Curator'.DS.'bootstrap.php');
