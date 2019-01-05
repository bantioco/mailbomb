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

        add_action( 'admin_menu', [ 'mailbombAdmin', 'mailbombAddMenuPage'] );

        add_action( 'show_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'edit_user_profile', [ 'mailbombAdmin', 'mailbomb_profile_fields'] );

        add_action( 'profile_update', [ 'mailbombAdmin', 'mailbombProfileFieldsSave' ], 10, 2 );

        add_filter( 'user_contactmethods', [ 'mailbombAdmin', 'mailbombTableColumn' ], 10, 1 );

        add_filter( 'manage_users_columns', [ 'mailbombAdmin', 'mailbombModifyUserTable' ] );

        add_filter( 'manage_users_custom_column', [ 'mailbombAdmin', 'mailbombModifyUserTableRow' ], 10, 3 );

        add_filter( 'admin_body_class', [ 'mailbombAdmin', 'mailbobmTableClasses' ], 10, 3 );

        add_filter( 'cron_schedules', [ 'mailbombAdmin', 'mailbombAddNewCronIntervals' ]); 

        register_activation_hook(  __FILE__, [ 'mailbombAdmin', 'mailbombActivationCron' ] );

        add_action('mailbomb_cron_event', [ 'mailbombAdmin', 'mailbombCronnewsletter'] ); 
    }


    public static function mailbombActivationCron()
    {
        if( !wp_next_scheduled( 'mailbomb_cron_event' ) )
        {
           wp_schedule_event( time(), 'mailbomb_minutes', 'mailbomb_hourly' );
        }
    }

    /**
     * MAILBOMB - ADD new cron interval Once Weekly / Once a month
     */
    public static function mailbombAddNewCronIntervals( $schedules ) 
    {
        $schedules['mailbomb_minutes'] = array(
            'interval' => 60*1,
            'display' => __('Une fois par minute')
        );

        $schedules['mailbomb_hourly'] = array(
            'interval' => 3600,
            'display' => __('Une fois par heure')
        );

        $schedules['mailbomb_dayly'] = array(
            'interval' => 86400,
            'display' => __('Une fois par jour')
        );

        $schedules['mailbomb_weekly'] = array(
            'interval' => 604800,
            'display' => __('Une fois par semaine')
        );

        $schedules['mailbomb_monthly'] = array(
            'interval' => 2635200,
            'display' => __('Une fois par mois')
        );

        return $schedules;
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
    
    public static function mailbombAddMenuPage()
    {
        add_menu_page( 
            'mailbomb', 
            'Mailbomb', 
            'manage_options', 
            'mailbomb-setting', 
            [ 'mailbombAdmin', 'mailbombAddMenuPageHtml' ], 
            'dashicons-email-alt', 
            61
        );
    }

    /**
     * mailbomb - Admin setting html
     */
    public static function mailbombAddMenuPageHtml()
    {
        self::mailbombAdminHtmlSave();

        global $wpdb;

        /**
         * MAILBOMB - Tables
         */
        $tableParams        = $wpdb->prefix.'mailbomb_params';
        $tableUsers         = $wpdb->prefix.'mailbomb_users';
        $tableTemplates     = $wpdb->prefix.'mailbomb_templates';

        // USERS - ITEMS PER PAGE
        $userItemsPerPage   =  $wpdb->get_row( "SELECT id, value_params FROM $tableParams WHERE key_params='users_list_items_per_page'", OBJECT );

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

        $userListsAll   =  $wpdb->get_results( "SELECT * FROM $tableUsers", OBJECT );
        $totalUsers     = count( $userListsAll );

        // NUMBERS OF PAGE
        if( round( $totalUsers / $numberItems ) > 1 ) $numberPages = round( $totalUsers / $numberItems );

        $userLists      =  $wpdb->get_results( "SELECT * FROM $tableUsers ORDER BY id DESC LIMIT $offset,$limit;", OBJECT );


        /**
         * MAILBOMB - PARAMS
         */

        // TEMPLATE NEWSLETTER PARAMS
        $defaultNewsletter              =  $wpdb->get_row( "SELECT id, value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

        $defaultTemplateNewsletter      = "mailbomb-newsletter";
        $defaultTemplateNewsletterId    = "";

        if( $defaultNewsletter ) 
        {
            $defaultTemplateNewsletter      = $defaultNewsletter->value_params;
            $defaultTemplateNewsletterId    = $defaultNewsletter->id;
        }


        // TEMPLATE REGISTER PARAMS
        $defaultRegister  =  $wpdb->get_row( "SELECT id, value_params FROM $tableParams WHERE key_params='default_template_register'", OBJECT );

        $defaultTemplateRegister    = "mailbomb-register";
        $defaultTemplateRegisterId  = "";

        if( $defaultRegister ) 
        {
            $defaultTemplateRegister      = $defaultRegister->value_params;
            $defaultTemplateRegisterId    = $defaultRegister->id;
        }


        // TEMPLATE REGISTER PARAMS
        $defaultUnregistered  =  $wpdb->get_row( "SELECT id, value_params FROM $tableParams WHERE key_params='default_template_unregistered'", OBJECT );


        $defaultTemplateUnregistered        = "mailbomb-unregistered";
        $defaultTemplateUnregisteredId      = "";

        if( $defaultUnregistered ) 
        {
            $defaultTemplateUnregistered      = $defaultUnregistered->value_params;
            $defaultTemplateUnregisteredId    = $defaultUnregistered->id;
        }


        // TEMPLATES GET ALL
        $mailbombTemplates  =  $wpdb->get_results( "SELECT * FROM $tableTemplates", OBJECT );


        /**
         * WP CRON
         */

        $schedules = wp_get_schedules();

        //echo "<pre>"; print_r( $schedules ); echo "</pre>";

        //@todo : a mettre dans une method à part..
        

        $cronJobsGet = get_option( 'cron' );

        //echo "<pre>"; print_r( $cronJobsGet ); echo "</pre>";

        //$cronJobs = self::cronJobsArray( $cronJobsGet );

        

        //var_dump( $cronJobs );

        //var_dump( $cronJobs );

        //echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';

        require_once('html/mailbomb_params.php');
    }

    public static function cronJobsArray( $cronJobsGet )
    {
        $cronJobs = [];

        if( $cronJobsGet )
        {
            foreach( $cronJobsGet as $keyCron => $crons )
            {
                if( $keyCron != 'version' )
                {
                    foreach( $crons as $index => $cron )
                    {
                        $cronJobs[$index] = [];

                        foreach( $cron as $i => $t)
                        {
                            $cronJobs[$index]['schedule']   = $t['schedule'];
                            $cronJobs[$index]['args']       = $t['args'];
                            $cronJobs[$index]['interval']   = $t['interval'];
                        }
                    }

                }

            }
        }

        return $cronJobs;
    }

    /**
     * ADMIN SETTING SAVE
     */
    public static function mailbombAdminHtmlSave()
    {
        /**
         * USERS LIST ITEMS PER PAGE
         */
        if( isset( $_POST['users_list_items_per_page'] ) && isset( $_POST['users_list_items_per_page_id'] ) )
        {
            $number     = $_POST['users_list_items_per_page'];
            $paramId    = $_POST['users_list_items_per_page_id'];

            self::usersListItemsPerPageUpdate( $number, $paramId );
        }

        /**
         * TEMPLATE NEWSLETTER UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_newsletter'] ) && isset( $_POST['mailbomb_template_default_newsletter_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_newsletter'];
            $paramId            = $_POST['mailbomb_template_default_newsletter_id'];

            self::mailbombTemplateDefaultUpdate( 'default_template_newsletter', $templateChoice, $paramId );
        }

        /**
         * TEMPLATE REGISTER UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_register'] ) && isset( $_POST['mailbomb_template_default_register_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_register'];
            $paramId            = $_POST['mailbomb_template_default_register_id'];

            self::mailbombTemplateDefaultUpdate( 'default_template_register', $templateChoice, $paramId );
        }

        /**
         * TEMPLATE UNREGISTERED UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_unregistered'] ) && isset( $_POST['mailbomb_template_default_unregistered_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_unregistered'];
            $paramId            = $_POST['mailbomb_template_default_unregistered_id'];

            self::mailbombTemplateDefaultUpdate( 'default_template_unregistered', $templateChoice, $paramId );
        }

        /**
         * CRON ADD
         */
        if( isset( $_POST['mailbomb_cron_post'] ) )
        {
            var_dump($_POST);
            /*
            $postDay        = $_POST['mailbomb_cron_day'];
            $postHours      = $_POST['mailbomb_cron_hours'];
            $postMinutes    = $_POST['mailbomb_cron_minutes'];
            $postMonth      = $_POST['mailbomb_cron_month'];

            $timestamp      = current_time( 'timestamp' );
            $recurrence     = 'mailbomb_weekly';
            $hook           = 'mailbomb_cron_event';
            $args           = false;

            if( $postMonth === "all_month" ) $recurrence = 'mailbomb_monthly';

            if( $postDay === "all_days" ) $recurrence = 'mailbomb_dayly';

            if( $postHours === "all_hours" ) $recurrence = 'mailbomb_hourly';

            //if( $postMinutes === "all_minutes" ) $recurrence = 'mailbomb_minutes';

            wp_schedule_event( $timestamp, $recurrence, $hook );
            */
        }

        /**
         * CRON DELETE
         */
        if( isset( $_POST['mailbomb_cron_name_delete'] ) && isset( $_POST['mailbomb_cron_timestamp_delete'] ) )
        {
            $name           = $_POST['mailbomb_cron_name_delete'];
            $cronTimestamp  = $_POST['mailbomb_cron_timestamp_delete'];

            self::mailbombDeleteCrons( $name, $cronTimestamp );
        }

        /**
         * CRON ADD
         */
        if( isset( $_POST['mailbomb_cron_run_test'] ) )
        {
            self::mailbombCronNewsletterTest();
        }
    }

    

    public static function convertSeconds($seconds) 
    {
        $dt1 = new DateTime("@0");

        $dt2 = new DateTime("@$seconds");

        return $dt1->diff($dt2)->format('%a days, %h hours, %i minutes');
    }

    public static function sec2Time($time)
    {
        $time = (int)$time;

        //var_dump( $time );

        if(is_numeric($time))
        {
          $value = array(
            "years" => 0, "days" => 0, "hours" => 0,
            "minutes" => 0, "seconds" => 0,
          );

          if($time >= 31556926){
            $value["years"] = floor($time/31556926);
            $time = ($time%31556926);
          }

          if($time >= 86400){
            $value["days"] = floor($time/86400);
            $time = ($time%86400);
          }

          if($time >= 3600){
            $value["hours"] = floor($time/3600);
            $time = ($time%3600);
          }

          if($time >= 60){
            $value["minutes"] = floor($time/60);
            $time = ($time%60);
          }

          $value["seconds"] = floor($time);

          $date = ($value['years'] != 0 ? $value['years'].' Years,' : '' ). " " . ($value['days'] != 0 ? $value['days'].' Days,' : '' ). " " . ($value['hours'] != 0 ? $value['hours'].' Hours,' : '' ). " " . ($value['minutes'] != 0 ? $value['minutes'].' Minutes' : '' );

          //return (array) $value;
          return $date;

        }else
        {
          return (bool) FALSE;
        }
      }

    public static function mailbombDeleteCrons( $name, $cronTimestamp ) 
    {
        $all = _get_cron_array();

        if ( empty( $all ) ) return;
        
        foreach( $all as $timestamp => $crons ) 
        {
            if( empty( $all[$timestamp] ) ) unset( $all[$timestamp] );

            foreach( $crons as $hook => $cron)
            {
                if( ( (int)$timestamp === (int)$cronTimestamp ) && ($hook === $name) ) unset( $all[$timestamp][$hook] );
            }
        }
        _set_cron_array( $all );

        return;
    }

    public static function mailbombCronNewsletter() 
    {
        global $wpdb;

        $tableParams 	    = $wpdb->prefix.'mailbomb_params';
        $newsletterTemplate = 'mailbomb-newsletter';
        $defaultNewsletter 	= $wpdb->get_row( "SELECT value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

        if( $defaultNewsletter ) $newsletterTemplate = $defaultNewsletter->value_params;
        
        $tableUsers 	    = $wpdb->prefix.'mailbomb_users';
        $users 	            = $wpdb->get_results( "SELECT id, email FROM $tableUsers", OBJECT );
        
        if( $users )
        {
            foreach( $users as $user )
            {
                $userEmail  = $user->email;

                $body       = file_get_contents( get_site_url().'/?mailbomb_templates='.$newsletterTemplate );
                $headers    = array('Content-Type: text/html; charset=UTF-8');

                $send = wp_mail( $userEmail, 'Newsletter - '.$newsletterTemplate, $body, $headers );

                if( $send )
                {
                    $userId 				= $user->id;

                    $newsletterSending 		= $user->newsletter_sending;
                    $newNewsletterSending 	= ( (int)$newsletterSending + 1 );

                    $now 		= new Datetime();
                    $dateNow 	= $now->format('Y-m-d H:i:s');

                    $datas = [ 
                        'newsletter_sending'    	=> $newNewsletterSending,
                        'last_newsletter_sending'   => $dateNow
                    ];
                    
                    $wpdb->update( $tableUsers, $datas, [ 'id' => $userId ], [ '%d', '%s' ], null );
                }
            }
        }

        /*
        if( $send ) echo '<div class="notice notice-success is-dismissible"><p>NEWSLETTER SEND SUCCESS</p></div>';
        else echo '<div class="notice notice-error is-dismissible"><p>NEWSLETTER SEND ERROR</p></div>';
        */
    }


    public static function mailbombCronNewsletterTest() 
    {
        global $wpdb;

        $email = get_option('admin_email');

        $tableParams 	    = $wpdb->prefix.'mailbomb_params';
        $newsletterTemplate = 'mailbomb-newsletter';
		$defaultNewsletter 	= $wpdb->get_row( "SELECT value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

		if( $defaultNewsletter ) $newsletterTemplate = $defaultNewsletter->value_params;

		$body       = file_get_contents( get_site_url().'/?mailbomb_templates='.$newsletterTemplate );
		$headers    = array('Content-Type: text/html; charset=UTF-8');

        $send 	    = wp_mail( $email, $newsletterTemplate.' :: Mailbomb newsletter test CRON', $body, $headers );

        if( $send ) echo '<div class="notice notice-success is-dismissible"><p>CRON TEST : NEWSLETTER SEND SUCCESS</p></div>';
        else echo '<div class="notice notice-error is-dismissible"><p>CRON TEST : NEWSLETTER SEND ERROR</p></div>';
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