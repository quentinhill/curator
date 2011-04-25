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
	const AppRootDir		= 'AppRootDir';
	
	/**
	 * The application bin directory.
	 */
	const AppBinDir			= 'AppBinDir';
	
	/**
	 * The application Curator directory.
	 */
	const AppCuratorDir		= 'AppCuratorDir';
	
	/**
	 * The application Curator/Library directory.
	 */
	const AppLibraryPath	= 'AppLibraryPath';
	
	/**
	 * The application Curator/Library directory.
	 */
	private $AllowedCommands	= array('version', 'help');
	
	/**
	 * Number of arguments from the command line.
	 * @access private
	 */
	private $argc;
	
	/**
	 * The arguments from the command line.
	 * @access private
	 */
	private $argv;
	
	/**
	 * Key directory paths.
	 * @access private
	 */
	private $paths;
	
	/**
	 * Current command.
	 * @access private
	 */
	private $command;
	
	/**
	 * Parsed arguments.
	 * @access private
	 */
	private $arguments;
	
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
		$this->argc			= $_SERVER['argc'];
		$this->argv			= $_SERVER['argv'];
		$this->paths		= array();
		$this->command		= '';
		$this->arguments	= array();
		
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
	public function Run()
	{
		$self = Curator::Singleton();
		
		switch( $self->command ) {
			case 'version':
				Console::stdout('Curator v'.$self->Version(), true);
				break;
			
			case 'help':
				Console::stdout($self->Help(), true);
				break;
		}
	}
	
	/**
	 * Gets a path from the internal array.
	 * @param identifier The identifier of the path you want.
	 * @return string The requested path, or null on error.
	 * @access public
	 */
	public function GetPath($identifier = null)
	{
		if( $identifier === null ) {
			$identifier = Curator::AppRootDir;
		}
		
		if( isset($this->paths[$identifier]) ) {
			return $this->paths[$identifier];
		}
		
		return null;
	}
	
	/**
	 * Basic argument parsing.
	 * @access private
	 */
	private function parseArguments()
	{
		if( $this->argc === 1 ) {
			Console::stdout('Please type \'curator help\' for usage.', true);
			exit();
		}
		
		$args = $this->argv;
		
		array_shift($args); // script name
		$this->command = $args[0];
		
		array_shift($args); // command name
		
		if( in_array($this->command, $this->AllowedCommands) === false ) {
			Console::stdout('Unknown command \''.$this->command.'\'.', true);
			Console::stdout('Please type \'curator help\' for usage.', true);
			exit();
		}
		
		$this->arguments = $args;
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
	
	/**
	 * Returns the current version of Curator.
	 * @access private
	 */
	private function Version()
	{
		return '0.1 α';
	}
	
	/**
	 * Builds the internal array of key directory paths.
	 * @access private
	 */
	private function Help()
	{
		$help = <<<HELP
Usage: curator [COMMAND]
Curator creates static websites.

Examples:
  curator help     # Displays help information

Main commands:

  

Other commands:

 version           Prints version information.
 help              Prints this help screen.

Report bugs to quentin@quentinhill.com
HELP;
		
		return $help;
	}
}
