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
	public function handleData($data, $options = array())
	{
		include_once(CURATOR_THIRDPARTY_DIR.DS.'yaml'.DS.'lib'.DS.'sfYamlParser.php');
		
		$yaml = new \sfYamlParser();
		$result = null;
		
		try {
			
			if( strpos($data, "\n") === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load yaml: '.$data);
				}
			}
			
			$result = $yaml->parse($data);
			
		} catch( \InvalidArgumentException $e ) {
			
			Console::stderr('** Unable to parse the YAML string:');
			Console::stderr('   '.$e->getMessage());
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not handle YAML data:');
			Console::stderr('  '.$e->getMessage());
			
		}
		
		return $result;
	}
}
	