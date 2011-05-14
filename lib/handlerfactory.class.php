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
 * HandlerFactory class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class HandlerFactory
{
	/**
	 * Handler registry.
	 * 
	 * @var array
	 * @access private
	 * @static
	 */
	private static $registry = array(
		//'text/yaml' => 'YamlHandler',
	);
	
	/**
	 * Triggers an error.
	 * 
	 * @access private
	 */
	private function __construct() 
	{
		trigger_error('Creating HandlerFactory is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Triggers an error.
	 *
	 * @access public
	 */
	public function __clone()
	{
		trigger_error('Cloning HandlerFactory is not allowed.', E_USER_ERROR);
	}
	
	/**
	 * Creates the proper Handler object for the specified media type.
	 * 
	 * @param string $media_type The media type.
	 * @returns object The object for the media type.
	 * @access public
	 */
	public function createHandlerFor($media_type)
	{
		$object = null;
		
		if( isset(HandlerFactory::$registry[$media_type]) ) {
			$object = new $registry[$media_type];
		}
		
		if( $object === null ) {
			throw new \Exception('Could not create handler for \''.$media_type.'\'');
		}
		
		return $object;
	}
}
