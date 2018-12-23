<?php

/*

Plugin Name: Mail bomb

Plugin URI: http://github.com

Description: Un plugin de newsletter

Version: 0.1

Author: ANTIOCO Benjamin

Author URI: http://localhost.dom

License: GPL2

*/


if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'MAILBOMB_VERSION', '0.1' );
define( 'MAILBOMB__MINIMUM_WP_VERSION', '4.0' );
define( 'MAILBOMB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


register_activation_hook( __FILE__, [ 'MailbombInit', 'pluginActivation' ] );
register_deactivation_hook( __FILE__, [ 'MailbombInit', 'pluginDesactivation' ] );

require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombInit.php' );
add_action( 'init', [ 'mailbombInit', 'init' ] );

require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombAdmin.php' );
add_action( 'init', [ 'mailbombAdmin', 'init' ] );

require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombTemplate.php' );
add_action( 'init', [ 'mailbombTemplate', 'init' ] );


require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombAjax.php' );
add_action( 'init', [ 'mailbombAjax', 'init' ] );

require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombTemplater.php' );
add_action( 'init', [ 'mailbombTemplater', 'init' ] );

require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombFront.php' );
add_action( 'init', [ 'mailbombFront', 'init' ] );

/*
require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombAdmin.php' );
require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombAjax.php' );
require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombWidget.php' );
require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombCollect.php' );
require_once( MAILBOMB__PLUGIN_DIR . 'class.mailbombCtp.php' );

add_action( 'init', [ 'mailbombAdmin', 'init' ] );
add_action( 'admin_init', [ 'mailbombAjax', 'init' ] );
add_action( 'init', [ 'mailbombWidget', 'init' ] );
add_action( 'admin_init', [ 'mailbombCtp', 'init' ] );
add_action( 'init', [ 'mailbomb', 'init' ] );
add_action( 'init', [ 'mailbombCollect', 'init' ] );

*/

