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
class DataBuilder extends Builder
{
	/**
	 * Build the data files.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		// Get our cast of characters.
		$data_dir	= $this->project->getDataDirPath();
		$data_files	= Filesystem::getDirectoryContents($data_dir);
		
		foreach( $data_files as $data_file ) {
			
			// Gather our facts here.
			$data_info	= pathinfo($data_file);
			$data_ext	= $data_info['extension'];
			$data_rel	= str_replace($this->project->getProjectDirPath().DS, '', $data_file); // relative from the project dir.
			$data_media	= HandlerFactory::getMediaTypeForFileExtension($data_ext);
			
			Console::stdout('  Building '.$data_rel);
			
			// load the data file.
			$handler	= HandlerFactory::getHandlerForMediaType($data_media);
			$data		= $handler->handleData($data_file);
			
			// Figure out what template to use. If the file has a specific
			// one, use it. Otherwise, grab the one specified in the
			// project manifest.
			if( isset($data['header']['template']) ) {
				$template_name = $data['header']['template'];
			} else {
				$manifest = $this->project->getManifestData();
				
				$template_name = $manifest['data']['template'];
			}
			
			// An overly complex way of doing something simple, I'm sure.
			// Just getting the path to the template file, the media type
			// for that file, based on its extension, and the handler for
			// that media type.
			$template_path = $this->project->getTemplatesDirPath().DS.$template_name;
			$template_media = HandlerFactory::getMediaTypeForFileExtension(pathinfo($template_path, PATHINFO_EXTENSION));
			$tmpl_handler = HandlerFactory::getHandlerForMediaType($template_media);
			
			// And a template is useless without things to change.
			$substitutions = array();
			
			// •• This needs to be defined somehow!
			$substitutions['styles_combined_url']	= '';
			$substitutions['site_title']			= $manifest['template']['title'];
			$substitutions['site_tagline']			= $manifest['template']['tagline'];
			$substitutions['site_host']				= $manifest['template']['host'];
			$substitutions['data_title']			= $data['header']['title'];
			$substitutions['data_content']			= $data['body'];
			
			$tmpl_data = $tmpl_handler->handleData($template_path, $substitutions);
			
			// See if the file specifies a special name, otherwise hack
			// together something resembling the data's filename.
			if( isset($data['header']['url']) ) {
				$filename = $data['header']['url'];
			} else {
				$filename = pathinfo($data_file, PATHINFO_FILENAME).'.html';
			}
			
			$output_path = $this->project->getPublicHtmlDirPath().DS.$filename;
			
			Console::stdout('  Creating public_html'.DS.$filename);
			
			if( !file_put_contents($output_path, $tmpl_data) ) {
				throw new \Exception('Could not write: '.$output_path);
			}
		}
	}
	
	/**
	 * Clean the data files from public_html.
	 * 
	 * @access public
	 */
	public function clean()
	{
		// Get our cast of characters.
		$data_dir	= $this->project->getDataDirPath();
		$data_files	= Filesystem::getDirectoryContents($data_dir);
		
		foreach( $data_files as $data_file ) {
			
			// Gather our facts here.
			$data_info	= pathinfo($data_file);
			$data_ext	= $data_info['extension'];
			$data_rel	= str_replace($this->project->getProjectDirPath().DS, '', $data_file); // relative from the project dir.
			$data_media	= HandlerFactory::getMediaTypeForFileExtension($data_ext);
			
			// load the data file.
			$handler	= HandlerFactory::getHandlerForMediaType($data_media);
			$data		= $handler->handleData($data_file);
			
			// See if the file specifies a special name, otherwise hack
			// together something resembling the data's filename.
			if( isset($data['header']['url']) ) {
				$filename = $data['header']['url'];
			} else {
				$filename = pathinfo($data_file, PATHINFO_FILENAME).'.html';
			}
			
			$output_path = $this->project->getPublicHtmlDirPath().DS.$filename;
			
			if( file_exists($output_path) ) {
				Console::stdout('  Deleting public_html'.DS.$filename);
				
				if( !unlink($output_path) ) {
					throw new \Exception('Could not delete: '.$output_path);
				}
			}
		}
	}
}
