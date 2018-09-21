<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wppro.nl/
 * @since      1.0.0
 *
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and loads dependecies.
 *
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/admin
 * @author     Daan Kortenbach <daan@wppro.nl>
 */
class Wppro_User_Filter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

	}

	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wppb_Demo_Plugin_Admin_Settings. Registers the admin settings and page.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-wppro-user-filter-settings.php';

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts() {


		// Get current afdmin page
		$page = get_current_screen();

		// Exit early if this is not the intended page.
		if ( $page->base != 'users_page_wppro_user_filter' ) {
			return;
		}

		/**
		 * Enqueue wp-util to load Underscore for templating
		 */
		 wp_enqueue_script( 'wp-util' );
	}
}
