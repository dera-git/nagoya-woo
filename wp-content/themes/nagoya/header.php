<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://use.typekit.net/hlq8scq.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<title><?= get_bloginfo('name'); ?> | <?= get_the_title(); ?></title>
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
	<header id="masthead" class="site-header position-relative navbar-static-top kl-site-header kl-bg-green-theme" role="banner">
        <div class="container kl-container-xl-1664 d-flex align-items-center">
			<button class="navbar-toggler kl-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span></span>
				<span></span>
				<span></span>
			</button>      	
            <div class="navbar-brand mx-auto logo-wrapper flex-fill text-center kl-navbar-brand">
				<a class="d-inline-block mx-auto" href="<?php echo esc_url( home_url( '/' )); ?>">
					<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg'); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
            </div>
            <div class="icon-wrapper d-flex align-items-center kl-icon-wrapper">	
	            <div class="icon-compte-wrapper me-3">
	            	<a href="/mon-compte" class="icon-cart">
	            		<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/compte.svg'); ?>" alt="icon-compte" width="19" height="19">
	            	</a>
	            </div> <div class="icon-cart-wrapper">
	            	<a href="/panier" class="icon-cart">
	            		<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/panier.svg'); ?>" alt="icon-cart" width="19" height="19">
	            	</a>
	            </div>
            </div>
        </div>
        <div id="main-nav" class="collapse navbar-collapse kl-navbar-collapse">
        	<div class="container kl-container-xl-1664 d-flex align-items-start">   
        		<?php if ( get_field( 'image_du_menu', 'option' ) ): ?>   
        			<div class="logo-menu-wrapper kl-img-menu-wrapper">
                        <img src="<?php echo get_field('image_du_menu', 'option'); ?>" class="img-fluid kl-img-cover" alt="image_menu">
                    </div>
                <?php endif; ?>

                <div class="menu-wrapper flex-fill align-items-start w-100">
			        <nav class="navbar navbar-expand-lg p-0 w-100 kl-navbar">
			            <?php
			            wp_nav_menu(array(
			            'theme_location'    => 'principal',
			            'container'       => false,
			            'menu_class'      => 'navbar-nav w-100 d-flex align-items-center align-items-lg-start justify-content-between',
			            'depth'           => 2,
						'fallback_cb' => '__return_false',
			            'walker'          => new bootstrap_5_wp_nav_menu_walker()
			            ));
			            ?>

			        </nav>
			        <div class="navbar-contact kl-navbar-contact d-flex flex-column flex-lg-row flex-nowrap align-items-center">
			        	<span class="position-relative text-uppercase kl-text-14 kl-ff-petersburg mb-0 mb-lg-0">Contact</span>
			        	<ul class="contact list-unstyled flex-fill d-flex flex-column flex-lg-row flex-nowrap align-items-center kl-header-contact">	
							<?php if ( have_rows('contact', 'option') ) : ?>
	
								<?php while( have_rows('contact', 'option') ) : the_row(); 
									$value = get_sub_field('type_de_contact');
								?>

									<li class="position-relative kl-contact-items">
										<a class="d-flex justify-content-center" href="<?php if($value == 'email') {echo 'mailto:';} else { echo 'tel:';} ?><?php echo get_sub_field('contenu_contact'); ?>" target="_blank">
											<?php echo get_sub_field('contenu_contact'); ?>
										</a>
									</li>

								<?php endwhile; ?>

							<?php endif; ?>
			        		<li class="position-relative kl-contact-items">
			        			<?php get_template_part( 'templates/suivez','nous' ); ?>
			        		</li>
			        	</ul>
			        </div>
                </div>


        	</div>
        </div>
	</header><!-- #masthead -->
	<div id="content" class="site-content <?php echo is_page_template('template-haute-joaillerie.php') ? 'pb-0' : ''; ?>">
        <?php if(!is_front_page() && !is_home() && !is_page_template('template-valeurs-savoir-faire.php') && !is_page_template('template-haute-joaillerie.php') && (!is_product() && !is_woocommerce()) && !is_page_template('precommande-paiement.php')): ?>
        <div class="separator-line before-or position-relative d-flex justify-content-center"></div>
        <?php elseif (is_page_template('template-haute-joaillerie.php') || is_page_template('precommande-paiement.php')): ?>
        <div class="separator-line bg-nagoya-secondary before-or position-relative d-flex justify-content-center"></div>
        <?php endif ?>
                <?php endif; ?>