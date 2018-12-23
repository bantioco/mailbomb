<?php

class mailbombTemplater 
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

		//self::my_custom_template();

		//add_filter( 'single_template', [ 'mailbombTemplater', 'my_custom_template' ], 11 );
	}
	

	public static function my_custom_template( $single ) 
	{

		global $post;

		if ( $post->post_type == 'mailbomb_newsletter' ) 
		{
			if ( file_exists( PLUGIN_PATH . '/Custom_File.php' ) ) 
			{
				return PLUGIN_PATH . '/Custom_File.php';
			}
		}
		return $single;
	}
}