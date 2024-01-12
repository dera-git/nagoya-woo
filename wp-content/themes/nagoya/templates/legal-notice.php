<?php

/**
 * Template Name: Template legal notice
 */
get_header();
?>
<div class="kl-bg-green-theme kl-separator-line d-none d-md-block kl-h-105 kl-top-40"></div>
<div class="kl-bg-green-theme kl-title-blog kl-title-legal-notice">
    <div class="chapitre-title text-uppercase kl-text-30 kl-color-white kl-ff-montserrat kl-fw-regular kl-mb-15">
        <?php the_title('<h2 class="kl-color-gold">', '</h2>'); ?>
    </div>
</div>

<div class="kl-legal-notice">
    <div class="container kl-container-xl-1664">
        <?= the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>