<?php

function add_theme_scripts() {

    $template_data       = wp_get_theme();
    $template_version    = $template_data['Version'];

    wp_enqueue_style('bootstrap.min', get_template_directory_uri().'/assets/node_modules/bootstrap/dist/css/bootstrap.min.css', array(), null);
    wp_enqueue_style('slick', get_template_directory_uri().'/assets/node_modules/slick-carousel/slick/slick.css', array(), null);
    wp_enqueue_style('slick-theme', get_template_directory_uri().'/assets/node_modules/slick-carousel/slick/slick-theme.css', array(), null);
    wp_enqueue_style('googlefont', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap', array(), null);
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/assets/css/style.css', array(), null);

    wp_enqueue_script('jquery.min', get_template_directory_uri().'/assets/node_modules/jquery/dist/jquery.min.js', array(), null, true);
    wp_enqueue_script('slick.min', get_template_directory_uri().'/assets/node_modules/slick-carousel/slick/slick.min.js', array(), null, true);

    wp_enqueue_script('boostrap.bundle', get_template_directory_uri().'/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.js', array(), $template_version, true);
    wp_enqueue_script('jquery.matchHeight-min', get_template_directory_uri().'/assets/node_modules/jquery-match-height/dist/jquery.matchHeight-min.js', array(), $template_version, true);
    wp_enqueue_script('theme-script', get_template_directory_uri().'/assets/js/script.js', array(), $template_version, true);
    wp_enqueue_script('main', get_template_directory_uri().'/assets/js/main.js', array(), $template_version, true);
}

add_action( "wp_enqueue_scripts", "add_theme_scripts" );