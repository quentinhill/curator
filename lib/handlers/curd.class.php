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
 * CurdHandler class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class CurdHandler implements Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'CurdHandler';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/curator-data';
	}
	
	/**
     * Return the file extensions of the Handler.
     *
     * @return array
	 * @access public
	 * @static
     */
    public static function getExtensions()
	{
		return array('curd');
	}
	
	/**
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
     */
	public function handleData($data, $options = array())
	{
		
		$result = null;
		
		try {
			
			if( strpos($data, "\n") === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load data: '.$data);
				}
			}
			
			$data_array = explode("\n\n---\n\n", $data, 2);
			
			$header_handler = HandlerFactory::createHandlerFor(YamlHandler::getMediaType());
			
			$header_data = $header_handler->handleData($data_array[0]);
			
			$body_format = $header_data['format'];
			
			$body_handler = HandlerFactory::createHandlerFor($body_format);
			
			$body_data = $body_handler->handleData($data_array[1]);
			
			$result = array();
			$result['header'] = $header_data;
			$result['body'] = $body_data;
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not handle curd data:');
			Console::stderr('   '.$e->getMessage());
			
		}
		
		return $result;
	}
}
	