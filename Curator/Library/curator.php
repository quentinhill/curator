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
	 * There can be only one Curator.
	 * 
	 * @return Curator
	 * @access public
	 */
	public static function singleton() 
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
		$this->argc = $_SERVER['argc'];
		$this->argv = $_SERVER['argv'];
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
	public static function run()
	{
		
	}
}
