<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       phoeniixx.com
 * @since      1.0.0
 *
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Custom_Fields_For_Variation
 * @subpackage Woo_Custom_Fields_For_Variation/admin
 * @author     Phoeniixx <contact@phoeniixx.com>
 */
class Woo_Custom_Fields_For_Variation_Admin {

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
		$this->phoen_custom_field_variation_admin_action();
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-custom-fields-for-variation-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-custom-fields-for-variation-admin.js', array( 'jquery' ), $this->version, false );

	}

	private function phoen_custom_field_variation_admin_action(){

		add_action('admin_menu', array($this,'phoen_register_custom_field_variation'),99);

		add_action( 'woocommerce_product_after_variable_attributes', array($this,'phoen_variation_options_tab_options'), 10, 3 );
		
		add_action( 'woocommerce_save_product_variation', array($this,'phoen_register_custom_field_variation_save_varaiation_data'), 10, 2 );
		
		add_action( 'woocommerce_process_product_meta_variable', array($this,'phoen_register_custom_field_variation_save_varaiation_data'), 10, 1 ); 
	}

	public function phoen_register_custom_field_variation(){

		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, PHOEN_CUSTOM_VARIATION_DIR_URL.'admin/images/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', 'Variation Options', 'Variation Options', 'manage_options', 'phoen_variation_options_setting', array($this,'phoen_variation_options_setting') ); 
	}

	public function phoen_variation_options_setting(){ 

		$tab = (isset($_GET['tab'])) ? sanitize_text_field( $_GET['tab'] ) : "" ; ?>
	
		<div id="profile-page" class="wrap">

		    <h2 style="text-transform: uppercase;color: #0c5777;font-size: 22px;font-weight: 700;text-align: left;display: inline-block;box-sizing: border-box;">  <?php _e('Woocommerce Custom Fields For Variation - Plugin Options', 'custom-variation'); ?></h2>

		    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			
				<a style="<?= ($tab == 'general' || $tab == '')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'phoen_rewpts_setting' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=phoen_variation_options_setting&amp;tab=general"><?php _e( 'General','phoen-rewpts'); ?></a>
			
				<a style="<?= ($tab == 'premium')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'phoen_rewpts_setting' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=phoen_variation_options_setting&amp;tab=premium"><?php _e( 'Premium','phoen-rewpts'); ?></a>

		    </h2>

		</div> 
		<?php $this->phoen_custom_field_variation_tab($tab); 
	}

	private function phoen_custom_field_variation_tab($tab){

		switch ($tab) {
			case 'general':
				$this->phoen_custom_field_variation_general_setting();
				break;

			case 'premium':
				$this->phoen_custom_field_variation_premium();
				break;
			
			default:
				$this->phoen_custom_field_variation_general_setting();
				break;
		}
	}

	private function phoen_custom_field_variation_general_setting(){

		require_once(PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/admin_section/phoen_general_setting.php');
	}

	private function phoen_custom_field_variation_premium(){
		require_once(PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/admin_section/phoen_premium_feature.php');
	}

	/**
	* this function showing after variable product in admin
	**/
	function phoen_variation_options_tab_options( $loop, $variation_data, $variation ) {
				
			$this->phoen_css_min();

			global $post;

			$variation_id  = absint($variation->ID);
			
			$variation_id_jh = absint($variation->ID);

			$vart_data = get_post_meta( $variation_id, 'phoen_variation_data', true);
			
			?>
			
			<div id="custom_tab_data_<?php echo  $variation_id;?>" class="panel phoen_new_min woocommerce_options_panel wc-metaboxes-wrapper">
					
					<div id="custom_tab_data_options_<?php echo $variation_id; ?>" class="wc-metaboxes">
					<input type="hidden" name="variation_id[]" value="<?php echo  $variation_id;?>" />
					<?php
						
							$loop = 0;

							foreach ( $vart_data as $option ) {
								
								include( PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/admin_section/custom_option_html.php' );
								//print_r( $option );
								
								$loop++;
							}
						?>

					</div>

				<div class="toolbar">
					
					<button type="button" data-id="<?php echo $variation_id; ?>" class="button add_new_custom_option button-primary"><?php _e( 'New Custom Option', 'custom-variation' ); ?></button>
				
				</div>
				
			</div>
			
			<style>
				#custom_tab_data_<?php echo $variation_id; ?> input {
					
					min-width: 139px;
					
				}
				#custom_tab_data_<?php echo $variation_id; ?> label {
					
					margin: 0;
					
				}
				
				#variable_product_options .phoen_new_min {
					width: 100%;
				}
				
				#variable_product_options .phoen_new_min h3 {
					background-color: #eee;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option {
					margin-bottom: 10px !important;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option table.wc-metabox-content {
					border: 1px solid #eee;
					border-top: none;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option table input[type="checkbox"] {
					min-width: auto;
					width: 18px;
				}
				
				</style>				
			<script type="text/javascript">
				jQuery(function(){

					jQuery('#custom_tab_data_<?php echo $variation_id; ?>').on( 'click', '.add_new_custom_option', function() {
						
						var loop = jQuery('#custom_tab_data_options_<?php echo $variation_id; ?> .woocommerce_product_option').size();
						
						var var_id=jQuery(this).attr('data-id')

						var html = '<?php
							
							ob_start();

							$option['name'] 			= '';
							
							$option['required'] 		= '';
							
							$option['type'] 			= 'custom';
							
							$option['options'] 		= array();
							
							$loop = "{loop}";
							
							$variation_id = "{variation_id}";

							include( PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/admin_section/custom_option_html.php' );
						
							$html = ob_get_clean();
							
							echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );
						
						?>';
						html = html.replace( /{loop}/g, loop );
						html = html.replace( /{variation_id}/g, var_id );
						//alert(html);
						jQuery('#custom_tab_data_options_<?php echo $variation_id_jh; ?>').append( html );
						
						jQuery('.clear_class'+var_id+loop).val( '' );
					});
					
					
					jQuery('#custom_tab_data_<?php echo $variation->ID ?>').on( 'click', '.remove_option', function() {

							var conf = confirm('<?php _e('Are you sure you want remove this option?', 'custom-variation'); ?>');

							if (conf) {
								
								var option = jQuery(this).closest('.woocommerce_product_option');
								
								//alert( option );
								
								jQuery(option).find('input').val('');
								
								jQuery(option).hide();
								
							}

							return false;
					});
					
				
				});
			</script>
			
		
			<?php
				
		}



	public function phoen_variation_options_tab_optionsssss( $loop, $variation_data, $variation ) {

		global $post;

		$variation_id  		= absint($variation->ID);

		$variation_id_jh 	= absint($variation->ID);

		$variation_data 	= get_post_meta( $variation_id, 'phoen_variation_data', true); 

		$this->phoen_custom_field_add_new_field($variation_id,$variation_data,$variation_id_jh);
	}

	private function phoen_custom_field_add_new_field($variation_id,$variation_data,$variation_id_jh){ ?>

		<?php $this->phoen_css_min() ?>

		<div id="custom_tab_data_<?php echo  $variation_id;?>" class="panel phoen_new_min woocommerce_options_panel wc-metaboxes-wrapper">
					
			<div id="custom_tab_data_options_<?php echo $variation_id; ?>" class="wc-metaboxes">
				<input type="hidden" name="variation_id[]" value="<?php echo  $variation_id;?>" />
				<?php $this->phoen_custom_field_variation_show_save_field_data($variation_data); ?>
			</div>

			<div class="toolbar">	
				<button type="button" data-id="<?php echo $variation_id; ?>" class="button add_new_custom_option button-primary"><?php _e( 'New Custom Option', 'custom-variation' ); ?></button>
			</div>
		
		</div>
		<style>
				#custom_tab_data_<?php echo $variation_id; ?> input {
					
					min-width: 139px;
					
				}
				#custom_tab_data_<?php echo $variation_id; ?> label {
					
					margin: 0;
					
				}
				
				#variable_product_options .phoen_new_min {
					width: 100%;
				}
				
				#variable_product_options .phoen_new_min h3 {
					background-color: #eee;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option {
					margin-bottom: 10px !important;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option table.wc-metabox-content {
					border: 1px solid #eee;
					border-top: none;
				}
				
				#variable_product_options .phoen_new_min .woocommerce_product_option table input[type="checkbox"] {
					min-width: auto;
					width: 18px;
				}
				
				</style>
		<?php $this->phoen_custom_field_variation_add_new_custom_option($variation_id,$variation_id_jh);
	}

	function phoen_css_min(){ ?>
		<style>
			#variable_product_options .phoen_new_min {
				width: 100%;
			}
			
			#variable_product_options .phoen_new_min h3 {
				background-color: #eee;
			}
			
			#variable_product_options .phoen_new_min .woocommerce_product_option {
				margin-bottom: 10px !important;
			}
			
			#variable_product_options .phoen_new_min .woocommerce_product_option table.wc-metabox-content {
				border: 1px solid #eee;
				border-top: none;
			}
			
			#variable_product_options .phoen_new_min .woocommerce_product_option table input[type="checkbox"] {
				min-width: auto;
				width: 18px;
			}
			
			.phoen_new_min label {
				margin: 0;
				width: 190px;
				text-align: right !important;
				padding-right: 20px;
			}

			.wc-metaboxes-wrapper .woocommerce_product_option h3 select{
				width: 200px !important;
				max-width: 40%;
			}
			</style>
			
			
	<?php }

	private function phoen_custom_field_variation_add_new_custom_option($variation_id,$variation_id_jh){ ?>

		<script>
			jQuery(function(){
				jQuery('#custom_tab_data_<?php echo $variation_id; ?>').on( 'click', '.add_new_custom_option', function() {
						
						var loop = jQuery('#custom_tab_data_options_<?php echo $variation_id; ?> .woocommerce_product_option').size();
						
						var var_id=jQuery(this).attr('data-id');
					var html 	= phone_get_custom_option_html('<?= _e($variation_id)?>',loop);
					html = html.replace( /{loop}/g, loop );
						html = html.replace( /{variation_id}/g, var_id );
					jQuery('#custom_tab_data_options_<?php echo $variation_id_jh; ?>').append( html );
						
						jQuery('.clear_class'+var_id+loop).val( '' );
				});

				jQuery('#custom_tab_data_<?php echo $variation_id ?>').on( 'click', '.remove_option', function() {

					var conf = confirm('<?php _e('Are you sure you want remove this option?', 'custom-variation'); ?>');

					if (conf) {
						
						var option = jQuery(this).closest('.woocommerce_product_option');
						
						jQuery(option).find('input').val('');
						
						jQuery(option).hide();
					}
				});
			});

			function phone_get_custom_option_html(variation_id,loop){

				return '<?php
						ob_start();
							$option['name'] 	= '';
							$option['required'] = '';
							$option['type'] 	= 'custom';
							$option['options'] 	= array();
							$loop 				= "{loop}";
							$variation_id 		= "{variation_id}";
							include( PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/partials/custom_option_html.php' );
						$html = ob_get_clean();	
						echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );?>';
			}
		</script>
	<?php }

	public function phoen_register_custom_field_variation_save_varaiation_data($post_id){

		$variation_id= isset($_POST['variation_id'])?array_map( 'sanitize_text_field', $_POST['variation_id'] ):'';

		if(isset($_POST) && is_array($variation_id)){
			
			for($m=0;$m <= count($variation_id);$m++){
		
				$vvgat_id = $variation_id[$m];
				
				$product_custom_options = array();
				
				if ( isset( $_POST[ 'product_option_name' ][$vvgat_id] ) && !empty($_POST[ 'product_option_name' ][$vvgat_id]) ) {
						
					$option_name  =isset($_POST['product_option_name'][$vvgat_id])? array_map( 'sanitize_text_field', $_POST['product_option_name'][$vvgat_id] ):'';
					
					$option_type  =isset($_POST['product_option_type'][$vvgat_id])? array_map( 'sanitize_text_field', $_POST['product_option_type'][$vvgat_id] ):'';
					
					$option_position  = isset($_POST['product_option_position'][$vvgat_id])?array_map( 'sanitize_text_field', $_POST['product_option_position'][$vvgat_id]):'';
					
					$option_required   =  isset( $_POST['product_option_required'][$vvgat_id] ) ? array_map('sanitize_text_field',$_POST['product_option_required'][$vvgat_id]) : '';
						
					$option_label = isset($_POST['product_option_label'][$vvgat_id])?array_map( 'sanitize_text_field', $_POST['product_option_label'][$vvgat_id]):'';
					
					$option_price = isset($_POST['product_option_price'][$vvgat_id])?array_map( 'sanitize_text_field', $_POST['product_option_price'][$vvgat_id]):'';
					
					$option_max   = isset($_POST['product_option_max'][$vvgat_id])?array_map( 'sanitize_text_field', $_POST['product_option_max'][$vvgat_id]):'';
					 
					
						for ( $i = 0; $i < sizeof( $option_name ); $i++ ) {

							if ( ! isset( $option_name[ $i ] ) || ( '' == $option_name[ $i ] ) ) {
										
								continue;
							}
								
							$product_custom_options[] = array(
								'name' 		=> sanitize_text_field( stripslashes( $option_name[ $i ] ) ),
								'type' 		=> sanitize_text_field( stripslashes( $option_type[ $i ] ) ),
								'position' 	=> absint( $option_position[ $i ] ),
								'required' 	=> isset( $option_required[ $i ] ) ? 1 : 0,
								'label' 	=> sanitize_text_field( stripslashes( $option_label[ $i ] ) ),
								'price' 	=> wc_format_decimal( sanitize_text_field( stripslashes( $option_price[ $i ] ) ) ),
								'max' 		=>  sanitize_text_field( stripslashes( $option_max[ $i ] ) )
							);
						}



					// echo "<pre>"; print_r($product_custom_options); echo "</pre>";
					$product_custom_options=array_values($product_custom_options);
					update_post_meta( $vvgat_id, 'phoen_variation_data', $product_custom_options);
					
				}
					
				
			}
			
		}
	}

	private function phoen_custom_field_variation_show_save_field_data($variation_data){

		$loop = 0;

		foreach ( $variation_data as $option ) {

			include( PHOEN_CUSTOM_VARIATION_DIR_PATH.'admin/partials/custom_option_html.php' );
			$loop++;
		}
	}

}
