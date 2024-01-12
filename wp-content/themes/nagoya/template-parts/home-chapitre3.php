<?php

//Recuperer les elements à afficher 
if( have_rows('chapitre_3') ): ?>

    <div id="chapitre-3" class="chapitre">

        <?php while( have_rows('chapitre_3') ): the_row(); ?>

            <?php if(!empty(get_sub_field('titre_du_chapitre_3'))):  ?>
                <div class="entry-header text-center kl-mb-40 kl-mb-md-75">
                    <div class="chapitre-title text-uppercase kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-semi-bold mb-2">
                        <h2><?php echo get_sub_field('titre_du_chapitre_3'); ?></h2>
                    </div>
                    <div>
                        <?php if(!empty(get_sub_field('sous_titre_du_chapitre_3'))):  ?>
                            <span class="fst-italic kl-text-20 kl-color-gold kl-ff-garamond kl-fw-regular">
                                <?php echo get_sub_field('sous_titre_du_chapitre_3'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty(get_sub_field('description_du_chapitre_3'))):  ?>
                <div class="entry-content mx-auto mt-0">
                    <?php echo get_sub_field('description_du_chapitre_3'); ?>
                </div><!-- .entry-content -->
            <?php endif; ?>

            <?php 
                $savoirs = get_sub_field('liste_des_valeurs__savoir-faire');

                if(!empty($savoirs)) : ?>

                <div class="chapitre-3-savoirs savoirs position-relative">

                    <?php
                        foreach( $savoirs as $key => $post):
                            $flexDirection = $key % 2 != 0 ? 'flex-row-reverse': '';
                            $class_px = $key % 2 != 0 ? 'kl-pe-xl-75 kl-ps-xl-75 kl-pe-xxl-135 kl-ps-xxl-175': 'kl-ps-xl-75 kl-pe-xl-75 kl-ps-xxl-135 kl-pe-xxl-135';
                            setup_postdata($post);
                    ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('kl-post-page-bloc'); ?>>
                            <div class="row align-items-center kl-gx-30 kl-gx-lg-70 <?= $flexDirection ?>">
                                <div class="col-md-6 kl-post-thumbnail-col">
                                    <?php the_post_thumbnail('full', array('class' => 'img-fluid kl-img-cover')); ?>
                                </div>

                                <div class="col-md-6 <?= $class_px ?>">
                                    <div class="entry-header text-start kl-text-25 kl-ff-garamond fst-italic kl-mb-25">
                                        <h3><?php the_title() ?></h3>
                                    </div>

                                    <div class="post-content kl-fw-light text-start kl-lh-1_188">
                                        
                                        <?php echo get_the_excerpt(); ?>

                                        <div class="btn-wrapper kl-mt-40 kl-mt-lg-60">
                                            <a title="<?php echo get_the_title();?>" href="<?php the_permalink(); ?>" class="btn d-block kl-max-w-226 kl-btn-theme">En savoir +</a>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </article><!-- #post-## -->
                    <?php endforeach; 
                        wp_reset_postdata();
                    ?>

                </div>

                <?php endif; ?> 
        <?php endwhile; ?>

    </div><!-- #post-## -->

<?php endif; ?>
