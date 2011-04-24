<?php

if( !defined('CURATOR_DIR') ) {
	define('CURATOR_DIR', ROOT_DIR.DS.'Curator');
}

if( !defined('CURATOR_CONFIGS_DIR') ) {
	define('CURATOR_CONFIGS_DIR', CURATOR_DIR.DS.'Configs');
}

if( !defined('CURATOR_LIBRARY_DIR') ) {
	define('CURATOR_LIBRARY_DIR', CURATOR_DIR.DS.'Library');
}

if( !defined('CURATOR_SKELETON_DIR') ) {
	define('CURATOR_SKELETON_DIR', CURATOR_DIR.DS.'Skeleton');
}

if( !defined('CURATOR_TEMPORARY_DIR') ) {
	define('CURATOR_TEMPORARY_DIR', CURATOR_DIR.DS.'Temporary');
}

if( !defined('CURATOR_VENDOR_DIR') ) {
	define('CURATOR_VENDOR_DIR', CURATOR_DIR.DS.'Vendor');
}

function curator_autoload($class_name)
{
	$class_file_name = strtolower($class_name).'.php';
	
	if( file_exists(CURATOR_LIBRARY_DIR.DS.$class_file_name) === true ) {
		require_once(CURATOR_LIBRARY_DIR.DS.$class_file_name);
		return;
	}
}

spl_autoload_register('curator_autoload');
