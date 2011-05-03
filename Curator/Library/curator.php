<?php
/**
 * Curator, the Coordinator.
 * 
 * The Curator is the main application delegate.
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
	
	const AppSkeletonPath	= 'AppSkeletonPath';
	
	/**
	 * Key directory paths.
	 * @access private
	 */
	private $paths;
	
	/**
	 * Our working directory.
	 * @access private
	 */
	private $pwd;
	
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
		$this->pwd			= $_SERVER['PWD'];
		$this->paths		= array();
		
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
	public function Run()
	{
		$self = Curator::Singleton();
		
		$parser = new \Console_CommandLine();
		
		$parser->name = 'curator';
		$parser->description = 'Curator creates static websites.';
		$parser->version = CURATOR_VERSION;
		$parser->addBuiltinOptions();
		
		$parser->addOption('quiet', array(
			'short_name' => '-q',
			'long_name' => '--quiet',
			'action' => 'StoreTrue',
			'description' => 'produce no output, except for warnings and errors',
			'optional' => true,
		));
		
		$cmd = $parser->addCommand('init', array(
			'description' => 'create a new project',
		));
		
		$cmd->addCommand('directory', array(
			'optional' => true,
			'action' => 'StoreString',
			'description' => 'create the project in the directory specified',
		));
		
		$parser->addCommand('update', array(
			'description' => 'build any changed files',
		));
		
		$parser->addCommand('clean', array(
			'description' => 'clean the current project',
		));
		
		$parser->addCommand('build', array(
			'description' => 'a combination of \'clean\' and \'update\'',
		));
		
		try {
			$result = $parser->parse();
			
			switch( $result->command_name ) {
				case 'init':
					Console::stdout('Initializing new project');
					$self->NewProject();
					break;
				
				case 'update':
					Console::stdout('Updating project');
					$self->UpdateProject();
					break;
				
				case 'clean':
					Console::stdout('Cleaning project');
					$self->CleanProject();
					break;
				
				case 'build':
					$self->clean();
					$self->UpdateProject();
					break;
				
				default:
					Console::stderr('Hello, Dave.', true);
					break;
			}
		} catch( \Exception $e ) {
			$parser->displayError($e->getMessage());
		}
	}
	
	private function NewProject()
	{
		$project_dir = $this->pwd.DS.'.curator';
		
		Console::stdout('Project: '.$project_dir.DS);
		Console::stdout('');
		
		$skel = new Skeleton(Curator::GetPath(Curator::AppSkeletonPath));
		
		$skel->CopyToPath($project_dir);
	}
	
	private function UpdateProject()
	{
		
	}
	
	private function CleanProject()
	{
		
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
	 * Builds the internal array of key directory paths.
	 * @access private
	 */
	private function buildPaths()
	{
		$this->paths[Curator::AppBinDir]		= ROOT_DIR.DS.'bin';
		$this->paths[Curator::AppCuratorDir]	= ROOT_DIR.DS.'Curator';
		$this->paths[Curator::AppLibraryPath]	= $this->paths[Curator::AppCuratorDir].DS.'Library';
		$this->paths[Curator::AppSkeletonPath]	= $this->paths[Curator::AppCuratorDir].DS.'Skeleton';
	}
}
