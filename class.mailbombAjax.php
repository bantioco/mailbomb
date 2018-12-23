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

		add_action( 'wp_ajax_mailbomb_test_send', [ 'mailbombAjax', 'mailbomb_test_send' ] );
		add_action( 'wp_ajax_nopriv_mailbomb_test_send', [ 'mailbombAjax', 'mailbomb_test_send' ] );
	}


	public function mailbomb_test_send() 
	{
		$result = null;

		if( isset( $_POST['email'] ) && isset( $_POST['index'] ) )
		{
			$email = filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL);
			$index = $_POST['index'];

			if( $email )
			{
				$send 	= wp_mail( $email, 'Mailbomb test email', 'Mailbomb test email' );

				$result = ['send' => $send, 'email' => $email, 'index' => $index ];
			}
		}
		wp_send_json_success( $result );
    }
}