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
 * Filesystem class
 * 
 * @package		curator
 * @subpackage	filesystem
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Filesystem
{
	/**
	 * Private constructor means only static functions!
	 * 
	 * @access private
	 */
	private function __construct() { }
	
	public function getDirectoryContents($path, $options = array())
	{
		$default_options = array(
			'files'			=> true,
			'directories'	=> true,
		);
		
		$options = array_merge($default_options, $options);
		
		// Copy directory contents.
		$iterator = new \DirectoryIterator($path);
		$files = array();
		
		foreach( $iterator as $file ) {
			if( $file->isDot() ) {
				continue;
			}
			
			if( $file->isFile() && $options['files'] ) {
				$files[] = $file->getPathname();
			}

			if( $file->isDir() && $options['directories'] ) {
				$files[] = $file->getPathname();
			}
		}
		
		return $files;
	}
	
	/**
	 * Recursively copy a source directory to a destination directory.
	 * 
	 * @param string $source The source directory.
	 * @param string $destination $the destination directory.
	 */
	public function recursiveCopy($source, $destination)
	{
		if( !is_dir($source) ) {
			throw new Exception('Source for recursive copy is not a directory: '.$source);
		}
		
		@mkdir($destination);
		$dir = opendir($source);

	    while( ($file = readdir($dir)) !== false ) {
	        if( ($file != '.') && ($file != '..') ) {
	            if( is_dir($source.DS.$file) ) {
	            	Console::stdout('  Creating '.$file);
					
	                Filesystem::recursiveCopy($source.DS.$file, $destination.DS.$file);
	            } else {
	            	Console::stdout('  Copying '.$file);
					
	                copy($source.DS.$file, $destination.DS.$file);
	            }
	        }
	    }
		
	    closedir($dir);
	}
}
