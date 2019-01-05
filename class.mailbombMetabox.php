<?php

class mailbombMetabox
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

        add_action( 'add_meta_boxes', [ 'mailbombMetabox', 'mailbomb_add_template_box' ], 10, 2 );

        add_action( 'add_meta_boxes', [ 'mailbombMetabox', 'mailbomb_add_template_view_box' ], 10, 2 );

        //add_action( 'add_meta_boxes', [ 'mailbombMetabox', 'mailbomb_add_template_upload_box' ], 10, 2 );
    }

    public static function mailbomb_add_template_view_box( $post_type, $post )
    {
        add_meta_box( 
            'mailbomb_template_view_box',
            'Mailbomb - Template view', 
            [ 'mailbombMetabox', 'mailbomb_add_template_view_box_html' ],
            'mailbomb_templates',
            'normal',
            'high'
        );   
    }

    public static function mailbomb_add_template_view_box_html()
    {
        self::mailbomb_remove_metabox();
        
        global $post;

        $view       = file_get_contents( home_url().'/?mailbomb_templates='.$post->post_name );
        $loader     = '<div style="display:none;" id="mailbomb_template_loader" class="mailbomb_template_loader">Chargement...</div>';

        if( $view ) echo $view.$loader;
    }

    /*
    public static function mailbomb_add_template_upload_box( $post_type, $post ){

        add_meta_box( 
            'mailbomb_template_upload_box',
            'Mailbomb - Template upload', 
            [ 'mailbombMetabox', 'mailbomb_add_template_upload_box_html' ],
            'mailbomb_templates',
            'side',
            'default'
        );   
    }

    public static function mailbomb_add_template_upload_box_html()
    {        
        global $post;

        $html = '<form method="POST">';
            $html .= '<br>';
            $html .= '<label class="mailbomb_metabox_upload_template" for="mailbomb_template_upload">Upload template</label>';
            $html .= '<input style="display:none;" type="file" id="mailbomb_template_upload" name="mailbomb_template_upload">';
            $html .= '<br>';
            $html .= '<button class="mailbomb-btn">Valider</button>';
        $html .= '</form>';

        echo $html;
    }
    */

    public static function mailbomb_add_template_box( $post_type, $post ){

        add_meta_box( 
            'mailbomb_template_box',
            'Mailbomb template', 
            [ 'mailbombMetabox', 'mailbomb_add_template_box_html' ],
            'mailbomb_templates',
            'side',
            'high'
        );   
    }

    public static function mailbomb_add_template_box_html()
    {

        self::mailbomb_remove_metabox();

        global $post;

        global $wpdb;

        $template_id = null;

        $table_name     = $wpdb->prefix.'mailbomb_templates';
        $template       =  $wpdb->get_row( "SELECT id FROM $table_name WHERE template_name='$post->post_name'", OBJECT );

        if( $template ) $template_id = $template->id;

        $field          = '<input type="hidden" name="mailbomb_content_template_field" id="mailbomb_content_template_field">';
        $template_id    = '<input type="hidden" name="mailbomb_content_template_id" id="mailbomb_content_template_id" value="'.$template_id.'">';

        $view = $field.$template_id;

        echo $view;
    }

    public static function mailbomb_remove_metabox()
    {

        //remove_meta_box( 'submitdiv', 'mailbomb_templates', 'side' );

        remove_meta_box( 'postimagediv', 'mailbomb_templates', 'side' );
    }
}