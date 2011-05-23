<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Curator;

// Load our core files.
require_once CURATOR_APP_DIR.DS.CURATOR_PACKAGE_NAME.DS.'Console.php';
require_once CURATOR_APP_DIR.DS.CURATOR_PACKAGE_NAME.DS.'Autoload.php';

 /**
  * Preps the application for running, then runs it. If this function is called more than once, an E_USER_ERROR is triggered.
  * 
  * @param string $root_dir The path to the root of the installation.
  * @return void
  */
function StartCurator()
{
	static $did_start = false;
	
	// set up the exit status.
	$exit_status = 0;
	
	// make sure this is only run once.
	if( $did_start === false ) {
		
		$autoload = Autoload::singleton();
		
		// configure the autoloader.
		try {
			
			$autoload->setBaseDir(CURATOR_APP_DIR);
			$autoload->register();
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not register the autoloader:');
			Console::stderr('   '.$e->getMessage());
			die;
			
		}
		
		// once the autoloader is in place, we are started up.
		$did_start = true;
		
		try {
			
			$app = new Application();
			
			$exit_status = $app->run();
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not run the application:');
			Console::stderr('   '.$e->getMessage());
			die;
			
		}
		
	} else {
		
		// if we are called again, bail.
		trigger_error('StartCurator called after already being called.', E_USER_ERROR);
		
	}
	
	// send the status back.
	return $exit_status;
}