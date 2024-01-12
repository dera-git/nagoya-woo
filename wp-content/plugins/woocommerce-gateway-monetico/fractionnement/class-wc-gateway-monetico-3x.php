<?php
class WC_Gateway_Monetico_x3 extends WC_Gateway_Monetico {	
	public function __construct() { 
        $this->id = 'monetico_x3';
        $this->order_button_text  = sprintf(__( 'Pay in %d times by credit card', 'monetico' ), 3);
        $this->method_title = 'Monetico 3x';
        $this->method_description = sprintf(__( "Accept %s payments on your Monetico contract.", 'monetico' ), '3x'); 
        $this->logo = plugins_url('woocommerce-gateway-monetico/logo/monetico-paiement.png');
        $this->has_fields = false;	
        $this->init_form_fields();
        $this->init_settings();
        $this->icon = apply_filters('woocommerce_monetico_x3_icon', $this->settings['gateway_image']);
        $this->title = $this->settings['title'];
        $this->description =  $this->settings['description'];
        $this->supports = array('products');
        add_action( 'woocommerce_receipt_' . $this->id, array($this, 'receipt_page') );
        add_action( 'woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options') ); 
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page') );
    }
	public function init_form_fields() {
		$this->form_fields = array(
				'enabled' => array(
								'title' => __( "Activate/Deactivate", 'monetico' ), 
								'type' => 'checkbox', 
								'label' => sprintf(__( "Activate %s Monetico payment", 'monetico' ), '3x'), 
								'default' => 'no'
							), 
				'title' => array(
								'title' => __( "Title", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Title displayed when selecting the method of payment.", 'monetico' ), 
								'default' => sprintf(__( "Credit card in %d times", 'monetico' ), 3),
								'css' => 'width:250px',
								'desc_tip' => true
							),
				'description' => array(
								'title' => __( "Message client", 'monetico' ), 
								'type' => 'textarea', 
								'description' => __( "Inform the customer of payment by credit card.", 'monetico' ), 
								'default' => sprintf(__( "By choosing this method of payment you can make your payment in %d times on the secure server of our bank.", 'monetico' ), 3),
								'desc_tip' => true
							), 
				'gateway_image' => array(
								'title' => __( "Icon payment", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Url of the image displayed when selecting the method of payment.", 'monetico' ),
								'default' => plugins_url('woocommerce-gateway-monetico/logo/3x-cb-visa-mastercard.png'),
								'css' => 'width:90%',
								'desc_tip' => true
							),
				'montants_plafonds' => array(
					'title' => __( "Ceiling amounts:", 'monetico' ),
					'type' => 'title'
				),
				'montant_minimum' => array(
								'title' => __( "Minimum amount", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Minimum amount required to offer this payment method.", 'monetico' ), 
								'css' => 'width:150px',
								'desc_tip' => true
							), 
				'montant_maximum' => array(
								'title' => __( "Maximum amount", 'monetico' ), 
								'type' => 'text', 
								'description' => __( "Maximum amount beyond which the payment method will not be offered.", 'monetico' ), 
								'css' => 'width:150px',
								'desc_tip' => true
							)
			);
	}
	public function admin_options() {
        ?>
        <p><img src="<?php echo $this->logo; ?>" /></p>
        <h2><?php _e("3x payment", 'monetico'); echo " — "; _e("Monetico", 'monetico'); echo "<sup>".PASSERELLE_MONETICO_VERSION."</sup>"; if(function_exists('wc_back_link')) { wc_back_link( __("Back to payments", 'monetico'), admin_url('admin.php?page=wc-settings&tab=checkout') ); } ?></h2>
        <p><?php printf(__("The method of payment in %d times requires a specific Monetico contract. Please check with your CIC or Crédit Mutuel to make sure that the contract for payment in instalments has been taken out.", 'monetico'), 3); ?></p>
        <p><?php printf(__("The main Monetico settings, which are necessary to use the %d times payment, are accessible on the %sMonetico settings%s page.", 'monetico'), 3, '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=monetico').'">', '</a>');  ?></p>
        <table class="form-table">
        <?php
            $this->generate_settings_html();
        ?>
        </table><!--/.form-table-->
        <?php
    }
	public function receipt_page( $order ) {
		global $fractionne_monetico;
		$fractionne_monetico = 3; // 2, 3 ou 4
		parent::receipt_page( $order );
	}
}
if(!is_admin())
	add_filter( 'woocommerce_available_payment_gateways', 'abw_disponibilite_monetico_x3' );
function abw_disponibilite_monetico_x3( $_available_gateways ) {
	$monetico_x3 = isset($_available_gateways['monetico_x3']) ? $_available_gateways['monetico_x3'] : NULL;
	if ( isset( $monetico_x3 ) ) {
		$total = isset(WC()->cart->total) ? WC()->cart->total : 0;
		if (is_wc_endpoint_url( 'order-pay' )) {
			$order_id = (int) get_query_var('order-pay');
			$order = new WC_Order($order_id);
			$total = $order->get_total();
		}
    	if($monetico_x3->settings['montant_minimum']!=''&&
			$monetico_x3->settings['montant_minimum']>$total) {
			unset($_available_gateways['monetico_x3']);
		}
		if(isset($monetico_x3)&&$monetico_x3->settings['montant_maximum']!=''&&
			$monetico_x3->settings['montant_maximum']<$total) {
			unset($_available_gateways['monetico_x3']);
		}
  	}
  	return $_available_gateways;
}