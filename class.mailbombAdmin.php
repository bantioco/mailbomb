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
    }

    public static function mailbomb_table_column( $contactmethods ) 
    {
        $contactmethods['mailbomb_newsletter'] = 'Mailbomb newsletter';
        return $contactmethods;
    }
    
    public static function mailbomb_modify_user_table( $column ) 
    {
        $column['mailbomb_newsletter'] = 'Mailbomb newsletter';
        return $column;
    }
    
    public static function mailbomb_modify_user_table_row( $val, $column_name, $user_id ) 
    {
        global $wpdb;
        $user = get_user_by( 'id', $user_id );

        switch ($column_name) {
            case 'mailbomb_newsletter' :
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

        $isActive = self::mailbomb_active_check();

        global $wpdb;

        $numberItems    = 10;
        $offset 	    = 0;
		$limit 		    = 10;
        $page 		    = 1;
        $numberPages    = 1;

        $userListsAll   =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_users", OBJECT );
        $totalUsers     = count( $userListsAll );

        if( round( $totalUsers / $numberItems ) > 1 ) $numberPages = round( $totalUsers / $numberItems );

		$userLists      =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mailbomb_users ORDER BY id DESC LIMIT $offset,$limit;", OBJECT );

        require_once('html/setting.php');
    }

    /**
     * mailbomb - Admin setting save
     * @todo: refactor function
     */
    public static function mailbomb_admin_html_save()
    {
        if( isset( $_POST['mailbomb_setting_post'] ) )
        {
            /**
             * GLOBAL ACTIVATION
             */
            delete_option('_mailbomb_active');

            $isActive = "off";

            if( isset( $_POST['mailbomb_active'] ) ) $isActive = $_POST['mailbomb_active'];

            if( $isActive === "on" ) add_option( '_mailbomb_active', 'on' );

        }
    }

    /**
     * mailbomb - GLOBAL ACTIVE CHECK
     */
    public static function mailbomb_active_check()
    {
        $getIsActive    = get_option('_mailbomb_active');
        $isActive       = "";

        if( $getIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
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
                            $send 	= wp_mail( $email, 'Mailbomb register newsletter', 'Mailbomb register newsletter' );
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
                    $send 	= wp_mail( $email, 'Mailbomb unregister newsletter', 'Mailbomb unregister newsletter' );
                }

            }
        }
    }

}