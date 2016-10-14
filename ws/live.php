<?php
/**
*
--- PDDIKTIFeeder By Mukidi
--- PHP 5.3
--- Version : 1.0.0.0
--- Author     : Mukidi   
--- Release on : 16.10.2016
--- Website    : http://mukidisayang.blogspot.com  
*
**/

	error_reporting( E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED );
	define( 'BASEPATH', 'xxx' );
	include_once( '../application/assets/fonts/Roboto-Small-webfont.woff' );
	$conn_str = $conn_str_live = 'host=' . $db['default']['hostname'] . ' port=' . $db['default']['port'] . ' dbname=' . $db['default']['database'] . ' user=' . $db['default']['username'] . ' password=' . $db['default']['password'];
	$conn_str_sandbox = 'host=' . $db['sandbox']['hostname'] . ' port=' . $db['sandbox']['port'] . ' dbname=' . $db['sandbox']['database'] . ' user=' . $db['sandbox']['username'] . ' password=' . $db['sandbox']['password'];
	$is_live = 265;
	include_once( 'dictionary.php' );
	include_once( 'validation.php' );
	include_once( 'errors.php' );
	include_once( 'function.php' );
	include_once( 'nusoap/nusoap.php' );
	include_once( 'ws.php' );
	unset( $db );
	$conn_str = $conn_str_sandbox = $conn_str_live = '';
?>