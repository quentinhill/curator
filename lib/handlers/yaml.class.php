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
 * YamlHandler class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class YamlHandler implements Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'YamlHandler';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/yaml';
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
		return array('yml', 'yaml');
	}
	
	/**
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
     */
	public function handleData($data)
	{
		include_once(CURATOR_THIRDPARTY_DIR.DS.'yaml'.DS.'lib'.DS.'sfYaml.php');
		include_once(CURATOR_THIRDPARTY_DIR.DS.'yaml'.DS.'lib'.DS.'sfYamlInline.php');
		include_once(CURATOR_THIRDPARTY_DIR.DS.'yaml'.DS.'lib'.DS.'sfYamlParser.php');
		
		$result = \sfYaml::load($data);
		
		return $result;
	}
}
	