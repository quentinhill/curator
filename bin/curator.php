#!/usr/bin/env php
<?php

if( !defined('DS') ) {
	define('DS', DIRECTORY_SEPARATOR);
}

if( !defined('ROOT_DIR') ) {
	define('ROOT_DIR', dirname(dirname(__FILE__)));
}

if( !defined('CURATOR_DIR') ) {
	define('CURATOR_DIR', ROOT_DIR.DS.'Curator');
}
