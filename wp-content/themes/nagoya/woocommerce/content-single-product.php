<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'kl-body-single-product', $product ); ?>>
	<section class="kl-section-detail-product">
		<div class="container kl-container-xl-1664">
			<div class="d-flex flex-column flex-lg-row align-items-stretch justify-content-md-between">
				<div class="woocommerce_before_single_product_summary d-flex flex-column kl-flex-50">	
					<?php
					/**
					 * Hook: woocommerce_before_single_product_summary.
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
					?>
					<?php /* $attachment_ids = $product->get_gallery_attachment_ids();
					if($attachment_ids): ?>
						<div class="kl-slick-gallery js-slick-gallery">
							<?php
								foreach( $attachment_ids as $attachment_id ):
								$image_link =wp_get_attachment_url( $attachment_id );
								?>
								<div class="kl-slick-gallery-item">
									<div>
										<img class="img-fluid kl-img-cover" src="<?php echo $image_link ?>">
									</div>
								</div>
							<?php endforeach ?>
						</div>
					<?php else: ?>
						<div class="kl-slick-gallery js-slick-gallery">
							<div class="kl-slick-gallery-item">
								<div>
									<?php the_post_thumbnail('full', array('class' => 'img-fluid kl-img-cover')) ?>
								</div>
							</div>
						</div>
					<?php endif */?>
				</div>	
				<!--section class="green"-->
				<div class="summary entry-summary mb-0 kl-flex-50 js-variation-content kl-mt-50 kl-mt-lg-30">
					<div class="summary-wrapper kl-detail-product">			
						<?php
						/**
						 * Hook: woocommerce_single_product_summary.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						do_action( 'woocommerce_single_product_summary' );

						?>
					</div>
				</div>
				<!--/section-->
			</div>
		</div>
	</section>

	<section class="overflow-hidden kl-section-stylist-word">
		<div class="container kl-container-xl-1664">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="mot-du-stylisite kl-max-w-565">
						<?php if(!empty(get_field('mot_du_styliste'))):  ?>

							<?php if(!empty(get_field('titre_mot_du_styliste', 'option'))): ?>
								<div class="kl-text-25 kl-ff-garamond kl-fw-regular fst-italic">
									<h4><?php the_field('titre_mot_du_styliste', 'option'); ?></h4>
								</div>
							<?php endif; ?>
							
							<div class="entry-styliste kl-lh-1_188 kl-fw-light kl-mt-20">
								<?php the_field('mot_du_styliste'); ?>
							</div>

							<?php if(!empty(get_field('libelle_du_bouton_mot_styliste', 'option'))):
								$url = get_field('lien_du_bouton_mot_styliste', 'option') ?>
								<div class="btn-decouvre kl-mt-40">
									<a href="<?= $url ? $url : '#' ?>" class="btn d-block kl-ff-garamond kl-fw-bold kl-max-w-260 kl-btn-theme">
										<?php the_field('libelle_du_bouton_mot_styliste', 'option'); ?>
									</a>
								</div>
							<?php endif; ?>

						<?php endif; ?>
					</div>
				</div>
				<div class="col-lg-6 d-none d-lg-block">
					<?php get_template_part( 'template-parts/single-product', 'images-supplementaires' ); ?>
				</div>
			</div>
		</div>
	</section>


	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
