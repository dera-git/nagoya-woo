<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://use.typekit.net/hlq8scq.css »>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php 

    // WordPress 5.2 wp_body_open implementation
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    } else {
        do_action( 'wp_body_open' );
    }

?>

<div id="page" class="site overflow-hidden">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	<header id="masthead" class="site-header py-0 position-relative navbar-static-top <?php echo (is_product() && is_woocommerce()) ? 'header-product' : ''; ?>" role="banner">
        <div class="container d-flex align-items-center">
            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon h-auto">
                	<span></span>
                	<span></span>
                	<span></span>
                </span>
            </button>        	
            <div class="navbar-brand mx-auto logo-wrapper flex-fill text-center">
                <?php if ( get_theme_mod( 'wp_bootstrap_starter_logo' ) ): ?>
                    <a class="d-inline-block mx-auto" href="<?php echo esc_url( home_url( '/' )); ?>">
                        <img src="<?php echo esc_url(get_theme_mod( 'wp_bootstrap_starter_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    </a>
                <?php else : ?>
                    <a class="d-inline-block mx-auto site-title" href="<?php echo esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>
                <?php endif; ?>

            </div>
            <div class="icon-wrapper d-flex align-items-center px-2">	
	            <div class="icon-compte-wrapper mr-3">
	            	<a href="/mon-compte" class="icon-cart">
	            		<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icon-compte.png'); ?>" alt="icon-compte">
	            	</a>
	            </div> <div class="icon-cart-wrapper">
	            	<a href="/panier" class="icon-cart">
	            		<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icon-cart.png'); ?>" alt="icon-cart">
	            	</a>
	            </div>
            </div>
        </div>
        <div id="main-nav" class="navbar-wrapper position-absolute mx-auto w-100 collapse navbar-collapse bg-white">
        	<div class="container d-flex align-items-start">   
        		<?php if ( get_theme_mod( 'image_menu' ) ): ?>   
        			<div class="logo-menu-wrapper">
                        <img src="<?php echo esc_url(get_theme_mod( 'image_menu' )); ?>" alt="image_menu">
                    </div>
                <?php endif; ?>

                <div class="menu-wrapper flex-fill align-items-start">
			        <nav class="navbar navbar-expand-xl p-0 w-100">
			            <?php
			            wp_nav_menu(array(
			            'theme_location'    => 'primary',
			            'container'       => '',
			            'container_id'    => '',
			            'container_class' => '',
			            'menu_id'         => false,
			            'menu_class'      => 'navbar-nav w-100 d-flex align-items-start justify-content-between',
			            'depth'           => 3,
			            'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
			            'walker'          => new wp_bootstrap_navwalker()
			            ));
			            ?>

			        </nav>
			        <div class="navbar-contact d-flex flex-nowrap align-items-center">
			        	<span class="position-relative text-uppercase">Contact</span>
			        	<ul class="contact p-0 m-0 list-unstyled flex-fill d-flex flex-nowrap align-items-center">
	    					<?php if ( get_theme_mod( 'mail_setting' ) ): ?>   
			        		<li class="position-relative">
			        			<a href="mailto:<?php echo get_theme_mod( 'mail_setting' ); ?>" title="Appeler au <?php echo get_theme_mod( 'mail_setting' ); ?>"  target="_blank"><?php echo get_theme_mod( 'mail_setting' ); ?></a>
			        		</li>
	            			<?php endif; ?>	  
	    					<?php if ( get_theme_mod( 'telephone_setting' ) ): ?>   
			        		<li class="position-relative">
			        			<a href="tel:<?php echo get_theme_mod( 'telephone_setting' ); ?>" title="Envoyer un mail au <?php echo get_theme_mod( 'telephone_setting' ); ?>"  target="_blank"><?php echo get_theme_mod( 'telephone_setting' ); ?></a>
			        		</li>
	            			<?php endif; ?>	                		       			
			        		<li class="position-relative">
			        			<?php get_template_part( 'templates/suivez','nous' ); ?>
			        		</li>
			        	</ul>
			        </div>
                </div>


        	</div>
        </div>
	</header><!-- #masthead -->
	<div id="content" class="site-content headershop">
        <?php if(!is_front_page() && !is_home() && !is_page_template('template-valeurs-savoir-faire.php') && (!is_product() && !is_woocommerce())): ?>
        <div class="separator-line before-or position-relative d-flex justify-content-center"></div>
        <?php endif ?>
		<div class="container <?php echo (is_product() && is_woocommerce()) ? 'container-product' : ''; ?>">
			<div class="row">
                <?php endif; ?>