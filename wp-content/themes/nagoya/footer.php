<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	</div><!-- #content -->
    <?php if(is_woocommerce() && !is_archive()): ?>           
          <div class="valeurs-wrapper">
              <?php get_template_part( 'template-parts/home', 'valeurs' ); ?>
          </div>    
    <?php endif; ?>
	<footer id="colophon" class="site-footer <?php if(is_product()): echo 'kl-footer-product'; endif; ?>" role="contentinfo">
		<div class="container kl-container-xl-1664">
            <div class="mx-auto logo-wrapper flex-fill text-center kl-mb-40 kl-mb-lg-80 kl-footer-logo">
              <a class="d-inline-block mx-auto" href="<?php echo esc_url( home_url( '/' )); ?>">
                  <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/logo/logo-black.svg'); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> " width="166px">
              </a>
            </div>
            <div class="menu-wrapper">
                <?php get_template_part( 'footer-widget' ); ?>
            </div>
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->


<!-- Modal -->
<?php if(!empty(get_field('tuto_video'))): 
    $id_video = str_replace('https://www.youtube.com/watch?v=', '', get_field('tuto_video', false, false));
?>

<div class="modal fade" id="tutoVideo" tabindex="-1" role="dialog" aria-labelledby="tutoVideoLabel" aria-hidden="true" data-src="<?php echo $id_video; ?>">
  <div class="modal-dialog modal-dialog-centered kl-modal-video" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tuto Vidéo</h5>
        <button type="button" class="close btn kl-btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="video-container mx-auto">
            <?php the_field('tuto_video'); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>



<?php wp_footer(); ?>

</body>
</html>