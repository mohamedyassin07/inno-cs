<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Redis_Actions
 *
 * This class contains all of the redis settings.
 *
 * @package		INNOCS
 * @subpackage	Classes/Redis_Actions
 * @author		Mohamed Yassin
 * @since		1.0.0
 */
class Redis_Actions{

	/**
	 * Our Redis_Actions constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->redis_instance();
		$this->add_hooks();
	}

	public function redis_instance(){
		$this->redis_instance =  Redis_Connection::instance();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
		add_action( 'wp_ajax_redis_store_products', array( $this , 'store_products' ) );
		add_action( 'wp_ajax_redis_flush_data', array( $this , 'flush_data' ) );
	}
	
	public function store_products(){
		$query = new WC_Product_Query( array(
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
		) );
		$products = $query->get_products();
		foreach ($products as $key => $product) { //latel will support pipeline
			$_product = wc_get_product( $product );
			wp_cache_add( $product->ID , $_product, 'posts' );
 		}
		
		$resp['msg'] = 'storing products completed';
		wp_send_json_success($resp);
	}

	public function flush_data(){
		$this->redis_instance =  Redis_Connection::instance();
		$this->redis_instance->flushall();
		$resp['msg'] = 'Flush Done';
		wp_send_json_success($resp);	
	}
}