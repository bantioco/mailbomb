<?php

class mailbombCron
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

        add_filter( 'cron_schedules', [ 'mailbombCron', 'mailbombAddNewCronIntervals' ]); 

        register_activation_hook(  __FILE__, [ 'mailbombCron', 'mailbombActivationCron' ] );

        add_action('mailbomb_cron_event', [ 'mailbombCron', 'mailbombCronnewsletter'] ); 
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

                        foreach( $cron as $t)
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

        $now 		= new Datetime();
        $dateNow 	= $now->format('Y-m-d H:i:s');

        $tableParams 	    = $wpdb->prefix.'mailbomb_params';
        $newsletterTemplate = 'mailbomb-newsletter';
        $defaultNewsletter 	= $wpdb->get_row( "SELECT value_params FROM $tableParams WHERE key_params='default_template_newsletter'", OBJECT );

        if( $defaultNewsletter ) $newsletterTemplate = $defaultNewsletter->value_params;

        $classUsers = new mailbombUsers();
        $users 		= $classUsers->mailbombUsersGet( 'id, email, newsletter_sending' );
        
        $oneSend = false;
        
        if( $users )
        {
            foreach( $users as $user )
            {
                $userEmail  = $user->email;

                $body       = file_get_contents( get_site_url().'/?mailbomb_templates='.$newsletterTemplate );
                $headers    = array('Content-Type: text/html; charset=UTF-8');

                $send       = wp_mail( $userEmail, 'Newsletter - '.$newsletterTemplate, $body, $headers );

                if( $send )
                {
                    $oneSend = true;
                    $userId 				= $user->id;
                    $newNewsletterSending 	= ( (int)$user->newsletter_sending + 1 );

                    $datas = [ 
                        'newsletter_sending'    	=> $newNewsletterSending,
                        'last_newsletter_sending'   => $dateNow
                    ];
            
                    $params = [ '%d', '%s' ];
            
                    $classUsers = new mailbombUsers();
                    $update 	= $classUsers->mailbombUserUpdate( $datas, $params, $userId );
                }
            }
        }

        $classStats = new mailbombStats();

        $datas = [
            'key_stats' 	=> 'newsletter_send',
            'value_stats' 	=> $newsletterTemplate,
            'state_stats' 	=> 'success',
            'created_at' 	=> $dateNow
        ];

        $params = [ '%s', '%s', '%s', '%s' ];

        if( !$oneSend ) $datas['state_stats'] = "error";

        $classStats->mailbombStatsAdd( $datas, $params );

        return;
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

        $classStats = new mailbombStats();

        $datas = [
            'key_stats' 	=> 'newsletter_test',
            'value_stats' 	=> $newsletterTemplate,
            'state_stats' 	=> 'success',
            'created_at' 	=> $dateNow
        ];

        $params = [ '%s', '%s', '%s', '%s' ];

        if( !$send ) $datas['state_stats'] = "error";

        $classStats->mailbombStatsAdd( $datas, $params );

        if( $send ) echo '<div class="notice notice-success is-dismissible"><p>CRON TEST : NEWSLETTER SEND SUCCESS</p></div>';
        else echo '<div class="notice notice-error is-dismissible"><p>CRON TEST : NEWSLETTER SEND ERROR</p></div>';
    }


    public static function mailbombCronAddSchedule( $schedule )
    {
        $timestamp      = current_time( 'timestamp' );
        $hook           = 'mailbomb_cron_event';

        if( $schedule != "mailbomb_schedule_single_event" )
        {
            wp_schedule_event( $timestamp, $schedule, $hook );
        }
        else
        {
            wp_schedule_single_event( time() + 3600, $hook, ['single_event'] );
        }
        return;
    }
}