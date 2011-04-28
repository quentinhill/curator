#!/usr/bin/env php
<?php
/**
 * Curator, the Face.
 * 
 * Curator is our core front-end to the user.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   bin
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

try {
	Curator\Curator::run();
} catch( Exception $e ) {
	Console::stderr('Exception ['.$e->getFile().':'.$e->getLine().']('.$e->getCode().'): '.$e->getMessage(), true);
}
