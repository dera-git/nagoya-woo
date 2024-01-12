<?php if ( have_rows('reseaux_sociaux', 'option') ) : ?>
<ul class="p-0 m-0 list-unstyled flex-fill d-flex flex-nowrap align-items-center kl-social-network">
	
		<?php while( have_rows('reseaux_sociaux', 'option') ) : the_row(); ?>

			<?php if(get_sub_field('icone')): ?>
				<li>
					<a class="d-flex" href="<?php echo get_sub_field('lien_du_reseau'); ?>" target="_blank">
						<img alt="..." src="<?php echo get_sub_field('icone')['url']; ?>" width="21" height="21">
					</a>
				</li>
			<?php endif; ?>
	
		<?php endwhile; ?>
	
</ul>
<?php endif; ?>