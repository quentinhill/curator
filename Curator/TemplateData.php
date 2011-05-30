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
 * TemplateData class
 * 
 * @package		Curator
 * @subpackage	Core
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class TemplateData
{
	/**
	 * The shared instance.
	 *
	 * @var object
	 * @access private
	 */
	private static $instance;
	
	/**
	 * The backend array.
	 *
	 * @var array
	 * @access protected
	 */
	protected $data = array();
	
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
	 * Loads the default values.
	 *
	 * @return Console
	 * @access private
	 */
	private function __construct() 
	{
		$this->data = \Curator\Config\YAML::LoadFromFile(CURATOR_APP_DIR.DS.'Config'.DS.'templatedata.yml');
	}
	
	/**
	 * Triggers an error.
	 *
	 * @access public
	 */
	public function __clone()
	{
		trigger_error('Cloning TemplateData is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Return the value of $data[$group][$key].
	 * 
	 * @param string $group The data group.
	 * @param string $key The key.
	 * @return Mixed the value.
	 * @access public
	 * @static
	 */
	public static function getValue($group, $key)
	{
		$self = TemplateData::singleton();
		$value = null;
		
		$group = strval($group);
		$key = strval($key);
		
		if( isset($self->data[$group][$key]) ) {
			$value = $self->data[$group][$key];
		}
		
		return $value;
	}
	
	/**
	 * Set $data[$group][$key] to $value.
	 * 
	 * @param string $group The data group.
	 * @param string $key The key.
	 * @param mixed $value The value.
	 * @access public
	 * @static
	 */
	public static function setValue($group, $key, $value)
	{
		$self = TemplateData::singleton();
		
		$group = strval($group);
		$key = strval($key);
		
		$self->data[$group][$key] = $value;
	}
	
	/**
	 * Get the data array.
	 * 
	 * @return array The data
	 * @access public
	 * @static
	 */
	public static function getData()
	{
		$self = TemplateData::singleton();
		
		return $self->data;
	}
}