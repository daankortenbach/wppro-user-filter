<?php
/**
 * The main plugin file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin
 * and defines a function that starts the plugin.
 *
 * @link              https://wppro.nl/
 * @since             1.0.0
 * @package           Wppro_User_Filter
 *
 * @wordpress-plugin
 * Plugin Name:       WP Pro User Filter
 * Plugin URI:        https://wppro.nl/
 * Description:       This plugin shows a user table in /wp-admin/ under the Users menu item. It has functions to filter users by user role and order by username and name.
 * Version:           1.0.1
 * Author:            Daan Kortenbach
 * Author URI:        https://wppro.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wppro-user-filter
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
define( 'WPPRO_USER_FILTER_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wppro-user-filter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wppro_user_filter() {

	$plugin = new Wppro_User_Filter();
	$plugin->run();

}
run_wppro_user_filter();
