<?php

$id_front_page = get_option('page_on_front');

//Recuperer les elements à afficher 
if( have_rows('bloc_listing_des_valeurs', $id_front_page )): ?>
    <section class="valeurs-wrapper kl-bg-green-theme <?php if(is_front_page()): echo "kl-section-icons"; elseif(is_woocommerce()): echo "kl-section-icons-wc"; else: echo "kl-section-icons-2"; endif; ?>">

        <div id="valeurs" class="container kl-container-xl-1664 position-relative">

            <?php while( have_rows('bloc_listing_des_valeurs', $id_front_page )): the_row(); ?>

                <div class="valeurs-savoirs savoirs position-relative d-flex flex-column flex-md-row justify-content-between align-items-stretch <?php echo is_product() ? 'border-top-or' : ''; ?>">
                
                <?php 

                // Check rows existexists.
                if( have_rows('liste_des_valeurs__savoir-faire') ):

                    // Loop through rows.
                    while( have_rows('liste_des_valeurs__savoir-faire') ) : the_row(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('text-center kl-valeur-item w-100'); ?>>
                                <div class="d-flex align-items-center px-3">

                                    <div class="content-wrapper w-100 d-flex flex-column">
                                        <div data-mh="icon-valeur" class="post-content">
                                            <?php $icon = get_sub_field('icone_de_la_valeur_'); ?>
                                            <img class="img-fluid" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                        </div>
                                        <div class="entry-header kl-text-18 kl-color-white kl-fw-regular kl-ff-garamond kl-mt-30 fst-italic">
                                            <h3 class="mx-auto  mb-0 post-title">
                                                <?php echo get_sub_field('description_courte') ?>
                                            </h3>
                                        </div>
                                    </div>

                                </div>
                            </article><!-- #post-## -->

                            <?php
                // End loop.
                endwhile;

                endif;

                ?>
            </div>                

            <?php endwhile; ?>
        </div><!-- #post-## -->

    </section>
<?php endif; ?>