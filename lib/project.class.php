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
 * Project class
 * 
 * @package		curator
 * @subpackage	project
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Project
{
	/**
	 * Full path to the project directory.
	 * 
	 * @var string
	 * @access private
	 */
	private $projectDir = '';
	
	/**
	 * Full path to the skeleton directory.
	 * 
	 * @var string
	 * @access private
	 */
	private $skeletonDir = '';
	
	/**
	 * The skeleton config data.
	 * 
	 * @var array
	 * @access private
	 */
	private $skeletonConfig = array();
	
	/**
	 * The project manifest data.
	 * 
	 * @var array
	 * @access private
	 */
	private $manifest = null;
	
	/**
	 * Class constructor.
	 * 
	 * @param string $project_dir The full path to the project directory
	 * @param string $output_dir The full path to the output directory
	 * @return Project
	 * @access public
	 */
	public function __construct($project_dir, $skeleton_dir = null)
	{
		if( $skeleton_dir === null ) {
			$skeleton_dir = CURATOR_SKELETON_DIR;
		} else {
			$skeleton_dir = strval($skeleton_dir);
		}
		
		$project_dir = strval($project_dir);
		
		$this->projectDir = realpath($project_dir);
		$this->skeletonDir = realpath($skeleton_dir);
		
		$config = new Config();
		
		$this->skeletonConfig = $config->loadData(CURATOR_CONFIG_DIR.DS.'skeleton.yml');
	}
	
	/**
	 * Returns the projects directory.
	 * 
	 * @return string The full path to the projects directory.
	 * @access public
	 */
	public function getProjectDirPath()
	{
		return $this->projectDir;
	}
	
	/**
	 * Returns the full path to project/data directory.
	 * 
	 * @return string The full path to the project/data directory.
	 * @access public
	 */
	public function getProjectDataDir()
	{
		$data_dir = $this->projectDir.DS.'data';
		
		return $data_dir;
	}
	
	/**
	 * Returns the full path to project/templates directory.
	 * 
	 * @return string The full path to the project/templates directory.
	 * @access public
	 */
	public function getTemplatesDirPath()
	{
		$templates_dir = $this->projectDir.DS.'templates';
		
		return $templates_dir;
	}
	
	/**
	 * Returns the full path to project/templates directory.
	 * 
	 * @return string The full path to the project/templates directory.
	 * @access public
	 */
	public function getStylesDirPath()
	{
		$styles_dir = $this->projectDir.DS.'styles';
		
		return $styles_dir;
	}
	
	/**
	 * Returns the full path to project/public_html directory.
	 * 
	 * @return string The full path to the project/public_html directory.
	 * @access public
	 */
	public function getPublicHtmlDirPath()
	{
		$public_dir = $this->projectDir.DS.'public_html';
		
		return $public_dir;
	}
	
	/**
	 * Returns the full path to project/public_html/styles directory.
	 * 
	 * @return string The full path to the project/public_html/styles directory.
	 * @access public
	 */
	public function getPublicStylesDirPath()
	{
		$public_dir = $this->getPublicHtmlDirPath().DS.'styles';
		
		return $public_dir;
	}
	
	/**
	 * Returns the projects skeleton directory.
	 * 
	 * @return string The full path to the projects skeleton directory.
	 * @access public
	 */
	public function getSkeletonDirPath()
	{
		return $this->skeletonDir;
	}
	
	/**
	 * Returns the projects manifest data.
	 * 
	 * @return array The manifest data.
	 * @access public
	 */
	public function getManifestData()
	{
		if( $this->manifest === null ) {
			$manifest_path = $this->getProjectDirPath().DS.'manifest.yml';
			$ext = HandlerFactory::getMediaTypeForFileExtension(pathinfo($manifest_path, PATHINFO_EXTENSION));
			$handler = HandlerFactory::getHandlerForMediaType($ext);
			
			$manifest = $handler->handleData($manifest_path);
			
			$this->manifest = $manifest;
		}
		
		return $this->manifest;
	}
	
	/**
	 * Installs the skeleton project into the project directory.
	 * 
	 * @returns boolean
	 * @access public
	 */
	public function install()
	{
		$project_dir	= $this->getProjectDirPath();
		$skeleton_dir	= $this->getSkeletonDirPath();
		
		// remind us where we are dumping all this
		Console::stdout('Project directory: '.$project_dir);
		Console::stdout('');
		
		try {
			
			$this->installDirectory($skeleton_dir, $project_dir);
			
		} catch( \Exception $e ) {
			
			Console::stderr('** Could not install \''.$skeleton_dir.'\' into \''.$project_dir.'\'');
			Console::stderr('   '.$e->getMessage());
			
		}
	}
	
	/**
	 * Install the directory at $source_dir into $destination_dir. Will
	 * recursively copy any subdirectories.
	 * 
	 * @param string $source_dir The source directory to copy from.
	 * @param string $destination_dir The directory to install to.
	 * @result void
	 * @access protected
	 * @throws Exception
	 */
	protected function installDirectory($source_dir, $destination_dir)
	{
		$source_rel		= str_replace($this->getSkeletonDirPath().DS, '', $source_dir);
		$source_info	= pathinfo($source_dir);
		
		// See if the destination exists.
		if( (file_exists($destination_dir) === false) && (is_dir($destination_dir) === false) ) {
			Console::stdout('  Creating '.$source_rel.DS);
			
			// Create the directory.
			if( !mkdir($destination_dir, 0755) ) {
				throw new \Exception('Could not create directory: '.$destination_dir);
			}
		}
		
		// Copy directory contents.
		$source_itr = new \DirectoryIterator($source_dir);
		
		foreach( $source_itr as $source_file ) {
			if( $source_file->isDot() ) {
				continue;
			}
			
			$source_path = $source_file->getPathname();
			$filename = str_replace($source_dir.DS, '', $source_path);
			$destination_path = $destination_dir.DS.$filename;
			
			if( $source_file->isFile() ) {
				$this->installFile($source_path, $destination_path);
			} elseif( $source_file->isDir() ) {
				$this->installDirectory($source_path, $destination_path);
			}
		}
	}
	
	/**
	 * Install the file at $source_file into $destination_file.
	 * 
	 * @param string $source_file The source file to copy.
	 * @param string $destination_file The destination path to copy to.
	 * @result void
	 * @access protected
	 * @throws Exception
	 */
	protected function installFile($source_file, $destination_file)
	{
		$source_rel		= str_replace($this->getSkeletonDirPath().DS, '', $source_file);
		$source_info	= pathinfo($source_file);
		
		if( !file_exists($source_file) && !is_file($source_file) ) {
			throw new \Exception('Source file does not exist: '.$source_file);
		}
		
		if( isset($this->skeletonConfig['skip']) ) {
			
			$filename = $source_info['filename'];
			
			foreach( $this->skeletonConfig['skip'] as $skip_filter ) {
				if( preg_match($skip_filter, $filename) !== 0 ) {
					return;
				}
			}
			
		}
		
		if( (file_exists($destination_file) === false) && (is_file($destination_file) === false) ) {
			Console::stdout('  Copying '.$source_rel);
			
			touch($destination_file);
			
			if( !copy($source_file, $destination_file) ) {
				throw new \Exception('Could not copy file: '.$source_file);
			}
		}
	}
	
	/**
	 * Builds the current project.
	 * 
	 * @access public
	 */
	public function build()
	{
		$manifest_path = $this->getProjectDirPath().DS.'manifest.yml';
		
		if( !is_file($manifest_path) ) {
			throw new \Exception('Could not locate manifest at: '.$manifest_path);
		}
		
		$config = new Config();
		
		$manifest = $config->loadData($manifest_path);
		
		Console::stdout('Project Directory: '.$this->getProjectDirPath());
		Console::stdout('');
		
		$styles_builder = new StylesBuilder();
		$styles_builder->setProject($this);
		
		Console::stdout(' Building stylesheets…');
		$styles_builder->build();
		Console::stdout('');
		
		$data_builder = new DataBuilder();
		$data_builder->setProject($this);
		
		Console::stdout(' Building data…');
		$data_builder->build();
		Console::stdout('');
	}
	
	/**
	 * Cleans the current project.
	 * 
	 * @access public
	 */
	public function clean()
	{
		$manifest_path = $this->getProjectDirPath().DS.'manifest.yml';
		
		if( !is_file($manifest_path) ) {
			throw new \Exception('Could not locate manifest at: '.$manifest_path);
		}
		
		$config = new Config();
		
		$manifest = $config->loadData($manifest_path);
		
		Console::stdout('Project Directory: '.$this->getProjectDirPath());
		Console::stdout('');
		
		$data_builder = new DataBuilder();
		$data_builder->setProject($this);
		
		$data_builder->clean();
	}
}
