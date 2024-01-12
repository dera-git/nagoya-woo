<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>


<?php

//Recuperer les elements à afficher 
if (have_rows('section_1')) : ?>

    <article id="section_1" class="chapitre kl-savoir-faire-chap1">
        <div class="container kl-container-xl-1664">
            <?php while (have_rows('section_1')) : the_row(); ?>

                <?php if (!empty(get_sub_field('titre'))) :  ?>
                    <div class="kl-mb-md-70 kl-mb-30">
                        <div class="text-uppercase kl-text-30 text-center kl-ff-montserrat kl-fw-medium kl-letter-space-2">
                            <h2><?php echo get_sub_field('titre'); ?></h2>
                        </div>
                        <?php if (!empty(get_sub_field('sous_titre'))) :  ?>
                            <span class="kl-text-20 kl-ff-garamond fst-italic d-block kl-mt-10 text-center"><?php echo get_sub_field('sous_titre'); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty(get_sub_field('description'))) :  ?>
                    <div class="kl-text-16 kl-lh-1_875 kl-fw-light mx-auto mt-0 text-justify kl-max-w-1275 kl-mb-xl-135 kl-mb-50">
                        <?php echo get_sub_field('description'); ?>
                    </div><!-- .entry-content -->
                <?php endif; ?>

                <?php $choix = get_field('choix_content');
                if ($choix == 'choix1') : ?>
                    <div>
                        <?php $image = get_sub_field('image_illustration');
                        if (!empty($image)) : ?>
                            <div class="image-wrapper kl-img-illustration">
                                <img class="mx-auto img-fluid kl-img-cover" src="<?php echo $image['url']; ?>" alt="<?php echo $image['url']; ?>">
                            </div>
                        <?php endif; ?>

                        <?php if (!empty(get_sub_field('description_if_image'))) :  ?>
                            <div class="kl-text-16 kl-lh-1_875 kl-fw-light kl-mt-90 kl-mb-50">
                                <?php echo get_sub_field('description_if_image'); ?>
                            </div><!-- .entry-content -->
                        <?php endif; ?>

                        <?php if (!empty(get_sub_field('libelle_du_bouton_if_image'))) :
                            $url = get_field('lien_if_image') ?>
                            <div class="btn-decouvre kl-mt-50">
                                <a href="<?= $url ? $url : '#' ?>" class="btn d-block kl-ff-garamond kl-fw-bold kl-max-w-260 kl-btn-theme mx-auto">
                                    <?php echo get_sub_field('libelle_du_bouton_if_image'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($choix == 'choix2') : ?>
                    <?php if (have_rows('contenu_en_deux_colonnes')) : ?>
                        <div class="row kl-gx-40 kl-gy-40">
                            <?php while (have_rows('contenu_en_deux_colonnes')) : the_row();
                                $img = get_sub_field('image_tow_column');
                                $title = get_sub_field('titre_tow_column');
                                $desc = get_sub_field('description_tow_column');
                            ?>

                                <div class="col-md-6">
                                    <?php if ($img) : ?>
                                        <div class="kl-img-column kl-mb-35">
                                            <img src="<?php echo $img ?>" class="img-fluid kl-img-cover" alt="">
                                        </div>
                                    <?php endif ?>
                                    <?php if ($title) : ?>
                                        <div class="kl-text-20 kl-fw-regular kl-ff-montserrat text-uppercase kl-mb-30">
                                            <h2><?php echo $title ?></h2>
                                        </div>
                                    <?php endif ?>
                                    <?php if ($desc) : ?>
                                        <div class="kl-text-16 kl-lh-1_875 kl-fw-light">
                                            <?php echo $desc ?>
                                        </div>
                                    <?php endif ?>
                                </div>

                            <?php endwhile ?>
                        </div>
                    <?php endif ?>
                <?php endif; ?>

            <?php endwhile; ?>
        </div>

    </article><!-- #post-## -->
                                    
    <?php if(get_field('description_section_03')): ?>
        <div class="kl-bloc-advantages kl-pb-md-60">
            <div class="container kl-container-xl-1664">
                <div class="row">
                    <div class="col-md-6 kl-mb-50 kl-mb-md-0 kl-pt-md-60">
                        <div class="kl-desc-advantages">
                            <?php if(get_field('titre_section_03')): ?>
                                <div class="text-uppercase kl-text-30 kl-ff-montserrat kl-fw-semi-bold kl-mb-md-60 kl-mb-30">
                                    <h2><?php the_field('titre_section_03') ?></h2>
                                </div>
                            <?php endif ?>
                            <div class="mx-auto kl-fw-light kl-lh-1_875">
                                <?php the_field('description_section_03') ?>
                            </div>
                            <?php if(get_field('libelle_du_bouton_section_03')): ?>
                                <?php $urlField = get_field('lien_btn_section_03');
                                    $urlBtn = $urlField ? $urlField : '#' ?>
                                <div class="btn-decouvre kl-mt-50">
                                    <a href="<?php echo $urlBtn ?>" class="btn d-block kl-ff-garamond kl-fw-bold kl-max-w-260 kl-btn-theme">
                                        <?php the_field('libelle_du_bouton_section_03') ?>								
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php if(get_field('image_section_03')): ?>
                        <div class="col-md-6">
                            <div class="kl-img-advantages">
                                <img src="<?php the_field('image_section_03') ?>" class="img-fluid kl-img-cover" alt="">
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>

<?php endif; ?>