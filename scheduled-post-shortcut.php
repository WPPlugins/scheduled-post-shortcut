<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/scheduled-post-shortcut/
 * @since             1.0.0
 * @package           Scheduled_Post_Shortcut
 *
 * @wordpress-plugin
 * Plugin Name:       Scheduled Post Shortcut
 * Plugin URI:        https://wordpress.org/plugins/scheduled-post-shortcut/
 * Description:       Easily access your scheduled posts from the WordPress dashboard.
 * Version:           1.4.0
 * Author:            Pressware, LLC
 * Author URI:        https://pressware.co
 * Text Domain:       scheduled-posts-shortcuts
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	 die;
}

// The primarily class responsible for introducing functionality into WordPress.
include_once( 'admin/classes/class-scheduled-post-shortcut.php' );

add_action( 'plugins_loaded', 'pressware_scheduled_post_shortcut' );
/**
 * Officially starts the plugin.
 *
 * @since 1.0.0
 */
function pressware_scheduled_post_shortcut() {

	$plugin = new Scheduled_Post_Shortcut();
	$plugin->init();
}
