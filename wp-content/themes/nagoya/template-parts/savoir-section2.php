<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>


<?php

//Recuperer les elements à afficher 
if( have_rows('section_2') ): 

	$class = '';

	if(empty(get_sub_field('titre')) && empty(get_sub_field('description'))):
		$class = 'no-title-and-description';
	endif;
?>

    <article id="section_2" class="<?php echo $class; ?> kl-savoir-faire-chap2">
		<div class="container kl-container-xl-1664">
			<div class="row">
				<?php while( have_rows('section_2') ): the_row(); ?>
					<div class="col-md-6 d-flex align-items-center">
						<div class="text-wrapper chapitre kl-max-w-708 kl-mb-md-0 kl-mb-30">
							
							<?php if(!empty(get_sub_field('titre'))):  ?>
								<div class="kl-mb-30">
									<div class="text-uppercase kl-text-30 kl-ff-montserrat kl-fw-regular">
										<h2><?php echo get_sub_field('titre'); ?></h2>
									</div>
									<?php if(!empty(get_sub_field('sous_titre'))):  ?>
										<span class="kl-sub-title"><?php echo get_sub_field('sous_titre'); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
		
							<?php if(!empty(get_sub_field('description'))):  ?>
								<div class="kl-text-20 kl-ff-montserrat kl-fw-regular mx-auto mt-0 text-justify kl-mb-md-60 kl-mb-30">
									<?php echo get_sub_field('description'); ?>
								</div><!-- .entry-content -->
							<?php endif; ?>
		
							<div class="btn-contact-wrapper">
								<a href="/contact" class="btn d-block kl-max-w-260 kl-btn-theme kl-btn-y-center">Une question ?  Contactez-nous</a>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<?php if(!empty(get_sub_field('image_illustration'))): 
							$image = get_sub_field('image_illustration'); ?>
						<div class="image-wrapper kl-img-illustration">
							<img class="mx-auto" src="<?php echo $image['url']; ?>" alt="<?php echo $image['url']; ?>">
						</div>
						<?php endif; ?>
					</div>
	
				<?php endwhile; ?>
			</div>
		</div>
    </article><!-- #post-## -->

<?php endif; ?>
