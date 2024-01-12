<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              phoeniixx.com
 * @since             1.0.0
 * @package           Woo_Custom_Fields_For_Variation
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Custom Field For Variation
 * Plugin URI:        https://www.phoeniixx.com/product/woocommerce-custom-fields-for-variation/
 * Description:       This plugin is designed to give your Ecommerce website the space to add customized options for your products.
 * Version:           2.1.0
 * Author:            Phoeniixx
 * Author URI:        phoeniixx.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-custom-fields-for-variation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_CUSTOM_FIELDS_FOR_VARIATION_VERSION', '2.0.0' );
define("PHOEN_CUSTOM_VARIATION_DIR_PATH",plugin_dir_path( __FILE__ ));
define("PHOEN_CUSTOM_VARIATION_DIR_URL", esc_url( plugin_dir_url( __FILE__ ) ) );
define('PHOEN_ARBPRPLUGURL',plugins_url(  "/", __FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-custom-fields-for-variation-activator.php
 */
function activate_woo_custom_fields_for_variation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-custom-fields-for-variation-activator.php';
	Woo_Custom_Fields_For_Variation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-custom-fields-for-variation-deactivator.php
 */
function deactivate_woo_custom_fields_for_variation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-custom-fields-for-variation-deactivator.php';
	Woo_Custom_Fields_For_Variation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_custom_fields_for_variation' );
register_deactivation_hook( __FILE__, 'deactivate_woo_custom_fields_for_variation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-custom-fields-for-variation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function phoen_custom_field_variation_install_woocommerce_admin_notice() {
    
    echo '<div class="error"><p>'; 
    printf(__('Woocommerce Custom Fields For Variation could not detect an active Woocommerce plugin. Make sure you have activated it. | <a href="%1$s">Hide Notice</a>'), '?phoen_custom_options_nag_ignore=0');
    echo "</p></div>";
}

function run_woo_custom_fields_for_variation() {

	$plugin = new Woo_Custom_Fields_For_Variation();
	$plugin->run();

}

function phoen_custom_field_variation_init(){

    run_woo_custom_fields_for_variation();
}
add_action('phoen_custom_field_variation_init','phoen_custom_field_variation_init');

function phoen_custom_field_variation_install(){

    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){

        do_action( 'phoen_custom_field_variation_init' );

    }else {
        
        add_action( 'admin_notices', 'phoen_custom_field_variation_install_woocommerce_admin_notice' );
    }
}
add_action( 'plugins_loaded', 'phoen_custom_field_variation_install', 11 );

