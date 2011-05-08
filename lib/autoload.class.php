<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cAutoload class
 * 
 * @package		curator
 * @subpackage	autoload
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class cAutoload
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
	 * Returns the shared Console.
	 *
	 * @return Console
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
	 * @access private
	 */
	private function __construct()
	{
		$this->setBaseDir(dirname(dirname(__FILE__)));
		
		$this->addClassPathToRegistry('cAutoload', 'autoload.class.php');
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
		$new_dir = realpath($basedir);
		
		if( is_string($basedir) && is_dir($new_dir) ) {
			$this->baseDir = realpath($new_dir);
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
			return;
		}
		
		ini_set('unserialize_callback_func', 'spl_autoload_call');
		
		if( !spl_autoload_register(array(self::singleton(), 'autoload')) ) {
			throw new Exception('Unable to register cAutoload::autoload() as an autoloading method.');
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
			require_once $path;
			
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
		$new_class = strtolower(strval($class));
		$new_path = realpath(strval($path));
		
		$this->classes[$new_class] = $new_path;
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
		$old_class = strtolower(strval($class));
		
		if( isset($this->classes[$old_class]) ) {
			$this->classes[$old_class] = null;
			unset($this->classes[$old_class]);
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
		$class = strtolower($class);
		
		if( !isset($this->classes[$class]) ) {
			return null;
		}
		
		return $this->baseDir.DS.$this->classes[$class];
	}
}