<?php
$query = get_post_all_posts('post');
if ( $query->have_posts() ) : ?>
	<div class="kl-sect-list-post">
		<div class="container kl-container-xl-1664">
			<div class="" id="id-blog">
		
		
					<?php
					$i = 0;
					while ( $query->have_posts() ) : $query->the_post();
						if ($i % 3 == 0) {
							if ($i % 2 == 0) {
								echo '<div class="row kl-row-odd kl-row">';
							} else {
								echo '<div class="row kl-row-pair kl-row">';
							}
						}
						?>
						
						<div id="post-<?php the_ID(); ?>" class="col-md-4 kl-post">
							<div class="kl-post-item">
								<div class="kl-post-thumbnail">
									<a title="<?php echo get_the_title();?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail(''); ?></a>
								</div>
		
								<div class="kl-entry-header kl-mt-20">
									<?php
		
										the_title( '<h2><a title="'.esc_url( get_the_title() ).'" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		
									?>
		
								</div>
		
								<div class="btn-wrapper text-center kl-mt-20">
									<a title="<?php echo get_the_title();?>" href="<?php the_permalink(); ?>" class="btn d-block mx-auto kl-max-w-226 kl-btn-theme">Lire la suite</a>
								</div>
							</div>
						</div>
		
					<?php
					$i++;
					if ($i % 3 == 0) {
						echo '</div>';
					}
					endwhile;
					?>
					
				
			</div>
		</div>
	</div>
	<?php
endif;
?>