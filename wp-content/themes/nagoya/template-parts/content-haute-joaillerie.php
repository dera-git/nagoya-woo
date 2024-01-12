<?php
/**
 * Template part for displaying page content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('text-center first-section'); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>

    <?php } ?>
	<?php 
		$product_id = get_field('produit_haute_joaillerie', 'option')->ID;
		$product = wc_get_product($product_id);
	?>
	<div class="kl-bg-green-theme kl-separator-line kl-h-105 kl-top-40"></div>
		<section class="kl-sect-haute-joaillerie kl-bg-green-theme">
			<div class="container kl-container-1112">
				<div class="chapitre-title text-uppercase kl-text-30 kl-color-white kl-ff-montserrat kl-fw-regular kl-mb-40">
					<?php the_title( '<h2>', '</h2>' ); ?>
				</div>
				<div class="kl-description kl-color-white kl-mb-60">
					<?php
						the_content();
					?>
				</div>
				<div class="kl-post-thumbnail">
					<?php 
						if ($product) {
							$image_html = $product->get_image();
							echo $image_html;
						} else {
							echo 'Aucun produit selectionner';
						}
					?>
					<?php //the_post_thumbnail(); ?>
				</div>
			</div>
		</section>
	<div id="content-page" class="entry-content kl-haute-joaillerie mt-0 mx-auto position-relative container kl-container-1112">
		<?php //if(!empty(get_field('prix'))):  ?>
		    <div class="prix mx-auto text-center kl-prix">
		        <?php //echo get_field('prix');
				if ($product) {
					$price = $product->get_price();
					$price_2 = $price / 3;
    				$currency_symbol = get_woocommerce_currency_symbol();
					echo $price . ' ' . $currency_symbol;
					echo ' ou 3x ' . number_format($price_2, 2) . ' '. $currency_symbol;
				} else {

				}
				?>
		    </div><!-- .entry-content -->
		<?php //endif; ?>

		<?php //if(!empty(get_field('information_concernant_le_prix'))):  ?>
		    <div class="information-prix mx-auto text-center kl-information-prix">
		        <?php //echo get_field('information_concernant_le_prix'); 
					if ($product) {
						echo $product->get_description();
					} else {
					}
				?>
		    </div><!-- .entry-content -->
		<?php //endif; ?>

		<?php if(!empty(get_field('lien_de_la_page_pre-commande'))):  ?>
		<div class="btn-wrapper kl-mt-30 kl-mb-60 text-center btn-decouvre">
			<?php if(!empty($product_id)): ?>
            	<a title="Pré-commander la pièce" href="<?php echo get_field('lien_de_la_page_pre-commande'); ?>?id=<?= $product_id; ?>" class="btn d-block mx-auto kl-max-w-226 kl-btn-theme kl-btn-y-center">Pré-commander la pièce</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->

<?php get_template_part('template-parts/haute-joaillerie', 'piece'); ?>
<?php get_template_part('template-parts/haute-joaillerie', 'conception'); ?>
