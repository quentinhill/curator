<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Curator\Config;

/**
 * YAML config.
 * 
 * @package		Curator
 * @subpackage	Config
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class YAML extends \Curator\Config
{
	/**
     * Load $data.
     * 
	 * @param mixed Either the path to a file, or the raw data to load.
     * @return array
	 * @access public
     */
	public function input($data)
	{
		$handler = \Curator\Handler\Factory::getHandlerForMediaType(\Curator\Handler\YAML::getMediaType());
		
		$config = $handler->input($data);
		
		return $config;
	}
	
	/**
     * Write $data to $output.
     * 
	 * @param array The data to write.
	 * @param string The path to write to.
     * @return bool
	 * @access public
     */
	public function output($data, $output)
	{
		$handler = \Curator\Handler\Factory::getHandlerForMediaType(\Curator\Handler\YAML::getMediaType());
		
		$config = $handler->input($data);
		
		return $config;
	}
}
