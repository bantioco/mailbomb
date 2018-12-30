<?php

class mailbombTemplate
{
    private static $initiated = false;
	
	public static function init() 
    {
        if ( ! self::$initiated ) self::initHooks();
    }

    /**
	 * Initializes WordPress hooks
	 */
    private static function initHooks() 
    {
        self::$initiated = true;

        self::mailbombTemplateCtp();

        self::mailbombCreateDefaultPost();

        add_action('admin_menu', [ 'mailbombTemplate', 'mailbombTemplateAddSubMenu' ]);

        add_filter( 'template_include', [ 'mailbombTemplate', 'mailbombForceTemplate' ] );

        add_action( 'before_delete_post', [ 'mailbombTemplate', 'mailbombAdminTemplateDelete' ] );

        add_action( 'admin_notices', [ 'mailbombTemplate', 'mailbombAdminTemplateCreateHtml' ] );

        add_action( 'save_post', [ 'mailbombTemplate', 'mailbombTemplatePostUpdate' ], 11 );
    }

    public static function mailbombTemplatePostUpdate( $post_id ) 
    {
        if ( wp_is_post_revision( $post_id ) ) return;

        if ( isset( $_POST['mailbomb_content_template_field'] ) && isset( $_POST['mailbomb_content_template_id'] ) ) 
        {    
            global $wpdb;

            $templateValue  = $_POST['mailbomb_content_template_field'];
            $templateId     = $_POST['mailbomb_content_template_id'];

            if( $templateValue != "" && $templateId )
            {
                $data = [
                    'template_value' => $templateValue
                ];

                $tableName = $wpdb->prefix . 'mailbomb_templates';
        
                if( $wpdb->update( $tableName, $data, [ 'id' => $templateId ], [ '%s' ], null ) )
                {
        
                    $postContentData = array(
                        'ID'           => $post_id,
                        'post_content' => $templateValue,
                    );

                    wp_update_post( $postContentData );
                }
            }
        }
        return;
    }
    
    public static function mailbombTemplateAddSubMenu()
    {
        add_submenu_page( 
            'mailbomb-setting', 
            'Template', 
            'Template',
            'manage_options', 
            'edit.php?post_type=mailbomb_templates',
            NULL
        );
    }

    /**
     * MAILBOMB - create custom post type mailbomb_template
     */
    public static function mailbombTemplateCtp()
    {
        $labels = [
            'name'                => _x( 'Mailbomb Template', 'Post Type General Name'),
            'singular_name'       => _x( 'Mailbomb Template', 'Post Type Singular Name'),
            'menu_name'           => __( 'Mailbomb Template'),
            'all_items'           => __( 'Toutes les Template'),
            'view_item'           => __( 'Voir les Templates'),
            'add_new_item'        => __( 'Ajouter un nouveau Template'),
            'add_new'             => __( 'Ajouter'),
            'edit_item'           => __( 'Editer le Template'),
            'update_item'         => __( 'Modifier le Template'),
            'search_items'        => __( 'Rechercher un Template'),
            'not_found'           => __( 'Non trouvée'),
            'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
        ];

        $args = [
            'label'               => __( 'Template'),
            'description'         => __( 'Tous sur Template'),
            'labels'              => $labels,
            'supports'            => array( 'thumbnail', 'title' ),
            'hierarchical'        => false,
            'public'              => true,
            'has_archive'         => true,
            'show_in_menu'        => false,
            'rewrite'			  => [ 'slug' => 'mailbomb-template-email' ],

        ];
        register_post_type( 'mailbomb_templates', $args );
    }

    /**
     * MAILBOMB - Create default post
     */
    public static function mailbombCreateDefaultPost()
    {         
        global $wpdb;

        $templates   =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_templates", OBJECT );

        if( $templates )
        {
            foreach( $templates as $template )
            {
                $post_id    = -1;
                $author_id  = 1;
                $slug       = $template->template_name;
                $title      = ucfirst( str_replace( '-', ' ', $slug ) );

                $post = get_page_by_title( $title, OBJECT, 'mailbomb_templates' );

                if( !$post )
                {
                    if( null == get_page_by_title( $title ) ) 
                    {  
                        $post_id = wp_insert_post(
                            [
                                'comment_status'	=>	'closed',
                                'ping_status'		=>	'closed',
                                'post_author'		=>	$author_id,
                                'post_name'			=>	$slug,
                                'post_title'		=>	$title,
                                'post_status'		=>	'publish',
                                'post_type'			=>	'mailbomb_templates',
                                'post_content'      => $template->template_value
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * TEMPLATE DELETE FUNCTION
     */
    public static function mailbombAdminTemplateDelete( $postid )
    {
        global $post_type;   

        if ( $post_type != 'mailbomb_templates' ) return;

        $post           = get_post( $postid );
        $templateName   = str_replace( '__trashed', '', $post->post_name );

        self::mailbombAdminDefaultTemplateUpdate( $templateName );

        self::mailbombTemplateDeleteDb( $templateName );

        self::mailbombTemplateMediaDelete( $templateName );
 
        return;
    }

    /**
     * MEDIA DELETE
     */
    public static function mailbombTemplateMediaDelete( $templateName )
    {
        global $wpdb;

        $tableName      = $wpdb->prefix . 'mailbomb_template_media';
        $mailbombMedia  = $wpdb->get_results( "SELECT * FROM $tableName WHERE post_name='$templateName'", OBJECT );

        if( $mailbombMedia )
        {
            foreach( $mailbombMedia as $media )
            {
                $mediaDir   = $media->media_dir;
                $homeUrl    = home_url();
                $mediaPath  = str_replace( $homeUrl.'/', '', $mediaDir );
                $filepath   = get_home_path().$mediaPath;

                if( file_exists ( $filepath ) ) unlink( $filepath );
                
                $wpdb->delete( "$tableName", ['id' => $media->id ] );

                wp_delete_attachment( $media->media_id, true );
            }
        }
        return;
    }

    /**
     * TEMPLATE DELETE
     */
    public static function mailbombTemplateDeleteDb( $templateName )
    {
        global $wpdb;

        $tableName         = $wpdb->prefix . 'mailbomb_templates';
        $mailbombTemplate   =  $wpdb->get_row( "SELECT id FROM $tableName WHERE template_name='$templateName'", OBJECT );

        if( $mailbombTemplate ) $wpdb->delete( "$tableName", ['id' => $mailbombTemplate->id ] );

        return;
    }

    /**
     * DEFAULT TEMPLATE PARAMS UPDATE
     */
    public static function mailbombAdminDefaultTemplateUpdate( $templateName )
    {   
        global $wpdb;

        // GET IF DEFAULT TEMPLATE NEED UPDATE
        $defaultTemplateGet  =  $wpdb->get_row( "SELECT id FROM {$wpdb->prefix}mailbomb_params WHERE value_params='$templateName'", OBJECT );

        if( $defaultTemplateGet )
        {
            $now                = new Datetime();
            $dateNow            = $now->format('Y-m-d H:i:s');
            $defaultTemplate    = "mailbomb-newsletter";
            $paramId           = $defaultTemplateGet->id;

            $datas = [ 
                'key_params'    => 'default_template_newsletter',
                'value_params'  => $defaultTemplate,
                'created_at'    => $dateNow
            ];

            $tableName = $wpdb->prefix . 'mailbomb_params';

            $wpdb->update( $tableName, $datas, [ 'id' => $paramId ], [ '%s', '%s', '%s' ], null );
        }

        return;
    }
    
    /**
     * MAILBOMB - Force post template
     */
    public static function mailbombForceTemplate( $template )
    {	
        global $post;
        
        if( is_singular( 'mailbomb_templates' ) ) 
        {
            global $wpdb;

            $mailbombTemplates =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_templates", OBJECT );

            if( $mailbombTemplates )
            {
                foreach( $mailbombTemplates as $mailbombTemplate )
                {
                    if( $post->post_name === $mailbombTemplate->template_name )
                    {
                        $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/templates/single-mailbomb-template.php';

                        return $template;
                    }
                }
            }
        }
        return $template;
    }


    public static function mailbombAdminTemplateCreateHtml() 
    {
        self::mailbombAdminTemplateCreateHtmlSave();

        $screen = get_current_screen();

        if( $screen &&  $screen->post_type )
        {
            if( $screen->id === "edit-mailbomb_templates" )
            {
                global $post;

                if( $post && $post->post_type === 'mailbomb_templates' )
                {
                    require_once('html/form_add_template.php');
                }
            }
        }
    }

    public static function mailbombAdminTemplateCreateHtmlSave()
    {
        if( isset( $_POST['mailbomb_add_name_template'] ) && isset( $_POST['mailbomb_add_html_template'] ) )
        {
            global $wpdb;

            $template_name      = strtolower( filter_var( $_POST['mailbomb_add_name_template'], FILTER_SANITIZE_STRING ) );
            $template_slug      = str_replace(' ', '-', $template_name );
            $template_content   = $_POST['mailbomb_add_html_template'];
            $table_name         = $wpdb->prefix . 'mailbomb_templates';
            $check              = $wpdb->get_row( "SELECT * FROM $table_name WHERE template_name='$template_name'", OBJECT );
            $now  		        = new Datetime();

			if( !$check )
			{
                $dateNow    = $now->format('Y-m-d H:i:s');

				$dataValues = [ 
					'template_name' => $template_slug,
					'template_value' => $template_content,
					'is_active' => '1',
					'created_at' => $dateNow
				];

                $wpdb->insert( $table_name, $dataValues, [ '%s', '%s', '%s', '%s' ] );
                
                $post_id    = -1;
                $author_id  = 1;
                $slug       = $template_slug;
                $title      = ucfirst( $template_name );

                $post = get_page_by_title( $title, OBJECT, 'mailbomb_templates' );

                if( !$post )
                {
                    if( null == get_page_by_title( $title ) ) 
                    {
                        $post_id = wp_insert_post(
                            [
                                'comment_status'	=>	'closed',
                                'ping_status'		=>	'closed',
                                'post_author'		=>	$author_id,
                                'post_name'			=>	$slug,
                                'post_title'		=>	$title,
                                'post_status'		=>	'publish',
                                'post_type'			=>	'mailbomb_templates',
                                'post_content'      => $template_content
                            ]
                        );

                        if( $_FILES )
                        {
                            $dirYear    = $now->format('Y');
                            $dirMonth   = $now->format('m');
                            $basedir    = get_home_path().'wp-content/uploads/';

                            if( !is_dir( $basedir.$dirYear )) mkdir( $basedir.$dirYear.'/' );

                            if( !is_dir( $basedir.$dirYear.'/'.$dirMonth )) mkdir( $basedir.$dirYear.'/'.$dirMonth.'/' );

                            $uploaddir  = $basedir.$dirYear.'/'.$dirMonth.'/';
                            $uploadfile = $uploaddir . basename( $_FILES['mailbomb_add_img_template']['name'] );

                            if( move_uploaded_file( $_FILES['mailbomb_add_img_template']['tmp_name'], $uploadfile ) ) 
                            {
                                $zip = new ZipArchive;

                                if ( $zip->open( $uploadfile ) === TRUE) 
                                {

                                    for( $i=0; $i < $zip->numFiles; $i++ ) 
                                    {
                                        $name = $zip->statIndex($i)['name'];

                                        if ( strpos( $name, '__MACOSX' ) === false ) $zip->extractTo( $uploaddir, $name );
                                    }

                                    $zip->close();

                                    unlink( $uploadfile );

                                    $scans = scandir( $uploaddir );

                                    if( $scans )
                                    {
                                        foreach( $scans as $item )
                                        {
                                            if( $item != '.' && $item != '..' && $item != '.DS_Store' )
                                            {
                                                if( is_dir( $uploaddir.$item ) )
                                                {
                                                    $scandir = scandir( $uploaddir.$item );

                                                    foreach( $scandir as $key => $file )
                                                    {
                                                        if( $file != '.' && $file != '..' && !is_dir( $file ) )
                                                        {
                                                            $fileinfo = pathinfo( $uploaddir.$file );

                                                            $uploadfilename           = $fileinfo['filename'];

                                                            $original_filename  = $file;
                                                            $rename_file        = $key.'_'.$now->format('YmdHis').'.'.$fileinfo['extension'];

                                                            if ( copy( $uploaddir.$item.'/'.$original_filename, $uploaddir.$rename_file ) ) 
                                                            {
                                                                $oldfiles[] = $uploaddir.$item.'/'.$original_filename;
                                                            }

                                                            self::mailbombAddWpMediaTemplate( $post_id, $uploaddir, $dirYear, $dirMonth, $uploadfilename, $original_filename, $rename_file );
                                                        }
                                                    }

                                                    if( $oldfiles ) foreach ( $oldfiles as $oldfile ) unlink( $oldfile );
                                                    
                                                    rmdir( $uploaddir.$item );

                                                    //unlink( $uploaddir.'.DS_Store'  );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
			}
        }
    }

    public static function mailbombAddWpMediaTemplate( $postid, $uploaddir, $dirYear, $dirMonth, $uploadfilename, $original_filename, $rename_file )
    {
        $filesize = getimagesize( $uploaddir.$rename_file );
        $fileinfo = pathinfo( $uploaddir.$rename_file );

        if( $filesize && $fileinfo )
        {
            $fileMime       = $filesize['mime'];
            $filename       = $fileinfo['filename'];
            $filenameExt    = $fileinfo['basename'];
            $fileid        = -1;
            $author_id      = 1;

            $post = get_page_by_title( $filename, OBJECT, 'attachment' );
            //$guid = home_url().'/wp-content/uploads/'.$dirYear.'/'.$dirMonth.'/'.$filenameExt;

            $guid = home_url().'/wp-content/uploads/'.$dirYear.'/'.$dirMonth.'/'.$filenameExt;

            if( !$post )
            {
                if( null == get_page_by_title( $filename ) ) 
                {
                    $fileid = wp_insert_post(
                        [
                            'comment_status'	=> 'closed',
                            'ping_status'		=> 'closed',
                            'post_author'		=> $author_id,
                            'post_name'			=> $uploadfilename,
                            'post_title'		=> $uploadfilename,
                            'post_status'		=> 'publish',
                            'post_type'			=> 'attachment',
                            'post_mime_type'    => $fileMime,
                            'guid'              => $guid
                        ]
                    );

                    $metaValue = $dirYear.'/'.$dirMonth.'/'.$filenameExt;
                    add_post_meta( $fileid, '_wp_attached_file', $metaValue, true );

                    $attachData = wp_generate_attachment_metadata( $fileid, $guid );
                    wp_update_attachment_metadata( $fileid,  $attachData );

                    global $wpdb;

                    $post       = get_post( $postid );
                    $now        = new Datetime();
		            $dateNow    = $now->format('Y-m-d H:i:s');

                    $table_name = $wpdb->prefix . 'mailbomb_template_media';

                    $mediaDatas = [ 
                        'media_id' 	        => $fileid,
                        'media_name' 	    => $uploadfilename,
                        'media_filename'    => $original_filename,
                        'media_dir' 		=> $guid,
                        'post_id'           => $post->ID,
                        'post_name'         => $post->post_name,
                        'created_at' 		=> $dateNow
                    ];
    
                    $wpdb->insert( $table_name, $mediaDatas, [ '%s', '%s', '%s', '%s', '%s', '%s', '%s' ] );

                    add_filter( 'template_include', [ 'mailbombTemplate', 'mailbombForceTemplate' ] );
                    
                    $url    = home_url().'/wp-admin/post.php?post='.$post->ID.'&action=edit';
                    $script = '<script type="text/javascript">window.location = "' . $url . '"</script>';

                    echo $script;
            
                }
            }
        }
    }
}