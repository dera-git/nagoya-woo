<div id="footer-widget" class="row m-0">
    <div class="container">
        <div class="row flex-md-row justify-content-md-between kl-gy-40">
            <div class="col-12 col-lg-2">
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'menu-footer-primary',
                        'container' => false,
                        'menu_class' => 'list-unstyled kl-menu-footer',
                    ) )
                ?>
            </div>
            <div class="col-12 col-lg-2">
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'menu-footer-secondary',
                        'container' => false,
                        'menu_class' => 'list-unstyled kl-menu-footer',
                    ) )
                ?>
            </div>
            <?php if ( is_active_sidebar( 'newsletter' )) : ?>
                <div class="col-12 col-lg-4 newsletter-wrapper kl-newsletter">
                    <?php dynamic_sidebar( 'newsletter' ); ?>
                </div>
            <?php endif; ?>

            <!-- Bloc text -->
            <div class="col-12 col-lg-2">
                <section class="widget widget_block">
                    <div class="widget_block-container">
                        <h3 class="widget-title">Une question ?</h3>
                        <div class="block-container kl-contact-footer">
                            <?php if ( get_theme_mod( 'telephone_setting' ) ): ?>   
                            <span class="d-flex justify-content-center justify-content-lg-start flex-wrap position-relative">
                                <span class="me-1">Par téléphone</span> <a href="tel:<?php echo get_theme_mod( 'telephone_setting' ); ?>"  title="<?php echo get_theme_mod( 'telephone_setting' ); ?>"  target="_blank"><?php echo get_theme_mod( 'telephone_setting' ); ?></a>
                            </span>
                            <?php endif; ?> 
                            <?php if ( get_theme_mod( 'mail_setting' ) ): ?>   
                            <span class="d-flex justify-content-center justify-content-lg-start flex-wrap position-relative">
                                <span class="me-1">ou par mail</span> <a href="mailto:<?php echo get_theme_mod( 'mail_setting' ); ?>" title="<?php echo get_theme_mod( 'mail_setting' ); ?>" target="_blank"><?php echo get_theme_mod( 'mail_setting' ); ?></a>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /Block text -->

            <!-- Block suivez-nous -->
            <div class="col-12 col-lg-2 suivez-nous-wrapper">
                <section class="widget widget_block">
                    <div class="widget_block-container">
                        <h3 class="widget-title mb-2">Suivez-nous</h3>
                        <div class="block-container">
                            <?php get_template_part( 'templates/suivez','nous' ); ?>            
                        </div>
                    </div>
                </section>
            </div>
            <!-- /Block suivez-nous -->
        </div>
    </div>
</div>