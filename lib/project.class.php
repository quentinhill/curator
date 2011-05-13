<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace Curator;

/**
 * Project class
 * 
 * @package		curator
 * @subpackage	project
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Project
{
	/**
	 * Full path to the project directory.
	 * 
	 * @var string
	 * @access private
	 */
	private $projectDir = '';
	
	/**
	 * Class constructor.
	 * 
	 * @param string $project_dir The full path to the project directory
	 * @param string $output_dir The full path to the output directory
	 * @return Project
	 * @access public
	 */
	public function __construct($project_dir)
	{
		$this->projectDir = $project_dir;
	}
	
	/**
	 * Installs the skeleton project into the output directory.
	 * 
	 * @returns boolean
	 * @access public
	 */
	public function install($source = null, $destination = null)
	{
		if( $source === null ) {
			$source = CURATOR_ROOT.DS.'skeleton';
		}
		
		if( $destination === null ) {
			$destination = $this->projectDir;
		}
		
		// remind us where we are dumping all this
		Console::stdout('Project directory: '.$destination);
		Console::stdout('');
		
		// copy the manifest first
		Console::stdout('  Copying manifest.yml');
		touch($destination.DS.'manifest.yml');
		copy($source.DS.'manifest.yml', $destination.DS.'manifest.yml');
		
		// The folders we care about
		$folders = array('cache', 'data', 'media', 'meta', 'public_html', 'scripts', 'styles', 'templates');
		
		// create them in destination, and copy source's contents over.
		foreach( $folders as $folder_name ) {
			$source_path = $source.DS.$folder_name;
			$dest_path = $destination.DS.$folder_name;
			
			Console::stdout('  Creating '.$folder_name);
			
			Filesystem::recursiveCopy($source_path, $dest_path);
		}
	}
}
	