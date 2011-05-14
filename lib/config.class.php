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
 * Config class
 * 
 * @package		curator
 * @subpackage	config
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Config
{
	/**
	 * The configuration data.
	 */
	private $config = array();
	
	/**
     * Return the name of the Handler.
     * 
     * @return array
	 * @access public
     */
	public function loadData($data)
	{
		$handler = HandlerFactory::createHandlerFor(YamlHandler::getMediaType());
		
		$config = $handler->handleData($data);
		
		$this->config = $config;
		
		return $config;
	}
	
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public function getConfig()
	{
		return $this->config;
	}
}
