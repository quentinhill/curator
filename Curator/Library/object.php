<?php
/**
 * Object, the Foundation.
 * 
 * The base class every other class we use is based off of.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   Library
 */

/**
 * Object class.
 *
 * @package Curator
 * @subpackage Library
 */
class Object
{
	/**
	 * Object-to-string conversion.
	 * Each class can override this method as necessary.
	 *
	 * @return string The name of this class
	 * @access public
	 */
	public function ToString()
	{
		$class = get_class($this);
		return $class;
	}
	
	/**
	 * Stop execution of the current script.
	 *
	 * @param $status see http://php.net/exit for values
	 * @return void
	 * @access protected
	 */
	protected function _stop($status = 0)
	{
		exit($status);
	}
}
