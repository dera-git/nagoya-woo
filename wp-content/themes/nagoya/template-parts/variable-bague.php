<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php 
			$i = 0;
			foreach ( $attributes as $attribute_name => $options ) : 
			$attr_name = $attribute_name;

			$tableAttr = [];

			$termsAttr = wc_get_product_terms( $product->id, $attr_name, array( 'fields' =>  'all' ) );
				echo '<pre>';
				print_r($attribute_name);
	        	echo '</pre>';
			if( $termsAttr ){
		        foreach ( $termsAttr as $term ){
		            $tableAttr[$term->name] = term_description( $term->term_id );
		        }
			}

		?>

		<div id="<?php echo $attribute_name; ?>" class="attributes-wrapper <?php echo ($i == 0) ? '' : 'd-none position-absolute px-5'; ?>">

		<?php

			if($attribute_name != 'pa_color' ):
			echo '<div class="d-flex justify-content-between align-items-center"><h2 class="product_title entry-title mb-0">' . renderTitleBloc($attr_name) . '</h2><button type="button" class="handleClose" data-toggle="#'.$attr_name.'"></button></div>';
			endif;


			if($attr_name != 'pa_color' ):
				get_template_part( 'template-parts/single-product', 'parts_'.$attr_name );
			endif;



		?>

			
			<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
				<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
			<?php else : ?>
				<table class="variations <?php echo $attribute_name; ?>" cellspacing="0" role="presentation">
					<tbody>
						<?php foreach ( $attributes as $attribute_name => $options ) :
						//var_dump($attributes); 
							if($attribute_name == $attr_name):
							?>
							<tr>
								<th class="label">
									<label class="mb-0" for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?>
									</label>
								</th>
								<td class="value">
									<div class="d-flex-wrapper">
										<div class="attr_description">
											<?php echo $tableAttr[$attribute_name]; ?>
										</div>	
									<?php
										wc_dropdown_variation_attribute_options(
											array(
												'options'   => $options,
												'attribute' => $attribute_name,
												'product'   => $product,
											)
										);
										echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
									?>									
									</div>
								</td>
							</tr>
						<?php endif; endforeach; ?>
					</tbody>
				</table>

				<?php 
					if($attr_name == 'pa_color'): 
						get_template_part( 'template-parts/single-product', 'parts_'.$attr_name );
					endif; 
					
					if($attr_name == 'pa_taille'): ?>

						<!-- Button trigger modal -->
						<div class="w-100 mb-4">
							<button type="button" class="btn btn-outline-info togglerModal" data-toggle="modal" data-target="#tutoVideo">
							  Bouton Tuto vidéo
							</button>
						</div>
				<?php
					endif; 
				?>

			<?php endif; ?>

			<?php if($attr_name != 'pa_color'): ?>
				<div class="btn-or-wrapper w-100">					
				<span class="btn-or handleClose" data-toggle="#<?php echo $attr_name;?>">Je valide mon choix</button>
				</div>
			<?php endif; ?>

		</div>

		<?php $i++; endforeach; ?>
		
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
