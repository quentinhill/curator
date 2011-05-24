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
			$skeleton_dir = CURATOR_APP_DIR.DS.'Skeleton';
		} else {
			$skeleton_dir = strval($skeleton_dir);
		}
		
		$project_dir = strval($project_dir);
		
		$this->projectDir = realpath($project_dir);
		$this->skeletonDir = realpath($skeleton_dir);
		
		$config = new \Curator\Config\YAML();
		
		$this->skeletonConfig = $config->loadData(CURATOR_APP_DIR.DS.'Config'.DS.'skeleton.yml');
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
	public function getDataDirPath()
	{
		$dir = $this->projectDir.DS.'data';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/media directory.
	 * 
	 * @return string The full path to the project/media directory.
	 * @access public
	 */
	public function getMediaDirPath()
	{
		$dir = $this->projectDir.DS.'media';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/templates directory.
	 * 
	 * @return string The full path to the project/templates directory.
	 * @access public
	 */
	public function getTemplatesDirPath()
	{
		$dir = $this->projectDir.DS.'templates';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/styles directory.
	 * 
	 * @return string The full path to the project/styles directory.
	 * @access public
	 */
	public function getStylesDirPath()
	{
		$dir = $this->projectDir.DS.'styles';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/scripts directory.
	 * 
	 * @return string The full path to the project/scripts directory.
	 * @access public
	 */
	public function getScriptsDirPath()
	{
		$dir = $this->projectDir.DS.'scripts';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/scripts directory.
	 * 
	 * @return string The full path to the project/scripts directory.
	 * @access public
	 */
	public function getScriptsLibsDirPath()
	{
		$dir = $this->getScriptsDirPath().DS.'libs';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/public_html directory.
	 * 
	 * @return string The full path to the project/public_html directory.
	 * @access public
	 */
	public function getPublicHtmlDirPath()
	{
		$dir = $this->projectDir.DS.'public_html';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/public_html/media directory.
	 * 
	 * @return string The full path to the project/public_html/media directory.
	 * @access public
	 */
	public function getPublicMediaDirPath()
	{
		$dir = $this->getPublicHtmlDirPath().DS.'media';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/public_html/styles directory.
	 * 
	 * @return string The full path to the project/public_html/styles directory.
	 * @access public
	 */
	public function getPublicStylesDirPath()
	{
		$dir = $this->getPublicHtmlDirPath().DS.'styles';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/public_html/scripts directory.
	 * 
	 * @return string The full path to the project/public_html/scripts directory.
	 * @access public
	 */
	public function getPublicScriptsDirPath()
	{
		$dir = $this->getPublicHtmlDirPath().DS.'scripts';
		
		return $dir;
	}
	
	/**
	 * Returns the full path to project/public_html/scripts/lib directory.
	 * 
	 * @return string The full path to the project/public_html/scripts/lib directory.
	 * @access public
	 */
	public function getPublicScriptsLibsDirPath()
	{
		$dir = $this->getPublicScriptsDirPath().DS.'libs';
		
		return $dir;
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
			$ext = \Curator\Handler\Factory::getMediaTypeForFileExtension(pathinfo($manifest_path, PATHINFO_EXTENSION));
			$handler = \Curator\Handler\Factory::getHandlerForMediaType($ext);
			
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
		\Curator\Console::stdout('Project directory: '.$project_dir);
		\Curator\Console::stdout('');
		
		try {
			
			$this->installDirectory($skeleton_dir, $project_dir);
			
		} catch( \Exception $e ) {
			
			\Curator\Console::stderr('** Could not install \''.$skeleton_dir.'\' into \''.$project_dir.'\'');
			\Curator\Console::stderr('   '.$e->getMessage());
			
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
			\Curator\Console::stdout('  Creating '.$source_rel.DS);
			
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
			
			if( $this->shouldSkip($filename) ) {
				return;
			}
		}
		
		if( (file_exists($destination_file) === false) && (is_file($destination_file) === false) ) {
			\Curator\Console::stdout('  Copying '.$source_rel);
			
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
		
		$config = new \Curator\Config\YAML();
		
		$manifest = $config->loadData($manifest_path);
		
		\Curator\Console::stdout('Project Directory: '.$this->getProjectDirPath());
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Styles();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Building stylesheets…');
		$builder->build();
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Scripts();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Building scripts…');
		$builder->build();
		\Curator\Console::stdout('');
		
		
		$builder = new \Curator\Builder\Data();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Building data…');
		$builder->build();
		\Curator\Console::stdout('');
		
		
		$builder = new \Curator\Builder\Media();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Building media…');
		$builder->build();
		\Curator\Console::stdout('');
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
		
		\Curator\Console::stdout('Project Directory: '.$this->getProjectDirPath());
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Styles();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Cleaning styles…');
		$builder->clean();
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Scripts();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Cleaning scripts…');
		$builder->clean();
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Data();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Cleaning data…');
		$builder->clean();
		\Curator\Console::stdout('');
		
		$builder = new \Curator\Builder\Media();
		$builder->setProject($this);
		
		\Curator\Console::stdout(' Cleaning media…');
		$builder->clean();
		\Curator\Console::stdout('');
	}
	
	/**
	 * Test a path to see if it should be skipped.
	 * 
	 * @param string $path The path to test.
	 * @access protected
	 */
	public function shouldSkip($path)
	{
		$patterns = $this->skeletonConfig['skip'];
		
		foreach( $patterns as $pattern ) {
			if( fnmatch($pattern, $path) ) {
				return true;
			}
		}
		
		return false;
	}
}
