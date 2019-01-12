<?php

class mailbombUsers
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
	 * MAILBOMB - Users get
	 */
	public static function mailbombUsersGet( $select = '*', $where = false )
	{
        $result     = false;

        global $wpdb;

        $tableName  = $wpdb->prefix.'mailbomb_users';

        $users      = $wpdb->get_results( "SELECT $select FROM  $tableName $where ", OBJECT );

        if( $users ) $result = $users;
        
		return $result;
    }

    /**
	 * MAILBOMB - Users get
	 */
	public static function mailbombUsersGetByDate( $select = '*', $dateStart = false, $dateEnd = false )
	{
        $result     = false;

        global $wpdb;

        $tableName  = $wpdb->prefix.'mailbomb_users';

        $users      = [];

        $getUsers   = $wpdb->get_results( "SELECT $select FROM  $tableName", ARRAY_A );

        if( $dateStart && !$dateEnd )
        {
            $start          = new Datetime( $dateStart );
            $formatStart    = (int)$start->format('Ymd');

            if( $getUsers )
            {
                $index = 0;

                foreach( $getUsers as $key => $getUser )
                {
                    $createdAt      = new DateTime( $getUser['created_at'] );
                    $formatCreated  = (int)$createdAt->format('Ymd');

                    if( $formatCreated >= $formatStart )
                    {
                        $users[$index] = [
                            'id'                        => $getUser['id'],
                            'email'                     => $getUser['email'],
                            'created_at'                => $getUser['created_at'],
                            'newsletter_sending'        => $getUser['newsletter_sending'],
                            'last_newsletter_sending'   => $getUser['last_newsletter_sending']
                        ];

                        $index ++;
                    }
                }
            }
        }

        elseif( !$dateStart && $dateEnd )
        {

            $end          = new Datetime( $dateEnd );
            $formatEnd    = (int)$end->format('Ymd');

            if( $getUsers )
            {
                $index = 0;

                foreach( $getUsers as $key => $getUser )
                {
                    $createdAt      = new DateTime( $getUser['created_at'] );
                    $formatCreated  = (int)$createdAt->format('Ymd');

                    if( $formatCreated <= $formatEnd )
                    {
                        $users[$index] = [
                            'id'                        => $getUser['id'],
                            'email'                     => $getUser['email'],
                            'created_at'                => $getUser['created_at'],
                            'newsletter_sending'        => $getUser['newsletter_sending'],
                            'last_newsletter_sending'   => $getUser['last_newsletter_sending']
                        ];

                        $index ++;
                    }
                }
            }
        }

        elseif( $dateStart && $dateEnd )
        {
            $start          = new Datetime( $dateStart );
            $formatStart    = (int)$start->format('Ymd');

            $end          = new Datetime( $dateEnd );
            $formatEnd    = (int)$end->format('Ymd');

            if( $getUsers )
            {
                $index = 0;

                foreach( $getUsers as $key => $getUser )
                {
                    $createdAt      = new DateTime( $getUser['created_at'] );
                    $formatCreated  = (int)$createdAt->format('Ymd');

                    if( ( $formatCreated >= $formatStart ) && ( $formatCreated <= $formatEnd ) )
                    {
                        $users[$index] = [
                            'id'                        => $getUser['id'],
                            'email'                     => $getUser['email'],
                            'created_at'                => $getUser['created_at'],
                            'newsletter_sending'        => $getUser['newsletter_sending'],
                            'last_newsletter_sending'   => $getUser['last_newsletter_sending']
                        ];

                        $index ++;
                    }
                }
            }  
        }
        else
        {
            if( $getUsers ) $users = $getUsers;
        }

        if( $users ) $result = $users;
        
		return $result;
    }
    
    /**
	 * MAILBOMB - User get
	 */
	public static function mailbombUserGet( $select = '*', $userId )
	{
        $result     = false;

        if( $userId && is_numeric( $userId ) )
        {
            global $wpdb;

            $tableName  = $wpdb->prefix.'mailbomb_users';
            $user       = $wpdb->get_row( "SELECT $select FROM  $tableName WHERE id=$userId", OBJECT );

            if( $user ) $result = $user;
        }
            
        return $result;
    }
    
    /**
	 * MAILBOMB - User update
	 */
	public static function mailbombUserUpdate( $datas = [], $params = [], $userId )
	{
        $result     = false;

        if( $userId && is_numeric( $userId ) && !empty( $datas ) && !empty( $params ) )
        {
            global $wpdb;

            $tableName  = $wpdb->prefix.'mailbomb_users';
            $update     = $wpdb->update( $tableName, $datas, [ 'id' => $userId ], $params, null );

            if( $update ) $result = $update;
        }
        return $result;
	}
}