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
 * @package		curator
 * @subpackage	teamplates
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class TemplateData implements \ArrayAccess
{
	/**
	 * Singleton instance.
	 *
	 * @var object
	 * @access private
	 */
	private static $instance;
	
	/**
	 * The backend array.
	 *
	 * @var array
	 * @access private
	 */
	private $dictionary = null;
	
	/**
	 * Returns the shared TemplateData.
	 *
	 * @return TemplateData
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
	 * Private constructor.
	 *
	 * @return Console
	 * @access private
	 */
	private function __construct() 
	{
		$this->dictionary = array();
		
		$this->loadDefaultValues();
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
	
	private function loadDefaultValues()
	{
		$config = new Config();
		
		$this->dictionary = $config->loadData(CURATOR_CONFIG_DIR.DS.'templatedata.yml');
	}
	
	public function offsetSet($offset, $value)
	{
		if( is_null($offset) ) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}
	
	public function offsetExists($offset)
	{
	    return isset($this->container[$offset]);
	}
	
	public function offsetUnset($offset)
	{
	    unset($this->container[$offset]);
	}
	
	public function offsetGet($offset)
	{
	    return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}