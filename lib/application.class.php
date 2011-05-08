<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cApplication class
 * 
 * @package		curator
 * @subpackage	application
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class cApplication
{
	/**
	 * The argument count.
	 * 
	 * @var integer
	 * @access private
	 */
	private $argc = 0;
	
	/**
	 * The argument list.
	 * 
	 * @var array
	 * @access private
	 */
	private $argv = array();
	
	/**
	 * The command we were evoked under.
	 * 
	 * @var string
	 * @access private
	 */
	private $cmd = '';
	
	/**
	 * The Curator version.
	 * 
	 * @var string
	 * @access private
	 */
	private $version = '0.1 α';
	
	/**
	 * The Curator version.
	 * 
	 * @var string
	 * @access private
	 */
	private $description = 'Curator is a static website generator.';
	
	/**
	 * Sets $argc and $argv from $_SERVER['argc'] and $_SERVER['argv'], respectively.
	 * 
	 * @return cApplication
	 * @access public
	 */
	public function __construct()
	{
		$this->argc = intval();
		$this->argv = $_SERVER['argv'];
		
		$full_path = array_shift($this->argv);
		$path_info = pathinfo($full_path);
		
		$this->cmd = $path_info['filename'];
		$this->argc--;
	}
	
	/**
	 * Runs the application, returning the status to exit with.
	 * 
	 * @return integer The exit status.
	 * @access public
	 */
	public function run()
	{
		$exit_status = 0;
		
		// Curator doesn't do anything without at least one argument, besides the curator command itself.
		if( $this->argc === 0 ) {
			cConsole::stdout('Use \''.$this->cmd.' --help\' for usage information.');
		} else {
			try {
				$parser = $this->buildCommandLineParser();
				
				$result = $parser->parse();
				
				$project_dir = realpath($result->options['proj_path']);
				$output_dir = realpath($result->options['out_path']);
				
				if( empty($project_dir) ) {
					$project_dir = $_SERVER['PWD'].DS.'.curator';
				}
				
				if( empty($output_dir) ) {
					$output_dir = $_SERVER['PWD'];
				}
				
				switch( $result->command_name ) {
					case 'new':
						
						break;
					
					case 'clean':
						
						break;
					
					case 'build':
						
						break;
				}
				
				print_r($result);
				
			} catch( Exception $e ) {
				$parser->displayError($e->getMessage());
				$exit_status = $e->getCode();
			}
		}
		
		return $exit_status;
	}
	
	/**
	 * Returns the argument count.
	 * 
	 * @return integer The argument count.
	 * @access public
	 */
	public function argc()
	{
		return $this->argc;
	}
	
	/**
	 * Returns the raw argument list.
	 * 
	 * @return array The raw argument list.
	 * @access public
	 */
	public function argv()
	{
		return $this->argv;
	}
	
	/**
	 * Returns the command name.
	 * 
	 * @return string The command name.
	 * @access public
	 */
	public function cmd()
	{
		return $this->cmd;
	}
	
	/**
	 * Returns the Curator version.
	 * 
	 * @return string The version.
	 * @access public
	 */
	public function getVersion()
	{
		return $this->version;
	}
	
	/**
	 * Returns the Curator description.
	 * 
	 * @return string The description.
	 * @access public
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Builds the command line parser.
	 * 
	 * @return object The command line parser.
	 * @access private
	 */
	private function buildCommandLineParser()
	{
		require_once 'Console/CommandLine.php';
		
		$parser = new Console_CommandLine(array(
			'name'               => $this->cmd(),
			'version'            => $this->getVersion(),
			'description'        => $this->getDescription(),
			'add_help_option'    => true,
			'add_version_option' => true,
			'force_posix'        => false,
		));
		
		$parser->addOption('proj_path', array(
			'short_name'  => '-p',
			'long_name'   => '--project',
			'action'      => 'StoreString',
			'description' => 'path for the project directory to use',
		));
		
		$parser->addOption('out_path', array(
			'short_name'  => '-o',
			'long_name'   => '--output',
			'action'      => 'StoreString',
			'description' => 'path for the output directory to use',
		));
		
		$new_command = $parser->addCommand('new', array(
			'description' => 'create a new Curator project',
		));
		
		$clean_command = $parser->addCommand('clean', array(
			'description' => 'clean the Curator project',
		));
		
		$build_command = $parser->addCommand('build', array(
			'description' => 'build the current Curator project',
		));
		
		$build_command->addOption('clean', array(
			'short_name'	=> '-c',
			'long_name'		=> '--clean',
			'action'		=> 'StoreTrue',
			'description'	=> 'clean the current project first',
		));
		
		return $parser;
	}
}
		