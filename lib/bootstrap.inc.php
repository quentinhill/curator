<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Load our bootstrap file.
require_once 'autoload.class.php';

 /**
  * Preps the application for running, then runs it. If this function is called more than once, an E_USER_ERROR is triggered.
  * 
  * @param string $root The path to the root of the installation.
  * @return void
  */
function StartCurator($root)
{
	static $did_start = false;
	
	// make sure this is only run once.
	if( $did_start === false ) {
		
		$autoload = cAutoload::singleton();
		
		// configure the autoloader.
		try {
			$autoload->setBaseDir($root.DS.'lib');
			$autoload->register();
		} catch( Exception $e ) {
			echo 'Could not register the autoloader: '.$e->getMessage()."\n";
			die;
		}
		
		// once the autoloader is in place, we are started up.
		$did_start = true;
		
		try {
			
			// run the application
			
		} catch( Exception $e ) {
			echo $e->getMessage();
			die;
		}
		
	} else {
		
		// if we are called again, bail.
		trigger_error('StartCurator called after already being called.', E_USER_ERROR);
	}
}