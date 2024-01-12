<?php
/**
 * Template Name: Template Valeurs / Savoir-faire
 */

get_header();
?>

    <section id="primary" class="savoir-faire-page content-area col-sm-12">
        <div id="main" class="site-main" role="main">

            <div id="content-wrapper" class="pb-5 position-relative">
                <?php
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'valeurs' );

                    get_template_part( 'template-parts/savoir', 'section1' );
                    
                    // get_template_part( 'template-parts/savoir', 'section2' );

                endwhile; // End of the loop.                

                //get_template_part( 'template-parts/content', 'partenaires' );
                
                ?>

            </div>

            <?php get_template_part( 'template-parts/home', 'valeurs' ); ?>

            <div class="chapitre4-wrapper kl-savoir-faire-chap4">
                <div class="container kl-container-xl-1664">
                    <?php get_template_part( 'template-parts/home', 'chapitre4' ); ?>
                </div>
            </div>


        </div><!-- #main -->
    </section><!-- #primary -->

<?php
//get_sidebar();
get_footer();