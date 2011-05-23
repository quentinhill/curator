<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Curator\Handler;

/**
 * BasicTemplateHandler class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Template implements \Curator\Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'Template';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/template';
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
		return array('tmpl');
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
			
			if( strpos($data, NL) === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load data: '.$data);
				}
			}
			
			foreach( $options as $key => $value ) {
				$needle = '%%__'.strtoupper($key).'__%%';
				
				$data = str_replace($needle, $value, $data);
			}
			
			$result = $data;
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not handle basic template data:');
			Console::stderr('   '.$e->getMessage());
			
		}
		
		return $result;
	}
}
	