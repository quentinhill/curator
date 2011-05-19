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
 * MarkdownHandler class
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class MarkdownHandler implements Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
     */
	public static function getName()
	{
		return 'Markdown';
	}
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
     */
	public static function getMediaType()
	{
		return 'text/markdown';
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
		return array('md', 'markdown');
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
		include_once(CURATOR_THIRDPARTY_DIR.DS.'php-markdown-'.DS.'markdown.php');
		include_once(CURATOR_THIRDPARTY_DIR.DS.'php-smartypants'.DS.'smartypants.php');
		
		
		$result = null;
		
		try {
			
			if( strpos($data, "\n") === false && is_file($data) ) {
				$data = file_get_contents($data);
				
				if( $data === false ) {
					throw new \Exception('Could not load file: '.$data);
				}
			}
			
			$result = (\SmartyPants(\Markdown($data)));
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not handle Mardkwon data:');
			Console::stderr('   '.$e->getMessage());
			
		}
		
		return $result;
	}
}
	