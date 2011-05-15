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
class DataBuilder implements Builder
{
	/**
	 * The project this Builder belongs to.
	 * 
	 * @var Project
	 * @access private
	 */
	private $project = null;
	
	/**
     * Set the project to $project.
     *
     * @param string $project The project.
	 * @access public
     */
    public function setProject($project)
	{
		$this->project = $project;
	}
	
	/**
	 * Build the data files.
	 * 
	 * @return void
	 * @access public
	 */
	public function build()
	{
		$data_dir		= $this->project->getProjectDataDir();
		
		$data_files = Filesystem::getDirectoryContents($data_dir);
		
		foreach( $data_files as $data_file ) {
			
			$data_info	= pathinfo($data_file);
			$data_ext	= $data_info['extension'];
			$data_rel	= str_replace($this->project->getProjectDir(), '', $data_file);
			$data_media	= HandlerFactory::getMediaTypeForFileExtension($data_ext);
			
			Console::stdout('  Building '.$data_rel);
			
			$handler = HandlerFactory::getHandlerForMediaType($data_media);
			
			$curd = $handler->handleData($data_file);
			
			if( isset($curd['header']['template']) ) {
				$template_name = $curd['header']['template'];
			} else {
				$manifest = $this->project->getManifestData();
				
				$template_name = $manifest['data']['template'];
			}
			
			$template_path = $this->project->getTemplatesDirPath().DS.$template_name;
			$template_media = HandlerFactory::getMediaTypeForFileExtension(pathinfo($template_path, PATHINFO_EXTENSION));
			$tmpl_handler = HandlerFactory::getHandlerForMediaType($template_media);
			
			$substitutions = array();
			
			$substitutions['styles_combined_url']	= '/styles/combined-345jlk43j5l34kj5l43kj5l.css';
			$substitutions['site_title']			= $manifest['template']['title'];
			$substitutions['site_tagline']			= $manifest['template']['tagline'];
			$substitutions['site_host']				= $manifest['template']['host'];
			$substitutions['data_title']			= $curd['header']['title'];
			$substitutions['data_content']			= $curd['body'];
			
			$tmpl_data = $tmpl_handler->handleData($template_path, $substitutions);
			
			if( isset($curd['header']['url']) ) {
				$filename = $curd['header']['url'];
			} else {
				$filename = pathinfo($data_file, PATHINFO_FILENAME).'.html';
			}
			
			$output_path = $this->project->getPublicHtmlDir().DS.$filename;
			
			Console::stdout('  Creating public_html'.DS.$filename);
			
			if( !file_put_contents($output_path, $tmpl_data) ) {
				throw new \Exception('Could not write: '.$output_path);
			}
		}
	}
}
