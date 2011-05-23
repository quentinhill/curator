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
	 * This function goes through the contents of the project's data directory,
	 * and attempts to build any files it encounters. Directories are not
	 * supported, and will be skipped.
	 * 
	 * Building a file is the process of loading a source data file, handling
	 * its contents, applying that to a template, then writing the finished
	 * template to disk.
	 * 
	 * @access public
	 */
	public function build()
	{
		// Get our cast of characters.
		$data_dir	= $this->project->getDataDirPath();
		$data_files	= Filesystem::getDirectoryContents($data_dir, array('directories' => false));
		$manifest = $this->project->getManifestData();
		
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
			
			// Stash the data stuff in the template data.
			TemplateData::setValue('data', 'title', $data['header']['title']);
			TemplateData::setValue('data', 'created', $data['header']['created']);
			TemplateData::setValue('data', 'next_link', $data['header']['next_link']);
			TemplateData::setValue('data', 'prev_link', $data['header']['prev_link']);
			TemplateData::setValue('data', 'body', $data['body']);
			TemplateData::setValue('site', 'title', $manifest['site']['title']);
			TemplateData::setValue('site', 'tagline', $manifest['site']['tagline']);
			TemplateData::setValue('site', 'host', $manifest['site']['host']);
			TemplateData::setValue('site', 'analytics', $manifest['site']['analytics']);
			
			
			// Figure out what template to use. If the file has a specific
			// one, use it. Otherwise, grab the one specified in the
			// project manifest.
			if( isset($data['header']['template']) ) {
				$template_name = $data['header']['template'];
			} else {
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
			$substitutions['styles_combined_url']	= TemplateData::getValue('styles', 'combined');
			$substitutions['site_title']			= TemplateData::getValue('site', 'title');
			$substitutions['site_tagline']			= TemplateData::getValue('site', 'tagline');
			$substitutions['site_host']				= TemplateData::getValue('site', 'host');
			$substitutions['site_analytics']		= TemplateData::getValue('site', 'analytics');
			$substitutions['data_title']			= TemplateData::getValue('data', 'title');
			$substitutions['data_created_date']		= date('l, F j, Y', TemplateData::getValue('data', 'created'));
			$substitutions['data_content']			= TemplateData::getValue('data', 'body');
			$substitutions['scripts_combined_url']	= TemplateData::getValue('scripts', 'combined');
			$substitutions['data_next_link']		= '';
			$substitutions['data_prev_link']		= '';
			
			if( TemplateData::getValue('scripts', 'libs') !== null ) {
				$libs = TemplateData::getValue('scripts', 'libs');
				
				foreach( $libs as $lib => $url ) {
					$substitutions['scripts_libs_'.$lib] = $url;
				}
			}
			
			if( TemplateData::getValue('data', 'next_link') !== null ) {
				$link = '<a href="'.TemplateData::getValue('data', 'next_link').'">Next</a>';
				
				$substitutions['data_next_link']	= $link;
			}
			
			if( TemplateData::getValue('data', 'prev_link') !== null ) {
				$link = '<a href="'.TemplateData::getValue('data', 'prev_link').'">Previous</a>';
				
				$substitutions['data_prev_link']	= $link;
			}
			
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
