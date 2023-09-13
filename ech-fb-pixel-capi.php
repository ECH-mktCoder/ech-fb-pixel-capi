<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://#
 * @since             1.0.0
 * @package           Ech_Fb_Pixel_Capi
 *
 * @wordpress-plugin
 * Plugin Name:       ECH FB Pixel CAPI
 * Plugin URI:        https://#
 * Description:       Adding FB Pixel tracking and Conversation API to specific elements.
 * Version:           1.0.0
 * Author:            Rowan Chang
 * Author URI:        https://#
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ech-fb-pixel-capi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ECH_FB_PIXEL_CAPI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ech-fb-pixel-capi-activator.php
 */
function activate_ech_fb_pixel_capi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-fb-pixel-capi-activator.php';
	Ech_Fb_Pixel_Capi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ech-fb-pixel-capi-deactivator.php
 */
function deactivate_ech_fb_pixel_capi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-fb-pixel-capi-deactivator.php';
	Ech_Fb_Pixel_Capi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ech_fb_pixel_capi' );
register_deactivation_hook( __FILE__, 'deactivate_ech_fb_pixel_capi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ech-fb-pixel-capi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ech_fb_pixel_capi() {

	$plugin = new Ech_Fb_Pixel_Capi();
	$plugin->run();

}
run_ech_fb_pixel_capi();
