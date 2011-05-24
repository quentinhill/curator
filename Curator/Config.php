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
	/**
     * Load $data.
     * 
	 * @param mixed Either the path to a file, or the raw data to load.
     * @return array
	 * @access public
     */
	abstract public function loadData($data);
}
