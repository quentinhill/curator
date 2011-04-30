<?php
/**
 * Console, the Fast Talker.
 * 
 * The console presents information to the user, and gets information back.
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
 * Console class.
 *
 * @package Curator
 * @subpackage Library
 */
class Console extends Object
{
	/**
	 * The singleton instance for the Console.
	 * @access private
	 */
	private static $instance;
	
	/**
	 * There can be only one Console.
	 * 
	 * @return Console
	 * @access public
	 */
	public static function Singleton() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		return self::$instance;
	}
	
	/**
	 * A private constructor; prevents direct creation of object
	 * 
	 * @return Console
	 * @access private
	 */
	private function __construct() 
	{
		$this->args = $_SERVER['argv'];
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
	 * Clears the console.
	 * @return void
	 * @access public
	 */
	public static function clear()
	{
		passthru('clear');
	}
	
	/**
	 * Prints a prompt to stdout, and reads from stdin.
	 * @param string $prompt What to ask the user.
	 * @return string The user's input.
	 * @access public
	 */
	public static function input($prompt)
	{
		$this->stdout($prompt."\n".'> ', false);
		
		$result = fgets(STDIN);
		
		if( $result === false ) {
			exit;
		}
		
		$result = trim($result);
		
		return $result;
	}
	
	/**
	 * Prints a string to stdout.
	 * @param string $string The string to print.
	 * @param boolean $newline If true, a newline is added to $string before printing.
	 * @return void
	 * @access public
	 */
	public static function stdout($string, $newline = true)
	{
		if( $newline === false ) {
			return fwrite(STDOUT, $string);
		} else {
			return fwrite(STDOUT, $string."\n");
		}
	}
	
	/**
	 * Prints a string to stderr.
	 * @param string $string The string to print.
	 * @param boolean $newline If true, a newline is added to $string before printing.
	 * @return void
	 * @access public
	 */
	public static function stderr($string, $newline = false)
	{
		if( $newline === false ) {
			return fwrite(STDERR, $string);
		} else {
			return fwrite(STDERR, $string."\n");
		}
	}
}
