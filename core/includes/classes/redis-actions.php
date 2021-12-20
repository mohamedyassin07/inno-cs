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
		add_action('pre_get_posts', array( $this, 'remove_products_on_redis_from_the_wp_query') );
		add_action( 'found_posts', array( $this, 'add_redis_stored_products' ) );		
	}
	
	public function store_products(){

	}

	public function flush_data(){
		$resp['msg'] = 'flush data';
		wp_send_json_success($resp);
	}

	public function remove_products_on_redis_from_the_wp_query($query) {
		$this->redis_instance =  Redis_Connection::instance();
		$products = $this->redis_instance->get('products');
		$products = json_decode( $products , true );
		$ids = array();
		foreach ((array)$products as $key => $product) {
			$ids[] = $products['ID'];
		}

		if( ! empty( $ids )) { 
			$query->set( 'post__in', $ids );
		}

		return $query;
	}

	public function add_redis_stored_products(){
		$this->redis_instance =  Redis_Connection::instance();
		$products = $this->redis_instance->get('products');
		$products = json_decode( $products , true );

		global $wp_query;
		$api_post = $wp_query->api;
		foreach ( (array) $products as $key  => $product) {
			array_push($wp_query->posts, $product); 
		}
	}

}