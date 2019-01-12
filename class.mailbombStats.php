<?php

class mailbombStats
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

    }

    /**
	 * MAILBOMB - Newsletter sending user state
	 */
	public function mailbombStatsAdd( $datas = [], $params = [] )
	{
        global $wpdb;

        $tableStats = $wpdb->prefix.'mailbomb_stats';

		$wpdb->insert( $tableStats, $datas, $params );

		return;
	}

}