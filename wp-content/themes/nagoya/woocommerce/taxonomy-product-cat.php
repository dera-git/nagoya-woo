<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();

?>

<section class="kl-section-header-taxonomy">
	<div class="container kl-container-xl-1664">
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<div class="woocommerce-products-header__title page-title kl-color-gold kl-bloc-title-cat">
				<?php woocommerce_page_title(); ?>
			</div>
		<?php endif; ?>
		<div class="kl-desc-category kl-text-20 fst-italic kl-ff-garamond kl-lh-1_875 kl-mt-30 kl-mt-md-50 kl-max-w-1088 mx-auto text-center">
			<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );
			?>
		</div>
	</div>
</section>

<?php 
	$current_category = get_queried_object(); // Get the current category
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'order' => 'DESC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $current_category->term_id,
			),
		),
	);

	$query = new WP_Query( $args );
?>

<?php $i = 0; if ( $query->have_posts() ) : ?>
<section class="kl-section-product-cat">
	<div class="container kl-container-xl-1664">
		<div class="row kl-gx-30 kl-gy-50 kl-gy-md-100">
			<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<?php 
						$price = $product->get_price();
						$currency = get_woocommerce_currency_symbol();
						$product_id = get_the_ID();
						$product_attributes = wc_get_product( $product_id )->get_attributes();
						$color_terms = array();

						if ( isset( $product_attributes['pa_color'] ) ) {
							$attribute_options = $product_attributes['pa_color']->get_terms();
							foreach ( $attribute_options as $option ) {
								$color_terms[] = $option->name;
							}
						}

						if(wp_is_mobile()){
							$class_block = $i > 7 ? 'kl-animate-block' : '';
							$dNone = $i > 7 ? 'display: none' : '';
							$dNoneBtn = $i < 8 ? 'd-none' : '';
						}else{
							$class_block = $i > 11 ? 'kl-animate-block' : '';
							$dNone = $i > 11 ? 'display: none' : '';
							$dNoneBtn = $i < 12 ? 'd-none' : '';
						}
						
					?>

					<div class="col-md-6 col-lg-4 kl-product-cat-col <?php echo $class_block ?>" style="<?php echo $dNone ?>">
						<div class="card border-0 kl-cart-product-cat h-100">
							<a href="<?php the_permalink() ?>" class="d-inline-block kl-thumb-product-cat">
								<?php the_post_thumbnail('full', array('class' => 'imf-fluid kl-img-cover')) ?>
							</a>
							<div class="card-body py-0 d-flex flex-column justify-content-between kl-mt-20">
								<div class="text-center kl-color-black mb-2 pb-1 kl-text-type">
									<div class="kl-text-20 kl-ff-garamond kl-fw-regular fst-italic kl-mb-10">
										<?= $price?> <?= $currency ?>
									</div>
									<a href="<?php the_permalink() ?>" class="kl-link">
										<p class="text-uppercase kl-text-20 kl-ff-montserrat kl-fw-semi-bold"><?php the_title() ?></p>
									</a>
									<?php if ( ! empty( $color_terms ) ) : ?>
										<div class="kl-text-20 kl-ff-garamond kl-fw-regular fst-italic mt-2">
											<?php echo esc_html( implode( ', ', $color_terms ) ); ?>
										</div>
									<?php endif; ?>
								</div>
								<a href="<?php the_permalink() ?>" class="btn d-block kl-ff-garamond kl-fw-bold kl-max-w-110 kl-btn-theme kl-small-btn-theme mx-auto">Je découvre</a>
							</div>
						</div>
					</div>

					<?php
					$i++;
				endwhile;
			?>
		</div>
		<div class="text-center kl-mt-50 kl-mt-md-100 <?php echo $dNoneBtn ?>">
			<button type="button" class="js-load-more-post btn kl-btn-theme kl-ff-garamond kl-fw-bold">Charger la suite</button>
		</div>
	</div>
</section>
<?php wp_reset_postdata() ?>
<?php endif ?>

<?php get_template_part( 'template-parts/home', 'valeurs' ); ?>

<?php get_template_part( 'template-parts/content', 'product_cat' ); ?>

<?php get_footer() ?>