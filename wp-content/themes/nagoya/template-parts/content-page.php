<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class('text-center'); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>
    <div class="kl-bg-green-theme kl-separator-line d-none d-md-block kl-h-105 kl-top-40"></div>
	<div class="kl-bg-green-theme kl-title-blog kl-title-legal-notice">
		<div class="chapitre-title text-uppercase kl-text-30 kl-color-white kl-ff-montserrat kl-fw-regular kl-mb-15">
			<h2 class="kl-color-gold"><?php the_title() ?></h2>
		</div>
	</div>
    <?php } ?>

	<div class="entry-content">
		<div class="container kl-container-xl-1664 kl-pt-50 kl-pt-md-100 kl-pb-50 kl-pb-md-100">
			<?php
				the_content();
			?>
		</div>
	</div><!-- .entry-content -->

</div><!-- #post-## -->
