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
 * StyleSheetHandler class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class StyleSheetHandler implements Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'StyleSheetHandler';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/css';
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
		return array('css');
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
		$default_options = array(
			'minify' => true,
		);
		
		$result = null;
		
		$options = array_merge($default_options, $options);
		
		try {
			
			if( strpos($data, "\n") === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load CSS: '.$data);
				}
			}
			
			require CURATOR_THIRDPARTY_DIR.DS.'css-compressor'.DS.'src'.DS.'CSSCompression.php';
			
			$result = \CSSCompression::express($data, 'sane');
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not handle CSS data:');
			Console::stderr('  '.$e->getMessage());
			
		}
		
		return $result;
	}
}
