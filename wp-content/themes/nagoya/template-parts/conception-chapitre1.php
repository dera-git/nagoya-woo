<?php
$id_front_page = get_option('page_on_front');

//Recuperer les elements à afficher 
if( have_rows('chapitre_1', $id_front_page) ): ?>

    <article id="chapitre-1" class="chapitre">

        <?php while( have_rows('chapitre_1', $id_front_page) ): the_row(); ?>

            <?php if(!empty(get_sub_field('titre_du_chapitre_1', $id_front_page))):  ?>
                <div class="entry-header text-center kl-mb-40 kl-mb-md-75">
                    <div class="chapitre-title text-uppercase kl-text-30 kl-color-gold kl-ff-montserrat kl-fw-regular mb-2">
                        <h2>
                            <?php if(is_front_page() && is_home()) :
                                echo get_sub_field('titre_du_chapitre_1', $id_front_page); 

                                elseif(is_page_template('template-haute-joaillerie.php') || is_page_template('precommande-paiement.php') ) :
                                    echo 'Vous aimerez aussi';
                                else:
                                    echo 'Chapitre 01';
                                endif;
                            ?>    
                        </h2>
                    </div>
                    <div>
                        <?php if(!empty(get_sub_field('sous_titre_du_chapitre_1', $id_front_page))):  
                            if(is_front_page()) :
                        ?>
                            <span class="fst-italic kl-text-20 kl-color-gold kl-ff-garamond kl-fw-regular">
                                <?php echo get_sub_field('sous_titre_du_chapitre_1', $id_front_page); ?>
                            </span>
                        <?php 
                            endif; 
                        endif; ?>
                    </div>
                </div><!-- .entry-header -->
            <?php endif; ?>

            <?php if(!empty(get_sub_field('description_du_chapitre_1', $id_front_page))):  ?>
                <div class="entry-content mx-auto mt-0">
                    <?php echo get_sub_field('description_du_chapitre_1', $id_front_page); ?>
                </div><!-- .entry-content -->
            <?php endif; ?>

            <?php 
                $produits = get_sub_field('liste_des_produits_dans_le_chapitre_1', $id_front_page);

                if(!empty($produits)) : ?>

                <div class="chapitre-1-products kl-chapitre-1-products kl-prod-conception row kl-gx-16 kl-gy-40 position-relative">

                    <?php
                        foreach( $produits as $post):
                            setup_postdata($post);
                    
                        get_template_part( 'template-parts/content', 'product-home' ); 

                        
                        endforeach; 
                        wp_reset_postdata();
                    ?>

                </div>

                <?php endif; ?> 
        <?php endwhile; ?>

    </article><!-- #post-## -->

<?php endif; ?>
