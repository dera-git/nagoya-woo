<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       phoeniixx.com
 * @since      1.0.0
 *
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/public
 * @author     Phoeniixx <contact@phoeniixx.com>
 */
class Woo_Custom_Fields_For_Variation_Public {

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

	private $woo_var_option_plugin = 0;
	private $woo_custom_var_option_optn_total;
	private $woo_custom_var_option_fnl_total;
	private $tax_display_mode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/* Store Data */
		$this->woo_var_option_plugin 			= get_option( 'woo_var_option_plugin' );
		$this->woo_custom_var_option_optn_total = get_option( 'woo_custom_var_option_optn_total' );
		$this->woo_custom_var_option_fnl_total 	= get_option( 'woo_custom_var_option_fnl_total' );
		$this->tax_display_mode 				= get_option( 'woocommerce_tax_display_shop' );

		/* Call Back Function Of public */
		$this->phoen_custom_field_variation_public_action($this->woo_var_option_plugin);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Custom_Fields_For_Variation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Custom_Fields_For_Variation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-custom-fields-for-variation-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Custom_Fields_For_Variation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Custom_Fields_For_Variation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-custom-fields-for-variation-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script("phoen-custom-field-variation-script",plugin_dir_url( __FILE__ )."js/options.js",array('jquery'),'',false);

	}

	private function phoen_custom_field_variation_public_action($woo_var_option_plugin){

		add_action( 'woocommerce_before_add_to_cart_button', array($this,'phoen_woocommerce_after_variations_form'), 10, 0 ); 

		add_action( 'admin_head', array($this,'phoen_script_var_add')); 

		if($woo_var_option_plugin == '1'){

			add_filter( 'woocommerce_add_cart_item',  array($this,'phoen_add_var_cart_item') , 20, 1 );

			// Load cart data per page load
			add_filter( 'woocommerce_get_cart_item_from_session', array($this,'phoen_get_cart_item_var_from_session') , 20, 2 );

			// Get item data to display
			add_filter( 'woocommerce_get_item_data',  array($this,'phoen_get_item_var_data') , 10, 2 );

			// Add item data to the cart
			add_filter( 'woocommerce_add_cart_item_data',  array($this,'phoen_add_to_var_cart_product') , 10, 2 );

			// Validate when adding to cart
			add_filter( 'woocommerce_add_to_cart_validation',  array($this,'phoen_validate_add_var_cart_product') , 10, 3 );

			// Add meta to order
			add_action( 'woocommerce_add_order_item_meta',  array($this,'phoen_order_item_var_meta') , 10, 2 );

		}

	}

	public function phoen_woocommerce_after_variations_form( ) { 
		
		global $product;

		$display_price    = '';
	
		if($product->get_type() === 'variable' && $this->woo_var_option_plugin == '1'){

			$this->phoen_script_var_add();
			
			$get_variation_ids = $product->get_children();
			 
			foreach($get_variation_ids as $variation_id){
				 
				$this->phoen_show_custom_field_data($variation_id);
			}
			
			( !isset( $product ) || $product->get_id() != $product->get_id() ) ? $the_product = wc_get_product( $product->get_id() ) : $the_product = $product;

			if ( is_object( $the_product ) ) {
				
				$display_price    = $this->tax_display_mode == 'incl' ? wc_get_price_including_tax($the_product) : wc_get_price_excluding_tax($the_product);
			}

			echo '<div id="product-options-var-total" product-type="' . $the_product->get_type() . '" product-price="' . $display_price . '"></div>';
		}
    }

    private function phoen_show_custom_field_data($variation_id){

    	$variation_data = get_post_meta( $variation_id, 'phoen_variation_data', true);

    	if(isset($variation_data) && is_array($variation_data)):

    		foreach($variation_data as $key => $value):

    			if ( isset($value['name']) && empty( $value['name'] ) ):
    				unset( $variation_data[ $key ] );		
					continue;
    			endif;

    			$this->phoen_check_field_type($value['type'],$value,$variation_id);

    		endforeach;	
    	endif;
    }

    private function phoen_check_field_type($type,$value,$variation_id){

    	if(isset($type) && $type === 'custom_field'):
    		$this->phoen_get_text_field($value,$variation_id);
    	elseif(isset($type) && $type === 'custom_textarea'):
    		$this->phoen_get_text_area_field($value,$variation_id);
    	endif;
    }

    private function phoen_get_text_field($options,$variation_id){

    	include(PHOEN_CUSTOM_VARIATION_DIR_PATH.'public/template/custom_fields.php');
    }

    private function phoen_get_text_area_field($options,$variation_id){

    	include(PHOEN_CUSTOM_VARIATION_DIR_PATH.'public/template/custom_textareas.php');
    }

    public function phoen_script_var_add(){ 
		
		include(PHOEN_CUSTOM_VARIATION_DIR_PATH.'public/front_section/header_script.php');
	}

	public function phoen_add_var_cart_item($cart_item_data) {
		
		$extra_cost = 0;

		if ( ! empty( $cart_item_data['options'] ) ) {

			foreach ( $cart_item_data['options'] as $options ) {
				
				$extra_cost+= ( $options['price'] > 0 ) ? $options['price'] : 0;					
			}
			
			$cart_item_data['data']->set_price( $extra_cost +$cart_item_data['data']->get_price());
		}

		return $cart_item_data;
	}

	public function phoen_get_cart_item_var_from_session($cart_item_data, $values) {
			
		if ( ! empty( $values['options'] ) ) {
			
			$cart_item_data['options'] = $values['options'];
			
			$cart_item_data = $this->phoen_add_var_cart_item( $cart_item_data );
		}
		return $cart_item_data;
	}

	public function phoen_get_item_var_data( $other_data, $cart_item_data ) {
			
		if ( ! empty( $cart_item_data['options'] ) ) {
			
			foreach ( $cart_item_data['options'] as $options ) {
								
				$name = $options['name'];

				if ( $options['price'] > 0 ) {
					
					$name .= ' (' . wc_price( $this->get_var_product_addition_options_price ( $options['price'] ) ) . ')';
				}

				$other_data[] = array(
					'name'    => $name,
					'value'   => $options['value'],
					'display' => ''
				);
			}
		}
		return $other_data;
	}

	public function phoen_add_to_var_cart_product( $cart_item_data,$product_id ) {
								
		$cart_item_data['options'] = (empty( $cart_item_data['options'] ) ) ? array() : $cart_item_data;

		$variations_id 	= isset($_POST['variation_id'])?$_POST['variation_id']:'';

		$variation_data = get_post_meta( $variations_id, 'phoen_variation_data', true);
		
		if(isset($variation_data) && is_array($variation_data) && !empty($variation_data)){
			
			foreach ( $variation_data as $options ) {
		
				$option_name = strtolower(str_replace(' ','-',$options['name'] ));
				
				$val_post =  isset($_POST['custom-variation'][$variations_id][$option_name])?$_POST['custom-variation'][$variations_id][$option_name]:'';
			
				if($val_post != ''){

					$data[] = array(
						'name'  => $options['label'],
						'value' => $val_post,
						'price' => $options['price']
					);
				
					$cart_item_data['options'] =  $data;
				}		
			}
		}
			
		return $cart_item_data;
	}

	public function phoen_validate_add_var_cart_product(  $passed, $product_id, $quantity ) {
	
		global $woocommerce;
		
		$variations_id 	= isset($_POST['variation_id'])?$_POST['variation_id']:'';
		
		$variation_data = get_post_meta( $variations_id, 'phoen_variation_data', true);
		
		if(isset($variation_data) && is_array($variation_data) && !empty($variation_data)){
			
			foreach ( $variation_data as $options_key => $options ) {
		
				$post_data =  isset($_POST['custom-variation'][$variations_id][sanitize_title( $options['name'] )])?$_POST['custom-variation'][$variations_id][sanitize_title( $options['name'] )]:'';
				
				if( $options['required'] == 1  ){

					if ( $post_data == "" && strlen( $post_data ) == 0 ) {
						
						$data = new WP_Error( 'error', sprintf( __( '"%s" is a required field.', 'custom-variation' ), $options['label'] ) );
						
						wc_add_notice( $data->get_error_message(), 'error' );
							
						$data_msg = 1;
					}
					
				}

				if ( strlen( $post_data ) > $options['max'] && $options['max']!='' ) {
					
					$data = new WP_Error( 'error', sprintf( __( 'The maximum allowed length for "%s" is %s letters.', 'custom-variation' ), $options['label'], $options['max'] ) );
					
					wc_add_notice( $data->get_error_message(), 'error' );
					
					$data_msg = 1;
				}
			}
		}
		
		if(isset($data_msg) && $data_msg == 1){

			return false;
		}
					
		return $passed;		
	}

	public function phoen_order_item_var_meta($item_id,$values) {
					
		if ( ! empty( $values['options'] ) ) {
			
			foreach ( $values['options'] as $options ) {

				$name = $options['name'];

				if ( $options['price'] > 0 ) {
					
					$name .= ' (' . wc_price( $this->get_var_product_addition_options_price( $options['price'] ) ) . ')';
				}

				woocommerce_add_order_item_meta( $item_id, $name, $options['value'] );	
			}	
		}
	}

	private function get_var_product_addition_options_price( $price) {
			
		global $product;		

		if ( !empty($price) && $price > 0 ):

			if(is_object($product)):
				return ($this->tax_display_mode == 'incl') ? $product->get_price_including_tax( 1, $price ) : $product->get_price_excluding_tax( 1, $price );
			else:
				return $price;
			endif;
		endif;
	}

}// Woo_Custom_Fields_For_Variation_Public Class Closed 
