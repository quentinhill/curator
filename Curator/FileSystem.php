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
	
	/**
	 * Gets the contents of the directory.
	 * 
	 * @param string path The directory.
	 * @param string $options The options.
	 */
	public static function getDirectoryContents($path, $options = array())
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
}
