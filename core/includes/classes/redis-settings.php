<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Redis_Settings
 *
 * This class contains all of the redis settings.
 *
 * @package		INNOCS
 * @subpackage	Classes/Redis_Settings
 * @author		Mohamed Yassin
 * @since		1.0.0
 */
class Redis_Settings{

	/**
	 * Unique Settings Page Slug
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	public $settings_page_slug = 'inno_cs';

	/**
	 * Our Redis_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->redis_instance();
		$this->add_hooks();
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
	
		add_action( 'plugin_action_links_' . INNOCS_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
	
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init') );
		
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	1.0.0
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*/
	public function add_plugin_action_link( $links ) {
		$settings_link = '#';
		$new_links['settings'] = sprintf( '<a href="%s" title="Settings" style="font-weight:700;">%s</a>', $settings_link, __( 'Redis Settings', 'inno-cs' ) );
		$links = array_merge( $new_links, $links );
		return $links;
	}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_script( 'innocs-ajax-handler', INNOCS_PLUGIN_URL . 'core/assets/js/ajax-handler.js', array(), false, false );
		wp_localize_script( 'innocs-ajax-handler', 'innocs', array(
			'please_wait_msg'   	=> __( 'Please Wait', 'inno-cs' ),
		));
	}

	public function admin_menu() {
		add_menu_page(
			__( 'Redis Settings', 'inno-cs' ),
			__( 'Redis Settings', 'inno-cs' ),
			'manage_options',
			$this->settings_page_slug,
			array( $this, 'admin_page_contents' ),
			'dashicons-schedule',
			3
		);
	}	

	public function admin_page_contents() {
		?>
		<h1> <?php esc_html_e( 'INNO Redis CS Settings Page', 'inno-cs' ); ?> </h1>
		<form method="POST" action="options.php">
		<?php
		settings_fields( $this->settings_page_slug );
		do_settings_sections( $this->settings_page_slug );
		submit_button();
		?>
		</form>
		<?php
	}
	
	public function settings_init() {
		
		// Redis Server Data Section
		add_settings_section(
			'redis_server_data',
			__( 'Redis Server Data', 'inno-cs' ),
			array( $this, 'setting_section_desc' ),
			$this->settings_page_slug
		);
	
		$fields = array('scheme','host','port');
		foreach ($fields as $field) {
			add_settings_field(
				'redis_server_'.$field,
				__( 'Redis Server Host', 'inno-cs' ),
				array( $this, 'redis_server_'.$field.'_field' ),
				$this->settings_page_slug,
				'redis_server_data'
			);
			register_setting( $this->settings_page_slug, 'redis_server_'.$field );
		}
	
		if(is_object($this->redis_instance) ){
			// Server Connection Acctions
			add_settings_field(
				'redis_server_connection_actions',
				__( 'Actions', 'inno-cs' ),
				array( $this, 'redis_server_connection_actions_field' ),
				$this->settings_page_slug,
				'redis_server_data'
			);
		}
	}
	
	public function setting_section_desc() {
		if( is_string($this->redis_instance) ){
			echo $this->redis_instance;
		}

		if(  is_object($this->redis_instance) ){
			echo __( 'Redis is working correctly', 'inno-cs' );
		}

	}
	
	public function redis_server_scheme_field() {
		?>
		<input type="text" id="redis_server_scheme" name="redis_server_scheme" placeholder="tcp" value="<?php echo get_option( 'redis_server_scheme' ); ?>" required>
		<?php
	}
	public function redis_server_host_field() {
		?>
		<input type="text" id="redis_server_host" name="redis_server_host" placeholder="127.0.0.1" value="<?php echo get_option( 'redis_server_host' ); ?>"  required>
		<?php
	}

	public function redis_server_port_field() {
		?>
		<input type="number" id="redis_server_port" name="redis_server_port" placeholder="6379" value="<?php echo get_option( 'redis_server_port' ); ?>"  required>
		<?php
	}

	public function redis_server_prefix_field() {
		?>
		<input type="text" id="redis_server_prefix" name="redis_server_prefix" placeholder="inno:" value="<?php echo get_option( 'redis_server_prefix' ); ?>" required>
		<?php
	}

	public function redis_server_connection_actions_field(){ ?>
	<a onClick="redis_action_request('redis_store_products')" class="button button-primary"><?= __( 'Store All Data', 'inno-cs' ); ?></a>
	<a onClick="redis_action_request('redis_flush_data')" class="button button-primary"><?= __( 'Flush All Data', 'inno-cs' ); ?></a>
	<p id='redis_actions_result'></p>
	<?php }
	
	public function redis_instance(){
		$this->redis_instance =  Redis_Connection::instance();
	}
}