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

$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$stderr = fopen('php://stderr', 'w');

fwrite($stdout, 'Curator Third Party Installer and Updater'."\n");
fwrite($stdout, 'Version 0.1 α'."\n");
fwrite($stdout, ''."\n");
fwrite($stdout, 'This will install or update the following third party packages'."\n");
fwrite($stdout, 'for Curator:'."\n");
fwrite($stdout, ' * CSS-Compressor'."\n");
fwrite($stdout, ' * JSMin PHP'."\n");
fwrite($stdout, ' * PHP Markdown with Extras'."\n");
fwrite($stdout, ' * PHP SmartyPants Typographer'."\n");
fwrite($stdout, ' * YAML'."\n");
fwrite($stdout, ''."\n");
fwrite($stdout, ''."\n");

$third_party_dirs = array(
	'css-compressor'	=> 'git://github.com/codenothing/css-compressor.git',
	'jsmin-php'			=> 'git://github.com/rgrove/jsmin-php.git',
	'php-markdown'		=> 'http://git.michelf.com/php-markdown',
	'php-smartypants'	=> 'http://git.michelf.com/php-smartypants',
	'yaml'				=> 'git://github.com/fabpot/yaml.git',
);

foreach( $third_party_dirs as $project => $giturl ) {
	
	$path = CURATOR_THIRDPARTY_DIR.DS.$project;
	
	if( is_dir($path.DS.'.git') ) {
		
		fwrite($stdout, $project.' is installed. Updating…'."\n");
		
		$result = exec('cd '.CURATOR_THIRDPARTY_DIR.DS.$project.'; git pull');
		
	} else {
		fwrite($stdout, $project.' is not installed. Installing…'."\n");
		
		exec('rm -rf '.$path);
		
		$result = exec('cd '.CURATOR_THIRDPARTY_DIR.'; git clone '.$giturl);
	}
	
	
	
	fwrite($stdout, ' '.$result."\n");
	fwrite($stdout, ''."\n");
	fwrite($stdout, '  Done'."\n");
	fwrite($stdout, ''."\n");
}
	