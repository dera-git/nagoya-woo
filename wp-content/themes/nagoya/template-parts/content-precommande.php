<?php
/**
 * Template part for displaying page content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
$product = wc_get_product($id);
$price = $product->get_price()* 0.4; //- (($product->get_price() * 40) / 100);
$currency_symbol = get_woocommerce_currency_symbol();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('kl-sect-precommand'); ?>>
    <div class="kl-title-precommande kl-bg-green-theme">
		<div class="container kl-container-xl-1664">
			<div class="chapitre-title text-uppercase text-center kl-text-30 kl-color-white kl-ff-montserrat kl-fw-regular">
				<h2><?php the_field('titre_du_page_precommande'); ?> <?= $product->get_name(); ?></h2>				
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-lg-5 col-xl-6">
			<div class="post-thumbnail kl-img-precommand position-relative text-start">
				<?php the_post_thumbnail('882x983'); ?>
			</div>
		</div>	

		<div class="col-md-12 col-lg-7 col-xl-6 d-flex align-items-center">
			<div class="kl-content-precommand">		
				<div class="kl-entry-content mx-auto">
					<?php
						the_content();
					?>
					<?php 
					if (!empty($id)) {
					?>
					<div class="btn-wrapper text-center">
						<?php if(!empty(get_field('texte_bouton_acompte'))): ?>
							<!--<a title="<?php the_field('texte_bouton_acompte'); ?> <?= $price . ' '. $currency_symbol; ?>" href="<?php echo esc_url(wc_get_cart_url()); ?>?add-to-cart=<?php echo $id; ?>" class="btn d-block mx-auto kl-max-w-350 kl-btn-theme kl-btn-y-center kl-btn-h-53 custom_button" name="lvx_add_button"><?php the_field('texte_bouton_acompte'); ?> <?= $price . ' '. $currency_symbol; ?></a>-->
							<!--<a href="<?php echo esc_url($add_to_cart_url); ?>" class="kl-add-to-cart-custom btn d-block mx-auto kl-max-w-350 kl-btn-theme kl-btn-y-center kl-btn-h-53 custom_button" data-product-id="<?php echo $id; ?>"><?php the_field('texte_bouton_acompte'); ?> <?= $price . ' '. $currency_symbol; ?></a>-->
							<form method="post">
								<input type="hidden" name="product_id" value="<?php echo $id; ?>">
								<button type="submit" class="kl-add-to-cart-custom btn d-block mx-auto kl-max-w-350 kl-btn-theme kl-btn-y-center kl-btn-h-53 custom_button"><?php the_field('texte_bouton_acompte'); ?> <?= $price . ' '. $currency_symbol; ?></button>
							</form>
						<?php endif; ?>
					</div>
					<?php
	
					} else {
					}
					?>
				</div>
			</div>
		</div>

	</div>
	<?php get_template_part( 'template-parts/home', 'valeurs' ); ?>
	<div class="kl-separator-line kl-h-70"></div>
	<div class="kl-chapitre1-conception-wrapper">
		<div class="container kl-container-xl-1664">
			<?php get_template_part( 'template-parts/conception', 'chapitre1' ); ?>
		</div>
	</div>
</article><!-- #post-## -->

