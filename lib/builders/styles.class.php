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
 * DataBuilder class
 * 
 * @package		curator
 * @subpackage	builders
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class DataBuilder implements Builder
{
	/**
	 * The project this Builder belongs to.
	 * 
	 * @var Project
	 * @access private
	 */
	private $project = null;
	
	/**
     * Set the project to $project.
     *
     * @param string $project The project.
	 * @access public
     */
    public function setProject($project)
	{
		$this->project = $project;
	}
	
	/**
	 * Build the data files.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		// Prep the build
		$success = true;
		
		try {
			
			
			
		} catch( \Exception $e ) {
			
			Console::stdout('** Could not build styles:');
			Console::stdout('   '.$e->getMessage());
			
			$success = false;
		}
		
		// final build cleanup
		return false;
	}
}
