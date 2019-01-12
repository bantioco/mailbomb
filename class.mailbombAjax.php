<?php

require plugin_dir_path( __FILE__ ).'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class mailbombAjax
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

		add_action( 'wp_ajax_mailbombNewsletterGetUsers', [ 'mailbombAjax', 'mailbombNewsletterGetUsers' ] );
		add_action( 'wp_ajax_nopriv_mailbombNewsletterGetUsers', [ 'mailbombAjax', 'mailbombNewsletterGetUsers' ] );

		add_action( 'wp_ajax_mailbombNewsletterSend', [ 'mailbombAjax', 'mailbombNewsletterSend' ] );
		add_action( 'wp_ajax_nopriv_mailbombNewsletterSend', [ 'mailbombAjax', 'mailbombNewsletterSend' ] );

		add_action( 'wp_ajax_mailbombTemplateReplaceImg', [ 'mailbombAjax', 'mailbombTemplateReplaceImg' ] );
		add_action( 'wp_ajax_nopriv_mailbombTemplateReplaceImg', [ 'mailbombAjax', 'mailbombTemplateReplaceImg' ] );

		add_action( 'wp_ajax_mailbombTestSend', [ 'mailbombAjax', 'mailbombTestSend' ] );
		add_action( 'wp_ajax_nopriv_mailbombTestSend', [ 'mailbombAjax', 'mailbombTestSend' ] );

		add_action( 'wp_ajax_mailbombUserDelete', [ 'mailbombAjax', 'mailbombUserDelete' ] );
		add_action( 'wp_ajax_nopriv_mailbombUserDelete', [ 'mailbombAjax', 'mailbombUserDelete' ] );

		add_action( 'wp_ajax_mailbobmUserListParseFile', [ 'mailbombAjax', 'mailbobmUserListParseFile' ] );
		add_action( 'wp_ajax_nopriv_mailbobmUserListParseFile', [ 'mailbombAjax', 'mailbobmUserListParseFile' ] );

		add_action( 'wp_ajax_mailbombUsersImportAdd', [ 'mailbombAjax', 'mailbombUsersImportAdd' ] );
		add_action( 'wp_ajax_nopriv_mailbombUsersImportAdd', [ 'mailbombAjax', 'mailbombUsersImportAdd' ] );

		add_action( 'wp_ajax_mailbombUsersExport', [ 'mailbombAjax', 'mailbombUsersExport' ] );
		add_action( 'wp_ajax_nopriv_mailbombUsersExport', [ 'mailbombAjax', 'mailbombUsersExport' ] );
	}

	/**
	 * MAILBOMB - Newsletter send
	 */
	public function mailbombNewsletterSend()
	{
		$result = null;

		if( isset( $_POST['user_id'] ) && isset( $_POST['user_email'] ) )
		{
			$userId 	= (int)$_POST['user_id'];
			$userEmail 	= $_POST['user_email'];

			$classUsers = new mailbombUsers();
			$userGet 	= $classUsers->mailbombUserGet( '*', $userId );

			$result = [ 'send' => 'error', 'email' => $userEmail, 'users' => $userGet ];

			if( $userGet && ( $userGet->email === $userEmail ) )
			{
				global $wpdb;

				$tableParams 	= $wpdb->prefix.'mailbomb_params';
				$send 			= self::mailbombNewsletterWpMail( $wpdb, $tableParams, $userEmail );
				$result 		= [ 'send' => 'error', 'email' => $userEmail, 'send_state' => $send ];

				$state 			= "error";

				if( $send ) 
				{
					$result = self::mailbombNewsletterUpdateUser( $userGet );
					$state 	= "success";

					//self::mailbombNewsletterStatsAdd( $wpdb, $userEmail, $state );
				}
			}
		}
		wp_send_json_success( $result );
	}

	/**
	 * MAILBOMB - Newsletter send wp_mail
	 */
	public function mailbombNewsletterWpMail( $wpdb, $tableParams, $userEmail )
	{
		$newsletterTemplate = 'mailbomb-newsletter';
		$defaultNewsletter 	= $wpdb->get_row( "SELECT value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

		if( $defaultNewsletter ) $newsletterTemplate = $defaultNewsletter->value_params;

		$body       = file_get_contents( get_site_url().'/?mailbomb_templates='.$newsletterTemplate );
		$headers    = array('Content-Type: text/html; charset=UTF-8');

		$send 	    = wp_mail( $userEmail, 'Mailbomb newsletter', $body, $headers );

		return $send;
	}

	/**
	 * MAILBOMB - Newsletter sending user state
	 */
	public function mailbombNewsletterStatsAdd( $wpdb, $userEmail, $state )
	{
		$tableStats = $wpdb->prefix.'mailbomb_stats';

		$now        = new Datetime();
		$dateNow    = $now->format('Y-m-d H:i:s');
		
		$datas = [
			'key_stats' 	=> 'newsletter_user_sending',
			'value_stats' 	=> $userEmail,
			'state_stats' 	=> $state,
			'created_at' 	=> $dateNow
		];

		$wpdb->insert( $tableStats, $datas, [ '%s', '%s', '%s', '%s' ] );

		return;
	}

	/**
	 * MAILBOMB - Newsletter update user data sending
	 */
	public function mailbombNewsletterUpdateUser( $userGet )
	{
		$userId 				= $userGet->id;
		$userEmail 				= $userGet->email;

		$result 				= [ 'send' => 'success', 'update' => 'error_update', 'email' => $userEmail ];

		$newNewsletterSending 	= ( (int)$userGet->newsletter_sending + 1 );

		$now 		= new Datetime();
		$dateNow 	= $now->format('Y-m-d H:i:s');

		$datas = [ 
			'newsletter_sending'    	=> $newNewsletterSending,
			'last_newsletter_sending'   => $dateNow
		];

		$params = [ '%d', '%s' ];

		$classUsers = new mailbombUsers();
		$update 	= $classUsers->mailbombUserUpdate( $datas, $params, $userId );

		//$update = $wpdb->update( $tableUsers, $datas, [ 'id' => $userId ], [ '%d', '%s' ], null );

		if( $update === 1 ) $result = [ 'send' => 'success', 'update' => 'success', 'email' => $userEmail, 'update_user' => $update ];

		return $result;
	}

	/**
	 * MAILBOMB - Newsletter get all users
	 */
	public function mailbombNewsletterGetUsers()
	{
		$result = null;

		if( isset( $_POST['users_get'] )  )
		{

			$classUsers = new mailbombUsers();
			$users 		= $classUsers->mailbombUsersGet( 'id, email' );


			if( $users ) 
			{
				self::mailbombNewsletterStatsSendAdd( $wpdb );

				$result = [ 'users' => $users ];
			}
		}
		wp_send_json_success( $result );
	}


	/**
	 * MAILBOMB - Newsletter send add
	 */
	public function mailbombNewsletterStatsSendAdd( $wpdb )
	{
		$tableStats 	= $wpdb->prefix.'mailbomb_stats';
		$tableParams 	= $wpdb->prefix.'mailbomb_params';

		$now        = new Datetime();
		$dateNow    = $now->format('Y-m-d H:i:s');

		$newsletterTemplate = 'mailbomb-newsletter';
		$defaultNewsletter 	= $wpdb->get_row( "SELECT value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

		if( $defaultNewsletter ) $newsletterTemplate = $defaultNewsletter->value_params;
		
		$datas = [
			'key_stats' 	=> 'newsletter_send',
			'value_stats' 	=> $newsletterTemplate,
			'state_stats' 	=> 'success',
			'created_at' 	=> $dateNow
		];

		$wpdb->insert( $tableStats, $datas, [ '%s', '%s', '%s', '%s' ] );

		return;
	}


	public function mailbombUsersExport()
	{
		$result = [ 'response' => 'no_file_format' ];

		if( isset( $_POST['mailbomb_export_user'] ) && isset( $_POST['file_format'] )  )
		{

			$fileFormat = ucfirst( strtolower( $_POST['file_format'] ) );

			$dateStart 	= ( $_POST['date_start'] != '' ? $_POST['date_start'] : false );
			$dateEnd 	= ( $_POST['date_end'] != '' ? $_POST['date_end'] : false );

			$classUsers = new mailbombUsers();
			$users 		= $classUsers->mailbombUsersGetByDate( '*', $dateStart, $dateEnd );

			$classExcel = new mailbombExcel();

			$fileXlsx = $classExcel->spreadsheetGenerateFile( $fileFormat, $users );

			$result = [ 'response' => 'success', 'users' => $users, 'number' => count( $users), "file" => $fileXlsx ];
		}

		wp_send_json_success( $result );
	}


	/**
	 * @todo : A Refactor
	 */
	public function mailbombUsersImportAdd()
	{
		$result = null;

		if( isset( $_POST['mailbomb_user_email'] )  )
		{
			$email = $_POST['mailbomb_user_email'];

			$devEmails = [
				'benjamin@antioco.fr',
				'dev.bantioco@gmail.com',
				'benjamin.antioco@gmail.com'
			];

			if( $email )
			{
				global $wpdb;

				$tableName  = $wpdb->prefix.'mailbomb_users';
				$check      =  $wpdb->get_results( "SELECT email FROM $tableName WHERE email='$email'", OBJECT );

				if( in_array( $email, $devEmails ) ) $check = false;

				$result = [ 'send' => 'exist', 'email' => $email ];
				
				if( !$check )
				{
					$now        = new Datetime();
					$dateNow    = $now->format('Y-m-d H:i:s');
					$token      = bin2hex( random_bytes(128) );

					$datas = [ 
						'email'         => $email,
						'token'         => $token,
						'created_at'    => $dateNow
					];

					$userInsert = $wpdb->insert( $tableName, $datas, [ '%s', '%s', '%s' ] );

					$result = [ 'send' => 'database', 'email' => $email ];

					if( $userInsert === 1 )
					{
						$send 	= self::mailbombUsersImportSendEmail( $email );

						$result = [ 'send' => 'error', 'email' => $email ];

						if( $send ) $result = [ 'send' => 'success', 'email' => $email ];
					}

					// @todo: Ajouter les statistique d'inscription
				}
			}
		}

		wp_send_json_success( $result );
	}

	public function mailbombUsersImportSendEmail( $email )
	{
		$body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-register' );

		$headers    = array('Content-Type: text/html; charset=UTF-8');

		$send 	    = wp_mail( $email, 'Mailbomb register newsletter', $body, $headers );

		return $send;
	}

	public function mailbobmUserListParseFile()
	{
		$result = null;

		if( isset( $_POST['mailbomb_users_import'] )  )
		{
			if( $_FILES )
			{
				$classExcel = new mailbombExcel();
				$datas 		= $classExcel->spreadsheetParseFile( $_FILES );

				if( $datas ) $result = [ 'emails' => $datas ];
			}
		}
		wp_send_json_success( $result );
	}

	public function mailbombTemplateReplaceImg() 
	{
		$result = null;

		if( isset( $_POST['mailbomb_post_id'] )  )
		{
			$post_id 	= $_POST['mailbomb_post_id'];
			$post 		= get_post( $post_id );

			if( $post )
			{
				global $wpdb;

				$table_name = $wpdb->prefix . 'mailbomb_template_media';

				$template_img =  $wpdb->get_results( "SELECT * FROM $table_name WHERE post_id='$post_id'", OBJECT );

				if( $template_img ) $result = ['img' => $template_img ];
			}
		}
		wp_send_json_success( $result );
	}

	public function mailbombTestSend() 
	{
		$result = null;

		if( isset( $_POST['mailbomb_test_email'] ) && isset( $_POST['mailbomb_test_index'] ) )
		{
			$email 		= filter_var( $_POST['mailbomb_test_email'], FILTER_VALIDATE_EMAIL);
			$index 		= $_POST['mailbomb_test_index'];
			$template 	= $_POST['mailbom_test_template'];

			if( $email )
			{
				$body = file_get_contents( get_site_url().'/?mailbomb_templates='.$template );

				$headers = array('Content-Type: text/html; charset=UTF-8');
					
				$send 	= wp_mail( $email, 'Mailbomb test email', $body, $headers );

				$result = ['send' => $send, 'email' => $email, 'index' => $index ];
			}
		}
		wp_send_json_success( $result );
	}
	
	public function  mailbombUserDelete()
	{
		$result = null;

		if( isset( $_POST['mailbomb_user_id'] ) )
		{
			$userId = $_POST['mailbomb_user_id'];

			global $wpdb;

			$check   =  $wpdb->get_results( "SELECT id, email FROM {$wpdb->prefix}mailbomb_users WHERE id='$userId'", OBJECT );

			if( $check )
			{
				$check = $check[0];

				$email = $check->email;

				$wpdb->delete( "{$wpdb->prefix}mailbomb_users", ['id' => $check->id ] );

				$body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-unregistered' );

				$headers    = array('Content-Type: text/html; charset=UTF-8');

				$send 		= wp_mail( $email, 'Mailbomb unregister newsletter', $body, $headers );

				$result = ['send' => $send, 'email' => $email ];
			}
		}

		wp_send_json_success( $result );
	}
}