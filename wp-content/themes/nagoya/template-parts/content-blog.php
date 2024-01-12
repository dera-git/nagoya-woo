<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('kl-sect-blog'); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>
	
	<div class="kl-title-blog">
		<div class="chapitre-title text-uppercase kl-text-30 kl-ff-montserrat kl-fw-semi-bold mb-2">
			<?php the_title( '<h2>', '</h2>' ); ?>
		</div>
		<?php if(!empty(get_field('sous_titre_blog'))): ?>
			<div class="fst-italic kl-text-20 kl-ff-garamond kl-fw-regular">
				<?= get_field('sous_titre_blog'); ?>
			</div>
		<?php endif; ?>
	</div>
    <?php } ?>

	<div class="entry-content mt-0">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-bootstrap-starter' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
