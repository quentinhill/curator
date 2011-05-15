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
 * Builder interface
 * 
 * @package		curator
 * @subpackage	builders
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
interface Builder
{
	/**
     * Return the media type of the Builder.
     *
     * @return string
	 * @access public
     */
	public function setProject($project);
	
	
	/**
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
     */
	public function build();
}
