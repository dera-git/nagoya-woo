<?php
/**
 * Plugin Name: WooCommerce Monetico Gateway
 * Plugin URI: https://www.absoluteweb.net/prestations/wordpress-woocommerce-extensions-traductions/woocommerce-monetico/
 * Description: Passerelle de paiement Monetico pour WooCommerce.
 * Version: 2.4.4
 * Requires PHP: 5.5
 * Author: Nicolas Maillard
 * Author URI: https://www.absoluteweb.net/
 * License: Copyright ABSOLUTE Web
 *
 * WC requires at least: 2.0
 * WC tested up to: 99
 *
 *	Intellectual Property rights, and copyright, reserved by Nicolas Maillard, ABSOLUTE Web as allowed by law incude,
 *	but are not limited to, the working concept, function, and behavior of this plugin,
 *	the logical code structure and expression as written.
 *
 *
 * @package     WooCommerce Monetico Gateway, WooCommerce API Manager
 * @author      Nicolas Maillard, ABSOLUTE Web
 * @category    Plugin
 * @copyright   Copyright (c) 2000-2022, Nicolas Maillard ABSOLUTE Web
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Displays an inactive message if the API License Key has not yet been activated
 */
if ( get_option( 'monetico_activated' ) != 'Activated' ) {
    add_action( 'admin_notices', 'MONETICO::am_monetico_inactive_notice' );
}

class MONETICO {

	/**
	 * Self Upgrade Values
	 */
	// Base URL to the remote upgrade API Manager server. If not set then the Author URI is used.
	public $upgrade_url = 'https://www.absoluteweb.net/';

	/**
	 * @var string
	 */
	public $version = '2.4.4';

	/**
	 * @var string
	 * This version is saved after an upgrade to compare this db version to $version
	 */
	public $monetico_version_name = 'plugin_monetico_version';

	/**
	 * @var string
	 */
	public $plugin_url;

	/**
	 * @var string
	 * used to defined localization for translation, but a string literal is preferred
	 *
	 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/issues/59
	 * http://markjaquith.wordpress.com/2011/10/06/translating-wordpress-plugins-and-themes-dont-get-clever/
	 * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
	 */
	public $text_domain = 'monetico';

	/**
	 * Data defaults
	 * @var mixed
	 */
	private $monetico_software_product_id;

	public $monetico_data_key;
	public $monetico_api_key;
	public $monetico_activation_email;
	public $monetico_product_id_key;
	public $monetico_instance_key;
	public $monetico_deactivate_checkbox_key;
	public $monetico_activated_key;

	public $monetico_deactivate_checkbox;
	public $monetico_activation_tab_key;
	public $monetico_deactivation_tab_key;
	public $monetico_settings_menu_title;
	public $monetico_settings_title;
	public $monetico_menu_tab_activation_title;
	public $monetico_menu_tab_deactivation_title;

	public $monetico_options;
	public $monetico_plugin_name;
	public $monetico_product_id;
	public $monetico_renew_license_url;
	public $monetico_instance_id;
	public $monetico_domain;
	public $monetico_software_version;
	public $monetico_plugin_or_theme;

	public $monetico_update_version;

	public $monetico_update_check = 'am_monetico_plugin_update_check';

	/**
	 * Used to send any extra information.
	 * @var mixed array, object, string, etc.
	 */
	public $monetico_extra;

    /**
     * @var The single instance of the class
     */
    protected static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
        	self::$_instance = new self();
        }

        return self::$_instance;
    }

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.2
	 */
	private function __clone() {}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	public function __wakeup() {} // private = warning php 8

	public function __construct() {

		// Run the activation function
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		// Ready for translation
		//load_plugin_textdomain( $this->text_domain, false, dirname( untrailingslashit( plugin_basename( __FILE__ ) ) ) . '/lang' );

		if ( is_admin() ) {

			// Check for external connection blocking
			add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );

			/**
			 * Software Product ID is the product title string
			 * This value must be unique, and it must match the API tab for the product in WooCommerce
			 */
			$this->monetico_software_product_id = 'WooCommerce Monetico Gateway';

			/**
			 * Set all data defaults here
			 */
			$this->monetico_data_key 				= 'monetico';
			$this->monetico_api_key 					= 'api_key';
			$this->monetico_activation_email 		= 'activation_email';
			$this->monetico_product_id_key 			= 'monetico_product_id';
			$this->monetico_instance_key 			= 'monetico_instance';
			$this->monetico_deactivate_checkbox_key 	= 'monetico_deactivate_checkbox';
			$this->monetico_activated_key 			= 'monetico_activated';

			/**
			 * Set all admin menu data
			 */
			$this->monetico_deactivate_checkbox 			= 'am_deactivate_monetico_checkbox';
			$this->monetico_activation_tab_key 			= 'monetico_dashboard';
			$this->monetico_deactivation_tab_key 		= 'monetico_deactivation';
			$this->monetico_settings_menu_title 			= 'Licence Passerelle Monetico';
			$this->monetico_settings_title 				= 'Licence Passerelle Monetico';
			$this->monetico_menu_tab_activation_title 	= __( 'License Activation', 'monetico' );
			$this->monetico_menu_tab_deactivation_title 	= __( 'License Deactivation', 'monetico' );

			/**
			 * Set all software update data here
			 */
			$this->monetico_options 				= get_option( $this->monetico_data_key );
			$this->monetico_plugin_name 			= untrailingslashit( plugin_basename( __FILE__ ) ); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
			$this->monetico_product_id 			= get_option( $this->monetico_product_id_key ); // Software Title
			$this->monetico_renew_license_url 	= 'https://www.absoluteweb.net/mon-compte'; // URL to renew a license. Trailing slash in the upgrade_url is required.
			$this->monetico_instance_id 			= get_option( $this->monetico_instance_key ); // Instance ID (unique to each blog activation)
			/**
			 * Some web hosts have security policies that block the : (colon) and // (slashes) in http://,
			 * so only the host portion of the URL can be sent. For example the host portion might be
			 * www.example.com or example.com. http://www.example.com includes the scheme http,
			 * and the host www.example.com.
			 * Sending only the host also eliminates issues when a client site changes from http to https,
			 * but their activation still uses the original scheme.
			 * To send only the host, use a line like the one below:
			 *
			 * $this->monetico_domain = str_ireplace( array( 'http://', 'https://' ), '', home_url() ); // blog domain name
			 */
			$this->monetico_domain 				= str_ireplace( array( 'http://', 'https://' ), '', home_url() ); // blog domain name
			$this->monetico_software_version 	= $this->version; // The software version
			$this->monetico_plugin_or_theme 		= 'plugin'; // 'theme' or 'plugin'

			// Performs activations and deactivations of API License Keys
			require_once( plugin_dir_path( __FILE__ ) . 'am/classes/class-wc-key-api.php' );

			// Checks for software updatess
			require_once( plugin_dir_path( __FILE__ ) . 'am/classes/class-wc-plugin-update.php' );

			// Admin menu with the license key and license email form
			require_once( plugin_dir_path( __FILE__ ) . 'am/admin/class-wc-api-manager-menu.php' );

			$options = get_option( $this->monetico_data_key );

			/**
			 * Check for software updates
			 */
			if ( ! empty( $options ) && $options !== false ) {

				$this->update_check(
					$this->upgrade_url,
					$this->monetico_plugin_name,
					$this->monetico_product_id,
					$this->monetico_options[$this->monetico_api_key],
					$this->monetico_options[$this->monetico_activation_email],
					$this->monetico_renew_license_url,
					$this->monetico_instance_id,
					$this->monetico_domain,
					$this->monetico_software_version,
					$this->monetico_plugin_or_theme,
					$this->text_domain
					);

			}

		}

		/**
		 * Deletes all data if plugin deactivated
		 */
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

	}

	/** Load Shared Classes as on-demand Instances **********************************************/

	/**
	 * API Key Class.
	 *
	 * @return MONETICO_Key
	 */
	public function key() {
		return MONETICO_Key::instance();
	}

	/**
	 * Update Check Class.
	 *
	 * @return MONETICO_Update_API_Check
	 */
	public function update_check( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $text_domain, $extra = '' ) {

		return MONETICO_Update_API_Check::instance( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $text_domain, $extra );
	}

	public function plugin_url() {
		if ( isset( $this->plugin_url ) ) {
			return $this->plugin_url;
		}

		return $this->plugin_url = plugins_url( '/', __FILE__ );
	}

	/**
	 * Generate the default data arrays
	 */
	public function activation() {
		global $wpdb;

		$global_options = array(
			$this->monetico_api_key 				=> '',
			$this->monetico_activation_email 	=> '',
					);

		update_option( $this->monetico_data_key, $global_options );

		require_once( plugin_dir_path( __FILE__ ) . 'am/classes/class-wc-api-manager-passwords.php' );

		$monetico_password_management = new MONETICO_Password_Management();

		// Generate a unique installation $instance id
		$instance = $monetico_password_management->generate_password( 12, false );

		$single_options = array(
			$this->monetico_product_id_key 			=> $this->monetico_software_product_id,
			$this->monetico_instance_key 			=> $instance,
			$this->monetico_deactivate_checkbox_key 	=> 'on',
			$this->monetico_activated_key 			=> 'Deactivated',
			);

		foreach ( $single_options as $key => $value ) {
			update_option( $key, $value );
		}

		$curr_ver = get_option( $this->monetico_version_name );

		// checks if the current plugin version is lower than the version being installed
		if ( version_compare( $this->version, $curr_ver, '>' ) ) {
			// update the version
			update_option( $this->monetico_version_name, $this->version );
		}

	}

	/**
	 * Deletes all data if plugin deactivated
	 * @return void
	 */
	public function uninstall() {
		global $wpdb, $blog_id;

		$this->license_key_deactivation();

		// Remove options
		if ( is_multisite() ) {

			switch_to_blog( $blog_id );

			foreach ( array(
					$this->monetico_data_key,
					$this->monetico_product_id_key,
					$this->monetico_instance_key,
					$this->monetico_deactivate_checkbox_key,
					$this->monetico_activated_key,
					) as $option) {

					delete_option( $option );

					}

			restore_current_blog();

		} else {

			foreach ( array(
					$this->monetico_data_key,
					$this->monetico_product_id_key,
					$this->monetico_instance_key,
					$this->monetico_deactivate_checkbox_key,
					$this->monetico_activated_key
					) as $option) {

					delete_option( $option );

					}

		}

	}

	/**
	 * Deactivates the license on the API server
	 * @return void
	 */
	public function license_key_deactivation() {

		$activation_status = get_option( $this->monetico_activated_key );

		$api_email = $this->monetico_options[$this->monetico_activation_email];
		$api_key = $this->monetico_options[$this->monetico_api_key];

		$args = array(
			'email' => $api_email,
			'licence_key' => $api_key,
			);

		if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
			$this->key()->deactivate( $args ); // reset license key activation
		}
	}

    /**
     * Displays an inactive notice when the software is inactive.
     */
	public static function am_monetico_inactive_notice() { ?>
		<?php if ( ! current_user_can( 'manage_options' ) ) return; ?>
		<?php if ( isset( $_GET['page'] ) && 'monetico_dashboard' == $_GET['page'] ) return; ?>
		<div id="message" class="error">
			<p><?php printf( __( 'The Monetico Gateway API License Key has not been activated, so the plugin is inactive! %sClick here%s to activate the license key and the plugin.', 'monetico' ), '<a href="' . esc_url( admin_url( 'options-general.php?page=monetico_dashboard' ) ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Check for external blocking contstant
	 * @return string
	 */
	public function check_external_blocking() {
		// show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
		if( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

			// check if our API endpoint is in the allowed hosts
			$host = parse_url( $this->upgrade_url, PHP_URL_HOST );

			if( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
				?>
				<div class="error">
					<p><?php printf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'monetico' ), $this->monetico_software_product_id, '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>'); ?></p>
				</div>
				<?php
			}

		}
	}

} // End of class

function MONETICO() {
    return MONETICO::instance();
}

// Initialize the class instance only once
MONETICO();

/*
*
*
*/
add_action('init','woocommerce_gateway_monetico_init');
function woocommerce_gateway_monetico_init() {
    load_plugin_textdomain('monetico', false, dirname(plugin_basename(__FILE__)).'/lang');
}
function woocommerce_gateway_monetico_activation() {
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
		deactivate_plugins(plugin_basename(__FILE__));		
		$message = sprintf(__("Sorry! To use WooCommerce extension Gateway %s, you must install and activate the WooCommerce extension.", 'monetico'), 'Monetico');
		wp_die($message, __("Extension Payment Gateway Monetico", 'monetico'), array('back_link' => true));
	}
}
register_activation_hook(__FILE__, 'woocommerce_gateway_monetico_activation');

add_action('plugins_loaded', 'init_gateway_monetico', 0);

function init_gateway_monetico() {
	
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }
	
	if(!defined("PASSERELLE_MONETICO_VERSION")) define("PASSERELLE_MONETICO_VERSION", "2.4.4");
	if(!defined('__WPRootMonetico__')) define('__WPRootMonetico__',dirname(dirname(dirname(dirname(__FILE__)))));
	if(!defined('__ServerRootMonetico__')) define('__ServerRootMonetico__',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	if(!defined('CURRENT_DIR')) define('CURRENT_DIR', plugin_dir_path( __FILE__ ));
	// Constantes issues du kit Monetico
	if(!defined("MONETICOPAIEMENT_VERSION")) define("MONETICOPAIEMENT_VERSION", "3.0");
	if(!defined('MONETICOPAIEMENT_CTLHMAC')) define("MONETICOPAIEMENT_CTLHMAC","V%s.sha1.php--[CtlHmac%s%s]-%s"); // V4.0.sha1.php--[CtlHmac%s%s]-%s
	if(!defined('MONETICOPAIEMENT_CTLHMACSTR')) define("MONETICOPAIEMENT_CTLHMACSTR", "CtlHmac%s%s");
	if(!defined('MONETICOPAIEMENT_PHASE2BACK_RECEIPT')) define("MONETICOPAIEMENT_PHASE2BACK_RECEIPT","version=2\ncdr=%s");
	if(!defined('MONETICOPAIEMENT_PHASE2BACK_MACOK')) define("MONETICOPAIEMENT_PHASE2BACK_MACOK","0");
	if(!defined('MONETICOPAIEMENT_PHASE2BACK_MACNOTOK')) define("MONETICOPAIEMENT_PHASE2BACK_MACNOTOK","1\n");
	if(!defined("MONETICOPAIEMENT_URLPAYMENT")) define("MONETICOPAIEMENT_URLPAYMENT", "paiement.cgi");
	if(!defined('MONETICOPAIEMENT_URLREFUND')) define("MONETICOPAIEMENT_URLREFUND", "recredit_paiement.cgi");
	if(!defined('MONETICOPAIEMENT_ALIASCB')) define("MONETICOPAIEMENT_ALIASCB", "gestion_aliascb.cgi");

	class WC_Gateway_Monetico extends WC_Payment_Gateway {
			
		public function __construct() { 
        	$this->id = 'monetico';
			$this->order_button_text  = __( 'Proceed to Credit Card', 'monetico' ); // Payer par Carte Bancaire
			$this->method_title = 'Monetico';
			$this->method_description = __( "Accept credit card payments for merchants who have a Monetico contract (CIC, Crédit Mutuel)", 'monetico' ); // Accepter les paiements par carte bancaire pour les commerçants qui disposent d'un contrat Monetico (CIC, Crédit Mutuel)
			$this->logo = plugins_url('woocommerce-gateway-monetico/logo/monetico-paiement.png');
        	$this->has_fields = false;	
			$this->init_form_fields();
			$this->init_settings();
			$this->icon = apply_filters('woocommerce_monetico_icon', $this->settings['gateway_image']);
			$this->title = $this->settings['title'];
			$this->description =  $this->settings['description'];
			$this->supports = array('products', 'refunds');
			$this->methodes = array('monetico', 'monetico_1euro', 'monetico_3x', 'monetico_4x', 'monetico_paypal', 'monetico_lyfpay', 'monetico_x2', 'monetico_x3', 'monetico_x4');
			add_action( 'woocommerce_api_'.strtolower(get_class($this)), array( $this, 'check_monetico_response' ) );
			if ( isset($this->settings['fractionnes']) && is_array($this->settings['fractionnes']) ):
				add_action( 'woocommerce_api_wc_gateway_monetico_nx', array( $this, 'check_monetico_response' ), 9 );
			endif;
			add_action('woocommerce_receipt_monetico', array($this, 'receipt_page'));
			add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options')); 
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_filter('woocommerce_thankyou_order_received_text', array($this, 'abw_txt_erreur_paiement'), 11, 2); // 11 pour passer après les protocoles
    		add_action( 'woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
			add_filter( 'woocommerce_endpoint_order-received_title', array($this, 'new_titre_commande_recue'), 11, 2);
			add_action( 'woocommerce_email_before_order_table', array( $this, 'paiement_confirme' ), 10, 4 );
    	} 
		function init_form_fields() {
			
			$upload_dir = wp_upload_dir();
			$dir_log = $upload_dir['basedir'];
			
			$this->form_fields = array(
				'enabled' => array(
								'title' => __( "Enable/Disable", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => __( "Check to enable the payment Monetico.", 'monetico' ), 
								'default' => 'yes'
							), 
				'title' => array(
								'title' => __( "Title", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Title displayed when selecting the method of payment.", 'monetico' ), 
								'default' => __( "Credit Card", 'monetico' ),
								'css' => 'width:150px',
								'desc_tip' => true
							),
				'description' => array(
								'title' => __( "Message client", 'monetico' ), 
								'type' => 'textarea', 
								'description' => __( "Inform the customer of payment by credit card.", 'monetico' ), 
								'default' => __( "By choosing this method of payment you can make your payment on the secure server of our bank.", 'monetico' ),
								'css' => 'width:90%',
								'desc_tip' => true
							), 
				'gateway_image' => array(
								'title' => __( "Icon payment", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Url of the image displayed when selecting the method of payment.", 'monetico' ),
								'default' => plugins_url('woocommerce-gateway-monetico/logo/logo-monetico-paiement.png'),
								'css' => 'width:90%',
								'desc_tip' => true
							), 
				'monetico_mode' => array(
                                'title' => __("Mode", 'monetico'), 
                                'type' => 'select', 
                                'description' => __( "Select the mode to use Monetico. You must perform three tests before requesting passage into production to Monetico.", 'monetico' ),
                                'options' => array(
                                    'Test' => __("Test", 'monetico'),
                                    'Production' => __("Production", 'monetico')
                                ),
                                'default' => 'Test',
                                'css' => 'width:160px',
                                'desc_tip' => true,
                                'class' => 'wc-enhanced-select'
				),
				'cle' => array(
								'title' => __("Key", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Secure key.", 'monetico' ), 
								'default' => '12345678901234567890123456789012345678P0',
								'desc_tip' => true
							), 
				'tpe' => array(
								'title' => __("TPE", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Number Electronic Payment Terminal.", 'monetico' ), 
								'default' => '0000001',
								'css' => 'width:100px',
								'desc_tip' => true
							),
				'code_societe' => array(
								'title' => __("Company code", 'monetico'), 
								'type' => 'text', 
								'default' => 'abcdefghij',
								'css' => 'width:150px',
								'desc_tip' => true
							),
				'currency_code' => array(
								'title' => __("Currency", 'monetico'), 
								'type' => 'text', 
								'description' => __( "ISO 4217 compliant.", 'monetico' ), 
								'default' => 'EUR',
								'css' => 'width:40px',
								'desc_tip' => true
							), 
				'merchant_country' => array(
								'title' => __("Language", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Language of the company: FR -> France.", 'monetico' ), 
								'default' => 'FR',
								'css' => 'width:40px',
								'desc_tip' => true
							), 
				'ThreeDSecureChallenge' => array(
                                'title' => 'ThreeDSecureChallenge', 
                                'type' => 'select', 
                                'description' => __( "Wish merchant regarding the challenge 3DSecure v2.X", 'monetico' ),
                                'options' => array(
                                    'no_preference' => __("no preference", 'monetico'),
                                    'challenge_preferred' => __("desired challenge", 'monetico'),
                                    'challenge_mandated' => __("challenge required", 'monetico'),
                                    'no_challenge_requested' => __("no challenge requested", 'monetico'),
                                    'no_challenge_requested_strong_authentication' => __("no challenge required - strong customer authentication has already been carried out by the merchant.", 'monetico'),
                                    'no_challenge_requested_trusted_third_party' => __("no challenge requested - exemption request because the merchant is a beneficiary of the customer's confidence.", 'monetico'),
                                    'no_challenge_requested_risk_analysis' => __("no challenge requested - request for exemption for another reason than mentioned above (for example: small amount)", 'monetico')
                                ),
                                'default' => 'no_preference',
                                'css' => 'width:90%',
                                'desc_tip' => true,
                                'class' => 'wc-enhanced-select'
				), 
				'bouton' => array(
								'title' => __("Button", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Button text to access the server from the bank.", 'monetico' ), 
								'default' => __("Secure connection to the server of the bank", 'monetico'),
								'desc_tip' => true
							),
				'redirection' => array(
								'title' => __( "Redirection", 'monetico' ), 
								'id' => 'activer_redirection',
								'type' => 'checkbox', 
								'label' => __( "Check to enable the automatic redirection to the bank server.", 'monetico' ), 
								'default' => 'yes',
								'description' => __("Automatically disabled when debug mode is enabled.", 'monetico'),
								'desc_tip' => true
							), 
				'msg_redirection' => array(
								'title' => __( "Redirection message", 'monetico' ), 
								'id' => 'activer_message_redirection',
								'type' => 'text', 
								'default' => __("Thank you for your order. We redirect you to the server of our bank.", 'monetico'),
								'description' => __("Leave blank to not show message in lightbox.", 'monetico'),
								'desc_tip' => true,
								'css' => 'width:90%'
							),
				'paiement_express' => array(
								'title' => __( "Express Payment", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => __( "Check to enable the Express Payment.", 'monetico' ),
								'description' => __("This option must have been subscribed on your Monetico Pack+ contract.", 'monetico'), 
								'desc_tip' => true,
								'default' => 'no'
							),
				'mode_affichage_iframe' => array(
								'title' => __( "Iframe display mode", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => __( "Check to enable the Iframe display mode.", 'monetico' ),
								'description' => __("This option must have been subscribed on your Monetico Pack+ contract.", 'monetico'), 
								'desc_tip' => true,
								'default' => 'no'
							),
				'protocoles' => array(
								'title' => __("Partners Payments", 'monetico'), 
								'type' => 'multiselect', 
								'description' => __( "This field lists the Monetico partners payment methods you have subscribed to. Once you have saved the settings, you will find the payment methods in the Payments tab of WooCommerce.", 'monetico' ),
								'options' => array ("1euro"=>"1euro Cofidis", "3xcb"=>"3xcb Cofidis", "4xcb"=>"4xcb Cofidis", "paypal"=>"Paypal", "lyfpay"=>"Lyf Pay"),
								'custom_attributes' => array( 'multiple' => 'multiple' ),
								'default' => '',
								'css' => 'width:90%',
								'desc_tip' => true,
								'class' => 'wc-enhanced-select'
							),
				'fractionnes' => array(
								'title' => __("Payments in several times", 'monetico'), 
								'type' => 'multiselect', 
								'description' => __( "This field lists the splits available for payment in several times. Once the settings are saved, you will find the corresponding payment methods in the Payments tab of WooCommerce.", 'monetico' ),
								'options' => array ("2x"=>__( "Payment in 2 times", 'monetico' ), "3x"=>__( "Payment in 3 times", 'monetico' ), "4x"=>__( "Payment in 4 times", 'monetico' )),
								'custom_attributes' => array( 'multiple' => 'multiple' ),
								'default' => '',
								'css' => 'width:90%',
								'desc_tip' => true,
								'class' => 'wc-enhanced-select'
							),
				'fractionne_cle' => array(
								'title' => __("Key of the contract of payment in several times", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Secure key.", 'monetico' ), 
								'default' => '12345678901234567890123456789012345678P0',
								'desc_tip' => true
							), 
				'fractionne_tpe' => array(
								'title' => __("TPE of the contract of payment in several times", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Number Electronic Payment Terminal.", 'monetico' ), 
								'default' => '0000001',
								'css' => 'width:100px',
								'desc_tip' => true
							),
				'fractionne_code_societe' => array(
								'title' => __("Company code of the contract of payment in several times", 'monetico'), 
								'type' => 'text', 
								'default' => 'abcdefghij',
								'css' => 'width:150px',
								'desc_tip' => true
							),
				'logfile' => array(
								'title' => __("Logfile", 'monetico'), 
								'type' => 'text', 
								'description' => __( "Leave blank to not register log. The destination folder must be writable. If the file does not exist it will be created.", 'monetico' ),
								'default' => $dir_log.'/wc-logs/monetico.log',
								'css' => 'width:90%',
								'desc_tip' => true
							), 
				'debug' => array(
								'title' => __( "Debug", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => __( "Show debugging information.", 'monetico'),
								'description' => __('Do not activate production.', 'monetico' ),
								'default' => 'yes',
								'desc_tip' => true
							)						
				);
		
		}
		public function admin_options() {
			?>
            <p><img src="<?php echo $this->logo; ?>" /></p>
			<h2><?php _e("Monetico payment", 'monetico'); echo "<sup>".PASSERELLE_MONETICO_VERSION."</sup>"; if(function_exists('wc_back_link')) { wc_back_link( __("Back to payments", 'monetico'), admin_url('admin.php?page=wc-settings&tab=checkout') ); } ?></h2>
			<p><?php printf(__("Authorizes payments Monetico. This requires the signing of a sales contract with a bank distance compatible with the payment solution %sMonetico%s.", 'monetico'), '<a href="https://www.monetico-paiement.fr/" target="_blank">', '</a>'); ?></p>
            <p><?php printf(__("See our %sinstructions%s carefully to set up your payment solution Monetico.", 'monetico'), '<a href="'.plugin_dir_url( __FILE__ ).'instructions-installation-parametrages.txt" target="_blank">', '</a>'); ?></p>
			<table class="form-table">
			<?php
				$this->generate_settings_html();
				$javascript = "$('input#woocommerce_monetico_redirection').change(function() {
					if ($(this).is(':checked')) {
						$('#woocommerce_monetico_msg_redirection').closest('tr').show();
					} else {
						$('#woocommerce_monetico_msg_redirection').closest('tr').hide();
					}
				}).change();
				
				$('#woocommerce_monetico_fractionnes').change(function() {
					if($(this).val().length==0) {
						$('#woocommerce_monetico_fractionne_cle').closest('tr').hide();
						$('#woocommerce_monetico_fractionne_tpe').closest('tr').hide();
						$('#woocommerce_monetico_fractionne_code_societe').closest('tr').hide();
					} else {
						$('#woocommerce_monetico_fractionne_cle').closest('tr').show();
						$('#woocommerce_monetico_fractionne_tpe').closest('tr').show();
						$('#woocommerce_monetico_fractionne_code_societe').closest('tr').show();
					}
				}).change();
				";
				wc_enqueue_js( $javascript );
			
			echo '<tr><td colspan="2"><strong>'.__("Information about your installation:",'monetico').'</strong></td></tr>';
			echo '<tr><td>'.__("CGI2 return URL recommended",'monetico').'</td><td><pre>'.home_url().'/wc-api/wc_gateway_monetico</pre></td></tr>';
			echo '<tr><td>'.__("Historical CGI2 return URL",'monetico').'</td><td><pre>'.home_url().'/?wc-api=WC_Gateway_Monetico</pre></td></tr>';
			echo '<tr><td>'.__("Wordpress root",'monetico').'</td><td><pre>'.__WPRootMonetico__.'</pre></td></tr>';
			echo '<tr><td>'.__("Hosting root",'monetico').'</td><td><pre>'.__ServerRootMonetico__.'</pre></td></tr>';
			echo '<script>
        jQuery(document).ready(function() { 
			jQuery("#woocommerce_monetico_protocoles").select2();
			jQuery("#woocommerce_monetico_fractionnes").select2();
		});
    </script>';
			?>
			</table><!--/.form-table-->
			<?php
		}
		function payment_fields() {
			if ($this->get_description()) echo wpautop(wptexturize($this->get_description()));
		}
		public function generate_monetico_form( $order_id ) {
			global $woocommerce, $protocole_monetico, $fractionne_monetico;
			
			$order = new WC_Order( $order_id );
			
			$monetico_settings = get_option('woocommerce_monetico_settings');
			if($monetico_settings['monetico_mode']=="Test"):
				$url_serveur = "https://p.monetico-services.com/test/";
			else:
				$url_serveur = "https://p.monetico-services.com/";
			endif;
			if(!defined('MONETICOPAIEMENT_URLSERVER')) define("MONETICOPAIEMENT_URLSERVER", $url_serveur);
			
			$order_total = apply_filters( 'monetico_change_montant_a_payer', $order->get_total() ); // WC 3.0
			$montant = number_format(str_replace(",",".",$order_total),2,".","");
			// Ajout de filtres
			$monetico_settings['cle'] = apply_filters( 'monetico_change_cle', (empty($fractionne_monetico)?$monetico_settings['cle']:$monetico_settings['fractionne_cle']) );
			$monetico_settings['tpe'] = apply_filters( 'monetico_change_tpe', (empty($fractionne_monetico)?$monetico_settings['tpe']:$monetico_settings['fractionne_tpe']) );
			$monetico_settings['code_societe'] = apply_filters( 'monetico_change_code_societe', (empty($fractionne_monetico)?$monetico_settings['code_societe']:$monetico_settings['fractionne_code_societe']) );
			$monetico_settings['bouton'] = apply_filters( 'monetico_change_bouton', $monetico_settings['bouton'] );
			$monetico_settings['msg_redirection'] = apply_filters( 'monetico_change_msg_redirection', $monetico_settings['msg_redirection'] );
			if(!defined('MONETICOPAIEMENT_KEY')) define("MONETICOPAIEMENT_KEY", $monetico_settings['cle']);
			if(!defined('MONETICOPAIEMENT_EPTNUMBER')) define("MONETICOPAIEMENT_EPTNUMBER", $monetico_settings['tpe']);
			if(!defined('MONETICOPAIEMENT_COMPANYCODE')) define("MONETICOPAIEMENT_COMPANYCODE", $monetico_settings['code_societe']);
			$urlok = $urlko = $order->get_checkout_order_received_url();
			$urlko = add_query_arg('retour', 'ko', $urlko);
			// Ajout de filtres pour modifier dynamiquement les urls
			$urlok = apply_filters( 'monetico_change_url_ok', $urlok );
			$urlko = apply_filters( 'monetico_change_url_ko', $urlko );
			if(!defined('MONETICOPAIEMENT_URLOK')) define("MONETICOPAIEMENT_URLOK", $urlok);
			if(!defined('MONETICOPAIEMENT_URLKO')) define("MONETICOPAIEMENT_URLKO", $urlko);
			
			require_once(CURRENT_DIR."monetico/MoneticoPaiement_Ept.inc.php");
			
			if(!empty($fractionne_monetico)):
				$echeances = $fractionne_monetico;
              	$total_cde = $order->get_total();
              
				add_post_meta( $order_id, '_echeances_paiement_monetico', $echeances, true ) 
				|| update_post_meta( $order_id, '_echeances_paiement_monetico', $echeances );
				add_post_meta( $order_id, '_encaissements_paiement_monetico', 0, true ) 
				|| update_post_meta( $order_id, '_encaissements_paiement_monetico', 0 );

				$ech = array();
				$ech[1]["montant"] = number_format($total_cde-($echeances-1)*number_format($total_cde/$echeances, 2, ".", ""), 2, ".", "");
				$ech[1]["date"] = date('d/m/Y');
				for($i=2;$i<=$echeances;$i++):
				  $ech[$i]["montant"] = number_format($total_cde/$echeances, 2, ".", "");
				  $ech[$i]["date"]	= abw_new_anniv( date("Y-m-d"), ($i-1) );
				endfor;
			endif;
			
			$sOptions = "";
			$sReference = $order_id;
			$sMontant = $montant;
			$sDevise  = apply_filters( 'monetico_change_devise', $monetico_settings['currency_code'] );
			$sDate = date("d/m/Y:H:i:s");
			$sLangue = apply_filters( 'monetico_change_langue', $monetico_settings['merchant_country'] );
			$billing_email = $order->get_billing_email(); // WC 3.0
			$sEmail = $billing_email;
			$sTexteLibre = apply_filters( 'monetico_change_texte_libre', $billing_email, $order );
			if(isset($monetico_settings['mode_affichage_iframe'])&&$monetico_settings['mode_affichage_iframe']=='yes'):
				$mode_affichage = 'iframe';
			else:
				$mode_affichage = '';
			endif;
			if(isset($monetico_settings['paiement_express'])&&$monetico_settings['paiement_express']=='yes'):
				//$compte_client = get_post_meta( $order_id, '_customer_user', true );
				$compte_client = wp_get_current_user();
				$aliascb = ($compte_client->ID>0?'Client'.str_pad($compte_client->ID, 10, "0", STR_PAD_LEFT).md5($compte_client->user_email):'');
			else:
				$aliascb = '';
			endif;
			if(empty($fractionne_monetico)):
                $sNbrEch = "";
                $sDateEcheance1 = "";
                $sMontantEcheance1 = "";
                $sDateEcheance2 = "";
                $sMontantEcheance2 = "";
                $sDateEcheance3 = "";
                $sMontantEcheance3 = "";
                $sDateEcheance4 = "";
                $sMontantEcheance4 = "";
			else:
				$sNbrEch = $echeances;
				$sDateEcheance3=$sMontantEcheance3=$sDateEcheance4=$sMontantEcheance4='';
				foreach($ech as $key => $value):
					$dateEch = 'sDateEcheance'.$key;
					$montantEch = 'sMontantEcheance'.$key;
					${$dateEch} = $value["date"];
					${$montantEch} = $value["montant"].$sDevise;
				endforeach;
			endif;
			// Protocole
			$protocole = $protocole_monetico;
			
			// Désactivation au niveau Monetico des paiements partenaires actifs mais hors plafonds
			$desactivemoyenpaiement = ''; 
			if(isset($monetico_settings['protocoles'])&&!empty($monetico_settings['protocoles'])&&is_array($monetico_settings['protocoles'])):
				$desactive = [];
				foreach($monetico_settings['protocoles'] as $protoc):
					$protocole_settings = get_option('woocommerce_monetico_'.str_replace('cb', '', $protoc).'_settings'); // woocommerce_monetico_3x_settings, woocommerce_monetico_lyfpay_settings, etc.
					$prococole_min = (isset($protocole_settings['montant_minimum'])&&!empty($protocole_settings['montant_minimum'])?$protocole_settings['montant_minimum']:0);
					$prococole_max = (isset($protocole_settings['montant_maximum'])&&!empty($protocole_settings['montant_maximum'])?$protocole_settings['montant_maximum']:PHP_INT_MAX);
					if($order_total<$prococole_min||$order_total>$prococole_max):
						$desactive[] = $protoc;
					endif;
				endforeach;
				if(!empty($desactive)):
					$desactivemoyenpaiement = implode(',', $desactive);
				endif;
			endif;
			
			// 3DS 2
			$ThreeDSecureChallenge = apply_filters( 'monetico_change_ThreeDSecureChallenge', (isset($monetico_settings['ThreeDSecureChallenge'])?$monetico_settings['ThreeDSecureChallenge']:'no_preference') );
			$facturation_prenom = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_prenom', $order->get_billing_first_name() ) ), 0, 45);
			$facturation_nom = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_nom', $order->get_billing_last_name() ) ), 0, 45);
			$facturation_email = substr( apply_filters( 'monetico_change_facturation_email', $order->get_billing_email() ), 0, 100);
			$facturation_adresse_1 = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_adresse_1', $order->get_billing_address_1() ) ), 0, 50);
			$facturation_adresse_2 = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_adresse_2', $order->get_billing_address_2() ) ), 0, 50);
			$facturation_cp = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_cp', $order->get_billing_postcode() ) ), 0, 10);
			$facturation_ville = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_ville', $order->get_billing_city() ) ), 0, 50);
			$facturation_pays = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_facturation_pays', $order->get_billing_country() ) ), 0, 2);
			$expedition_prenom = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_prenom', $order->get_shipping_first_name() ) ), 0, 45);
			$expedition_nom = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_nom', $order->get_shipping_last_name() ) ), 0, 45);
			$expedition_adresse_1 = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_adresse_1', $order->get_shipping_address_1() ) ), 0, 50);
			$expedition_adresse_2 = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_adresse_2', $order->get_shipping_address_2() ) ), 0, 50);
			$expedition_cp = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_cp', $order->get_shipping_postcode() ) ), 0, 10);
			$expedition_ville = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_ville', $order->get_shipping_city() ) ), 0, 50);
			$expedition_pays = substr( sansCaracteresSpeciaux( apply_filters( 'monetico_change_expedition_pays', $order->get_shipping_country() ) ), 0, 2);
			$matchBillingAddress = "false";
			if($facturation_adresse_1==$expedition_adresse_1&&$facturation_adresse_2==$expedition_adresse_2&&$facturation_cp==$expedition_cp&&$facturation_ville==$expedition_ville&&
			   $facturation_pays==$expedition_pays) $matchBillingAddress = "true";
			$rawContexteCommand = "{
	\"billing\":{
		\"firstName\":\"".$facturation_prenom."\",
		\"lastName\":\"".$facturation_nom."\",
		\"email\":\"".$facturation_email."\",
		\"addressLine1\":\"".$facturation_adresse_1."\",\n";
			if($facturation_adresse_2!="")
				$rawContexteCommand .= "\t\t\"addressLine2\":\"".$facturation_adresse_2."\",\n";
			 $rawContexteCommand .= "\t\t\"city\":\"".$facturation_ville."\",
		\"postalCode\":\"".$facturation_cp."\",
		\"country\":\"".$facturation_pays."\"
		}";
			if(!empty(trim($expedition_adresse_1.$expedition_ville.$expedition_cp.$expedition_pays))): // php 5.5 minimum
				$rawContexteCommand .= ",
	\"shipping\":{
		\"firstName\":\"".$expedition_prenom."\",
		\"lastName\":\"".$expedition_nom."\",
		\"addressLine1\":\"".$expedition_adresse_1."\",\n";
				if($expedition_adresse_2!="")
				 	$rawContexteCommand .= "\t\t\"addressLine2\":\"".$expedition_adresse_2."\",\n";
				 $rawContexteCommand .= "\t\t\"city\":\"".$expedition_ville."\",
		\"postalCode\":\"".$expedition_cp."\",
		\"country\":\"".$expedition_pays."\",
		\"matchBillingAddress\":".$matchBillingAddress."
		}\n";
			endif;
			$rawContexteCommand .= "}";
			
			//$utf8ContexteCommande = utf8_encode( $rawContexteCommand );
			$sContexteCommande = base64_encode( $rawContexteCommand );
						
			$oEpt = new MoneticoPaiement_Ept($sLangue);      		
			$oHmac = new MoneticoPaiement_Hmac($oEpt);
			
			// Chaine de controle pour support
			//$CtlHmac = sprintf(MONETICOPAIEMENT_CTLHMAC, $oEpt->sVersion, $oEpt->sNumero, $oHmac->computeHmac(sprintf(MONETICOPAIEMENT_CTLHMACSTR, $oEpt->sVersion, $oEpt->sNumero)));
			
			$phase1go_fields = implode(
			  '*',
			  [
				"TPE={$oEpt->sNumero}",
				"ThreeDSecureChallenge=$ThreeDSecureChallenge",
				"aliascb=$aliascb",
				"contexte_commande=$sContexteCommande",
				"date=$sDate",
				"dateech1=$sDateEcheance1",
				"dateech2=$sDateEcheance2",
				"dateech3=$sDateEcheance3",
				"dateech4=$sDateEcheance4",
				"desactivemoyenpaiement=$desactivemoyenpaiement",
				"lgue=$sLangue",
				"mail=$sEmail",
				"mode_affichage=$mode_affichage",
				"montant=$sMontant{$sDevise}",
				"montantech1=$sMontantEcheance1",
				"montantech2=$sMontantEcheance2",
				"montantech3=$sMontantEcheance3",
				"montantech4=$sMontantEcheance4",
				"nbrech=$sNbrEch",
				"protocole=$protocole",
				"reference=$sReference",
				"societe={$oEpt->sCodeSociete}",
				"texte-libre=$sTexteLibre",
				"url_retour_err=$oEpt->sUrlKO",
				"url_retour_ok=$oEpt->sUrlOK",
				"version={$oEpt->sVersion}"
			  ]
			);

			$sMAC = $oHmac->computeHmac($phase1go_fields);
			
			if($mode_affichage == 'iframe'): 
			
				$fraction = '';
				if(!empty($fractionne_monetico)):
                	foreach($ech as $key => $value):
						$dateEch = 'sDateEcheance'.$key;
                        $montantEch = 'sMontantEcheance'.$key;
						$fraction .= '&dateech'.$key.'='.urlencode(${$dateEch}).'&montantech'.$key.'='.${$montantEch};
                    endforeach;
                endif;
                for($i=($fractionne_monetico+1);$i<=4;$i++){
					$fraction .= '&dateech'.$i.'=&montantech'.$i.'=';
                }

			?>
				<iframe id="iframePaiement" name="iframePaiement" width="100%" height="330" frameborder="0" src="<?php echo $oEpt->sUrlPaiement; ?>?version=<?php echo $oEpt->sVersion;?>&TPE=<?php echo $oEpt->sNumero;?>&date=<?php echo urlencode($sDate);?>&contexte_commande=<?php echo $sContexteCommande;?>&ThreeDSecureChallenge=<?php echo $ThreeDSecureChallenge;?>&montant=<?php echo $sMontant.$sDevise;?>&reference=<?php echo $sReference;?>&MAC=<?php echo $sMAC;?>&url_retour_ok=<?php echo urlencode($oEpt->sUrlOK);?>&url_retour_err=<?php echo urlencode($oEpt->sUrlKO);?>&lgue=<?php echo $oEpt->sLangue;?>&societe=<?php echo $oEpt->sCodeSociete;?>&texte-libre=<?php echo HtmlEncode($sTexteLibre);?>&mail=<?php echo urlencode($sEmail);?>&protocole=<?php echo $protocole;?>&desactivemoyenpaiement=<?php echo $desactivemoyenpaiement;?>&aliascb=<?php echo $aliascb;?>&mode_affichage=iframe&nbrech=<?php echo $fractionne_monetico;?><?php echo $fraction; ?>"></iframe>
			<?php
			else:
				echo '<p>'.apply_filters( 'monetico_change_bank_msg', __("Thank you for your order, please click on the button below to make payment to our bank.", "monetico")).'</p>';
              ?>
              <!-- FORMULAIRE TYPE DE PAIEMENT / PAYMENT FORM TEMPLATE -->
              <form action="<?php echo $oEpt->sUrlPaiement; ?>" method="post" id="PaymentRequest">
              <p>
                  <input type="hidden" name="version"             	id="version"        		value="<?php echo $oEpt->sVersion;?>" />
                  <input type="hidden" name="TPE"                 	id="TPE"            		value="<?php echo $oEpt->sNumero;?>" />
                  <input type="hidden" name="date"                	id="date"           		value="<?php echo $sDate;?>" />
                  <input type="hidden" name="contexte_commande"   	id="contexte_commande" 		value="<?php echo $sContexteCommande;?>" />
                  <input type="hidden" name="ThreeDSecureChallenge" id="ThreeDSecureChallenge" 	value="<?php echo $ThreeDSecureChallenge;?>" />
                  <input type="hidden" name="montant"             	id="montant"        		value="<?php echo $sMontant.$sDevise;?>" />
                  <input type="hidden" name="reference"           	id="reference"      		value="<?php echo $sReference;?>" />
                  <input type="hidden" name="MAC"                 	id="MAC"            		value="<?php echo $sMAC;?>" />
                  <input type="hidden" name="url_retour_ok"       	id="url_retour_ok"  		value="<?php echo $oEpt->sUrlOK;?>" />
                  <input type="hidden" name="url_retour_err"      	id="url_retour_err" 		value="<?php echo $oEpt->sUrlKO;?>" />
                  <input type="hidden" name="lgue"                	id="lgue"           		value="<?php echo $oEpt->sLangue;?>" />
                  <input type="hidden" name="societe"             	id="societe"        		value="<?php echo $oEpt->sCodeSociete;?>" />
                  <input type="hidden" name="texte-libre"         	id="texte-libre"    		value="<?php echo HtmlEncode($sTexteLibre);?>" />
                  <input type="hidden" name="mail"                	id="mail"           		value="<?php echo $sEmail;?>" />
                  <input type="hidden" name="protocole"             id="protocole"         		value="<?php echo $protocole;?>" />
				  <input type="hidden" name="desactivemoyenpaiement" id="desactivemoyenpaiement" value="<?php echo $desactivemoyenpaiement;?>" />
                  <input type="hidden" name="aliascb"               id="aliascb"         		value="<?php echo $aliascb;?>" />
                  <input type="hidden" name="mode_affichage"        id="mode_affichage"         value="<?php echo $mode_affichage;?>" />
                  <input type="hidden" name="nbrech"              	id="nbrech"         		value="<?php echo $fractionne_monetico;?>" />
                  <?php
                  if(!empty($fractionne_monetico)):
                      foreach($ech as $key => $value):
                          $dateEch = 'sDateEcheance'.$key;
                          $montantEch = 'sMontantEcheance'.$key;
                          echo '<input type="hidden" name="dateech'.$key.'" 		id="dateech'.$key.'" value="'.${$dateEch}.'" />'."\n";
                          echo '<input type="hidden" name="montantech'.$key.'" 	id="montantech'.$key.'" value="'.${$montantEch}.'" />'."\n";
                      endforeach;
                  endif;
                  for($i=($fractionne_monetico+1);$i<=4;$i++){
                      echo '<input type="hidden" name="dateech'.$i.'" 		id="dateech'.$i.'" value="" />'."\n";
                      echo '<input type="hidden" name="montantech'.$i.'" 		id="montantech'.$i.'" value="" />'."\n";
                  }
                  ?>
                  <input type="submit" name="bouton"              	id="bouton"         		value="<?php _e($monetico_settings['bouton'], 'monetico'); ?>" />
              </p>
              </form>
              <!-- FIN FORMULAIRE TYPE DE PAIEMENT / END PAYMENT FORM TEMPLATE -->			
  <?php		
              if($monetico_settings['redirection']=='yes'&&$monetico_settings['debug']!='yes'){
                  if(trim($monetico_settings['msg_redirection'])!="") {
                      $javascript = '
                      jQuery.blockUI({
                              message: "' . esc_js( __( $monetico_settings['msg_redirection'], 'monetico' ) ) . '",
                              baseZ: 99999,
                              overlayCSS:
                              {
                                  background: "#000",
                                  opacity: 0.75
                              },
                              css: {
                                  padding:        "20px",
                                  zindex:         "9999999",
                                  textAlign:      "center",
                                  color:          "#555",
                                  border:         "3px solid #aaa",
                                  backgroundColor:"#fff",
                                  cursor:         "wait",
                                  lineHeight:		"24px",
                              }
                          });
                      ';
                      wc_enqueue_js( $javascript );
                  }
                  wc_enqueue_js( 'jQuery("#bouton").click();' );
              }
			endif; // Iframe or not
			if($monetico_settings['debug']=='yes'){
				echo "<fieldset><legend><strong>".__("Debug mode active","monetico")."</strong>&nbsp;</legend>";
				echo "<strong>Clé :</strong> ".substr($monetico_settings['cle'],0,5).str_repeat("*",30).substr($monetico_settings['cle'],35,5)."<br/>";
				echo "<strong>URL Monetico :</strong> ".MONETICOPAIEMENT_URLSERVER."<br/>";
				echo "<strong>version :</strong> ".$oEpt->sVersion."<br/>";
				echo "<strong>TPE :</strong> ".$oEpt->sNumero."<br/>";
				echo "<strong>date :</strong> ".$sDate."<br/>";
				echo "<strong>contexte_commande :</strong> ".wordwrap($sContexteCommande, 80, "\n", true)."<br/>";
				echo "<strong>ThreeDSecureChallenge :</strong> ".$ThreeDSecureChallenge."<br/>";
				echo "<strong>montant :</strong> ".$sMontant.$sDevise."<br/>";
				echo "<strong>reference :</strong> ".$sReference."<br/>";
				echo "<strong>MAC :</strong> ".$sMAC."<br/>";
				echo "<strong>url_retour_ok :</strong> ".$oEpt->sUrlOK."<br/>";
				echo "<strong>url_retour_err :</strong> ".$oEpt->sUrlKO."<br/>";
				echo "<strong>lgue :</strong> ".$oEpt->sLangue."<br/>";
				echo "<strong>societe :</strong> ".$oEpt->sCodeSociete."<br/>";
				echo "<strong>mail :</strong> ".$sEmail."<br/>";
				echo "<strong>texte-libre :</strong> ".$sTexteLibre;
				if($aliascb!='') echo "<br/><strong>aliascb :</strong> ".$aliascb;
				if($mode_affichage!='') echo "<br/><strong>mode_affichage :</strong> ".$mode_affichage;
				if($protocole!='') echo "<br/><strong>protocole :</strong> ".$protocole;
				if($desactivemoyenpaiement!='') echo "<br/><strong>desactivemoyenpaiement :</strong> ".$desactivemoyenpaiement;
				if(!empty($fractionne_monetico)):
					echo "<br/><strong>nbrech :</strong> ".$echeances."<br/>";
					foreach($ech as $key => $value):
						$dateEch = 'sDateEcheance'.$key;
						$montantEch = 'sMontantEcheance'.$key;
						echo "<strong>dateech".$key." :</strong> ".${$dateEch}."<br/>";
						echo "<strong>montantech".$key." :</strong> ".${$montantEch}."<br/>";
					endforeach;
				endif;
				echo "</fieldset>";
			}
		}		
		function process_payment( $order_id ) {
			$order = new WC_Order( (int) $order_id );
			$redirect = $order->get_checkout_payment_url( true ); /* WC 2.1 */
			return array(
				'result' 	=> 'success',
				'redirect'	=> $redirect
			);		
		}
		function receipt_page( $order ) {
			echo $this->generate_monetico_form( $order );
		}
		function check_monetico_response() {
			global $woocommerce, $wp;
			$wc_api = strtolower($wp->query_vars['wc-api']);
			if ( isset($wc_api) && ( $wc_api == 'wc_gateway_monetico' || $wc_api == 'wc_gateway_monetico_nx') ):
				require_once(CURRENT_DIR."monetico/MoneticoPaiement_Ept.inc.php");
				$MoneticoPaiement_bruteVars = getMethode();
				if(empty($MoneticoPaiement_bruteVars))
					die(__("The CGI2 URL accesses the payment gateway but no banking data is transmitted.", 'monetico'));
				do_action('monetico_tableau_retour_banque', $MoneticoPaiement_bruteVars);
				$monetico_settings = get_option('woocommerce_monetico_settings');
				$order_id = (int) isset($MoneticoPaiement_bruteVars['reference'])?$MoneticoPaiement_bruteVars['reference']:NULL;
				set_transient('monetico_autoresponse_'.md5($order_id), 1, 300);
				$order = new WC_Order( $order_id );
				// $aRequiredConstants = array('MONETICOPAIEMENT_KEY', 'MONETICOPAIEMENT_VERSION', 'MONETICOPAIEMENT_EPTNUMBER', 'MONETICOPAIEMENT_COMPANYCODE');
				if(!defined('MONETICOPAIEMENT_KEY')) define("MONETICOPAIEMENT_KEY", apply_filters('monetico_change_cle',(empty($MoneticoPaiement_bruteVars['montantech'])?$monetico_settings['cle']:$monetico_settings['fractionne_cle'])));
				if(!defined('MONETICOPAIEMENT_EPTNUMBER')) define("MONETICOPAIEMENT_EPTNUMBER", apply_filters('monetico_change_tpe',(empty($MoneticoPaiement_bruteVars['montantech'])?$monetico_settings['tpe']:$monetico_settings['fractionne_tpe'])));
				if(!defined('MONETICOPAIEMENT_COMPANYCODE')) define("MONETICOPAIEMENT_COMPANYCODE", apply_filters('monetico_change_code_societe',(empty($MoneticoPaiement_bruteVars['montantech'])?$monetico_settings['code_societe']:$monetico_settings['fractionne_code_societe'])));
				if(!defined('MONETICOPAIEMENT_URLSERVER')) define("MONETICOPAIEMENT_URLSERVER", "");
				if(!defined('MONETICOPAIEMENT_URLOK')) define("MONETICOPAIEMENT_URLOK", "");
				if(!defined('MONETICOPAIEMENT_URLKO')) define("MONETICOPAIEMENT_URLKO", "");

				$oEpt = new MoneticoPaiement_Ept();
				$oHmac = new MoneticoPaiement_Hmac($oEpt);
			
				$MAC_source = computeHmacSource($MoneticoPaiement_bruteVars, $oEpt);
				$computed_MAC = $oHmac->computeHmac($MAC_source);
				$congruent_MAC = array_key_exists('MAC', $MoneticoPaiement_bruteVars) && $computed_MAC == strtolower($MoneticoPaiement_bruteVars['MAC']);

				$logfile = $monetico_settings['logfile'];
				if($logfile!="")
					$fp=@fopen($logfile, "a");
				
				if ($congruent_MAC) {
					if(!isset($MoneticoPaiement_bruteVars['motifrefus'])) $MoneticoPaiement_bruteVars['motifrefus']='';
					$statuts = array('processing', 'completed');
					$order_status = $order->get_status(); // WC 3.0
					
					if(!empty($MoneticoPaiement_bruteVars['montantech'])):
						$echeances = get_post_meta( $order_id, '_echeances_paiement_monetico', true );
						$encaissements = get_post_meta( $order_id, '_encaissements_paiement_monetico', true );
						if($encaissements=='') $encaissements = 0;
						$encaissements++;
						add_post_meta( $order_id, '_encaissements_paiement_monetico', $encaissements, true ) 
						|| update_post_meta( $order_id, '_encaissements_paiement_monetico', $encaissements );
	/// 50.50EUR -> 50.50
						$mtEch = substr($MoneticoPaiement_bruteVars['montantech'], 0, -3);
						$prix = wc_price($mtEch);
					endif;
										
					switch($MoneticoPaiement_bruteVars['code-retour']) {
						case "Annulation" :
							switch($MoneticoPaiement_bruteVars['motifrefus']) {
								case "Appel Phonie" : $msg_err = __("The customer's bank requests additional information.","monetico"); break;
								case "Refus" :
								case "Interdit" : $msg_err = __("The customer's bank refuses to grant permission.","monetico"); break;
								case "Filtrage" : $msg_err = __("The payment request was blocked by the filter settings that the merchant has implemented in its Fraud Prevention Module.","monetico");
									$filtrage = array(1=>__("IP address","monetico"), 2=>__("Card number","monetico"), 3=>__("Card BIN","monetico"), 4=>__("Country card","monetico"), 5=>__("Country IP","monetico"), 6=>__("Consistency country / card countries IP","monetico"), 7=>__("Disposable email","monetico"), 8=>__("Limitation amount for a CB over time","monetico"), 9=>__("Limitation of number of transactions for a CB over time","monetico"), 11=>__("Limitation of number of transactions over a period alias","monetico"), 12=>__("Limitation on amount per alias over time","monetico"), 13=>__("Limitation amount by IP over time","monetico"), 14=>__("Limitation of number of transactions by IP over time","monetico"), 15=>__("Testers cards","monetico"), 16=>__("Limitation on number of aliases by CB","monetico"));
									$filtres = explode("-", $MoneticoPaiement_bruteVars['filtragecause']);
									$filtres_valeur = explode("-", $MoneticoPaiement_bruteVars['filtragevaleur']); // Valeur ayant déclenché le filtrage, même ordre que la cause
									foreach($filtres as $key => $filtre) {
										if(trim($filtre)!=""&&is_numeric($filtre)) {
											$liste.= $filtrage[trim($filtre)]." (".$filtres_valeur[$key]."), ";
										}
									}
									if($liste!="") {
										$liste = substr($liste, 0, -2);
										$msg_err .= " ".__("Cause of filtering:","monetico")." ".$liste.".";
									}
								break;
								case "3DSecure" : $msg_err = __("3D Secure authentication negatively received from the holder's bank.","monetico"); break;
								default : $msg_err = __("Unknown error","monetico");
							}
							$order->update_status('failed');
							
							$order->add_order_note(__("Credit Card Payment: FAIL<br/>Error:",'monetico').' '.$msg_err);
							$payer_url = $order->get_checkout_payment_url();
							$order->add_order_note(sprintf(__("Failure of payment by credit card for your order, <a href=\"%s\">click here</a> to retry payment.", "monetico"), $payer_url),1); /* WC 2.1 */
							break;
				
						case "payetest":
						case "paiement":
							if ( !in_array($order_status, apply_filters( 'monetico_change_liste_statuts_ok', $statuts)) ) {
								if(!empty($MoneticoPaiement_bruteVars['montantech'])):
                                    add_post_meta( $order_id, '_encaissement_initial_monetico', $mtEch, true ) 
                                    || update_post_meta( $order_id, '_encaissement_initial_monetico', $mtEch );
                                endif;
								$authentification = json_decode(base64_decode($MoneticoPaiement_bruteVars['authentification']), true);
								$protocol = $authentification['protocol'];
								$version = $authentification['version'];
								switch($authentification['status']):
									case "authenticated": $msg_3ds = sprintf(__("%s authentication is successful.", 'monetico'), $protocol.' '.$version); break;
									case "authentication_not_performed": $msg_3ds = sprintf(__("The %s authentication could not be completed (technical problem or other).", 'monetico'), $protocol.' '.$version); break;
									case "not_authenticated": $msg_3ds = sprintf(__("%s authentication failed.", 'monetico'), $protocol.' '.$version); break;
									case "authentication_rejected": $msg_3ds = sprintf(__("%s authentication has been denied by the issuer.", 'monetico'), $protocol.' '.$version); break;
									case "authentication_attempted": $msg_3ds = sprintf(__("An %s authentication attempt has been made. The authentication could not be done but a proof was generated (CAVV).", 'monetico'), $protocol.' '.$version); break;
									case "not_enrolled": $msg_3ds = sprintf(__("The card is not enrolled in %s.", 'monetico'), $protocol.' '.$version); break;
									case "disabled": $msg_3ds = sprintf(__("3DSecure has been disengaged.", 'monetico'), $protocol.' '.$version); // 3DS1
										if(!empty($authentification['details']['disablingReason'])):
											switch($authentification['details']['disablingReason']):
												case "commercant": $msg_3ds .= " ".__("Explicit disengaging.", 'monetico'); break;
												case "seuilnonatteint": $msg_3ds .= " ".__("Disengagement because the amount of the transaction does not reach the configured amount.", 'monetico'); break;
												case "scoring": $msg_3ds .= " ".__("Disengagement on scoring pattern.", 'monetico'); break;
											endswitch;
										endif;
										break;
									default : $msg_3ds = "";
								endswitch;
								if(!empty($authentification['details']['status3ds'])): // 3DS1 uniquement
									switch($authentification['details']['status3ds']):
										case "-1": $msg_3ds .= __("The transaction was not done according to the 3DSecure protocol and the risk of unpaid is high.", 'monetico'); break;
										case "1": $msg_3ds .= __("The transaction was made according to 3DS protocol and the risk level is", 'monetico')." ".__("low.", 'monetico'); break;
										case "4": $msg_3ds .= __("The transaction was made according to 3DS protocol and the risk level is", 'monetico')." ".__("high.", 'monetico'); break;
									endswitch;
								endif;
								$msg_3ds = " ".$msg_3ds;
								if(empty($MoneticoPaiement_bruteVars['montantech'])):
									$order->add_order_note(trim(__("Credit card payment confirmed.",'monetico').$msg_3ds));
								else:
									$order->add_order_note(sprintf(trim(__("Credit card payment %d of %d from %s confirmed.",'monetico').$msg_3ds), $encaissements, $echeances, $prix));
								endif;
								$order->payment_complete($MoneticoPaiement_bruteVars['numauto']);
								$woocommerce->cart->empty_cart();
							}	
							break;
				
						case "paiement_pf2":
						case "paiement_pf3":
						case "paiement_pf4":
							if ( !in_array($order_status, apply_filters( 'monetico_change_liste_statuts_ok', $statuts)) ) {
								$order->add_order_note(sprintf(__("Credit card payment %d of %d from %s confirmed.",'monetico'), $encaissements, $echeances, $prix),1);
							}
							break;
						case "Annulation_pf2":
						case "Annulation_pf3":
						case "Annulation_pf4":
							$msg_err = motif_refus($MoneticoPaiement_bruteVars['motifrefus']);
							if ( !in_array($order_status, apply_filters( 'monetico_change_liste_statuts_ok', $statuts)) ) {
								$order->add_order_note(sprintf(__("Failure of payment by Credit Card %d of %d from %s. Error:",'monetico'), $encaissements, $echeances, $prix).' '.$msg_err,1);
							}
							break;
					}
				
					$receipt = MONETICOPAIEMENT_PHASE2BACK_MACOK;
					
					if($fp) {
						foreach($MoneticoPaiement_bruteVars as $key => $value)
							fwrite($fp, $key." : ".$value."\n");
						fwrite( $fp, "-------------------------------------------\n");
					}
				
				} else {
					$receipt = MONETICOPAIEMENT_PHASE2BACK_MACNOTOK.$computed_MAC."\n$MAC_source";
					if($fp)
						fwrite($fp, date("d/m/Y H:i:s")." : ".utf8_decode(__("Payment problem, HMAC does not match","monetico"))."\n".$computed_MAC."\n".$MAC_source."\n-------------------------------------------\n");
				}
				if($fp)
					@fclose($fp);
				printf (MONETICOPAIEMENT_PHASE2BACK_RECEIPT, $receipt);
				die();
			endif;
		}
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			global $wpdb;
			$order = wc_get_order( $order_id );
			if ( ! ($order && $order->get_transaction_id()) ) {
				return false;
			}
			$monetico_settings = get_option('woocommerce_monetico_settings');
			$devise = $monetico_settings['currency_code'];
			$montant_recredit = number_format(str_replace(",", ".", $amount), 2, ".", "").$devise;
			$montant = number_format(str_replace(",", ".", $order->get_total()), 2, ".", "").$devise;
			
			$sql = "SELECT m.meta_value AS refund
    				FROM {$wpdb->prefix}postmeta AS m
    				LEFT JOIN {$wpdb->prefix}posts AS p ON ( p.ID = m.post_id )
    				WHERE m.meta_key LIKE '_refund_amount'
    				AND p.post_parent = ".$order_id;
			$refunds = $wpdb->get_results( $sql );
			
			foreach ($refunds as $refund) {
 				$total_refunds += $refund->refund;
			}
			$total_refunds = $total_refunds-$amount;
			$montant_possible = number_format(str_replace(",", ".", $order->get_total()-$total_refunds ), 2, ".", "").$devise;
			
			if($monetico_settings['monetico_mode']=="Test"):
				$url_serveur = "https://payment-api.e-i.com/test/";
			else:
				$url_serveur = "https://payment-api.e-i.com/";
			endif;
			if(!defined('MONETICOPAIEMENT_URLSERVER')) define("MONETICOPAIEMENT_URLSERVER", $url_serveur);
			$payment_method = $order->get_payment_method(); // WC 3.0
			if($payment_method=='monetico_x2'||$payment_method=='monetico_x3'||$payment_method=='monetico_x4'):
				$monetico_settings['cle'] = $monetico_settings['fractionne_cle'];
				$monetico_settings['tpe'] = $monetico_settings['fractionne_tpe'];
				$monetico_settings['code_societe'] = $monetico_settings['fractionne_code_societe'];
			endif;
			// Ne gère pas les filtres
			if(!defined('MONETICOPAIEMENT_KEY')) define("MONETICOPAIEMENT_KEY", $monetico_settings['cle']);
			if(!defined('MONETICOPAIEMENT_EPTNUMBER')) define("MONETICOPAIEMENT_EPTNUMBER", $monetico_settings['tpe']);
			if(!defined('MONETICOPAIEMENT_COMPANYCODE')) define("MONETICOPAIEMENT_COMPANYCODE", $monetico_settings['code_societe']);
			if(!defined('MONETICOPAIEMENT_URLOK')) define("MONETICOPAIEMENT_URLOK", "");
			if(!defined('MONETICOPAIEMENT_URLKO')) define("MONETICOPAIEMENT_URLKO", "");
			
			require_once(CURRENT_DIR."monetico/MoneticoPaiement_Ept.inc.php");
			
			$sDate = date("d/m/Y:H:i:s");
			$sLangue = $monetico_settings['merchant_country'];
			$sReference = $order_id;
			$oEpt = new MoneticoPaiement_Ept($sLangue);      		
			$oHmac = new MoneticoPaiement_Hmac($oEpt);      	        
			
			$date_cde = $order->get_date_paid(); // WC 3.0
			
			$args = array(
              'body' => array( 
                  'TPE' => $monetico_settings['tpe'],
                  'date' => $sDate,
                  'date_commande' => date("d/m/Y", strtotime( $date_cde )),
                  'date_remise' => date("d/m/Y", strtotime( $date_cde )),
                  'lgue' => $oEpt->sLangue,
                  'montant' => $montant,
                  'montant_possible' => $montant_possible,
                  'montant_recredit' => $montant_recredit,
                  'num_autorisation' => $order->get_transaction_id(),
                  'reference' => $sReference,
                  'societe' => $oEpt->sCodeSociete,
                  'version' => MONETICOPAIEMENT_VERSION,
                   )
              );
			$source = $args['body'];
			array_walk($source, function(&$a, $b) { $a = "$b=$a"; });
    		$refund_fields = implode( '*', $source);
			$sMAC = $oHmac->computeHmac($refund_fields);
			$args['body']['MAC'] = $sMAC;
			
			$response = wp_remote_post( MONETICOPAIEMENT_URLSERVER.MONETICOPAIEMENT_URLREFUND, $args );
			
			if ( is_wp_error( $response ) ):
			   return $response;
			else:
				$body = explode("\n", $response['body']);
				if($body[2]!="cdr=0"):
					return new WP_Error( 'erreur_monetico', __("Error:", 'monetico')." ".str_replace("lib=", "", $body[3]) );	
				else:
					if(!empty($reason)) $more = " (".$reason.")"; else $more = "";
					$order->add_order_note( sprintf( __( 'Refunded %s - Autorisation ID: %s', 'monetico' ).$more, wc_price($amount), str_replace("aut=", "", $body[4])), 1);
					if($montant_possible==$montant_recredit):
						$order->update_status( 'refunded', '' );
					endif;
					return true;	
				endif;
			endif;
  			return false;
		}
		
		function paiement_confirme($order, $sent_to_admin, $plain_text, $email) {
			$payment_method = $order->get_payment_method(); // WC 3.0
			if ( $email->id == 'customer_processing_order' ) { // customer_completed_order
				switch($payment_method):
                  case 'monetico_x2':
                  case 'monetico_x3':
                  case 'monetico_x4':
                      $order_id = $order->get_id();
                      $echeances = get_post_meta( $order_id, '_echeances_paiement_monetico', true );
                      $encaissements = get_post_meta( $order_id, '_encaissements_paiement_monetico', true );
                      $initial = get_post_meta( $order_id, '_encaissement_initial_monetico', true );
                      $initial = wc_price($initial);
                      echo '<p>'.sprintf(__("Credit card payment %d of %d from %s confirmed.",'monetico'), $encaissements, $echeances, $initial).'</p>';  
                      break;
                  case 'monetico':
                      echo '<p>'.__("Credit card payment confirmed.",'monetico').'</p>'; break;
                  case 'monetico_1euro':
                      echo '<p>'.__("1euro payment confirmed.",'monetico').'</p>'; break;
                  case 'monetico_3x':
                      echo '<p>'.__("Cofidis 3xCB payment confirmed.",'monetico').'</p>'; break;
                  case 'monetico_4x':
                      echo '<p>'.__("Cofidis 4xCB payment confirmed.",'monetico').'</p>'; break;
                  case 'monetico_paypal':
                      echo '<p>'.__("Paypal payment confirmed.",'monetico').'</p>'; break;
                  case 'monetico_lyfpay':
                      echo '<p>'.__("lyfpay payment confirmed.",'monetico').'</p>'; break;
				endswitch;
			}
		}

		function new_titre_commande_recue($titre, $terminaison) {
			global $wp;
			$order_id = (int) $wp->query_vars['order-received'];
			$order = new WC_Order( $order_id );
			$payment_method = $order->get_payment_method(); // WC 3.0
			$order_status = $order->get_status(); // WC 3.0
			if ( in_array($payment_method, $this->methodes) && ($order_status == 'pending' || $order_status == 'cancelled')) {
				$autoresponse = get_transient('monetico_autoresponse_'.md5($order->get_id()));
				if( false === $autoresponse ): // Pas de transient suite à l'autoresponse, on ne peut pas affirmer que c'est un échec
					$retour = isset($_GET['retour']) ? $_GET['retour'] : NULL;
					if($retour==='ko'):
						return __("Payment not received", "monetico"); // Paiement non reçu
					else:
						return __("Payment pending confirmation", 'monetico'); // Paiement en attente de confirmation
					endif;
				else:
					return __("Payment error!", "monetico");
				endif;
			} else {
				return $titre;
			}
		}
		
		function abw_txt_erreur_paiement($texte, $order) {
			$payment_method = $order->get_payment_method(); // WC 3.0
			$order_status = $order->get_status(); // WC 3.0
			if ( in_array($payment_method, $this->methodes) && ($order_status == 'pending'||$order_status == 'cancelled')) {
				$autoresponse = get_transient('monetico_autoresponse_'.md5($order->get_id()));
				if( false === $autoresponse ): // Pas de transient suite à l'autoresponse, on ne peut pas affirmer que c'est un échec
					$retour = isset($_GET['retour']) ? $_GET['retour'] : NULL;
					if($retour==='ko'):
						$payer_url = $order->get_checkout_payment_url(); // WC 2.1
						return sprintf("<p>".__("Failure of payment by credit card for your order, <a href=\"%s\">click here</a> to retry payment.", "monetico")."</p>", $payer_url);
					else:
						return __("Your order is pending confirmation of payment.", 'monetico'); // Votre commande est en attente de confirmation de paiement.
					endif;
				else:
					return __("Payment error! Your order is not confirmed.", "monetico");
				endif;
			} else {
				return $texte;
			}
		}
	
		function thankyou_page() {
			global $wp;
			$order_id = (int) $wp->query_vars['order-received'];
			$order = new WC_Order( $order_id );
			$payment_method = $order->get_payment_method(); // WC 3.0
			$statuts = array('processing', 'completed');
			$order_status = $order->get_status(); // WC 3.0
			if ( in_array($order_status, apply_filters( 'monetico_change_liste_statuts_ok', $statuts)) ) {
				$url_commande = $order->get_view_order_url();
				$order_total = $order->get_total(); // WC 3.0
				$montant_commande = apply_filters( 'monetico_change_montant_paye', wc_price($order_total));
				$compte_client = get_post_meta( $order_id, '_customer_user', true );
				switch($payment_method):
                  case 'monetico_x2':
                  case 'monetico_x3':
                  case 'monetico_x4':
					$initial = get_post_meta( $order_id, '_encaissement_initial_monetico', true );
					$initial = wc_price($initial);
					printf("<p>".__("Your credit card payment of %s of a total of %s has been finalized with our bank", "monetico"), $initial, $montant_commande);
					break;
				default:
					printf("<p>".__("Your credit card payment of %s has been finalized with our bank", "monetico"), $montant_commande);
				endswitch;
				if($compte_client>0):
					printf(__(", <a href=\"%s\">click here</a> to view your order.", "monetico")."</p>", $url_commande);
				else:
					echo ".</p>";
				endif;
			} elseif($order_status != 'failed') {
				$autoresponse = get_transient('monetico_autoresponse_'.md5($order_id));
				if( false === $autoresponse ): // Pas de transient suite à l'autoresponse, on ne peut pas affirmer que c'est un échec
					$retour = isset($_GET['retour']) ? $_GET['retour'] : NULL;
					if($retour!=='ko'):
						echo "<p>".__("The bank has not yet confirmed the payment of your order. You can try to refresh the page, if the payment is not confirmed, please contact us.", 'monetico')."</p>";
					endif;
				else:
					$payer_url = $order->get_checkout_payment_url();
					printf("<p>".__("Failure of payment by credit card for your order, <a href=\"%s\">click here</a> to retry payment.", "monetico")."</p>", $payer_url); /* WC 2.1 */
				endif;
			}
		}
	}
	$monetico_settings = get_option('woocommerce_monetico_settings');
	if ( isset($monetico_settings['protocoles']) && is_array($monetico_settings['protocoles']) ):
		if (! class_exists('WC_Gateway_Monetico3xCofidis')) {
			if( in_array('3xcb', $monetico_settings['protocoles']) ) {
        		require_once 'partenaires/class-wc-gateway-monetico-3xcofidis.php';
			}
    	}
		if (! class_exists('WC_Gateway_Monetico4xCofidis')) {
			if( in_array('4xcb', $monetico_settings['protocoles']) ) {
				require_once 'partenaires/class-wc-gateway-monetico-4xcofidis.php';
			}
		}
		if (! class_exists('WC_Gateway_Monetico1euro')) {
			if( in_array('1euro', $monetico_settings['protocoles']) ) {
				require_once 'partenaires/class-wc-gateway-monetico-1euro.php';
			}
		}
		if (! class_exists('WC_Gateway_MoneticoPaypal')) {
			if( in_array('paypal', $monetico_settings['protocoles']) ) {
				require_once 'partenaires/class-wc-gateway-monetico-paypal.php';
			}
		}
		if (! class_exists('WC_Gateway_MoneticoLyfpay')) {
			if( in_array('lyfpay', $monetico_settings['protocoles']) ) {
				require_once 'partenaires/class-wc-gateway-monetico-lyfpay.php';
			}
		}
	endif;
	if ( isset($monetico_settings['fractionnes']) && is_array($monetico_settings['fractionnes']) ):
		if (! class_exists('WC_Gateway_Monetico_2x')) {
			if( in_array('2x', $monetico_settings['fractionnes']) ) {
        		require_once 'fractionnement/class-wc-gateway-monetico-2x.php';
			}
    	}
		if (! class_exists('WC_Gateway_Monetico_3x')) {
			if( in_array('3x', $monetico_settings['fractionnes']) ) {
				require_once 'fractionnement/class-wc-gateway-monetico-3x.php';
			}
		}
		if (! class_exists('WC_Gateway_Monetico_4x')) {
			if( in_array('4x', $monetico_settings['fractionnes']) ) {
				require_once 'fractionnement/class-wc-gateway-monetico-4x.php';
			}
		}
	endif;
	
	function add_monetico_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Monetico';
		$monetico_settings = get_option('woocommerce_monetico_settings');
		if ( isset($monetico_settings['protocoles']) && is_array($monetico_settings['protocoles']) ):
			if( in_array('3xcb', $monetico_settings['protocoles']) ) {
				$methods[] = 'WC_Gateway_Monetico3xCofidis';
			}
			if( in_array('4xcb', $monetico_settings['protocoles']) ) {
				$methods[] = 'WC_Gateway_Monetico4xCofidis';
			}
			if( in_array('1euro', $monetico_settings['protocoles']) ) {
				$methods[] = 'WC_Gateway_Monetico1euro';
			}
			if( in_array('paypal', $monetico_settings['protocoles']) ) {
				$methods[] = 'WC_Gateway_MoneticoPaypal';
			}
			if( in_array('lyfpay', $monetico_settings['protocoles']) ) {
				$methods[] = 'WC_Gateway_MoneticoLyfpay';
			}	
		endif;
		if ( isset($monetico_settings['fractionnes']) && is_array($monetico_settings['fractionnes']) ):
			if( in_array('2x', $monetico_settings['fractionnes']) ) {
				$methods[] = 'WC_Gateway_Monetico_x2';
			}
			if( in_array('3x', $monetico_settings['fractionnes']) ) {
				$methods[] = 'WC_Gateway_Monetico_x3';
			}
			if( in_array('4x', $monetico_settings['fractionnes']) ) {
				$methods[] = 'WC_Gateway_Monetico_x4';
			}	
		endif;
		return $methods;
	}
	add_filter('woocommerce_payment_gateways', 'add_monetico_gateway', 11 ); // 11 pour éviter une fatale erreur avec WC Multilingual

}
if(!function_exists('sansCaracteresSpeciaux')):
	function sansCaracteresSpeciaux($str0) {
		$str0 = preg_replace('#[^[:alnum:]]#u', ' ', $str0);
		$str0 = trim($str0);
		$str0 = str_replace('###antiSlashe###t', ' ', $str0); // tabulations
		$str0 = preg_replace('!\s+!', ' ', $str0); // espaces multiples
		return $str0;
	}
endif;
if(!function_exists('computeHmacSource')):
	function computeHmacSource($source, $oEpt) {
		$anomalies = null;
		if( array_key_exists('TPE', $source) )
			$anomalies = $source["TPE"] != $oEpt->sNumero ? ":TPE" : null;
		if( array_key_exists('version', $source) )
			$anomalies .= $source["version"] == $oEpt->sVersion ? ":version" : null;
		if( array_key_exists('MAC', $source) )
			unset($source['MAC']);
		else
			$anomalies .= ":MAC";
		if($anomalies != null)
			return "anomaly_detected" . $anomalies;
		ksort($source);
		array_walk($source, function(&$a, $b) { $a = "$b=$a"; });
		return implode( '*', $source);
	}
endif;
if(!function_exists('abw_new_anniv')):
	function abw_new_anniv( $date, $echeance ) { // Calcul les dates pour une et une seule échéance par mois
		$date               = strtotime( $date );
		$parse_date         = getdate( $date );
		$new_month          = (12+1-$echeance) == $parse_date['mon'] ? 1 : $parse_date['mon'] + $echeance;
		$new_year           = 1 == $new_month ? $parse_date['year'] + 1 : $parse_date['year'];
		$num_days_new_month = date( 't', mktime( 0, 0, 0, $new_month, 01, $new_year ) );
		$new_day            = $parse_date['mday'] > $num_days_new_month ? $num_days_new_month : $parse_date['mday'];
		$new_anniversary    = mktime( 0, 0, 0, $new_month, $new_day, $new_year );
		return date( 'd/m/Y', $new_anniversary );
	}
endif;
function woocommerce_gateway_monetico_add_link($links, $file) {
	$reglages_url = 'admin.php?page=wc-settings&tab=checkout&section=monetico';
	$links[] = '<a href="'.admin_url($reglages_url).'">' . __('Settings','monetico') .'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'woocommerce_gateway_monetico_add_link',  10, 2);

function abw_set_urlserver($mode){
	if($mode=="Test")
		return "https://p.monetico-services.com/test/";
	return "https://p.monetico-services.com/";
}

/* Paiement Express */
$monetico_settings = get_option('woocommerce_monetico_settings');
if(isset($monetico_settings['paiement_express']) && $monetico_settings['paiement_express']=='yes'):
	add_action( 'init', 'abw_add_paiement_express_endpoint' );
	function abw_add_paiement_express_endpoint() {
		add_rewrite_endpoint( 'paiement-express', EP_ROOT | EP_PAGES );
		flush_rewrite_rules();
	}
	add_filter( 'query_vars', 'abw_paiement_express_query_vars', 0 );
	function abw_paiement_express_query_vars( $vars ) {
		$vars[] = 'paiement-express';
		return $vars;
	}
	add_filter( 'woocommerce_account_menu_items', 'abw_add_paiement_express_link_my_account' );
	function abw_add_paiement_express_link_my_account( $items ) {
		$items['paiement-express'] = __('Express Payment', 'monetico');
		return $items;
	}
	add_action( 'woocommerce_account_paiement-express_endpoint', 'abw_paiement_express_content' );
	function abw_paiement_express_content() {

		echo '<h3>'.__('Express Payment', 'monetico').'</h3><p>'.__("Find here the bank cards registered with Monetico to simplify your future payments on our site.", 'monetico').'</p><p id="abw_retour_express"></p>';

		$monetico_settings = get_option('woocommerce_monetico_settings');
		$url_serveur = abw_set_urlserver($monetico_settings['monetico_mode']);
		if(!defined('MONETICOPAIEMENT_URLSERVER')) define("MONETICOPAIEMENT_URLSERVER", $url_serveur);
		if(!defined('MONETICOPAIEMENT_URLOK')) define("MONETICOPAIEMENT_URLOK", "");
		if(!defined('MONETICOPAIEMENT_URLKO')) define("MONETICOPAIEMENT_URLKO", "");
		if(!defined('MONETICOPAIEMENT_KEY')) define("MONETICOPAIEMENT_KEY", $monetico_settings['cle']);
		if(!defined('MONETICOPAIEMENT_EPTNUMBER')) define("MONETICOPAIEMENT_EPTNUMBER", $monetico_settings['tpe']);
		if(!defined('MONETICOPAIEMENT_COMPANYCODE')) define("MONETICOPAIEMENT_COMPANYCODE", $monetico_settings['code_societe']);

		require_once(CURRENT_DIR."monetico/MoneticoPaiement_Ept.inc.php");

		$sLangue = $monetico_settings['merchant_country'];
		$oEpt = new MoneticoPaiement_Ept($sLangue);      		
		$oHmac = new MoneticoPaiement_Hmac($oEpt);  

		$current_user = wp_get_current_user();
		$aliascb = 'Client'.str_pad($current_user->ID, 10, "0", STR_PAD_LEFT).md5($current_user->user_email);
		$args = array(
		  'body' => array( 
			  'version' => MONETICOPAIEMENT_VERSION,
			  'tpe' => $monetico_settings['tpe'],
			  'aliascb' => $aliascb,
			  'action' => 'lister_cartes',
			  'identifiant_carte' => ''
			   )
		  );
		$source = $args['body'];
		$liste_fields = implode( '*', $source).'*';
		$sMAC = $oHmac->computeHmac($liste_fields);
		$args['body']['mac'] = $sMAC;

		$response = wp_remote_post( MONETICOPAIEMENT_URLSERVER.MONETICOPAIEMENT_ALIASCB, $args );

		if ( is_wp_error( $response ) ):
		   echo "<pre>".print_r($response, true)."</pre>";
		else:
			$body = explode("\n", $response['body']);
			$body = json_decode($body[0]);
			if($body->cdr!=0):
				echo abw_erreur_paiement_express($body->cdr);
			else:
				$defaultCard = $body->defaultCard;
				echo '<table><thead><tr><th>'.__("Name", 'monetico').'</th><th>'.__("Card", 'monetico').'</th><th>'.__("Expiration", 'monetico').'</th><th>'.__("Expired", 'monetico').'</th><th>'.__("Default", 'monetico').'</th><th>'.__("Actions", 'monetico').'</th></tr></thead><tbody>';
				foreach($body->cards as $key => $value):
					echo '<tr><td>'.$value->name.'</td><td>'.$value->hiddenNumber.'</td><td>'.$value->expMonth.'/'.$value->expYear.'</td><td>'.($value->isExpired?__("yes", 'monetico'):__("no", 'monetico')).'</td><td>'.($value->id==$defaultCard?__("yes", 'monetico'):'').'</td><td>';
					$sMAC = $oHmac->computeHmac(MONETICOPAIEMENT_VERSION.'*'.$monetico_settings['tpe'].'*'.$aliascb.'*desactiver_carte*'.$value->id.'*');
					echo abw_get_form_express('desactiver_carte', MONETICOPAIEMENT_URLSERVER.MONETICOPAIEMENT_ALIASCB, MONETICOPAIEMENT_VERSION, $monetico_settings['tpe'], $aliascb, $value->id, $sMAC, __("delete",'monetico'), false);
					$display_none = false;
					if($value->id==$defaultCard):
						$display_none = true;
					endif;
						$sMAC = $oHmac->computeHmac(MONETICOPAIEMENT_VERSION.'*'.$monetico_settings['tpe'].'*'.$aliascb.'*positionner_carte_defaut*'.$value->id.'*');
						echo abw_get_form_express('positionner_carte_defaut', MONETICOPAIEMENT_URLSERVER.MONETICOPAIEMENT_ALIASCB, MONETICOPAIEMENT_VERSION, $monetico_settings['tpe'], $aliascb, $value->id, $sMAC, __("default",'monetico'), $display_none);
					echo '</td></tr>';
				endforeach;
				echo '</tbody></table>';
			endif;
		endif;
	}
	function abw_get_form_express($action, $url, $version, $tpe, $alias, $carte, $mac, $bouton, $display_none) {
		if($display_none) $style = ' style="display:none;"'; else $style='';
		return '<form method="post" name="GestionPortfeuilleFormulaire" id="'.$action.'_'.$carte.'" target="_top" action="'.$url.'"'.$style.'>
					<input type="hidden" class="version" name="version" value="'.$version.'">
					<input type="hidden" class="tpe" name="tpe" value="'.$tpe.'">
					<input type="hidden" class="action" name="action" value="'.$action.'">
					<input type="hidden" class="aliascb" name="aliascb" value="'.$alias.'">
					<input type="hidden" class="identifiant_carte" name="identifiant_carte" value="'.$carte.'">
					<input type="hidden" class="mac" name="mac" value="'.$mac.'">
					<input type="submit" value="'.$bouton.'">
					</form>';
	}
	function abw_erreur_paiement_express($code) {
		switch($code):
			case -100: $err = __("Technical problem, it is necessary to repeat the request and contact the Monetico Payment support in case of new failure.", 'monetico'); break;
			case -101: $err = __("The MAC is not present in the call.", 'monetico'); break;
			case -110: $err = __("Different version from 3.0.", 'monetico'); break;
			case -111: $err = __("The virtual payment terminal does not exist. Check the value in the tpe field.", 'monetico'); break;
			case -112: $err = __("The wallet does not exist. Check the value in the aliascb field.", 'monetico'); break;
			case -113: $err = __("The value of the action field does not correspond to one of the actions that can be performed on the card wallet.", 'monetico'); break;
			case -114: $err = __("The MAC is not correct.", 'monetico'); break;
			case -115: $err = __("The content of the version field does not check the required format.", 'monetico'); break;
			case -116: $err = __("The content of the tpe field does not check the required format.", 'monetico'); break;
			case -117: $err = __("The content of the aliascb field does not check the required format.", 'monetico'); break;
			case -118: $err = __("The content of the action field does not check the required format.", 'monetico'); break;
			case -119: $err = __("The content of the mac field does not check the required format.", 'monetico'); break;
			case -120: $err = __("The content of the card_identifier field does not check the required format.", 'monetico'); break;
			case -121: $err = __("The bank card is not present in the card wallet.", 'monetico'); break; //
			case -122: $err = __("The content of the card_name field does not check the required format.", 'monetico'); break;
			case -201: $err = __("Impossible to modify the credit card because it has expired.", 'monetico'); break;
			case -202: $err = __("Impossible to delete the credit card.", 'monetico'); break;
			case -205: $err = __("The card wallet is empty.", 'monetico'); break;
			case -207: $err = __("The card is already the default card.", 'monetico'); break;
		endswitch;
		return $err;
	}
	add_action( 'wp_enqueue_scripts', 'abw_assets' );
	function abw_assets() {
		global $wp_query;
		if( isset($wp_query->query_vars['paiement-express']) ):
			wp_enqueue_script( 'abw', plugin_dir_url( __FILE__ ).'js/scripts.js', array( 'jquery' ), '1.0', true );
			wp_localize_script( 'abw', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
		endif;
	}
	add_action( 'wp_ajax_paiement_express', 'abw_paiement_express' );
	add_action( 'wp_ajax_nopriv_paiement_express', 'abw_paiement_express' );
	function abw_paiement_express() {
		$args = array(
		  'body' => array( 
			  'version' => $_POST['version'],
			  'tpe' => $_POST['tpe'],
			  'aliascb' => $_POST['aliascb'],
			  'action' => $_POST['action_express'],
			  'identifiant_carte' => $_POST['identifiant_carte'],
			  'mac' => $_POST['mac']
			   )
		  );
		$monetico_settings = get_option('woocommerce_monetico_settings');
		$url_serveur = abw_set_urlserver($monetico_settings['monetico_mode']);
		if(!defined('MONETICOPAIEMENT_URLSERVER')) define("MONETICOPAIEMENT_URLSERVER", $url_serveur);
		$response = wp_remote_post( MONETICOPAIEMENT_URLSERVER.MONETICOPAIEMENT_ALIASCB, $args );
		if ( is_wp_error( $response ) ):
		   return $response;
		else:
			$body = json_decode($response['body']);
			if($body->cdr!=0):
				echo '**'.abw_erreur_paiement_express($body->cdr);
			else:
				switch($_POST['action_express']):
					case 'desactiver_carte': echo 'desactiver_carte*'.$body->deletedCard.'*'.__( 'Card removed', 'monetico' ); break;
					case 'positionner_carte_defaut': echo 'positionner_carte_defaut*'.$body->defaultCard.'*'.__( 'Default card modified', 'monetico' ).'*'.__("yes", 'monetico'); break;
				endswitch;
			endif;
		endif;
		wp_die();
	}
endif;

// Nécessaire pour que la fermeture de la notice dure x jours, sur la session ou indéfiniment
require  __DIR__ . '/vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'abw_expiration_prochaine' );
function abw_expiration_prochaine() {
	$dismissible = 'notice-expiration-session'; // nom-duree (nom-1 : 1 jour, nom-forever : indéfiniment, nom-session : session)
	if ( ! PAnD::is_admin_notice_active( $dismissible ) ) {
		return;
	}
	$access_expires = get_transient( 'access_expires_monetico' );
	if( !empty($access_expires) ) $strtotime_access_expires = strtotime($access_expires);
	if( !empty($access_expires) && checkdate( date('m', $strtotime_access_expires), date('d', $strtotime_access_expires), date('Y', $strtotime_access_expires) ) && $strtotime_access_expires>time() && $strtotime_access_expires<(time()+15*24*3600) ):
		$class = 'notice notice-warning is-dismissible';
		$titre = __( "Your WooCommerce Gateway Monetico license expires in", 'monetico' )." ";
		$message = __( "After expiration, you will no longer have access to updates for your Monetico gateway or ABSOLUTE Web support.", 'monetico' );
		$bouton = sprintf( __( "Extend your license with 50%% off before the %s!", 'monetico' ), date('d/m/Y', $strtotime_access_expires) );
		$script = '<script>var countDownDate = new Date("'.$access_expires.'").getTime(); var x = setInterval(function() { var maintenant = new Date(); var now = maintenant.getTime(); var distance = countDownDate - now +(maintenant.getTimezoneOffset()*60*1000); var days = Math.floor(distance / (1000 * 60 * 60 * 24)); var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)); var seconds = Math.floor((distance % (1000 * 60)) / 1000); document.getElementById("abw_countdown_monetico").innerHTML = days + " jour"+(days>1?"s":"")+ " " + (hours>0?("0" + hours).slice(-2):"0") + " heure"+(hours>1?"s":"")+ " " + (minutes>0?("0" + minutes).slice(-2):"0") + " minute"+(minutes>1?"s":"")+ " et " + ("0" + seconds).slice(-2) + " seconde"+(seconds>1?"s":""); if (distance < 0) {  clearInterval(x); document.getElementById("abw_countdown_monetico").innerHTML = "EXPIRÉE"; } }, 1000);</script>';

		printf( '<div data-dismissible="%1$s" class="%2$s"><p><strong style="font-size:16px">%3$s<span id="abw_countdown_monetico"></span></strong></p><p>%4$s</p><p><a class="button button-primary" href="https://www.absoluteweb.net/boutique/renouveler-vos-licences/?utm_source=site_client&utm_medium=notice" target="_blank">%5$s</a></p>%6$s</div>', esc_attr( $dismissible ), esc_attr( $class ), esc_html( $titre ), esc_html( $message ), esc_html( $bouton ), $script );
	endif;
}
?>