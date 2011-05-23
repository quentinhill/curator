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
 * MediaBuilder class
 * 
 * @package		curator
 * @subpackage	builders
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class MediaBuilder extends Builder
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
		$config = new Config();
		
		$this->config = $config->loadData(CURATOR_CONFIG_DIR.DS.'media.yml');
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
			
			Console::stdout(' Building '.$rel_path);
			$dir_contents	= Filesystem::getDirectoryContents($dir_path);
		
			foreach( $dir_contents as $file_path ) {
				$filename = pathinfo($file_path, PATHINFO_BASENAME);
				$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $file_path);
				$out_path = $this->project->getPublicHtmlDirPath().DS.$rel_path;
				
				if( !$this->shouldSkip($file_path) ) {
					Console::stdout('  Copying '.$rel_path);
					
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
			
			Console::stdout(' Cleaning '.$rel_path);
			$dir_contents	= Filesystem::getDirectoryContents($dir_path);
		
			foreach( $dir_contents as $file_path ) {
				$filename = pathinfo($file_path, PATHINFO_BASENAME);
				$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $file_path);
				
				Console::stdout('  Deleting '.$rel_path);
				
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
