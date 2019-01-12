<?php

class mailbombHtml
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

        add_action( 'admin_menu', [ 'mailbombHtml', 'mailbombAddMenuPage'] );
    }

    public static function mailbombAddMenuPage()
    {
        add_menu_page( 
            'mailbomb', 
            'Mailbomb', 
            'manage_options', 
            'mailbomb-setting', 
            [ 'mailbombHtml', 'mailbombAddMenuPageHtml' ], 
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

        $schedules      = wp_get_schedules();
        $cronJobsGet    = get_option( 'cron' );

        require_once('html/mailbomb_params.php');
    }

    /**
     * ADMIN SETTING SAVE
     */
    public static function mailbombAdminHtmlSave()
    {
        $classAdmin = new mailbombAdmin();
        $classCron  = new mailbombCron();

        /**
         * USERS LIST ITEMS PER PAGE
         */
        if( isset( $_POST['users_list_items_per_page'] ) && isset( $_POST['users_list_items_per_page_id'] ) )
        {
            $number     = $_POST['users_list_items_per_page'];
            $paramId    = $_POST['users_list_items_per_page_id'];

            $classAdmin->usersListItemsPerPageUpdate( $number, $paramId );
        }

        /**
         * TEMPLATE NEWSLETTER UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_newsletter'] ) && isset( $_POST['mailbomb_template_default_newsletter_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_newsletter'];
            $paramId            = $_POST['mailbomb_template_default_newsletter_id'];

            $classAdmin->mailbombTemplateDefaultUpdate( 'default_template_newsletter', $templateChoice, $paramId );
        }

        /**
         * TEMPLATE REGISTER UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_register'] ) && isset( $_POST['mailbomb_template_default_register_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_register'];
            $paramId            = $_POST['mailbomb_template_default_register_id'];

            $classAdmin->mailbombTemplateDefaultUpdate( 'default_template_register', $templateChoice, $paramId );
        }

        /**
         * TEMPLATE UNREGISTERED UPDATE
         */
        if( isset( $_POST['mailbomb_template_default_unregistered'] ) && isset( $_POST['mailbomb_template_default_unregistered_id'] ) )
        {
            $templateChoice     = $_POST['mailbomb_template_default_unregistered'];
            $paramId            = $_POST['mailbomb_template_default_unregistered_id'];

            $classAdmin->mailbombTemplateDefaultUpdate( 'default_template_unregistered', $templateChoice, $paramId );
        }

        /**
         * CRON ADD
         */
        if( isset( $_POST['mailbomb_cron_post'] ) && isset( $_POST['mailbomb_cron_schedule'] ) )
        {
            $schedule   = $_POST['mailbomb_cron_schedule'];

            $classCron->mailbombCronAddSchedule( $schedule );
        }

        /**
         * CRON DELETE
         */
        if( isset( $_POST['mailbomb_cron_name_delete'] ) && isset( $_POST['mailbomb_cron_timestamp_delete'] ) )
        {
            $name           = $_POST['mailbomb_cron_name_delete'];
            $cronTimestamp  = $_POST['mailbomb_cron_timestamp_delete'];

            $classCron->mailbombDeleteCrons( $name, $cronTimestamp );
        }

        /**
         * CRON ADD
         * @todo : A faire en ajax
         */
        if( isset( $_POST['mailbomb_cron_run_test'] ) )
        {
            $classCron->mailbombCronNewsletter();
        }
    }

    /**
     * FUNCTONS
     */

    public static function convertSeconds($seconds) 
    {
        $dt1 = new DateTime("@0");

        $dt2 = new DateTime("@$seconds");

        return $dt1->diff($dt2)->format('%a days, %h hours, %i minutes');
    }

    public static function sec2Time($time)
    {
        $time = (int)$time;

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

}