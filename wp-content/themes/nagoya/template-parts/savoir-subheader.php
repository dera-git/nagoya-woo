<article id="post-<?php the_ID(); ?>" <?php post_class('kl-sect-savoir-faire'); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>
    <div class="container kl-container-xl-1664">
    	<div class="row">
			<div class="col-md-12 col-xl-5">
				<div class="kl-post-thumbnail kl-image-thumbnail">
					<?php the_post_thumbnail('full', array('class' => 'img-fluid kl-img-cover')); ?>
				</div>
			</div>
			<div class="col-md-12 col-xl-7">
				<div class="entry-content flex-fill">
					<div class="content">
						<div class="chapitre-title text-uppercase text-center kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-semi-bold kl-title-savoir-faire">
							<?php the_title( '<h2>', '</h2>' ); ?>
						</div>
						<!-- <div class="kl-separator-line kl-h-130"></div> -->
						<div class="separator-line before-or position-relative d-flex justify-content-center"></div>
						<div class="kl-description kl-max-w-750 mx-auto">						
							<?php
								the_content();
							?>
						</div>
					</div>
				</div>
			</div>
    	</div>
	</div>

    <?php } ?>


</article><!-- #post-## -->