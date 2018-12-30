<?php

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

		add_action( 'wp_ajax_mailbombTemplateReplaceImg', [ 'mailbombAjax', 'mailbombTemplateReplaceImg' ] );
		add_action( 'wp_ajax_nopriv_mailbombTemplateReplaceImg', [ 'mailbombAjax', 'mailbombTemplateReplaceImg' ] );

		add_action( 'wp_ajax_mailbombTestSend', [ 'mailbombAjax', 'mailbombTestSend' ] );
		add_action( 'wp_ajax_nopriv_mailbombTestSend', [ 'mailbombAjax', 'mailbombTestSend' ] );
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
			$email = filter_var( $_POST['mailbomb_test_email'], FILTER_VALIDATE_EMAIL);
			$index = $_POST['mailbomb_test_index'];

			if( $email )
			{
				$body = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-test' );

				$headers = array('Content-Type: text/html; charset=UTF-8');
					
				$send 	= wp_mail( $email, 'Mailbomb test email', $body, $headers );

				$result = ['send' => $send, 'email' => $email, 'index' => $index ];
			}
		}
		wp_send_json_success( $result );
    }
}