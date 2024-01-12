<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php 
// get the current taxonomy term
$term = get_queried_object();

if( have_rows('informations_complementaires', $term) ):
?>
	<section class="info-complementaire-archive kl-section-video-category kl-pt-50 kl-pt-md-110">
		<div class="container kl-container-xl-1664">
	    <?php while( have_rows('informations_complementaires', $term) ): the_row(); ?>
	        <?php if(!empty(get_sub_field('titre'))):  ?>
	            <div class="text-center kl-mb-40 kl-mb-md-70">
					<div class="chapitre-title text-uppercase kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-semi-bold mb-2">
	                	<h2><?php echo get_sub_field('titre'); ?></h2>
					</div>
					<?php if(!empty(get_sub_field('sous-titre'))):  ?>
						<div>
							<span class="fst-italic kl-text-20 kl-color-gold kl-ff-garamond kl-fw-regular"><?php echo get_sub_field('sous-titre'); ?></span>
						</div>
					<?php endif; ?>
				</div>
	        <?php endif; ?> 

	        <?php if(!empty(get_sub_field('description'))):  ?>
	        <div class="kl-lh-1_188 kl-fw-light text-center kl-max-w-lg-750 kl-max-w-xl-950 kl-max-w-xxl_1680-1379 m-auto kl-mb-40 kl-mb-md-70">
	            <?php echo get_sub_field('description'); ?>
	        </div>   	
	        <?php endif; ?> 

	        <?php if(!empty(get_sub_field('lien_du_bouton'))): 
                $lien = get_sub_field('lien_du_bouton');
	        ?>
				<div class="">
					<a href="<?php echo $lien; ?>" class="btn d-block kl-max-w-226 mx-auto kl-btn-theme">
						<?php echo (get_sub_field('texte_du_bouton') ? get_sub_field('texte_du_bouton') : 'Voir');?>
					</a>
				</div>
	        <?php endif; ?> 

	        <?php if(!empty(get_sub_field('video_interpretation'))): ?>
				<div class="kl-mt-55">
					<div class="kl-parent-video overflow-hidden position-relative">
						<?php if(!empty(get_sub_field('image_de_couverture_video_interpretation'))): ?>
							<div class="kl-cover-img">
								<img src="<?php the_sub_field('image_de_couverture_video_interpretation') ?>" class="kl-img-cover" alt="">
								<button type="button" class="kl-btn-play js-btn-play">
									<svg xmlns="http://www.w3.org/2000/svg" width="32" height="37" viewBox="0 0 32 37">
										<g id="Polygone_1" data-name="Polygone 1" transform="translate(32) rotate(90)" fill="#fff">
											<path d="M 36.13339233398438 31.5 L 0.8666082620620728 31.5 L 18.5 0.9989981055259705 L 36.13339233398438 31.5 Z" stroke="none"/>
											<path d="M 18.5 1.997978210449219 L 1.733207702636719 31 L 35.26679229736328 31 L 18.5 1.997978210449219 M 18.5 0 L 37 32 L 0 32 L 18.5 0 Z" stroke="none" fill="#707070"/>
										</g>
									</svg>
								</button>
							</div>
						<?php endif; ?>
						<div class="position-relative h-100">
							<?php echo do_shortcode(get_sub_field('video_interpretation')); ?>
						</div>
					</div>
				</div>
				
	        <?php endif; ?> 

		<?php endwhile; ?>
		</div>
	</section>
<?php endif; ?>
