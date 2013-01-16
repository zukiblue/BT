<?php

if ( file_exists( 'offline.php' ) && !isset( $_GET['admin'] ) ) {
	include( 'offline.php' );
	exit;
}

// Stats - for page request time
$g_request_time = microtime(true);
// Output off
ob_start();

/**
 * Load constants
 */
require_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'constant_inc.php' );

$config_inc_found = false;
/**
 * Include default configuration settings
 */
require_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'config_defaults_inc.php' );

# config_inc may not be present if this is a new install
if ( file_exists( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'config_inc.php' ) ) {
	require_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'config_inc.php' );
	$config_inc_found = true;
}


/*
 * Set include paths
 */
#define ( 'BASE_PATH' , realpath( dirname(__FILE__) ) );
#$library = BASE_PATH . DIRECTORY_SEPARATOR . 'library';
$core_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR;
$include_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'include';

$path = array($core_path,
              $include_path,
              get_include_path()
             );
set_include_path( implode( PATH_SEPARATOR, $path ) );

// Unset global variables that are no longer needed.
unset($core_path, $include_path, $path);

# load UTF8-capable string functions
#require_once( 'utf8/utf8.php' );
#require_once( UTF8 . '/str_pad.php' );

# Include compatibility file before anything else
#require_once( 'php_api.php' );

// Verify errors
if ( ($output = ob_get_contents()) != '') {
	echo 'Possible Error in Configuration File - Aborting. Output so far follows:<br />';
	echo var_dump($output);
	die;
}

#require_once( 'utility_api.php' );
#require_once( 'compress_api.php' );
#compress_start_handler();

# Verify if is installed, if not redirect to install.php
if ( false === $config_inc_found ) {
	# this needs to be long form and not replaced by is_page_name as that function isn't loaded yet
	if ( !( isset( $_SERVER['SCRIPT_NAME'] ) && ( 0 < strpos( $_SERVER['SCRIPT_NAME'], 'admin' ) ) ) ) {
		if ( OFF == $g_use_iis ) {
			header( 'Status: 302' );
		}
		header( 'Content-Type: text/html' );

		if ( ON == $g_use_iis ) {
			header( "Refresh: 0;url=setup/install.php" );
		} else {
			header( "Location: setup/install.php" );
		}

		exit; # additional output can cause problems so let's just stop output here
	}
}

# Load rest of core in separate directory.

#require_once( 'config_api.php' );
#require_once( 'logging_api.php' );

# Load internationalization functions (needed before database_api, in case database connection fails)
require_once( 'lang_api.php' );

# error functions should be loaded to allow database to print errors
#require_once( 'error_api.php' );
#require_once( 'helper_api.php' );

# DATABASE WILL BE OPENED HERE!!  THE DATABASE SHOULDN'T BE EXPLICITLY
# OPENED ANYWHERE ELSE.
#require_once( 'database_api.php' );

# PHP Sessions
#require_once( 'session_api.php' );

# Initialize Event System
#require_once( 'event_api.php' );
#require_once( 'events_inc.php' );

# Authentication and user setup
#require_once( 'authentication_api.php' );
#require_once( 'project_api.php' );
#require_once( 'project_hierarchy_api.php' );
#require_once( 'user_api.php' );
#require_once( 'access_api.php' );

# Display API's
require_once( 'http_api.php' );
require_once( 'html_api.php' );
#require_once( 'gpc_api.php' );
#require_once( 'form_api.php' );
#require_once( 'print_api.php' );
#require_once( 'collapse_api.php' );

if ( !isset( $g_login_anonymous ) ) {
	$g_login_anonymous = true;
}

// set HTTP response headers
#http_all_headers();

// push push default language to speed calls to lang_get
if ( !isset( $g_skip_lang_load ) ) {
#	lang_push( lang_get_default() );
}

# signal plugins that the core system is loaded
#event_signal( 'EVENT_CORE_READY' );


?>
