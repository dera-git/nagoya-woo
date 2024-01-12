<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

	<section id="primary" class="single content-area col-sm-12">
		<div id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

		endwhile; // End of the loop.
		?>

		<div class="btn-contact-wrapper text-center mt-5 kl-pb-85 kl-pb-lg-172">
			<a href="/contact" class="btn-contact d-block mx-auto kl-max-w-226 kl-btn-theme w-100 btn btn-outline-info kl-btn-border-noir border-radius-0">Une question ?  Contactez-nous</a>
		</div>

		</div><!-- #main -->
	</section><!-- #primary -->

<?php
//get_sidebar();
get_footer();
