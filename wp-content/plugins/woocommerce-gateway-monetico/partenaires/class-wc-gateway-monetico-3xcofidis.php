<?php
class WC_Gateway_Monetico3xCofidis extends WC_Gateway_Monetico {	
	public function __construct() { 
        $this->id = 'monetico_3x';
        $this->order_button_text  = __( 'Proceed to Credit Card', 'monetico' ); // Payer par Carte Bancaire
        $this->method_title = 'Monetico 3xCB Cofidis';
        $this->method_description = sprintf(__( "Accept payments %s, Monetico partner.", 'monetico' ), '3xCB Cofidis'); // Accepter les paiements 3xCB Cofidis, partenaire Monetico.
        $this->logo = plugins_url('woocommerce-gateway-monetico/logo/3xcb-cofidis.png');
        $this->has_fields = false;	
        $this->init_form_fields();
        $this->init_settings();
        $this->icon = apply_filters('woocommerce_monetico_3x_icon', $this->settings['gateway_image']);
        $this->title = $this->settings['title'];
        $this->description =  $this->settings['description'];
        if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ):
            $this->supports = array('products');
        endif;
        add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
        add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options')); 
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }
	public function init_form_fields() {
		$this->form_fields = array(
				'enabled' => array(
								'title' => __( "Enable/Disable", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => __( "Check to enable the payment Cofidis.", 'monetico' ), 
								'default' => 'no'
							), 
				'title' => array(
								'title' => __( "Title", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Title displayed when selecting the method of payment.", 'monetico' ), 
								'default' => __( "3xCB Cofidis", 'monetico' ),
								'css' => 'width:150px',
								'desc_tip' => true
							),
				'description' => array(
								'title' => __( "Message client", 'monetico' ), 
								'type' => 'textarea', 
								'description' => __( "Inform the customer of payment by credit card.", 'monetico' ), 
								'default' => __( "By choosing this method of payment, you can make your secure payment in 3 times with Cofidis.", 'monetico' ),
								'desc_tip' => true
							), 
				'gateway_image' => array(
								'title' => __( "Icon payment", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Url of the image displayed when selecting the method of payment.", 'monetico' ),
								'default' => plugins_url('woocommerce-gateway-monetico/logo/logo-3xcb.png'),
								'css' => 'width:90%',
								'desc_tip' => true
							),
				'montants_plafond' => array(
					'title' => __( "Ceiling amounts:", 'monetico' ),
					'type' => 'title'
				),
				'montant_minimum' => array(
								'title' => __( "Minimum amount", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Minimum amount needed to offer this payment method.", 'monetico' ), 
								'default' => 100,
								'css' => 'width:150px',
								'desc_tip' => true
							), 
				'montant_maximum' => array(
								'title' => __( "Maximum amount", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Maximum amount needed to offer this payment method.", 'monetico' ), 
								'default' => 1500,
								'css' => 'width:150px',
								'desc_tip' => true
							)
			);
	}
	public function admin_options() {
        ?>
        <p><img src="<?php echo $this->logo; ?>" /></p>
        <h2><?php _e("3xCB Cofidis", 'monetico'); echo " — "; _e("Monetico payment", 'monetico'); echo "<sup>".PASSERELLE_MONETICO_VERSION."</sup>"; if(function_exists('wc_back_link')) { wc_back_link( __("Back to payments", 'monetico'), admin_url('admin.php?page=wc-settings&tab=checkout') ); } ?></h2>
        <p><?php printf(__("The %s payment method requires a specific contract. Make sure you activate it only if you have the appropriate contracts. If necessary, contact Monetico and the financial partner.", 'monetico'), '3xCB Cofidis'); // Le moyen de paiement 3xCB Cofidis nécessite un contrat spécifique. Veillez à ne l'activer que si vous disposez des contrats adaptés. Si besoin renseignez-vous auprès de Monetico et du partenaire financier.?></p>
        <p><?php printf(__("The main Monetico settings, necessary to use the %s payment method, can be accessed on the Monetico Gateway %ssettings page%s.", 'monetico'), '3xCB Cofidis', '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=monetico').'">', '</a>'); // Les réglages principaux de Monetico, nécessaires à l'utilisation du moyen de paiement 3xCB Cofidis, sont accessibles sur la page de réglage de la passerelle Monetico. ?></p>
        <table class="form-table">
        <?php
            $this->generate_settings_html();
        ?>
        </table><!--/.form-table-->
        <?php
    }
	public function receipt_page( $order ) {
		global $protocole_monetico;
		$protocole_monetico = '3xcb'; // 1euro, 3xcb, 4xcb, paypal ou lyfpay
		parent::receipt_page( $order );
	}
}
if(!is_admin())
	add_filter( 'woocommerce_available_payment_gateways', 'abw_disponibilite_3xcb' );
function abw_disponibilite_3xcb( $_available_gateways ) {
	$monetico_3x = isset($_available_gateways['monetico_3x']) ? $_available_gateways['monetico_3x'] : NULL;
	if ( isset( $monetico_3x ) ) {
		$total = isset(WC()->cart->total) ? WC()->cart->total : 0;
		if (is_wc_endpoint_url( 'order-pay' )) {
			$order_id = (int) get_query_var('order-pay');
			$order = new WC_Order($order_id);
			$total = $order->get_total();
		}
    	if($monetico_3x->settings['montant_minimum']!=''&&
			$monetico_3x->settings['montant_minimum']>$total) {
			unset($_available_gateways['monetico_3x']);
		}
		if(isset($monetico_3x)&&$monetico_3x->settings['montant_maximum']!=''&&
			$monetico_3x->settings['montant_maximum']<$total) {
			unset($_available_gateways['monetico_3x']);
		}
  	}
  	return $_available_gateways;
}