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
if( have_rows('section_piece') ): ?>
    <article id="section_piece" class="section_piece kl-sect-piece">

    	<div class="container kl-container-xl-1664">

    		<div class="row">

			    <?php while( have_rows('section_piece') ): the_row(); ?>

			        <?php if(!empty(get_sub_field('image'))):  
				        // Load sub field value.
				        $image = get_sub_field('image');

					    // Image variables.
					    $url = $image['url'];
					    $title = $image['title'];
					    $alt = $image['alt'];
					    $caption = $image['caption'];

					    // Thumbnail size attributes.
					    $size = '640x640';
					    $thumb = $image['sizes'][ $size ];
					    $width = $image['sizes'][ $size . '-width' ];
					    $height = $image['sizes'][ $size . '-height' ];
					?>
					<div class="col-md-6">
				        <div class="post-thumbnail kl-post-thumbnail">
				            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>" />
				        </div>
					</div>
			    	<?php endif;?>
					<div class="col-md-6 d-flex align-items-center mt-md-0 mt-5">
						<div class="content-wrapper kl-max-w-677">
							<?php if(!empty(get_sub_field('titre'))):  ?>
								<div class="chapitre-title text-uppercase kl-text-30 kl-ff-montserrat kl-fw-semi-bold kl-mb-md-60 kl-mb-30">
									<h2><?php echo get_sub_field('titre'); ?></h2>
								</div>
							<?php endif;?>
	
							<div class="mx-auto kl-fw-light kl-lh-1_875">
								<?php if(!empty(get_sub_field('description_de_la_piece'))):  ?>
									<?php echo get_sub_field('description_de_la_piece') ?>
								<?php endif;?>
	
								<?php if(!empty(get_sub_field('lien_de_la_page_renseignements'))):  ?>
										<div class="btn-wrapper mt-5 mb-md-5 mb-3 text-center btn-decouvre">
											<a title="Je souhaite des renseignements" href="<?php echo get_sub_field('lien_de_la_page_renseignements'); ?>" class="btn d-block kl-max-w-250 mx-md-0 mx-auto kl-btn-theme kl-btn-y-center">Je souhaite des renseignements</a>
										</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
			    <?php endwhile;?>
    		</div>
    	</div>

    </article>

<?php endif; ?>
