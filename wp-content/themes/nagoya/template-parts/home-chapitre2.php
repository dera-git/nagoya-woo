<?php

//Recuperer les elements à afficher 
if( have_rows('chapitre_2') ): ?>

    <article id="chapitre-2" class="chapitre">

        <?php while( have_rows('chapitre_2') ): the_row(); ?>

            <?php if(!empty(get_sub_field('titre_du_chapitre_2'))):  ?>
                <div class="entry-header text-center kl-mb-40 kl-mb-md-75">
                    <div class="chapitre-title text-uppercase kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-semi-bold mb-2">
                        <h2><?php echo get_sub_field('titre_du_chapitre_2'); ?></h2>
                    </div><!-- .entry-header -->
                    <div>
                        <?php if(!empty(get_sub_field('sous_titre_du_chapitre_2'))):  ?>
                            <span class="fst-italic kl-text-20 kl-color-gold kl-ff-garamond kl-fw-regular">
                                <?php echo get_sub_field('sous_titre_du_chapitre_2'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty(get_sub_field('description_du_chapitre_2'))):  ?>
                <div class="entry-content mx-auto kl-lh-1_188 kl-fw-light kl-mb-40 kl-mb-md-70">
                    <?php echo get_sub_field('description_du_chapitre_2'); ?>
                </div><!-- .entry-content -->
            <?php endif; ?>

            <?php if(!empty(get_sub_field('lien_du_bouton_du_chapitre_2'))):  ?>
                <div class="btn-wrapper text-center">
                    <a title="<?php echo get_sub_field('lien_du_bouton_du_chapitre_2');?>" href="<?php echo get_sub_field('lien_du_bouton_du_chapitre_2') ?>" class="btn d-block mx-auto kl-max-w-226 kl-btn-theme">
                        <?php echo get_sub_field('libelle_du_bouton_du_chapitre_2'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php 

                if(!empty(get_sub_field('video_du_chapitre_2'))) : ?>

                <div class="kl-chapitre-2-video">
                    <div class="kl-parent-video overflow-hidden position-relative">
                        <?php if(!empty(get_sub_field('image_de_couverture_video'))): ?>
                            <div class="kl-cover-img">
                                <img src="<?php the_sub_field('image_de_couverture_video') ?>" class="kl-img-cover" alt="">
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
                            <?php echo do_shortcode(get_sub_field('video_du_chapitre_2')); ?>
                        </div>
                    </div>
                </div>

        <?php endif; ?>
        <?php endwhile; ?>

    </article><!-- #post-## -->

<?php endif; ?>