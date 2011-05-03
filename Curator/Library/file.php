<?php
/**
 * File, the Grunt.
 * 
 * The File object interacts with files in the file system.
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
 * File class.
 *
 * @package Curator
 * @subpackage FileSystem
 */
class File extends Object
{
	/**
	 * The path to the file.
	 * @access private
	 */
	 private $path = '';
	
	/**
	 * Creates a File object representing the file at $path.
	 * 
	 * @param string The full path to the file.
	 * @return File
	 * @access private
	 */
	public function __construct($path)
	{
		$clean = FSO::CleanDirectoryPath($path);
		
		$this->path = $clean;
	}
	
	/**
	 * Returns the path to the file.
	 * @access public
	 */
	public function GetPath()
	{
		return $this->path;
	}
}
