<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wppro.nl/
 * @since      1.0.0
 *
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wppro_User_Filter
 * @subpackage Wppro_User_Filter/includes
 * @author     Daan Kortenbach <daan@wppro.nl>
 */
class Wppro_User_Filter_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wppro-user-filter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
