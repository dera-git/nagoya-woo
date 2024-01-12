<?php
/**
 * Template Name: Template Precommande
 */

get_header();
?>

	<section id="primary" class="precommande-paiement content-area">
		<div id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'precommande' );

			endwhile; // End of the loop.
			?>

		</div><!-- #main -->
	</section><!-- #primary -->

<?php
//get_sidebar();
get_footer();