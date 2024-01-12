<?php
$id_front_page = get_option('page_on_front');

//Recuperer les elements à afficher 
if( have_rows('chapitre_4', $id_front_page) ): ?>

    <div id="chapitre-4" class="chapitre bg-window position-relative">

        <?php while( have_rows('chapitre_4', $id_front_page) ): the_row(); ?>

            <?php if(!empty(get_sub_field('titre_du_chapitre_4', $id_front_page))):  ?>
                <div class="entry-header text-center kl-mb-40 kl-mb-md-60">
                    <div class="chapitre-title text-uppercase kl-text-30 kl-color-gold kl-ff-avenir kl-fw-regular kl-letter-space-2 mb-2">
                        <h2><?php echo get_sub_field('titre_du_chapitre_4'); ?></h2>
                    </div>

                    <?php if(!empty(get_sub_field('sous_titre_du_chapitre_4', $id_front_page))):  ?>
                        <div>
                            <span class="fst-italic kl-text-20 kl-color-gold kl-ff-garamond kl-fw-regular">
                                <?php if(is_front_page() && is_home()) : 
                                    echo get_sub_field('sous_titre_du_chapitre_4', $id_front_page);
                                    else:
                                        echo 'Carnet de blog';
                                    endif;
                                ?>                            
                            </span>
                        </div>
                    <?php endif; ?>
                </div><!-- .entry-header -->
            <?php endif; ?>

            <?php if(!empty(get_sub_field('description_du_chapitre_4', $id_front_page))):  ?>
                <div class="entry-content mx-auto mt-0">
                    <?php echo get_sub_field('description_du_chapitre_4', $id_front_page); ?>
                </div><!-- .entry-content -->
            <?php endif; ?>

            <?php 
                $posts = get_sub_field('liste_des_articles_blog', $id_front_page);

                if(!empty($posts)) : ?>

                <div class="chapitre-4-posts posts row align-items-stretch kl-gx-16 kl-gy-50 position-relative">

                    <?php
                        foreach( $posts as $post):
                            setup_postdata($post);
                    ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('col-sm-12 col-md-4 kl-hover-zoom'); ?>>
                            <div class="d-flex flex-column h-100">
                                <div class="post-thumbnail overflow-hidden kl-thumbnail-blog-post">
                                    <a href="<?php the_permalink(); ?>" class="d-block h-100 w-100" rel="bookmark">
                                        <?php the_post_thumbnail('full', array('class' => 'img-fluid kl-img-cover')); ?>
                                    </a>
                                </div>

                                <div class="content-wrapper kl-mt-20">
                                    <div class="entry-header text-start fst-italic kl-text-20 kl-ff-garamond kl-fw-regular">
                                        <?php
                                            the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" class="kl-color-black" rel="bookmark">', '</a></h3>' );
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </article><!-- #post-## -->
                    <?php endforeach; 
                        wp_reset_postdata();
                    ?>

                </div>

                <?php if(!empty(get_sub_field('lien_du_bouton_du_chapitre'))): 
                    $lien = get_sub_field('lien_du_bouton_du_chapitre'); ?>
                    <div class="btn-wrapper text-center kl-mt-50 kl-mt-md-70">
                        <a title="<?php echo get_sub_field('libelle_du_bouton_du_chapitre');?>" href="<?php echo $lien ?>" class="btn mx-auto d-block kl-max-w-226 kl-btn-theme <?php if(is_page(649)){ echo "kl-btn-y-center"; } else {echo " "; } ?>"><?php echo get_sub_field('libelle_du_bouton_du_chapitre'); ?></a>
                    </div>
                <?php endif; ?>

                <?php endif; ?> 
        <?php endwhile; ?>

    </div><!-- #post-## -->

<?php endif; ?>
