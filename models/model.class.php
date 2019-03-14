<?php
class model
{
	private static $dbName = 'restaurant' ;
	private static $dbHost = 'localhost' ;
	private static $dbUsername = 'root';
	private static $dbUserPassword = '';
	private static $cont  = null;

	public function __construct() {

	}

	public static function connect()
	{
		// One connection through whole application
		if ( null == self::$cont )
		{
			try
			{
				self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
			}
			catch(PDOException $e)
			{
				die($e->getMessage());
			}
		}
		return self::$cont;
	}



	public function  sanatize_post($var)
	{
    	foreach ($var as $key => $val)
    	{
    		if (is_string($val))
    		{
        		$var[$key] = trim($val);
			}
      	}
      	return $var;
	}


	public function  initiate_session()
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
		} elseif (session_id() == '')
		{
			session_start();
	 	} else {}
	}
}

