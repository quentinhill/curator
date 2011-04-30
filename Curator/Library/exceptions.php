<?php
/**
 * Exceptions, the Freak Out.
 * 
 * This file defines the exceptions used throughout the Curator.
 * 
 * @author       Quentin Hill <quentin@quentinhill.com>
 * @copyright    Copyright © 2011 Quentin Hill. Some Rights Reserved.
 * @link         http://quentinhill.github.com/curator
 * @license      http://www.opensource.org/licenses/mit-license.php
 * @package      Curator
 * @subpackage   Exceptions
 */

namespace Curator;

class CommandLineArgument extends \Exception {}

class BadCommandLineArgumentCountException extends CommandLineArgument {}

class BadCommandLineArgumentException extends CommandLineArgument
{
	public $argument = '';
	
	public function __construct($argument, $message)
	{
		$new_message = 'Unknown command \''.$argument.'\''."\n".$message;
		
		parent::__construct($new_message);
		
		$this->argument = $argument;
		
	}
}
