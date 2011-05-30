<?php
/*
 * This file is part of the Curator package.
 * Copyright © 2011 Quentin Hill <quentin@quentinhill.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Curator\Builder;

/**
 * Media builder.
 * 
 * @package		Curator
 * @subpackage	Builder
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Media extends \Curator\Builder
{
	/**
	 * The config data for the MediaBuilder.
	 * 
	 * @var string
	 * @access private
	 */
	
	private $config = null;
	
	/**
	 * Class constructor.
	 * 
	 * @access public
	 */
	public function __construct()
	{
		$this->config = \Curator\Config\YAML::LoadFromFile(CURATOR_APP_DIR.DS.'Config'.DS.'media.yml');
	}
	
	/**
	 * Build the media files.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		foreach( $this->config['directories'] as $dirname ) {
			$dir_path = $this->project->getMediaDirPath().DS.$dirname;
			$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $dir_path);
			
			\Curator\Console::stdout(' Building '.$rel_path);
			$dir_contents	= \Curator\FileSystem::getDirectoryContents($dir_path);
		
			foreach( $dir_contents as $file_path ) {
				$filename = pathinfo($file_path, PATHINFO_BASENAME);
				$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $file_path);
				$out_path = $this->project->getPublicHtmlDirPath().DS.$rel_path;
				
				if( !$this->shouldSkip($file_path) ) {
					\Curator\Console::stdout('  Copying '.$rel_path);
					
					if( !copy($file_path, $out_path) ) {
						throw new \Exception('Could not copy '.$file_path.' to '.$out_path);
					}
				}
			}
		}
	}
	
	/**
	 * Clean the media files from public_html.
	 * 
	 * @access public
	 */
	public function clean()
	{
		foreach( $this->config['directories'] as $dirname ) {
			$dir_path = $this->project->getPublicMediaDirPath().DS.$dirname;
			$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $dir_path);
			
			\Curator\Console::stdout(' Cleaning '.$rel_path);
			$dir_contents	= \Curator\FileSystem::getDirectoryContents($dir_path);
		
			foreach( $dir_contents as $file_path ) {
				$filename = pathinfo($file_path, PATHINFO_BASENAME);
				$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $file_path);
				
				\Curator\Console::stdout('  Deleting '.$rel_path);
				
				unlink($file_path);
			}
		}
	}
	
	/**
	 * Test a path to see if it should be skipped.
	 * 
	 * @param string $path The path to test.
	 * @access protected
	 */
	public function shouldSkip($path)
	{
		$patterns = $this->config['skip'];
		
		foreach( $patterns as $pattern ) {
			if( fnmatch($pattern, $path) ) {
				return true;
			}
		}
		
		return false;
	}
}
