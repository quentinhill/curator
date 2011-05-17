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
class TemplateData
{
	/**
	 * The backend array.
	 *
	 * @var array
	 * @access protected
	 */
	protected static $data = array();
	
	/**
	 * Private constructor.
	 *
	 * @return Console
	 * @access private
	 */
	public function __construct() 
	{
		$this->loadDefaultValues();
	}
	
	private function loadDefaultValues()
	{
		$config = new Config();
		
		TemplateData::$data = $config->loadData(CURATOR_CONFIG_DIR.DS.'templatedata.yml');
	}
	
	public function getValue($group, $key)
	{
		$value = TemplateData::$data[$group][$key];
		
		return $value;
	}
	
	public function setValue($group, $key, $value)
	{
		TemplateData::$data[$group][$key] = $value;
	}
}