<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       phoeniixx.com
 * @since      1.0.0
 *
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/includes
 * @author     Phoeniixx <contact@phoeniixx.com>
 */
class Woo_Custom_Fields_For_Variation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-custom-fields-for-variation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
