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
	 * Parsed arguments.
	 * @access private
	 */
	private static $arguments;
	
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
		$this->parseArguments();
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
		
		
		Console::stdout('Type \'curator help\' for usage.', true);
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
	 * PARSE ARGUMENTS
	 * 
	 * This command line option parser supports any combination of three types
	 * of options (switches, flags and arguments) and returns a simple array.
	 * 
	 * [pfisher ~]$ php test.php --foo --bar=baz
	 *   ["foo"]   => true
	 *   ["bar"]   => "baz"
	 * 
	 * [pfisher ~]$ php test.php -abc
	 *   ["a"]     => true
	 *   ["b"]     => true
	 *   ["c"]     => true
	 * 
	 * [pfisher ~]$ php test.php arg1 arg2 arg3
	 *   [0]       => "arg1"
	 *   [1]       => "arg2"
	 *   [2]       => "arg3"
	 * 
	 * [pfisher ~]$ php test.php plain-arg --foo --bar=baz --funny="spam=eggs" --also-funny=spam=eggs \
	 * > 'plain arg 2' -abc -k=value "plain arg 3" --s="original" --s='overwrite' --s
	 *   [0]       => "plain-arg"
	 *   ["foo"]   => true
	 *   ["bar"]   => "baz"
	 *   ["funny"] => "spam=eggs"
	 *   ["also-funny"]=> "spam=eggs"
	 *   [1]       => "plain arg 2"
	 *   ["a"]     => true
	 *   ["b"]     => true
	 *   ["c"]     => true
	 *   ["k"]     => "value"
	 *   [2]       => "plain arg 3"
	 *   ["s"]     => "overwrite"
	 *
	 * @author Patrick Fisher <patrick@pwfisher.com>
	 * @since August 21, 2009
	 * @see http://www.php.net/manual/en/features.commandline.php
	 *                      #81042 function arguments($argv) by technorati at gmail dot com, 12-Feb-2008
	 *                      #78651 function getArgs($args) by B Crawford, 22-Oct-2007
	 * @usage               $args = Console::parse_args($_SERVER['argv']);
	 */
	private function parseArguments()
	{
		$argv = self::$args;
		
	    array_shift($argv);
	    $out                            = array();
	
	    foreach ($argv as $arg) {
	
	        // --foo --bar=baz
	        if (substr($arg,0,2) == '--'){
	            $eqPos                  = strpos($arg,'=');
	
	            // --foo
	            if ($eqPos === false){
	                $key                = substr($arg,2);
	                $value              = isset($out[$key]) ? $out[$key] : true;
	                $out[$key]          = $value;
	            }
	            // --bar=baz
	            else {
	                $key                = substr($arg,2,$eqPos-2);
	                $value              = substr($arg,$eqPos+1);
	                $out[$key]          = $value;
	            }
	        }
	        // -k=value -abc
	        else if (substr($arg,0,1) == '-'){
	
	            // -k=value
	            if (substr($arg,2,1) == '='){
	                $key                = substr($arg,1,1);
	                $value              = substr($arg,3);
	                $out[$key]          = $value;
	            }
	            // -abc
	            else {
	                $chars              = str_split(substr($arg,1));
	                foreach ($chars as $char){
	                    $key            = $char;
	                    $value          = isset($out[$key]) ? $out[$key] : true;
	                    $out[$key]      = $value;
	                }
	            }
	        }
	        // plain-arg
	        else {
	            $value                  = $arg;
	            $out[]                  = $value;
	        }
	    }
	    self::$args                     = $out;
	    return $out;
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
