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
	 * Full path to the output directory.
	 * 
	 * @var string
	 * @access private
	 */
	private $outputDir = '';
	
	/**
	 * Class constructor.
	 * 
	 * @param string $project_dir The full path to the project directory
	 * @param string $output_dir The full path to the output directory
	 * @return Project
	 * @access public
	 */
	public function __construct($project_dir, $output_dir)
	{
		$this->projectDir = $project_dir;
		$this->outputDir = $output_dir;
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
		
		Console::stdout('Project directory: '.$destination);
		Console::stdout('');
		
		Console::stdout('  Copying manifest.yml');
		touch($destination.DS.'manifest.yml');
		copy($source.DS.'manifest.yml', $destination.DS.'manifest.yml');
		
		$folders = array('cache', 'data', 'meta', 'scripts', 'styles', 'templates');
		
		foreach( $folders as $folder_name ) {
			$source_path = $source.DS.$folder_name;
			$dest_path = $destination.DS.$folder_name;
			
			if( !is_dir($dest_path) ) {
				Console::stdout('  Creating '.$folder_name);
				mkdir($dest_path, 0777, true);
			}
			
			$directory = new \RecursiveDirectoryIterator($source_path);
			$iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
			
			foreach( $iterator as $item ) {
				$file_name = $item->getFilename();
				$source_file = $source_path.DS.$file_name;
				$dest_file = $dest_path.DS.$file_name;
				
				touch($dest_file);
				
				Console::stdout('  Copying '.$folder_name.DS.$file_name);
				
				copy($source_file, $dest_file);
			}
		}
	}
}
	