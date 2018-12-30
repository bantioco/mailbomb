<?php

class mailbombFront
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
        
        self::mailbombGetForm();
    }


    public static function mailbombGetForm()
    {
        if( isset( $_POST['_mailbomb_email'] ) && isset( $_POST['_mailbomb_register'] ) )
        {
            $email = filter_var( $_POST['_mailbomb_email'], FILTER_VALIDATE_EMAIL );

            global $wp;
            
            $url = home_url( $wp->request );
            
            if( $email )
            {
                global $wpdb;

                $check   =  $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}mailbomb_users WHERE email='$email'", OBJECT );

                if( !$check )
                {
                    $now        = new Datetime();
                    $dateNow    = $now->format('Y-m-d H:i:s');

                    $token      = bin2hex( random_bytes(128) );

                    $datas = [ 
                        'email' => $email,
                        'token' => $token,
                        'created_at' => $dateNow
                    ];

                    $table_name = $wpdb->prefix . 'mailbomb_users';

                    $wpdb->insert( $table_name, $datas, [ '%s', '%s', '%s' ] );

                    $body = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-register' );

                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    $send 	= wp_mail( $email, 'Mailbomb register newsletter', $body, $headers );

                    if( $send ) 
                    {
                        wp_redirect( $url.'/?mailbomb=success', 302 );
                        exit;
                    }
                    else 
                    {
                        wp_redirect( $url.'/?mailbomb=error', 302 );
                        exit;
                    }
                }
                else
                {
                    wp_redirect( $url.'/?mailbomb=exist', 302 );
                    exit;
                }
            }
            else
            {
                wp_redirect( $url.'/?mailbomb=invalid_email', 302 );
                exit;
            }
        }
    }
    
}