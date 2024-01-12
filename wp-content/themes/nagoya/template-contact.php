<?php
/**
 * Template Name: Template contact
 */


  get_header();
$fields = get_fields();
?>
<section id="primary" class="content-area col-sm-12">
    <div id="main" class="site-main" role="main">

        <?php
        while (have_posts()) : the_post();

            get_template_part('template-parts/content', 'page');

        endwhile; // End of the loop.
        ?>

    </div><!-- #main -->
</section><!-- #primary -->

</div>
<div class="container">
    <div class="kl-contact">
        <div class="row">
            <div class="col-md-6 kl-form-contact">
                <?php echo do_shortcode('[contact-form-7 id="499" title="Contact form 1"]'); ?>
            </div>
            <div class="col-md-6 kl-coordonnee">
                <div class="mx-auto logo-wrapper flex-fill text-center d-flex justify-content-center align-items-center h-100">
                    <div class="w-100">
                        <?php if (get_theme_mod('wp_bootstrap_starter_logo')) : ?>
                            <img class="svgblack kl-logo" src='https://nagoya.preproduction.run/wp-content/uploads/2022/12/logonoir.svg'>
                        <?php else : ?>
                            <a class="d-inline-block mx-auto site-title kl-site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_url(bloginfo('name')); ?></a>
                        <?php endif; ?>
                        <div>
                            <?php the_field('coordonnees'); ?>
                        </div>
                        <div class="kl-social-media">
                            <!-- Block suivez-nous -->
                            <div class="block-container suiveznous my-4">
                                <?php get_template_part('templates/suivez', 'nous'); ?>
                            </div>
                            <!-- /Block suivez-nous -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//get_sidebar();
get_footer();
