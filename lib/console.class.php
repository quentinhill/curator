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
 * Console class
 * 
 * @package		curator
 * @subpackage	console
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Console
{
	/**
	 * Singleton instance.
	 *
	 * @var object
	 * @access private
	 */
	private static $instance;
	
	/**
	 * Standard input stream.
	 *
	 * @var filehandle
	 * @access public
	 */
	public $stdin = null;
	
	/**
	 * Standard output stream.
	 *
	 * @var filehandle
	 * @access public
	 */
	public $stdout = null;
	
	/**
	 * Standard error stream.
	 *
	 * @var filehandle
	 * @access public
	 */
	public $stderr = null;
	
	/**
	 * Newline character to use when appending newlines.
	 * 
	 * @var string
	 * @access public
	 */
	public $newline = "\n";
	
	/**
	 * Returns the shared Console.
	 *
	 * @return Console
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
	 * Opens access to the standard IO streams.
	 *
	 * @return Console
	 * @access private
	 */
	private function __construct() 
	{
		$this->stdin = fopen('php://stdin', 'r');
		$this->stdout = fopen('php://stdout', 'w');
		$this->stderr = fopen('php://stderr', 'w');
	}
	
	/**
	 * Triggers an error.
	 *
	 * @access public
	 */
	public function __clone()
	{
		trigger_error('Cloning is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Outputs to the stdout filehandle, and accepts a response from stdin.
	 *
	 * @param string $message String to output.
	 * @param boolean $newline If true, the outputs gets an added newline.
	 * @return integer Returns the number of bytes output to stdout.
	 * @access public
	 */
	public static function prompt($prompt, $options = null, $default = null)
	{
		$self = Console::singleton();
		$print_options = '';
		
		// build print options for display, if need be.
		if( is_array($options) ) {
			$print_options = '('.implode('/', $options).')';
		}
		
		// build our prompt.
		if( $default === null ) {
			$full_prompt = $prompt.' '.$print_options.$self->newline."> ";
		} else {
			$full_prompt = $prompt.' '.$print_options.$self->newline."[$default] > ";
		}
		
		// show our prompt, and get our response.
		$self->stdout($full_prompt, false);
		$result = fgets($self->stdin);
		
		// something bad happened. goodbye.
		if( $result === false ) {
			exit;
		}
		
		$result = trim($result);
		
		// do we need to return the default?
		if( $default != null && empty($result) ) {
			return $default;
		}
		
		return $result;
	}
	
	/**
	 * Outputs to the stdout filehandle.
	 *
	 * @param string $message String to output.
	 * @param boolean $newline If true, the outputs gets an added newline.
	 * @return integer Returns the number of bytes output to stdout.
	 * @access public
	 */
	public static function stdout($message, $newline = true)
	{
		$self = Console::singleton();
		
		if( $newline ) {
			$message = $message.$self->newline;
		}
		
		$result = fwrite($self->stdout, $message);
		
		return $result;
	}
	
	/**
	 * Outputs $message to the stderr filehandle, with an optional ($newline) newline appended.
	 *
	 * @param string $message String to output.
	 * @param boolean $newline If true, the output gets a newline appended.
	 * @return void
	 * @access public
	 */
	public static function stderr($message, $newline = true)
	{
		$self = Console::singleton();
		
		if( $newline ) {
			$message = $message.$self->newline;
		}
		
		fwrite($self->stderr, $message);
	}
}