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

// Load our bootstrap file.
require_once realpath(dirname(__FILE__).DS.'..'.DS.'lib'.DS.'bootstrap.inc.php');

// Determine our root path
$root_path = dirname(dirname(__FILE__));

// Get her going.
exit(\Curator\StartCurator($root_path));
