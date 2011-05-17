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
 * StylesBuilder class
 * 
 * @package		curator
 * @subpackage	builders
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class StylesBuilder extends Builder
{
	/**
	 * Build the stylesheet files.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		$project = $this->project;
		$manifest = $project->getManifestData();
		$style_options = $manifest['styles'];
		
		$css_data = '';
		
		if( is_array($style_options['combined']) ) {
			foreach( $style_options['combined'] as $filename ) {
				$filepath = $this->project->getStylesDirPath().DS.$filename;
				$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $filepath);
				
				Console::stdout('  Loading '.$rel_path);
				
				$css_data = $css_data."\n\n".file_get_contents($filepath);
			}
			
			$hash = hash('sha256', $css_data);
			
			$output_path = $project->getPublicStylesDirPath().DS.'output-'.$hash.'.css';
			$rel_output = str_replace($this->project->getProjectDirPath().DS, '', $output_path);
			$url_output = str_replace($this->project->getPublicHtmlDirPath(), '', $output_path);
			
			Console::stdout('  Writing '.$rel_output);
			
			if( !file_put_contents($output_path, $css_data) ) {
				throw new \Exception('Could not write CSS to: '.$output_path);
			}
			
			TemplateData::setValue('styles', 'combined', $url_output);
		} else {
			Console::stdout('  Nothing to do.');
		}
	}
	
	/**
	 * Clean the stylesheet files from public_html/styles.
	 * 
	 * @access public
	 */
	public function clean()
	{
		$dir = $this->project->getPublicStylesDirPath();
		$files = FileSystem::getDirectoryContents($dir, array('directories' => false));
		
		foreach( $files as $path ) {
			$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $path);
			
			if( file_exists($path) ) {
				Console::stdout('  Deleting '.$rel_path);
				
				if( !unlink($path) ) {
					throw new \Exception('Could not delete: '.$path);
				}
			}
		}
	}
}
