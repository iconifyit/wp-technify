<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://vectoricons.net
 * @since             1.0.0
 * @package           Technify
 *
 * @wordpress-plugin
 * Plugin Name:       Technify
 * Plugin URI:        https://github.com/iconifyit/wp-technify
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Scott Lewis
 * Author URI:        https://vectoricons.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       technify
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-technify-activator.php
 */
function activate_technify() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-technify-activator.php';
	Technify_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-technify-deactivator.php
 */
function deactivate_technify() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-technify-deactivator.php';
	Technify_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_technify' );
register_deactivation_hook( __FILE__, 'deactivate_technify' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-technify.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_technify() {

	$plugin = new Technify();
	$plugin->run();

}
run_technify();
