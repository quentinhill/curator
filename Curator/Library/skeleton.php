<?php
/**
 * Skeleton, the Bare Bones.
 * 
 * The Skeleton manages the basic files for all Curator projects.
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
 * Skeleton class.
 *
 * @package Curator
 * @subpackage FileSystem
 */
class Skeleton extends Object
{
	private $path = '';
	private $directory = null;
	
	public function __construct($path)
	{
		$path = FSO::CleanDirectoryPath($path);
		
		$this->path = $path;
		
		$this->directory = new Directory($this->path);
	}
	
	public function CopyToPath($path)
	{
		$success = true;
		
		try {
			$success = $this->directory->CopyContentsToPath($path);
		} catch( Exception $e ) {
			Console::stderr($e->getMessage(), true);
			$success = false;
		}
		
		return $success;
	}
}
