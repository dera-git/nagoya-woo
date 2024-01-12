<?php
/**
 * Template Name: Template Haute Joaillerie
 */

get_header();
?>

    <section id="primary" class="haute-joaillerie-page content-area col-sm-12">
        <div id="main" class="site-main" role="main">

            <div id="content-wrapper" class="position-relative">
                <?php
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'haute-joaillerie' );

                    get_template_part( 'template-parts/savoir', 'section1' );
                    
                    get_template_part( 'template-parts/savoir', 'section2' );

                endwhile; // End of the loop.                

                //get_template_part( 'template-parts/content', 'partenaires' );
                
                ?>

            </div>

        </div><!-- #main -->
    </section><!-- #primary -->

<?php
//get_sidebar();
get_footer();