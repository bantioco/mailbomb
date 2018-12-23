<?php

class mailbombTemplate
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
        self::mailbombCtp();
        add_action('admin_menu', [ 'mailbombTemplate', 'mailbombTemplateAddSubMenu' ]);
    }

    
    public static function mailbombTemplateAddSubMenu()
    {
        add_submenu_page( 
            'mailbomb-setting', 
            'Template', 
            'Template',
            'manage_options', 
            'edit.php?post_type=mailbomb_newsletter',
            NULL
        );
    }

    public static function mailbombCtp()
    {
        $labels = [
            'name'                => _x( 'Mailbomb Newsletter', 'Post Type General Name'),
            'singular_name'       => _x( 'Mailbomb Newsletter', 'Post Type Singular Name'),
            'menu_name'           => __( 'Mailbomb Newsletter'),
            'all_items'           => __( 'Toutes les Newsletter'),
            'view_item'           => __( 'Voir les Newsletter'),
            'add_new_item'        => __( 'Ajouter une nouvelle newsletter'),
            'add_new'             => __( 'Ajouter'),
            'edit_item'           => __( 'Editer la Newsletter'),
            'update_item'         => __( 'Modifier la Newsletter'),
            'search_items'        => __( 'Rechercher une newsletter'),
            'not_found'           => __( 'Non trouvée'),
            'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
        ];

        $args = [
            'label'               => __( 'Newsletter'),
            'description'         => __( 'Tous sur Newsletter'),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            'hierarchical'        => false,
            'public'              => true,
            'has_archive'         => true,
            'show_in_menu'        => false,
            //'show_in_rest'        => true,//GUTTENBERG ACTIVE
            'rewrite'			  => [ 'slug' => 'mailbomb-newsletter' ],

        ];
        register_post_type( 'mailbomb_newsletter', $args );
    }

}