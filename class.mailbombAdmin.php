<?php

class mailbombAdmin
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

        add_action( 'admin_menu', [ 'mailbombAdmin', 'mailbomb_add_menu_page'] );

        add_action( 'show_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'edit_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'profile_update', [ 'mailbombAdmin', 'mailbomb_profile_fields_save' ], 10, 2 );

        add_filter( 'user_contactmethods', [ 'mailbombAdmin', 'mailbomb_table_column' ], 10, 1 );

        add_filter( 'manage_users_columns', [ 'mailbombAdmin', 'mailbomb_modify_user_table' ] );

        add_filter( 'manage_users_custom_column', [ 'mailbombAdmin', 'mailbomb_modify_user_table_row' ], 10, 3 );

        add_filter( 'admin_body_class', [ 'mailbombAdmin', 'mailbobm_table_classes' ], 10, 3 );
    }    

    public static function mailbobm_table_classes( $classes ) 
    {
        $screen = get_current_screen();

        if( $screen &&  $screen->post_type )
        {
            $classes = $screen->post_type.'_class_edit';

            if( $screen->id === "edit-mailbomb_templates" )
            {
                $classes .= ' '.$screen->post_type.'_class_table';
            }

            if( $screen->id === "mailbomb_templates" )
            {
                global $post;

                if( $post && $post->post_name )
                {
                    $classes .= ' '.$post->post_name.'_class';
                }
            }
        }
        return $classes;
    }

    public static function mailbomb_table_column( $mailbombNewsletter ) 
    {
        $mailbombNewsletter['mailbomb_templates'] = 'Mailbomb newsletter';
        return $mailbombNewsletter;
    }
    
    public static function mailbomb_modify_user_table( $column ) 
    {
        $column['mailbomb_templates'] = 'Mailbomb newsletter';
        return $column;
    }
    
    public static function mailbomb_modify_user_table_row( $val, $column_name, $user_id ) 
    {
        global $wpdb;
        $user = get_user_by( 'id', $user_id );

        switch ($column_name) {
            case 'mailbomb_templates' :
                $check   =  $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}mailbomb_users WHERE email='$user->user_email'", OBJECT );
                $val = "-";
                if( $check ) $val = "OUI";
                return $val;
                break;
            default:
        }
        return $val;
    }
    
    public static function mailbomb_add_menu_page()
    {
        add_menu_page( 
            'mailbomb', 
            'Mailbomb', 
            'manage_options', 
            'mailbomb-setting', 
            [ 'mailbombAdmin', 'mailbomb_add_menu_page_html' ], 
            'dashicons-email-alt', 
            61
        );
    }

    /**
     * mailbomb - Admin setting html
     */
    public static function mailbomb_add_menu_page_html()
    {
        self::mailbomb_admin_html_save();

        global $wpdb;

        $userItemsPerPage   =  $wpdb->get_row( "SELECT id, value_params FROM {$wpdb->prefix}mailbomb_params WHERE key_params='users_list_items_per_page'", OBJECT );

        $numberItems    = 5;
        $limit 		    = 5;
        $itemsPerPageId = 0;
        $offset 	    = 0;
        $page 		    = 1;
        $numberPages    = 1;
        

        if( $userItemsPerPage ) 
        {
            $numberItems    = (int)$userItemsPerPage->value_params;
            $limit 		    = (int)$userItemsPerPage->value_params;
            $itemsPerPageId = $userItemsPerPage->id;
        }

        if( isset( $_GET['users-list'] ) && isset( $_GET['number-page'] ) )
        {
            $page = (int)$_GET['number-page'];

            if( $page > 1 ) $offset = ( $page * $numberItems ) - $numberItems;
        }

        $userListsAll   =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_users", OBJECT );
        $totalUsers     = count( $userListsAll );

        if( round( $totalUsers / $numberItems ) > 1 ) $numberPages = round( $totalUsers / $numberItems );

        $userLists =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_users ORDER BY id DESC LIMIT $offset,$limit;", OBJECT );

        /**
         * TEMPLATE NEWSLETTER PARAMS
         */
        $defaultTemplateGet  =  $wpdb->get_row( "SELECT id, value_params FROM {$wpdb->prefix}mailbomb_params WHERE key_params='default_template_newsletter'", OBJECT );

        $defaultTemplate            = "mailbomb-newsletter";
        $defaultTemplateParamsId    = "";

        if( $defaultTemplateGet ) 
        {
            $defaultTemplate            = $defaultTemplateGet->value_params;
            $defaultTemplateParamsId    = $defaultTemplateGet->id;
        }

        $mailbombTemplates  =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_templates", OBJECT );

        require_once('html/setting.php');
    }

    /**
     * mailbomb - Admin setting save
     * @todo: refactor function
     */
    public static function mailbomb_admin_html_save()
    {
        /**
         * USERS LIST ITEMS PER PAGE
         */
        if( isset( $_POST['users_list_items_per_page'] ) && isset( $_POST['users_list_items_per_page_id'] ) )
        {
            $number = $_POST['users_list_items_per_page'];
            $id     = $_POST['users_list_items_per_page_id'];

            self::users_list_items_per_page_update( $number, $id );
        }

        /**
         * TEMPLATE NEWSLETTER UPDATE
         */
        if( isset( $_POST['mailbomb_template_default'] ) && isset( $_POST['mailbomb_template_default_id'] ) )
        {
            $template_choice    = $_POST['mailbomb_template_default'];
            $id                 = $_POST['mailbomb_template_default_id'];

            self::mailbombTemplateDefaultUpdate( $template_choice, $id );
        }


        //@todo : a faire
        if( isset( $_POST['mailbomb_users_list_delete'] ) && isset( $_POST['user_list_selected_delete'] ) )
        {
            $usersId = $_POST['user_list_selected_delete'];

            foreach( $usersId as $userId )
            {
                $userId = (int)$userId;
            }
        }
    }

    public static function mailbombTemplateDefaultUpdate( $template_choice, $id )
    {

        if( $template_choice != "" && $template_choice != null )
        {
            $now        = new Datetime();
            $dateNow    = $now->format('Y-m-d H:i:s');

            $datas = [ 
                'key_params'    => 'default_template_newsletter',
                'value_params'  => $template_choice,
                'created_at'    => $dateNow
            ];

            global $wpdb;

            $table_name = $wpdb->prefix . 'mailbomb_params';

            $wpdb->update( $table_name, $datas, [ 'id' => $id ], [ '%s', '%s', '%s' ], null );
        }
    }

    /**
     *  UPDATE USERS LIST ITEMS PER PAGE
     */
    public static function users_list_items_per_page_update( $number, $id )
    {
        if( ( (int)$number >= 1 ) && ( (int)$number <= 100 ) )
        {
            $now        = new Datetime();
            $dateNow    = $now->format('Y-m-d H:i:s');

            $datas = [ 
                'key_params'    => 'users_list_items_per_page',
                'value_params'  => $number,
                'created_at'    => $dateNow
            ];

            global $wpdb;

            $table_name = $wpdb->prefix . 'mailbomb_params';

            $wpdb->update( $table_name, $datas, [ 'id' => $id ], [ '%s', '%s', '%s' ], null );
        }
    }

    
    public static function mailbomb_profile_fields( $user ) 
    {
        global $wpdb;

        $check   =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_users WHERE email='$user->user_email'", OBJECT );
        $checked = "";

        if( $check ) $checked = 'checked="checked"';

        ?>
            <h2>Mailbomb</h2>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Ajouter à la newsletter</th>
                        <td>
                            
                            <fieldset><legend class="screen-reader-text"><span>Barre d’outils</span></legend>
                                <label for="_mailbomb_profile_user_newsletter">
                                    <input type="checkbox" <?php echo $checked;?> id="_mailbomb_profile_user_newsletter" name="_mailbomb_profile_user_newsletter">
                                    Cocher pour activer
                                </label>
                                <br>
                            </fieldset>
                            <input type="hidden" name="_mailbomb_profile_user_hidden" value="1">
                        </td>
                    </tr>
                </tbody>
            </table>

        <?php
    }


    public static function mailbomb_profile_fields_save( $user_id, $old_user_data ) 
    {
        if( isset( $_POST['_mailbomb_profile_user_hidden'] ) )
        {
            global $wpdb;

            $email      = $_POST['email'];
            $table_name = $wpdb->prefix . 'mailbomb_users';

            if( isset( $_POST['_mailbomb_profile_user_newsletter'] ) )
            {
                $value = $_POST['_mailbomb_profile_user_newsletter'];

                if( $value === "on" )
                {
                    if( $email )
                    {
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

                            $wpdb->insert( $table_name, $datas, [ '%s', '%s', '%s' ] );

                            $body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-user-register' );
                            $headers    = array('Content-Type: text/html; charset=UTF-8');
                            $send 	    = wp_mail( $email, 'Mailbomb register newsletter', $body, $headers );
                        }
                    }
                }
            }
            else
            {
                $check   =  $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}mailbomb_users WHERE email='$email'", OBJECT );

                if( $check )
                {
                    $check = $check[0];

                    $wpdb->delete( "{$wpdb->prefix}mailbomb_users", ['id' => $check->id ] );

                    $body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-user-unregistered' );
                    $headers    = array('Content-Type: text/html; charset=UTF-8');
                    $send 	    = wp_mail( $email, 'Mailbomb unregister newsletter', $body, $headers );
                }

            }
        }
    }

}