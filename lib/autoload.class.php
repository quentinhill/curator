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
 * @package		curator
 * @subpackage	autoload
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
		$this->setBaseDir(CURATOR_LIB_DIR);
		
		$this->addClassPathToRegistry('Curator\Application',			'application.class.php');
		$this->addClassPathToRegistry('Curator\Autoload',				'autoload.class.php');
		$this->addClassPathToRegistry('Curator\BasicTemplateHandler',	'handlers'.DS.'basictemplate.class.php');
		$this->addClassPathToRegistry('Curator\Builder',				'builder.abstract.php');
		$this->addClassPathToRegistry('Curator\Config',					'config.class.php');
		$this->addClassPathToRegistry('Curator\Console',				'console.class.php');
		$this->addClassPathToRegistry('Curator\CurdHandler',			'handlers'.DS.'curd.class.php');
		$this->addClassPathToRegistry('Curator\DataBuilder',			'builders'.DS.'data.class.php');
		$this->addClassPathToRegistry('Curator\Filesystem',				'filesystem.class.php');
		$this->addClassPathToRegistry('Curator\Handler',				'handler.interface.php');
		$this->addClassPathToRegistry('Curator\HandlerFactory',			'handlerfactory.class.php');
		$this->addClassPathToRegistry('Curator\MarkdownHandler',		'handlers'.DS.'markdown.class.php');
		$this->addClassPathToRegistry('Curator\Project',				'project.class.php');
		$this->addClassPathToRegistry('Curator\StylesBuilder',			'builders'.DS.'styles.class.php');
		$this->addClassPathToRegistry('Curator\YamlHandler',			'handlers'.DS.'yaml.class.php');
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
