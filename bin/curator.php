#!/usr/bin/env php
<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
 * Defines the full path to the root of the Curator installation.
 */
if( !defined('CURATOR_ROOT_DIR') ) {
	define('CURATOR_ROOT_DIR', dirname(dirname(__FILE__)));
}

/**
 * Defines the full path to the config dir of the Curator installation.
 */
if( !defined('CURATOR_CONFIG_DIR') ) {
	define('CURATOR_CONFIG_DIR', CURATOR_ROOT_DIR.DS.'config');
}

/**
 * Defines the full path to the lib dir of the Curator installation.
 */
if( !defined('CURATOR_LIB_DIR') ) {
	define('CURATOR_LIB_DIR', CURATOR_ROOT_DIR.DS.'lib');
}

/**
 * Defines the full path to the skeleton dir of the Curator installation.
 */
if( !defined('CURATOR_SKELETON_DIR') ) {
	define('CURATOR_SKELETON_DIR', CURATOR_ROOT_DIR.DS.'skeleton');
}

/**
 * Defines the full path to the skeleton dir of the Curator installation.
 */
if( !defined('CURATOR_THIRDPARTY_DIR') ) {
	define('CURATOR_THIRDPARTY_DIR', CURATOR_ROOT_DIR.DS.'third-party');
}

// Load our bootstrap file.
require_once realpath(CURATOR_LIB_DIR.DS.'bootstrap.inc.php');

// Get her going.
exit(\Curator\StartCurator(CURATOR_ROOT_DIR));
