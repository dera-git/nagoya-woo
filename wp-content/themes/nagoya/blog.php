<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */
/**
 * Template Name: Blog
 */

get_header(); ?>

    <section id="primary" class="content-area col-sm-12 blog">
        <div id="main" class="site-main" role="main">

            <?php
            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content', 'blog' );

            endwhile; // End of the loop.

            get_template_part( 'template-parts/content', 'posts' );
            ?>



        </div><!-- #main -->
    </section><!-- #primary -->

<?php
//get_sidebar();
get_footer();