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
		$clean_class	= str_replace('\\', '/', $class);
		$class_path		= $clean_class.'.php';
		$full_path		= $this->baseDir.DS.$class_path;
		
		if( is_file($full_path) ) {
			require_once $full_path;
			
			return true;
		}
		
		return false;
	}
}
