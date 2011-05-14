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
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
     */
	public function handleData($data);
}
