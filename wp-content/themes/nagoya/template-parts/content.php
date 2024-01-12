<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('text-center'); ?>>
	<!-- <header class="entry-header"> -->
		<?php
		//if ( is_single() ) :
			//the_title( '<h1 class="entry-title mx-auto border-0">', '</h1>' );
		//else :
			//the_title( '<h2 class="entry-title mx-auto border-0"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		//endif;

		//if ( 'post' === get_post_type() ) : ?>
		<!-- <div class="entry-meta">
			<?php //wp_bootstrap_starter_posted_on(); ?>
		</div> -->
		<!-- .entry-meta -->
		<?php
		//endif; ?>
	<!-- </header> -->
	<!-- .entry-header -->
	
	<div class="container kl-container-xl-1664">
		<div class="kl-pt-55 kl-pt-md-115 kl-pb-75 kl-pb-md-149 kl-fw-bold fst-italic">

			<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title mx-auto border-0 kl-title-single">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title mx-auto border-0"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) : ?>
			<!-- <div class="entry-meta">
				<?php //wp_bootstrap_starter_posted_on(); ?>
			</div> -->
			<!-- .entry-meta -->
			<?php
			endif; ?>

		</div>
		<div class="post-thumbnail kl-post-thumbnail kl-pb-40 kl-pb-md-85">
			<?php the_post_thumbnail(); ?>
		</div>
		<div class="kl-separator-line kl-h-112 d-none d-md-block"></div>
		<div class="entry-content kl-pt-30 kl-pt-md-192 kl-content-single-post">
			<?php
			if ( is_single() ) :
				the_content();
			else :
				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wp-bootstrap-starter' ) );
			endif;

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-bootstrap-starter' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div>

	<!-- <footer class="entry-footer">
		<?php //wp_bootstrap_starter_entry_footer(); ?>
	</footer> -->
	<!-- .entry-footer -->
</article><!-- #post-## -->
