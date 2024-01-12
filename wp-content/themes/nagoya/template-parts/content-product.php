
<article id="post-<?php the_ID(); ?>" <?php post_class('text-center col-sm-12 col-md-4 kl-card-post'); ?>>
    <div class="d-flex flex-column h-100">
        <div class="post-thumbnail kl-card-post-thumbnail kl-hover-zoom">
            <a title="<?php echo get_the_title();?>" href="<?php the_permalink(); ?>" class="d-block h-100 w-100" rel="bookmark">
                <?php the_post_thumbnail('full', array('class' => 'img-fluid kl-img-cover')); ?>
            </a>
        </div>

        <div class="content-wrapper flex-grow-1 d-flex flex-column kl-content-wrapper">
            <div class="entry-header mx-auto kl-text-17 kl-ff-montserrat kl-mb-16">
                <?php
                    the_title( '<h3 class="mx-auto post-title text-uppercase"><a title="'.esc_url( get_the_title() ).'" href="' . esc_url( get_permalink() ) . '" class="kl-color-white" rel="bookmark">', '</a></h3>' );
                ?>
            </div><!-- .entry-header -->

            <div class="post-content mx-auto kl-color-white kl-card-post-content">
                <?php the_excerpt(); ?>
            </div>

            <div class="btn-wrapper text-center btn-decouvre mt-auto">
                <a title="<?php echo get_the_title();?>" href="<?php the_permalink(); ?>" class="btn d-block mx-auto kl-max-w-226 kl-btn-theme">Je découvre</a>
            </div>
        </div>

    </div>
</article><!-- #post-## -->