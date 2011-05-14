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
	private static $registry = null;
	
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
		$registry = HandlerFactory::loadHandlers();
		$handler_info = null;
		
		if( !isset($registry[$media_type]) ) {
			throw new \Exception('Unknown handler media type: '.$media_type);
		}
		
		$handler_info = $registry[$media_type];
		
		$object = new $handler_info['class'];
		
		return $object;
	}
	
	/**
	 * Goes through the lib/handlers directory and loads any found helpers.
	 * 
	 * @return array The loaded handlers.
	 * @access public
	 */
	public function loadHandlers()
	{
		if( HandlerFactory::$registry === null ) {
			$registry = array();
			
			$handlers = Filesystem::getDirectoryContents(CURATOR_LIB_DIR.DS.'handlers');
			
			foreach( $handlers as $file_path ) {
				$file_info = pathinfo($file_path);
				$file_name = explode('.', $file_info['filename'], 2);
				$file_name = $file_name[0];
				
				$class_name = '\\Curator\\'.ucfirst($file_name).'Handler';
				
				require_once $file_path;
				
				$handler_name = $class_name::getName();
				$handler_media = $class_name::getMediaType();
				
				$registry[$handler_media] = array('name' => $handler_name, 'class' => $class_name);
			}
		}
		
		HandlerFactory::$registry = $registry;
		
		return HandlerFactory::$registry;
	}
}
