#!/usr/bin/env php
<?php

if( !defined('DS') ) {
	define('DS', DIRECTORY_SEPARATOR);
}

if( !defined('ROOT_DIR') ) {
	define('ROOT_DIR', dirname(dirname(__FILE__)));
}

require_once(ROOT_DIR.DS.'Curator'.DS.'bootstrap.php');

Config::load();
