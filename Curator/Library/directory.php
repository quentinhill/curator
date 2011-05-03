<?php
/**
 * Directory, the Grunt.
 * 
 * The Directory object interacts with directories in the file system.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   FileSystem
 */

namespace Curator;

/**
 * Directory class.
 *
 * @package Curator
 * @subpackage FileSystem
 */
class Directory extends Object
{
	/**
	 * The path to the directory.
	 * @access private
	 */
	 private $path = '';
	
	/**
	 * Creates a Folder object representing the directory at $path.
	 * 
	 * @param string The full path to the directory.
	 * @return File
	 * @access private
	 */
	public function __construct($path)
	{
		$path = FSO::CleanDirectoryPath($path);
		
		$this->path = $path;
	}
	
	/**
	 * Returns the path to the directory.
	 * @access public
	 */
	public function GetPath()
	{
		return $this->path;
	}
	
	public function CopyContentsToPath($dest, $source = null)
	{
		if( $source === null ) {
			$source = $this->path;
		}
		
		if( is_dir($source) ) {
			@mkdir($dest);
			$objects = scandir($source);
			if( sizeof($objects) > 0 ) {
				foreach( $objects as $file ) {
					if( $file == "." || $file == ".." )
						continue;
					
					// go on
					if( is_dir($source.DS.$file) ) {
						$this->CopyContentsToPath($dest.DS.$file, $source.DS.$file);
					} else {
						copy($source.DS.$file, $dest.DS.$file);
					}
				}
			}
			
			return true;
		} elseif( is_file($source) ) {
			return copy($source, $dest);
		} else {
			return false;
		}
	}
}
