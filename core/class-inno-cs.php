<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Inno_Cs' ) ) :

	/**
	 * Main Inno_Cs Class.
	 *
	 * @package		INNOCS
	 * @subpackage	Classes/Inno_Cs
	 * @since		1.0.0
	 * @author		Mohamed Yassin
	 */
	final class Inno_Cs {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Inno_Cs
		 */
		private static $instance;

		/**
		 * INNOCS helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Inno_Helpers
		 */
		public $helpers;

		/**
		 * INNOCS settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Inno_Cs_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'inno-cs' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'inno-cs' ), '1.0.0' );
		}

		/**
		 * Main Inno_Cs Instance.
		 *
		 * Insures that only one instance of Inno_Cs exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Inno_Cs	The one true Inno_Cs
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Inno_Cs ) ) {
				self::$instance					= new Inno_Cs;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Inno_Helpers();
				self::$instance->settings		= new Inno_Cs_Settings();
				self::$instance->redis_settings	= new Redis_Settings();
				self::$instance->redis_actions	= new Redis_Actions();
				

				
				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'INNOCS/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once INNOCS_PLUGIN_DIR . 'core/includes/classes/helpers.php';
			require_once INNOCS_PLUGIN_DIR . 'core/includes/classes/settings.php';
			require_once INNOCS_PLUGIN_DIR . 'core/includes/classes/redis-connection.php';
			require_once INNOCS_PLUGIN_DIR . 'core/includes/classes/redis-settings.php';
			require_once INNOCS_PLUGIN_DIR . 'core/includes/classes/redis-actions.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			//register_activation_hook( __FILE__ , array( self::$instance ,  'plugin_activate' ) );
			// add_action( 'init', array( $this , 'load_textdomain' ) );
			//register_deactivation_hook( INNOCS_PLUGIN_FILE , array( $this , 'plugin_deactivate' ) );
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			add_action( 'init', array( self::$instance, 'plugin_activate' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'inno-cs', FALSE, dirname( plugin_basename( INNOCS_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * The code that runs during plugin activation.
		 */
		public function plugin_activate() {
			@$source = WP_PLUGIN_DIR . '/inno-cs/core/includes/functions/object-cache.php';
			$cache_file = WP_CONTENT_DIR .'/object-cache.php';
			copy($source, $cache_file);
		}
	}

endif; // End if class_exists check.