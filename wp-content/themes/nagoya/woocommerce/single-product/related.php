<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>
	<div class="kl-separator-line kl-h-72 kl-mt-50 kl-mt-md-135">
		<div class="container kl-container-xl-1664">
			<div class="kl-line-horizontal"></div>
		</div>
	</div>
	<section class="position-relative kl-section-chapitre1 kl-section-related-product">
		<div class="container kl-container-xl-1664">	
			<div class="kl-line-horizontal-bot kl-pb-50 kl-pb-md-80">	
				<?php
				$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Vous aimerez aussi', 'woocommerce' ) );

				if ( $heading ) :
					?>
					<div class="kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-semi-bold kl-letter-space-2 kl-mb-40 kl-mb-md-90">
						<h2 class="chapitre-title text-uppercase border-0 text-center"><?php echo esc_html( $heading ); ?></h2>
					</div>
				<?php endif; ?>

				<?php woocommerce_product_loop_start(); ?>
					<div class="kl-chapitre-1-products row kl-gx-16 kl-gy-40 position-relative">

						<?php foreach ( $related_products as $related_product ) : ?>

								<?php
								$post_object = get_post( $related_product->get_id() );

								setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

								//wc_get_template_part( 'content', 'product' );

								get_template_part( 'template-parts/content', 'product' ); 
								?>

						<?php endforeach; ?>
					</div>
				<?php woocommerce_product_loop_end(); ?>
		</div>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
