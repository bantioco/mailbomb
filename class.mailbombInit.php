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

		wp_enqueue_style( 'style-mailbombCodemirror', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/lib/codemirror.css', 20 );
		wp_enqueue_script( 'script-mailbombCodemirror', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/lib/codemirror.js', [], '1.0.0', true );
		wp_enqueue_script( 'script-mailbombCodemirrorXml', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/mode/xml/xml.js', [], '1.0.0', true );
		wp_enqueue_script( 'script-mailbombCodemirrorCss', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/mode/css/css.js', [], '1.0.0', true );
		wp_enqueue_script( 'script-mailbombCodemirrorJs', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/mode/javascript/javascript.js', [], '1.0.0', true );
		wp_enqueue_script( 'script-mailbombCodemirrorHtml', plugin_dir_url( __FILE__ ).'assets/src/plugins/codemirror/mode/htmlmixed/htmlmixed.js', [], '1.0.0', true );
	}


	public static function mailbombCreateDb()
	{
		global $wpdb;

		$charset_collate 	= $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		/**
		 * MAILBOMB USERS
		 */
		$table_name = $wpdb->prefix . 'mailbomb_users';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			token text NOT NULL,
			newsletter_sending int(11) NULL default '0',
			created_at varchar(255) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		dbDelta( $sql );

		/**
		 *  MAILBOMB PARAMS
		 */
		$table_name = $wpdb->prefix . 'mailbomb_params';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			key_params varchar(255) NOT NULL,
			value_params text NOT NULL,
			created_at varchar(255) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		dbDelta( $sql );

		self::mailbombAddParams();

		/**
		 *  MAILBOMB PARAMS
		 */
		$table_name = $wpdb->prefix . 'mailbomb_templates';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			template_name varchar(255) NOT NULL,
			template_value text NOT NULL,
			is_active varchar(255) DEFAULT '0' NOT NULL,
			created_at varchar(255) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		dbDelta( $sql );

		self::mailbombAddTemplates();


		/**
		 *  MAILBOMB MEDIA
		 */
		$table_name = $wpdb->prefix . 'mailbomb_template_media';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			media_id varchar(255) NOT NULL,
			media_name varchar(255) NOT NULL,
			media_filename varchar(255) NOT NULL,
			media_dir text NOT NULL,
			post_id varchar(255) NOT NULL,
			post_name varchar(255) NOT NULL,
			created_at varchar(255) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		dbDelta( $sql );
	}

	public static function mailbombAddParams()
	{
		global $wpdb;

		$now        = new Datetime();
		$dateNow    = $now->format('Y-m-d H:i:s');
		
		$table_name = $wpdb->prefix . 'mailbomb_params';

		$users_list_items_per_page   =  $wpdb->get_row( "SELECT value_params FROM {$wpdb->prefix}mailbomb_params WHERE key_params='users_list_items_per_page'", OBJECT );

		if( !$users_list_items_per_page )
		{
			$datas = [ 
				'key_params' => 'users_list_items_per_page',
				'value_params' => '5',
				'created_at' => $dateNow
			];

			$wpdb->insert( $table_name, $datas, [ '%s', '%s', '%s' ] );
		}


		$default_template_newsletter   =  $wpdb->get_row( "SELECT value_params FROM {$wpdb->prefix}mailbomb_params WHERE key_params='default_template_newsletter'", OBJECT );

		if( !$default_template_newsletter )
		{
			$datas = [ 
				'key_params' => 'default_template_newsletter',
				'value_params' => 'mailbomb-newsletter',
				'created_at' => $dateNow
			];

			$wpdb->insert( $table_name, $datas, [ '%s', '%s', '%s' ] );
		}
	}


	public static function mailbombAddTemplates()
	{
		$content = "";

		global $wpdb;

		$now  		= new Datetime();		
		$table_name = $wpdb->prefix . 'mailbomb_templates';

		$templates = [
			0 => [
				'name' 		=> 'mailbomb-test',
				'content' 	=> 'Ceci est un email de test - Mailbomb'
			],
			1 => [
				'name' 		=> 'mailbomb-newsletter',
				'content' 	=> 'Voici la newsletter - Mailbomb'
			],
			2 => [
				'name' 		=> 'mailbomb-register',
				'content' 	=> 'Vous êtes désormais inscrit à la newsletter - Mailbomb'
			],
			3 => [
				'name' 		=> 'mailbomb-unregistered',
				'content' 	=> 'Vous êtes désormais désinscrit de la newsletter - Mailbomb'
			]
		];

		foreach( $templates as $data )
		{
			$template_name 		= $data['name'];
			$template_content 	= $data['content'];

			$check   =  $wpdb->get_row( "SELECT * FROM $table_name WHERE template_name='$template_name'", OBJECT );

			if( !$check )
			{
				$dateNow    = $now->format('Y-m-d H:i:s');

				ob_start();

				require( 'templates/single-mailbomb-model-template.php' );

				$content = ob_get_contents();

				ob_end_clean();

				$dataValues = [ 
					'template_name' 	=> $template_name,
					'template_value' 	=> $content,
					'is_active' 		=> '1',
					'created_at' 		=> $dateNow
				];

				$wpdb->insert( $table_name, $dataValues, [ '%s', '%s', '%s', '%s' ] );
			}

			$template_name 		= null;
			$template_content 	= null;
		}
	}
}