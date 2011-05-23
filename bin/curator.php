#!/usr/bin/env php
<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 // By default, we want all warnings and errors shown.
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

/**
 * Defines shorthand notation for DIRECTORY_SEPARATOR.
 */
if( !defined('DS') ) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Defines shorthand notation for the desired newline character..
 */
if( !defined('NL') ) {
	define('NL', "\n");
}

/**
 * The name of the Curator package.
 */
if( !defined('CURATOR_PACKAGE_NAME') ) {
	define('CURATOR_PACKAGE_NAME', 'Curator');
}

/**
 * The current version of the Curator package.
 */
if( !defined('CURATOR_CURRENT_VERSION') ) {
	define('CURATOR_CURRENT_VERSION', 'alpha');
}

/**
 * Defines the full path to the root of the Curator installation.
 */
if( !defined('CURATOR_APP_DIR') ) {
	define('CURATOR_APP_DIR', dirname(dirname(__FILE__)));
}
// Load our bootstrap file.
require_once realpath(CURATOR_APP_DIR.DS.CURATOR_PACKAGE_NAME.DS.'Bootstrap.php');

// Get her going.
exit(\Curator\StartCurator());
