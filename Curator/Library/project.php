<?php
/**
 * Project, the Organizer.
 * 
 * The project manages the files and directories associated with a project.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   Library
 */

namespace Curator;

/**
 * Project class.
 *
 * @package Curator
 * @subpackage Library
 */
class Project extends Object
{
	const DefaultProjectName = '.curator';
	
	/**
	 * The absolute path to the project's `.curator` directory.
	 * @access private
	 */
	private $ProjectPath = null;
	
	/**
	 * Object-to-string conversion.
	 * Each class can override this method as necessary.
	 *
	 * @return string The name of this class
	 * @access public
	 */
	public function __construct($path = null)
	{
		if( $path === null ) {
			$path = $_SERVER['PWD'];
		}
		
		$this->ProjectPath = realpath($path);
	}
	
	/**
	 * Returns the full path to the project.
	 * @return string The absolute project path.
	 * @access public
	 */
	public function GetProjectPath()
	{
		return $this->ProjectPath;
	}
	
	/**
	 * Attempts to locate the project responsible for the path.
	 * @param string $path The path in question.
	 * @return string The full path to the project found, or null if no project was found.
	 * @access public
	 */
	public function FindProjectDirectory($path)
	{
		$path = realpath($path);
		
		if( $path === '/' ) {
			return null;
		}
		
		// check to see if the path points to a file
		if( file_exists($path) === true ) {
			if( is_file($path) === true ) {
				$path = dirname($path);
			}
		}
		
		// build our test path.
		$test_path = $path.DS.Project::DefaultProjectName;
		
		// do we have a winner?
		if( (file_exists($test_path) === true) && (is_dir($test_path) === true) ) {
			return $test_path;
		} else {
			return Project::FindProjectDirectory(dirname($path));
		}
	}
}
