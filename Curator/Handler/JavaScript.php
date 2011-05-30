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
 * JavaScript handler.
 * 
 * @package		Curator
 * @subpackage	Handler
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class JavaScript implements \Curator\Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'JavaScript';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/javascript';
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
		return array('js', 'javascript');
	}
	
	/**
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
     */
	public function input($data, $options = array())
	{
		$default_options = array(
			'minify' => true,
		);
		
		$result = null;
		
		$options = array_merge($default_options, $options);
		
		try {
			
			if( strpos($data, NL) === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load CSS: '.$data);
				}
			}
			
			require_once CURATOR_APP_DIR.DS.'Curator'.DS.'Vendors'.DS.'jsmin-php'.DS.'dist'.DS.'jsmin.php';
			
			if( $options['minify'] ) {
				$result = \JSMin::minify($data);
			}
			
		} catch( \Exception $e ) {
			
			\Curator\Console::stderr('** Could not handle CSS data:');
			\Curator\Console::stderr('  '.$e->getMessage());
			
		}
		
		return $result;
	}
	
	public function output($data, $options = array())
	{
		
	}
}
