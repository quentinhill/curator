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
 * Autoload class
 * 
 * @package		Curator
 * @subpackage	Core
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Autoload
{
	/**
	 * Singleton instance.
	 *
	 * @var object
	 * @access private
	 * @static
	 */
	private static $instance = null;
	
	/**
	 * Registered count. 
	 * 
	 * @var integer
	 * @access private
	 * @static
	 */
	private static $registeredCount = 0;
	
	/**
	 * Classname -> path lookup table.
	 * 
	 * @var array
	 * @access private
	 * @static
	 */
	protected $classRegistry = array();
	
	/**
	 * The base library directory.
	 *
	 * @var string
	 * @access protected
	 */
	protected $baseDir = null;
	
	/**
	 * Returns the shared Autoloader.
	 *
	 * @return Autoload
	 * @access public
	 * @static
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
	 * Class constructor.
	 * 
	 * @return Autoload
	 * @access private
	 */
	private function __construct()
	{
		$this->setBaseDir(CURATOR_APP_DIR);
		
		$this->addClassPathToRegistry('Curator\Application',			'Curator'.DS.'Application.php');
		$this->addClassPathToRegistry('Curator\Autoload',				'Curator'.DS.'Autoload.php');
		$this->addClassPathToRegistry('Curator\Builder',				'Curator'.DS.'Builder.php');
		$this->addClassPathToRegistry('Curator\Builder\Data',			'Curator'.DS.'Builder'.DS.'Data.php');
		$this->addClassPathToRegistry('Curator\Builder\Media',			'Curator'.DS.'Builder'.DS.'Media.php');
		$this->addClassPathToRegistry('Curator\Builder\Scripts',		'Curator'.DS.'Builder'.DS.'Scripts.php');
		$this->addClassPathToRegistry('Curator\Builder\Styles',			'Curator'.DS.'Builder'.DS.'Styles.php');
		$this->addClassPathToRegistry('Curator\Config',					'Curator'.DS.'Config.php');
		$this->addClassPathToRegistry('Curator\Console',				'Curator'.DS.'Console.php');
		$this->addClassPathToRegistry('Curator\FileSystem',				'Curator'.DS.'FileSystem.php');
		$this->addClassPathToRegistry('Curator\Handler',				'Curator'.DS.'Handler.php');
		$this->addClassPathToRegistry('Curator\Handler\CSS',			'Curator'.DS.'Handler'.DS.'CSS.php');
		$this->addClassPathToRegistry('Curator\Handler\Curd',			'Curator'.DS.'Handler'.DS.'CURD.php');
		$this->addClassPathToRegistry('Curator\Handler\Factory',		'Curator'.DS.'Handler'.DS.'Factory.php');
		$this->addClassPathToRegistry('Curator\Handler\JavaScript',		'Curator'.DS.'Handler'.DS.'JavaScript.php');
		$this->addClassPathToRegistry('Curator\Handler\Markdown',		'Curator'.DS.'Handler'.DS.'Markdown.php');
		$this->addClassPathToRegistry('Curator\Handler\Template',		'Curator'.DS.'Handler'.DS.'Template.php');
		$this->addClassPathToRegistry('Curator\Handler\YAML',			'Curator'.DS.'Handler'.DS.'YAML.php');
		$this->addClassPathToRegistry('Curator\Project',				'Curator'.DS.'Project.php');
		$this->addClassPathToRegistry('Curator\TemplateData',			'Curator'.DS.'TemplateData.php');
	}
	
	/**
	 * Returns the base directory this autoloader is working on.
	 *
	 * @return string The path to the Curator lib directory
	 * @access public
	 */
	public function getBaseDir()
	{
		return $this->baseDir;
	}
	
	/**
	 * Sets the base directory this autoloader is working on to $basedir.
	 *
	 * @param string $basedir The path to the Curator lib directory
	 * @access public
	 */
	public function setBaseDir($basedir)
	{
		$basedir = realpath($basedir);
		
		if( is_string($basedir) && is_dir($basedir) ) {
			$this->baseDir = realpath($basedir);
		} else {
			throw new \Exception('Invalid path give for autoload base directory: '.$basedir);
		}
	}
	
	/**
	 * Register the class to handle class autoloading.
	 * 
	 * This class uses an internal counter to ensure the class is registered and unregistered once.
	 * 
	 * @param boolean $force If true, the register count is set to zero and the class is registered.
	 * @return void
	 * @access public
	 */
	public function register($force = false)
	{
		if( $force === true ) {
			self::$registeredCount = 0;
		}
		
		if( self::$registeredCount > 0 ) {
			self::$registeredCount++;
			return;
		}
		
		ini_set('unserialize_callback_func', 'spl_autoload_call');
		
		if( !spl_autoload_register(array(self::singleton(), 'autoload')) ) {
			throw new \Exception('Unable to register Autoload::autoload() as an autoloading method.');
		}
		
		self::$registeredCount++;
	}
	
	/**
	 * Unregisters the class from handling class autoloading.
	 * 
	 * @param boolean $force If true, the register count is reset to zero and the class is unregistered.
	 * @return void
	 * @access public
	 */
	public function unregister($force = false)
	{
		if( $force === true ) {
			self::$registeredCount = 0;
		} else {
			self::$registeredCount--;
		}
		
		if( self::$registeredCount === 0 ) {
			spl_autoload_unregister(array(self::singleton(), 'autoload'));
		}
	}
	
	/**
	 * Attempts to load the file containing $class, according to an internal class to filename array.
	 * 
	 * @param string $class A class name to load.
	 * @return boolean Returns true if the class has been loaded.
	 * @access public
	 */
	public function autoload($class)
	{
		if( $path = $this->getClassPathFromRegistry($class) ) {
			require_once $this->baseDir.DS.$path;
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Adds $path to the registry under $class.
	 * 
	 * @param string $class The name of the class to add.
	 * @param string $path The path to the class file.
	 * @return void
	 * @access public
	 */
	public function addClassPathToRegistry($class, $path)
	{
		$class = strval($class);
		$path = strval($path);
		
		$this->classRegistry[$class] = $path;
	}
	
	/**
	 * Removes the entry for $class from the internal registry.
	 * 
	 * @param string $class The name of the class to remove.
	 * @return void
	 * @access public
	 */
	public function removeClassPathFromRegistry($class)
	{
		$class = strval($class);
		
		if( isset($this->classRegistry[$class]) ) {
			$this->classRegistry[$class] = null;
			unset($this->classRegistry[$class]);
		}
	}
	
	/**
	 * Returns the absolute path of the supplied class, or null if there is no class registered.
	 * 
	 * @param string $class A class name
	 * @return mixed An absolute path or null.
	 */
	public function getClassPathFromRegistry($class)
	{
		if( !isset($this->classRegistry[$class]) ) {
			return null;
		}
		
		return $this->classRegistry[$class];
	}
}