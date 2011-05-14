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
	 * Returns the appropriate media type for a file extension, according to the handlers.
	 * 
	 * @param string $extension The file extension.
	 * @return string The appropriate media type.
	 * @access public
	 */
	public static function getMediaTypeForFileExtension($extension)
	{
		$registry = HandlerFactory::loadHandlers();
		$handler = null;
		$media_type = null;
		
		foreach( $registry as $handler ) {
			if( isset($handler['extensions'][$extension]) ) {
				$media_type = $handler['media_type'];
				break;
			}
		}
		
		return $media_type;
	}
	
	/**
	 * Creates the proper Handler object for the specified media type.
	 * 
	 * @param string $media_type The media type.
	 * @returns object The object for the media type.
	 * @access public
	 */
	public static function createHandlerFor($media_type)
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
	public static function loadHandlers()
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
				$handler_extensions = $class_name::getExtensions();
				
				$registry[$handler_media] = array(
					'name' => $handler_name,
					'media_type' => $handler_media,
					'class' => $class_name,
					'extensions' => $handler_extensions,
				);
			}
		}
		
		HandlerFactory::$registry = $registry;
		
		return HandlerFactory::$registry;
	}
}
