<?php
/**
 * FSO, the Bookworm.
 * 
 * The FSO is a utility class for working with the file system.
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
 * FSO class.
 *
 * @package Curator
 * @subpackage FileSystem
 */
class FSO extends Object
{
	/**
	 * Test a filename for validity. Need to come up with a policy about this.
	 * 
	 * @param string The filename to test.
	 * @return boolean true if the filename is valid, false otherwise.
	 * @access public
	 */
	public static function IsValidFilename($name)
	{
		return true;
	}
	
	/**
	 * Clean up a path by running it through realpath.
	 * 
	 * @param string The path to clean.
	 * @return string The cleaned path.
	 * @access public
	 */
	public static function CleanDirectoryPath($path)
	{
		$clean = realpath($path);
		
		return $clean;
	}
	
	public static function SomethingExistsAtPath($path)
	{
		$path = FSO::CleanDirectoryPath($path);
		$exists = false;
		
		if( file_exists($path) ) {
			$exists = true;
		}
		
		return $exists;
	}
	
	public static function IsDirectoryAtPath($path)
	{
		$path = FSO::CleanDirectoryPath($path);
		$exists = false;
		
		if( file_exists($path) && is_dir($path) ) {
			$exists = true;
		}
		
		return $exists;
	}
	
	public static function IsFileAtPath($path)
	{
		$path = FSO::CleanDirectoryPath($path);
		$exists = false;
		
		if( file_exists($path) && !is_dir($path) ) {
			$exists = true;
		}
		
		return $exists;
	}
	
	public static function FindFileNamed($name, $down = true)
	{
		
	}
	
	public static function ObjectForPath($path)
	{
		$object = null;
		$path = FSO::CleanDirectoryPath($path);
		
		if( FSO::SomethingExistsAtPath($path) ) {
			if( FSO::IsFileAtPath($path) ) {
				$object = new File($path);
			} else if( FSO::IsDirectoyrAtPath($path) ) {
				$object = new Directory($path);
			} else {
				throw new \Exception('not a file or directory: '.$path);
			}
		}
		
		return $object;
	}
}
