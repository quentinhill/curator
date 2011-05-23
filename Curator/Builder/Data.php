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
 * Data builder.
 * 
 * @package		Curator
 * @subpackage	Builder
 * @author		Quentin Hill <quentin@quentinhill.com>
 */
class Data extends \Curator\Builder
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
		$data_files	= \Curator\FileSystem::getDirectoryContents($data_dir, array('directories' => false));
		$manifest = $this->project->getManifestData();
		
		foreach( $data_files as $data_file ) {
			
			// Gather our facts here.
			$data_info	= pathinfo($data_file);
			$data_ext	= $data_info['extension'];
			$data_rel	= str_replace($this->project->getProjectDirPath().DS, '', $data_file); // relative from the project dir.
			$data_media	= \Curator\Handler\Factory::getMediaTypeForFileExtension($data_ext);
			
			\Curator\Console::stdout('  Building '.$data_rel);
			
			// load the data file.
			$handler	= \Curator\Handler\Factory::getHandlerForMediaType($data_media);
			$data		= $handler->handleData($data_file);
			
			// Stash the data stuff in the template data.
			\Curator\TemplateData::setValue('data', 'title', $data['header']['title']);
			
			if( isset($data['header']['created']) ) {
				\Curator\TemplateData::setValue('data', 'created', $data['header']['created']);
			}
			
			if( isset($data['header']['next_link']) ) {
				\Curator\TemplateData::setValue('data', 'next_link', $data['header']['next_link']);
			}
			
			if( isset($data['header']['prev_link']) ) {
				\Curator\TemplateData::setValue('data', 'prev_link', $data['header']['prev_link']);
			}
			
			\Curator\TemplateData::setValue('data', 'body', $data['body']);
			\Curator\TemplateData::setValue('site', 'title', $manifest['site']['title']);
			\Curator\TemplateData::setValue('site', 'tagline', $manifest['site']['tagline']);
			\Curator\TemplateData::setValue('site', 'host', $manifest['site']['host']);
			
			if( isset($data['header']['analytics']) ) {
				\Curator\TemplateData::setValue('site', 'analytics', $manifest['site']['analytics']);
			}
			
			
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
			$template_media = \Curator\Handler\Factory::getMediaTypeForFileExtension(pathinfo($template_path, PATHINFO_EXTENSION));
			$tmpl_handler = \Curator\Handler\Factory::getHandlerForMediaType($template_media);
			
			// And a template is useless without things to change.
			$substitutions = array();
			
			// •• This needs to be defined somehow!
			$substitutions['styles_combined_url']	= \Curator\TemplateData::getValue('styles', 'combined');
			$substitutions['site_title']			= \Curator\TemplateData::getValue('site', 'title');
			$substitutions['site_tagline']			= \Curator\TemplateData::getValue('site', 'tagline');
			$substitutions['site_host']				= \Curator\TemplateData::getValue('site', 'host');
			$substitutions['site_analytics']		= \Curator\TemplateData::getValue('site', 'analytics');
			$substitutions['data_title']			= \Curator\TemplateData::getValue('data', 'title');
			$substitutions['data_created_date']		= date('l, F j, Y', \Curator\TemplateData::getValue('data', 'created'));
			$substitutions['data_content']			= \Curator\TemplateData::getValue('data', 'body');
			$substitutions['scripts_combined_url']	= \Curator\TemplateData::getValue('scripts', 'combined');
			$substitutions['data_next_link']		= '';
			$substitutions['data_prev_link']		= '';
			
			if( \Curator\TemplateData::getValue('scripts', 'libs') !== null ) {
				$libs = \Curator\TemplateData::getValue('scripts', 'libs');
				
				foreach( $libs as $lib => $url ) {
					$substitutions['scripts_libs_'.$lib] = $url;
				}
			}
			
			if( \Curator\TemplateData::getValue('data', 'next_link') !== null ) {
				$link = '<a href="'.\Curator\TemplateData::getValue('data', 'next_link').'">Next</a>';
				
				$substitutions['data_next_link']	= $link;
			}
			
			if( \Curator\TemplateData::getValue('data', 'prev_link') !== null ) {
				$link = '<a href="'.\Curator\TemplateData::getValue('data', 'prev_link').'">Previous</a>';
				
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
			
			\Curator\Console::stdout('  Creating public_html'.DS.$filename);
			
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
		$data_files	= \Curator\FileSystem::getDirectoryContents($data_dir);
		
		foreach( $data_files as $data_file ) {
			
			// Gather our facts here.
			$data_info	= pathinfo($data_file);
			$data_ext	= $data_info['extension'];
			$data_rel	= str_replace($this->project->getProjectDirPath().DS, '', $data_file); // relative from the project dir.
			$data_media	= \Curator\Handler\Factory::getMediaTypeForFileExtension($data_ext);
			
			// load the data file.
			$handler	= \Curator\Handler\Factory::getHandlerForMediaType($data_media);
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
				\Curator\Console::stdout('  Deleting public_html'.DS.$filename);
				
				if( !unlink($output_path) ) {
					throw new \Exception('Could not delete: '.$output_path);
				}
			}
		}
	}
}
