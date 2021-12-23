<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Redis_Connection
 *
 * Redis Client Instance.
 *
 * @package		INNOCS
 * @subpackage	Classes/Redis_Connection
 * @author		Mohamed Yassin
 * @since		1.0.0
 */
class Redis_Connection{
	
	public static function instance(){

		$scheme = get_option( 'redis_server_scheme' );
		$host 	= get_option( 'redis_server_host' );
		$port 	= get_option( 'redis_server_port' );
		$prefix = get_option( 'redis_server_prefix' );

		if( $scheme && $host && $port ){
			try {
				require_once INNOCS_PLUGIN_DIR . 'core/includes/libs/predis/autoload.php';
					$client =  new Predis\Client([
						'scheme' => $scheme ,
						'host'   => $host,
						'port'   => $port ,
					],
					[
						'prefix' => $prefix,
					]
				);
	
				if ( $client->ping() == 'PONG' ) {
					return $client;
				};
				return __('Something Went Wrong' , 'inno-cs' );
			} catch (Exception $e) {
				return 'Caught exception: ' .   $e->getMessage() .  "\n";
			}

		}else {
			return __('Please Fill All Data Correctly' , 'inno-cs' );
		}
	}


}