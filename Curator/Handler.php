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
 * Handler interface
 * 
 * @package		curator
 * @subpackage	handlers
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
interface Handler
{
	/**
     * Return the name of the Handler.
     * 
     * @return string
	 * @access public
	 * @static
     */
	public static function getName();
	
	/**
     * Return the media type of the Handler.
     *
     * @return string
	 * @access public
	 * @static
     */
	public static function getMediaType();
	
	/**
     * Return the file extensions of the Handler.
     *
     * @return array
	 * @access public
	 * @static
     */
	public static function getExtensions();
	
	/**
     * Read any data stored in $data, using options in $options.
	 * 
     * @param string $data The data to read.
	 * @param array $options The options for reading data.
     * @return string
	 * @access public
     */
	public function input($data, $options = array());
	
	/**
     * Convert $data (using $options) for writing as a string.
	 * 
     * @param string $data The data to convert.
	 * @param array $options The options for converting data.
     * @return string
	 * @access public
     */
	public function output($data, $options = array());
}
