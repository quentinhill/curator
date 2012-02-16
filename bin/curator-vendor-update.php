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
if( !defined('CURATOR_APP_DIR') ) {
	define('CURATOR_APP_DIR', dirname(dirname(__FILE__)));
}

$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$stderr = fopen('php://stderr', 'w');

fwrite($stdout, 'Curator Third Party Installer and Updater'.NL);
fwrite($stdout, 'Version 0.1 α'.NL);
fwrite($stdout, ''.NL);
fwrite($stdout, 'This will install or update the following third party packages'.NL);
fwrite($stdout, 'for Curator:'.NL);
fwrite($stdout, ' * CSS-Compressor'.NL);
fwrite($stdout, ' * JSMin PHP'.NL);
fwrite($stdout, ' * lessphp'.NL);
fwrite($stdout, ' * PHP Markdown with Extras'.NL);
fwrite($stdout, ' * PHP SmartyPants Typographer'.NL);
fwrite($stdout, ' * YAML'.NL);
fwrite($stdout, ''.NL);
fwrite($stdout, ''.NL);

$third_party_dirs = array(
	'css-compressor'	=> 'git://github.com/codenothing/css-compressor.git',
	'jsmin-php'			=> 'git://github.com/rgrove/jsmin-php.git',
	'lessphp'			=> 'git://github.com/leafo/lessphp.git',
	'php-markdown'		=> 'http://git.michelf.com/php-markdown',
	'php-smartypants'	=> 'http://git.michelf.com/php-smartypants',
	'yaml'				=> 'git://github.com/fabpot/yaml.git',
);

foreach( $third_party_dirs as $project => $giturl ) {
	
	$path = CURATOR_APP_DIR.DS.'Vendors'.DS.$project;
	
	if( is_dir($path.DS.'.git') ) {
		
		fwrite($stdout, $project.' is installed. Updating…'.NL);
		
		$result = exec('cd '.CURATOR_APP_DIR.DS.'Vendors'.DS.$project.'; git pull');
		
	} else {
		fwrite($stdout, $project.' is not installed. Installing…'.NL);
		
		exec('rm -rf '.$path);
		
		$result = exec('cd '.CURATOR_APP_DIR.DS.'Vendors; git clone '.$giturl);
	}
	
	
	
	fwrite($stdout, ' '.$result.NL);
	fwrite($stdout, ''.NL);
	fwrite($stdout, '  Done'.NL);
	fwrite($stdout, ''.NL);
}
	