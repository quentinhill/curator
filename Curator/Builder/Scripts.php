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
 * Script builder.
 * 
 * @package		Curator
 * @subpackage	Builder
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Scripts extends \Curator\Builder
{
	/**
	 * Build the scripts.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		if( !isset($manifest['scripts']) ) {
			return;
		}
		
		$project = $this->getProject();
		$manifest = $project->getManifestData();
		$script_options = $manifest['scripts'];
		
		if( isset($script_options['libraris']) ) {
			\Curator\Console::stdout(' Minifying libraries…');
			
			foreach( $script_options['libraries'] as $filename ) {
				$path = $project->getScriptsLibsDirPath().DS.$filename;
				$rel_path = str_replace($project->getProjectDirPath().DS, '', $path);
				$basename = pathinfo($filename, PATHINFO_FILENAME);
				
				$raw_size = filesize($path);
				$script_data = file_get_contents($path);
				
				$handler = HandlerFactory::getHandlerForMediaType('text/javascript');
				$script_data = $handler->input($script_data);
				
				$hash = hash('sha256', $script_data);
				
				$out_path = $project->getPublicScriptsLibsDirPath().DS.$basename.'-'.$hash.'.js';
				$out_rel = str_replace($project->getProjectDirPath().DS, '', $out_path);
				$out_url = str_replace($project->getPublicHtmlDirPath(), '', $out_path);
				
				if( !file_exists($out_path) ) {
					touch($out_path);
				}
				
				if( !file_put_contents($out_path, $script_data) ) {
					throw new \Exception('Could not save script library: '.$out_path);
				}
				
				$output_size = filesize($out_path);
				$a = sprintf('%d', (100 * ($output_size/$raw_size)));
				
				\Curator\Console::stdout('  Wrote '.$out_rel.' ('.$a.'%)');
				
				$lib_paths[$basename] = $out_url;
			}
			
			\Curator\TemplateData::setValue('scripts', 'libs', $lib_paths);
			
			\Curator\Console::stdout('');
		}
		
		if( isset($script_options['combine']) ) {
			\Curator\Console::stdout(' Minifying scripts…');
			
			$script_data = null;
			
			foreach( $script_options['combine'] as $filename ) {
				$path = $project->getScriptsDirPath().DS.$filename;
				$rel_path = str_replace($project->getProjectDirPath().DS, '', $path);
				
				$raw_size = filesize($path);
				$script_data = $script_data.NL.NL.NL.file_get_contents($path);
			}
			
			$handler = HandlerFactory::getHandlerForMediaType('text/javascript');
			$script_data = $handler->input($script_data);
			
			$hash = hash('sha256', $script_data);
			
			$out_path = $project->getPublicScriptsDirPath().DS.'combined-'.$hash.'.js';
			$out_rel = str_replace($project->getProjectDirPath().DS, '', $out_path);
			$out_url = str_replace($project->getPublicHtmlDirPath(), '', $out_path);
			
			if( !file_exists($out_path) ) {
				touch($out_path);
			}
			
			if( !file_put_contents($out_path, $script_data) ) {
				throw new \Exception('Could not save script file: '.$out_path);
			}
			
			$output_size = filesize($out_path);
			$a = sprintf('%d', (100 * ($output_size/$raw_size)));
			
			\Curator\Console::stdout('  Characters: '.strlen($script_data));
			\Curator\Console::stdout('  Wrote '.$out_rel.' ('.$a.'%)');
			
			\Curator\TemplateData::setValue('scripts', 'combined', $out_url);
		}
	}
	
	/**
	 * Clean the script files from public_html/scripts.
	 * 
	 * @access public
	 */
	public function clean()
	{
		$dir = $this->project->getPublicScriptsDirPath();
		$files = \Curator\FileSystem::getDirectoryContents($dir, array('directories' => false));
		
		foreach( $files as $path ) {
			$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $path);
			
			if( file_exists($path) ) {
				\Curator\Console::stdout('  Deleting '.$rel_path);
				
				if( !unlink($path) ) {
					throw new \Exception('Could not delete: '.$path);
				}
			}
		}
		
		$dir = $this->project->getPublicScriptsLibsDirPath();
		$files = \Curator\FileSystem::getDirectoryContents($dir, array('directories' => false));
		
		foreach( $files as $path ) {
			$rel_path = str_replace($this->project->getProjectDirPath().DS, '', $path);
			
			if( file_exists($path) ) {
				\Curator\Console::stdout('  Deleting '.$rel_path);
				
				if( !unlink($path) ) {
					throw new \Exception('Could not delete: '.$path);
				}
			}
		}
	}
}
