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
abstract class Builder
{
	/**
	 * The project this Builder belongs to.
	 * 
	 * @var Project
	 * @access protected
	 */
	protected $project = null;
	
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
     * Set the project to $project.
     *
     * @param string $project The project.
	 * @access public
     */
    public function getProject()
	{
		return $this->project;
	}
	
	/**
     * Handle $data, and return the results.
     *
     * @param string data The data to handle.
     * @return string
	 * @access public
	 * @abstract
     */
	abstract public function build();
}
