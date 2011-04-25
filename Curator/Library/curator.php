<?php
/**
 * Curator, the Coordinator.
 * 
 * The Curator is the main application delegate.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://creativecommons.org/licenses/by-sa/3.0/
 * @package      Curator
 * @subpackage   Library
 */

/**
 * Curator class.
 *
 * @package Curator
 * @subpackage Library
 */
class Curator extends Object
{
	/**
	 * The root of the application directory.
	 */
	const AppRootDir       = 'AppRootDir';
	
	/**
	 * The application bin directory.
	 */
	const AppBinDir        = 'AppBinDir';
	
	/**
	 * The application Curator directory.
	 */
	const AppCuratorDir    = 'AppCuratorDir';
	
	/**
	 * The application Curator/Library directory.
	 */
	const AppLibraryPath   = 'AppLibraryPath';
	
	/**
	 * Number of arguments from the command line.
	 * @access private
	 */
	private static $argc;
	
	/**
	 * The arguments from the command line.
	 * @access private
	 */
	private static $argv;
	
	/**
	 * The singleton instance for the Curator.
	 * @access private
	 */
	private static $instance;
	
	/**
	 * Key directory paths.
	 * @access private
	 */
	private static $paths;
	
	/**
	 * There can be only one Curator.
	 * 
	 * @return Curator
	 * @access public
	 */
	public static function Singleton() 
	{
		if( !isset(self::$instance) ) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		return self::$instance;
	}
	
	/**
	 * A private constructor; prevents direct creation of object
	 * 
	 * @return Curator
	 * @access private
	 */
	private function __construct()
	{
		$this->argc		= $_SERVER['argc'];
		$this->argv		= $_SERVER['argv'];
		$this->paths	= array();
		
		$this->paths[Curator::AppRootDir] = ROOT_DIR;
		
		$this->buildPaths();
	}
	
	/**
	 * Prevent the Curator from being cloned.
	 * 
	 * Triggers an E_USER_ERROR.
	 * @return void
	 * @access public
	 */
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Gets Curator going on its way.
	 * @access public
	 */
	public static function Run()
	{
		
	}
	
	/**
	 * Gets a path from the internal array.
	 * @param identifier The identifier of the path you want.
	 * @return string The requested path, or null on error.
	 * @access public
	 */
	public static function GetPath($identifier = null)
	{
		if( $identifier === null ) {
			$identifier = Curator::AppRootDir;
		}
		
		if( isset(Curator::$paths[$identifier]) ) {
			return Curator::$paths[$identifier];
		}
		
		return null;
	}
	
	/**
	 * Builds the internal array of key directory paths.
	 * @access private
	 */
	private function buildPaths()
	{
		$this->paths[Curator::AppBinDir]       = ROOT_DIR.DS.'bin';
		$this->paths[Curator::AppCuratorDir]   = ROOT_DIR.DS.'Curator';
		$this->paths[Curator::AppLibraryPath]  = $this->paths[Curator::AppCuratorDir].DS.'Library';
	}
}
