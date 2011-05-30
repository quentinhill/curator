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
 * Config abstract class.
 * 
 * @package		Curator
 * @subpackage	Config
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
abstract class Config
{
	public static function LoadFromFile($path)
	{
		$config = null;
		$class = get_called_class();
		
		$config = new $class;
		
		$data = $config->input($path);
		
		return $data;
	}
	
	public static function WriteToFile($data, $path)
	{
		$config = null;
		$class = get_called_class();
		
		$config = new $class;
		
		$output = $config->output($data);
		
		file_put_contents($path, $output);
	}
	
	/**
     * Load $data.
     * 
	 * @param mixed Either the path to a file, or the raw data to load.
     * @return array
	 * @access public
     */
	abstract public function input($data);
	
	/**
     * Write $data.
     * 
	 * @param mixed Either the path to a file, or the raw data to load.
	 * @access public
     */
	abstract public function output($data, $output);
}
