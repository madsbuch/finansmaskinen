<?php
/**
* general configuration
*
* configuration
*
* this is a static class, this implies that no other objects or methods can be
* called. This is because of the CLI based cron system.
*/
namespace config;
define("DEBUG", true);
define("CORE_CEHCK_PERMISSIONS", false);//shall the core classes check if the object can be created?

//dirs
define("ROOT", "/var/www/");//remember ending backslash
define("APPDIR", ROOT."/apps/");
define("TMPDIR", ROOT."/temporary/");
define("STATICDIR", ROOT."/static/");
define("CACHEDIR", ROOT."cache/");
define("PLUGINDIR", ROOT."/plugins/");
define("LANDIR", ROOT."localization/");//language dir
define("LOGDIR", ROOT."log/");


class config{
	public static $configs = array(
		'finance' => array( //max 10 char
			//mysql
			'mysql' => array (
				'dbname' => 'finance',
				'driver' => 'mysql',
				'host' => '192.168.1.18',
				'username' => 'appf',
				'password' => 'appfDB'
			),
			
			//mongo
			'mongo' => array (
				'dbname' => 'finance',
				'replicaSet'=> false,
			
				// persistent related vars
				'persistent' 	=> true, 			// persistent connection to DB?
				'persistentId' 	=> 'financeMongo', 	// name of persistent connection
			
				'servers' => array(
					array(
						'host'          => '192.168.1.18',
						'port'          => 27017,
						'username'      => null,
						'password'      => null
					)
				)
			),
			
			'mail' => array(
				'smtp' => array(
					'server' => 'smtp.mandrillapp.com',
					'port' => 587,
					'user' => 'madspbuch@gmail.com',
					'pass' => 'e53f1729-e432-4a49-8185-bc875fbb5335'
				)
			),
			
			'domains' => array(
				'static' => 'static.finansmaskinen.dev',
				'web' => 'www.finansmaskinen.dev',
				'rpc' => 'rpc.finansmaskinen.dev'
			),
			
			'settings' => array(
				'protocol' => 'http'
			)
		),
	);
	
	public static $coreConfig = array(
		'mysql' => array (
			'dbname' => 'core',
			'driver' => 'mysql',
			'host' => '192.168.1.18',
			'username' => 'appf',
			'password' => 'appfDB'
		),
		'mongo' => array (
			'dbname' => 'core',
			'replicaSet'=> false,
			
			// persistent related vars
			'persistent' 	=> true, 			// persistent connection to DB?
		    'persistentId' 	=> 'coreMongo', 	// name of persistent connection
			
			'servers' => array(
				array(
		            'host'          => '192.168.1.18',
		            'port'          => 27017,
		            'username'      => null,
		            'password'      => null
		        )
			)
		),
	
	);
	
	public static $logConfig = array(
		'mysql' => array (
			'dbname' => 'logs',
			'driver' => 'mysql',
			'host' => '192.168.1.18',
			'username' => 'appf',
			'password' => 'appfDB'
		),
	);
	
	/**
	* those values, which are null, is read from coreconfig
	*/
	public static $sessionConfig = array(
		    // session related vars
		    'max_lock_time'	=> 60,			//if something goes wrong with the server
		    								//a user should not hang all the lifetime
		    'lifetime'      => 3600,        // session lifetime in seconds
		    'database'      => null,   		// name of MongoDB database
		    'collection'    => 'session',   // name of MongoDB collection
			// persistent related vars
			'persistent' 	=> true, 			// persistent connection to DB?
		    'persistentId' 	=> 'MongoSession', 	// name of persistent connection
			
			// whether we're supporting replicaSet
			'replicaSet'		=> null,

			// array of mongo db servers
		    'servers'   	=> array(
		        array(
		            'host'          => null,
		            'port'          => null,
		            'username'      => null,
		            'password'      => null
		        )
		    )
		);
	
	public static $settings = array(
		'useCDN' => false,	//use Content Delivery Network (if available)
							//for static content
		
		
	);
	
	public static $domain = array(
		'main' => 'www.finansmaskinen.dev',
		'static' => 'static.finansmaskinen.dev',
		'api' => 'api.finansmaskinen.dev',
	);
	
	//f.eks. http or https
	public static $protocol = "http";
	
	/**
	* this i a very cheap hack :S
	*
	* the thing is, that it is not possible to reference other static variables
	* from each others, so I had to make this function, to do the bindings :S
	*/
	public static function initialize(){
		self::$sessionConfig['replicaSet'] = &self::$coreConfig['mongo']['replicaSet'];
		self::$sessionConfig['database'] = &self::$coreConfig['mongo']['dbname'];
		self::$sessionConfig['servers'] = &self::$coreConfig['mongo']['servers'];
	}
}
?>
