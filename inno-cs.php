<?php
/**
 * Inno CS
 *
 * @package       INNOCS
 * @author        Mohamed Yassin
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Inno CS
 * Plugin URI:    https://innoshop.co/redis
 * Description:   Inno Case Study 
 * Version:       1.0.0
 * Author:        Mohamed Yassin
 * Author URI:    https://innoshop.co
 * Text Domain:   inno-cs
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'INNOCS_NAME',			'Inno CS' );

// Plugin version
define( 'INNOCS_VERSION',		'1.0.0' );

// Plugin Root File
define( 'INNOCS_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'INNOCS_PLUGIN_BASE',	plugin_basename( INNOCS_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'INNOCS_PLUGIN_DIR',	plugin_dir_path( INNOCS_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'INNOCS_PLUGIN_URL',	plugin_dir_url( INNOCS_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once INNOCS_PLUGIN_DIR . 'core/class-inno-cs.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Mohamed Yassin
 * @since   1.0.0
 * @return  object|Inno_Cs
 */
function INNOCS() {
	return Inno_Cs::instance();
}

INNOCS();
