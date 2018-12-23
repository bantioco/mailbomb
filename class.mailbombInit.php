<?php

class mailbombInit
{

	private static $initiated = false;

    public static function init() 
    {
        if ( ! self::$initiated ) 
        {
			self::initHooks();
		}
    }
    
    /**
	 * Initializes WordPress hooks
	 */
    private static function initHooks() 
    {
		self::$initiated = true;

		add_action( 'wp_enqueue_scripts', [ 'MailbombInit', 'mailbombAddStyle' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ 'MailbombInit', 'mailbombAddScript' ], 10, 2 );

		add_action( 'admin_enqueue_scripts', [ 'MailbombInit', 'mailbombAddAdminAssets' ], 10, 2 );
	}

	public static function pluginActivation()
	{
		self::mailbombCreateDb();
		return;
	}

	public static function pluginDesactivation()
	{
		return;
	}

	public static function mailbombAddStyle() {
		wp_enqueue_style( 'mailbomb-css', plugin_dir_url( __FILE__ ).'dist/front_mailbomb.css', 20  ); 
	}
	
	public static function mailbombAddScript() {
		wp_enqueue_script( 'mailbomb-js', plugin_dir_url( __FILE__ ).'dist/front_mailbomb.pack.js', [ 'jquery' ], '1.0.0', true );
	}
	
	
	/**
	 * Proper way to enqueue scripts and styles.
	 */
	public static function mailbombAddAdminAssets() 
	{
		wp_enqueue_style( 'style-mailbomb', plugin_dir_url( __FILE__ ).'dist/back_mailbomb.css', 20 );
		wp_enqueue_script( 'script-mailbomb', plugin_dir_url( __FILE__ ).'dist/back_mailbomb.pack.js', [ 'jquery' ], '1.0.0', true );
		
		// pass Ajax Url to mailbomb.pack.js
		wp_localize_script('script-mailbomb', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

		wp_enqueue_script( 'script-mailbombChart', plugin_dir_url( __FILE__ ).'assets/src/plugins/chart/Chart.min.js', [], '1.0.0', true );

		wp_enqueue_style( 'style-mailbombPrism', plugin_dir_url( __FILE__ ).'assets/src/plugins/prism/prism.css', 20 );
		wp_enqueue_script( 'script-mailbombPrism', plugin_dir_url( __FILE__ ).'assets/src/plugins/prism/prism.js', [], '1.0.0', true );
	}


	public static function mailbombCreateDb()
	{
		global $wpdb;

		$charset_collate 	= $wpdb->get_charset_collate();
		$table_name 		= $wpdb->prefix . 'mailbomb_users';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			token text NOT NULL,
			created_at varchar(255) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}