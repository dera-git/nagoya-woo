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

do_action( 'woocommerce_before_add_to_cart_form' ); 

?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php 

			$i = 0;
			foreach ( $attributes as $attribute_name => $options ) : 
			$attr_name = $attribute_name;

			$tableAttr = [];

			$termsAttr = wc_get_product_terms( $product->id, $attr_name, array( 'fields' =>  'all' ) );

			if( $termsAttr ){
		        foreach ( $termsAttr as $term ){
		            $tableAttr[strtolower($term->name)] = term_description( $term->term_id );

		            if(term_description( $term->term_id )): ?>
		            	<div class="d-none toInsertHtml" data-attr="<?php echo strtolower($term->name); ?>"><?php echo term_description( $term->term_id );?></div>	

		            <?php endif;
		        }
			}
		?>

		<div id="<?php echo $attribute_name; ?>" class="attributes-wrapper kl-attributes-wrapper <?php echo ($i == 0) ? '' : 'd-none position-relative'; ?>">

		<?php if($attribute_name != 'pa_color' && $attr_name != 'pa_multiple'): ?>
			<div class="d-flex justify-content-between align-items-start">
				<h2 class="product_title entry-title kl-mb-40">
					<?php echo renderTitleBloc($attr_name) ?>
				</h2>
				<button type="button" class="handleClose btn p-0 kl-btn-handeClose" data-toggle="#<?php echo $attr_name?>">
					<span>+</span>
				</button>
			</div>
		<?php endif ?>

		<?php if($attr_name == 'pa_police'): ?>
			<div class="kl-mb-35">Je choisis ma police</div>
		<?php endif ?>

		<?php if($attr_name != 'pa_color' && $attr_name != 'pa_police'):
				get_template_part( 'template-parts/single-product', 'parts_'.$attr_name, array($attr_name) );
			endif;
		?>

			
			<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
				<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
			<?php else : ?>
				<table class="variations <?php echo $attribute_name; ?>" cellspacing="0" role="presentation">
					<tbody>
						<?php foreach ( $attributes as $attribute_name => $options ) :
							if($attribute_name == $attr_name):
							?>
							<tr>
								<!-- <th class="label">
									<label class="mb-0" for="<?php //echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php //echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?>
									</label>
								</th> -->
								<td class="value" data-attr="<?php echo $attr_name ?>">
									<div class="d-flex-wrapper kl-wc-variation kl-variation-<?php echo $attr_name ?>">	
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
									<?php if($attr_name == 'pa_taille'): ?>
										<div class="woocommerce-variation-taille kl-woocommerce-variation-taille d-flex align-items-center">
											<label for="taille" class="kl-fw-regular">Je saisie ma taille</label>
											<input class="kl-input-text-variation" type="text" name="taille" id="taille" value="">
										</div>
									<?php endif ?>
								</td>
							</tr>
						<?php endif; endforeach; ?>
					</tbody>
				</table>

				<?php 
					if($attr_name == 'pa_color'): ?>

						<?php foreach ( $attributes as $attribute_name => $options ) :

						if($attribute_name == 'pa_pierre'): ?>
						<button id="id-pa_pierre" data-handle="attribute_pa_pierre" data-toggle="#pa_pierre" type="button" class="kl-title-variation js-btn-modal-variation show-parts w-100 btn rounded-0 mb-2 d-flex align-items-center justify-content-between">
							<span class="texte kl-color-black kl-text-15">Je choisis ma pierre</span>
							+
						</button>
						<?php endif;?>

						<?php if($attribute_name == 'pa_taille'): ?>
						<button id="id-pa_taille" data-handle="attribute_pa_taille" data-toggle="#pa_taille" type="button" class="kl-title-variation js-btn-modal-variation show-parts w-100 btn rounded-0 mb-2 d-flex align-items-center justify-content-between">
							<span class="texte kl-color-black kl-text-15">Je choisis ma taille</span>
							+
						</button>
						<?php endif;?>
						<?php if($attribute_name == 'pa_police'): ?>
						<button id="id-pa_police" data-handle="attribute_pa_police" data-toggle="#pa_police" type="button" class="kl-title-variation js-btn-modal-variation show-parts w-100 btn rounded-0 mb-2 text-start">
							<span class="texte kl-color-black kl-text-15">Je souhaite faire graver mon bijou</span>
						</button>
						<?php endif;?>
					
					<?php endforeach; ?>	

					<?php endif; 
					if($attr_name == 'pa_police'): 
						get_template_part( 'template-parts/single-product', 'parts_'.$attr_name );
					endif; 
					
					if($attr_name == 'pa_taille'): ?>

						<!-- Button trigger modal -->
						<div class="w-100 mb-4">
							<button type="button" class="btn kl-btn-bordered-black togglerModal kl-mt-16" data-bs-toggle="modal" data-bs-target="#tutoVideo">
							  Bouton Tuto vidéo
							</button>
						</div>
						<?php if(!empty(get_field('description_taille_produits', 'option'))): ?>
							<div class="kl-link-black kl-mb-20">
								<?php the_field('description_taille_produits', 'option') ?>
							</div>
						<?php endif ?>
				<?php
					endif; 
				?>

			<?php endif; ?>

			<?php if($attr_name != 'pa_color' & $attr_name != 'pa_multiple'): ?>
				<div class="btn-or-wrapper w-100 mt-auto">					
					<button class="btn-or handleClose kl-secondary-btn-theme" data-toggle="#<?php echo $attr_name;?>">
						<span>Je valide mon choix</span>
					</button>
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
