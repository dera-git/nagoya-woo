<main id="post-<?php the_ID(); ?>" <?php post_class('text-center content-home kl-main-content'); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>

	<section class="post-thumbnail position-relative bg-window kl-section-home-thumbnail">
		<div class="container kl-container-xl-1664">
			<?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
		</div>
	</section>

    <?php } ?>
	<section class="chapitre1-wrapper position-relative kl-section-chapitre1">
		<div class="container kl-container-xl-1664">
			<?php get_template_part( 'template-parts/home', 'chapitre1' ); ?>
		</div>
	</section>

	<div class="kl-separator-line kl-h-112 kl-separator-top"></div>

	<section class="chapitre2-wrapper kl-section-chapitre2">
		<div class="container kl-container-xl-1664">
			<?php get_template_part( 'template-parts/home', 'chapitre2' ); ?>
		</div>
	</section>    

    <div class="kl-separator-line kl-h-72"></div>

	<section class="chapitre3-wrapper kl-section-chapitre3">
		<div class="container kl-container-xl-1664">
			<?php get_template_part( 'template-parts/home', 'chapitre3' ); ?>
		</div>
	</section>

    <?php get_template_part( 'template-parts/home', 'valeurs' ); ?>

    <div class="kl-separator-line kl-separator-down-md kl-h-112 kl-h-mob-72"></div>

	<section class="chapitre4-wrapper kl-section-chapitre4">
		<div class="container kl-container-xl-1664">
			<?php get_template_part( 'template-parts/home', 'chapitre4' ); ?>
		</div>
	</section>

</main><!-- #post-## -->
