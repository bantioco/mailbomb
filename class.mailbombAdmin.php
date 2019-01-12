<?php

require plugin_dir_path( __FILE__ ).'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        add_action( 'show_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'edit_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'profile_update', [ 'mailbombAdmin', 'mailbombProfileFieldsSave' ], 10, 2 );

        add_filter( 'user_contactmethods', [ 'mailbombAdmin', 'mailbombTableColumn' ], 10, 1 );

        add_filter( 'manage_users_columns', [ 'mailbombAdmin', 'mailbombModifyUserTable' ] );

        add_filter( 'manage_users_custom_column', [ 'mailbombAdmin', 'mailbombModifyUserTableRow' ], 10, 3 );

        add_filter( 'admin_body_class', [ 'mailbombAdmin', 'mailbobmTableClasses' ], 10, 3 );
    }
    

    public static function mailbobmTableClasses( $classes ) 
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

    public static function mailbombTableColumn( $mailbombNewsletter ) 
    {
        $mailbombNewsletter['mailbomb_templates'] = 'Mailbomb newsletter';
        return $mailbombNewsletter;
    }
    
    public static function mailbombModifyUserTable( $column ) 
    {
        $column['mailbomb_templates'] = 'Mailbomb newsletter';
        return $column;
    }
    
    public static function mailbombModifyUserTableRow( $val, $column_name, $user_id ) 
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


    public static function mailbombTemplateDefaultUpdate( $keyParams, $templateChoice, $id )
    {
        if( $templateChoice != "" && $templateChoice != null )
        {
            $now        = new Datetime();
            $dateNow    = $now->format('Y-m-d H:i:s');

            $datas = [ 
                'key_params'    => $keyParams,
                'value_params'  => $templateChoice,
                'created_at'    => $dateNow
            ];

            global $wpdb;

            $tableName = $wpdb->prefix . 'mailbomb_params';

            $wpdb->update( $tableName, $datas, [ 'id' => $id ], [ '%s', '%s', '%s' ], null );
        }
    }

    /**
     *  UPDATE USERS LIST ITEMS PER PAGE
     */
    public static function usersListItemsPerPageUpdate( $number, $paramId )
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

            $tableName = $wpdb->prefix . 'mailbomb_params';

            $wpdb->update( $tableName, $datas, [ 'id' => $paramId ], [ '%s', '%s', '%s' ], null );
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


    public static function mailbombProfileFieldsSave( $user_id, $old_user_data ) 
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

                            //$tempalte   =  $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}mailbomb_users WHERE email='$email'", OBJECT );

                            $wpdb->insert( $table_name, $datas, [ '%s', '%s', '%s' ] );

                            $body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-register' );
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

                    $body       = file_get_contents( get_site_url().'/?mailbomb_templates=mailbomb-unregistered' );
                    $headers    = array('Content-Type: text/html; charset=UTF-8');
                    $send 	    = wp_mail( $email, 'Mailbomb unregister newsletter', $body, $headers );
                }

            }
        }
    }
}