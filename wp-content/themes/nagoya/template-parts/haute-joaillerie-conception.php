<?php
/**
 * Template part for displaying page content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php

//Recuperer les elements à afficher 
if( have_rows('section_conception') ): ?>
    <article id="section_conception" class="section_conception kl-sect-conception">

    	<div class="container kl-container-xl-1664">

			<?php while( have_rows('section_conception') ): the_row(); ?>

		    	<div class="content-wrapper mx-auto kl-max-w-1013">		    		
			        <?php if(!empty(get_sub_field('titre'))):  ?>
			            <div class="chapitre-title text-uppercase kl-text-30 kl-ff-montserrat kl-fw-semi-bold kl-mb-md-60 kl-mb-30 text-center">
			                <h2><?php echo get_sub_field('titre'); ?></h2>
					</div><!-- .entry-header -->        	
			    	<?php endif;?>		        

			        <div class="post-content mx-auto">
			        	<?php if(!empty(get_sub_field('description'))):  ?>
			        		<div class="description kl-lh-1_875 kl-fw-light">			        			
			            	<?php echo get_sub_field('description') ?>
			        		</div>
			    		<?php endif;?>

						<?php if(!empty(get_sub_field('lien_de_la_page_contact'))):  ?>
								<div class="btn-wrapper my-5 text-center btn-decouvre">
						            <a title="Contactez-nous" href="<?php echo get_sub_field('lien_de_la_page_contact'); ?>" class="btn d-block mx-auto kl-max-w-250 kl-btn-theme kl-btn-y-center">Contactez-nous</a>
						        </div>
						<?php endif; ?>
			        </div>
		    	</div>    		
		    	<?php if(!empty(get_sub_field('url_video'))):  ?>
		             <div class="kl-chapitre-2-video">
						<div class="kl-parent-video kl-conception-video overflow-hidden position-relative">
								<div class="kl-cover-img">
									<?php if(get_field('image_couverture_video')): ?>
										<img src="<?= get_field('image_couverture_video')['url']; ?>" class="kl-img-cover" alt="">
									<?php else: ?>
										<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/cover-img.jpg'); ?>" class="kl-img-cover" alt="">
									<?php endif; ?>
									<button type="button" class="kl-btn-play js-btn-play">
										<svg xmlns="http://www.w3.org/2000/svg" width="32" height="37" viewBox="0 0 32 37">
											<g id="Polygone_1" data-name="Polygone 1" transform="translate(32) rotate(90)" fill="#fff">
												<path d="M 36.13339233398438 31.5 L 0.8666082620620728 31.5 L 18.5 0.9989981055259705 L 36.13339233398438 31.5 Z" stroke="none"/>
												<path d="M 18.5 1.997978210449219 L 1.733207702636719 31 L 35.26679229736328 31 L 18.5 1.997978210449219 M 18.5 0 L 37 32 L 0 32 L 18.5 0 Z" stroke="none" fill="#707070"/>
											</g>
										</svg>
									</button>
								</div>
							<div class="position-relative h-100">
								<?php echo do_shortcode(get_sub_field('url_video')); ?>
							</div>
						</div>
	                </div>      	
		    	<?php endif;?>
		</div>
		
		<div>
	    		<div class="galleries position-relative kl-conception-galleries">
					<div class="container kl-container-xl-1664">
						<div class="row align-items-center justify-content-center">

							<?php 

								if( have_rows('gallerie_images') ): 
									while( have_rows('gallerie_images') ) : the_row();

												// Get sub value.
												// Load sub field value.
												$image = get_sub_field('image');

												// Image variables.
												$url = $image['url'];
												$title = $image['title'];
												$alt = $image['alt'];
												$caption = $image['caption'];

												// Thumbnail size attributes.
												$size = '529x793';
												$thumb = $image['sizes'][ $size ];
												$width = $image['sizes'][ $size . '-width' ];
												$height = $image['sizes'][ $size . '-height' ];

											?>

												<div class="kl-post-thumbnail col-md-4">
													<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>" />
												</div>

										<?php
									endwhile;
								endif; ?>
						</div>
					</div>
	    		</div>
			<?php endwhile; ?>
    	</div>
		<?php get_template_part( 'template-parts/home', 'valeurs' ); ?>
		<div class="kl-separator-line kl-h-70"></div>
		<div class="kl-chapitre1-conception-wrapper">
			<div class="container kl-container-xl-1664">
				<?php get_template_part( 'template-parts/conception', 'chapitre1' ); ?>
			</div>
		</div>
    </article>

<?php endif; ?>
