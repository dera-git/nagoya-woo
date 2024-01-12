<?php
function nagoya_setup() {
    // Ajouter les thumbnails
    add_theme_support( 'post-thumbnails' );
    
    //Definition des tailles d'images à ajouter
    add_image_size( '929x594', 929, 594, false );
    add_image_size( '398x576', 398, 576, true );
    add_image_size( '1640x820', 1640, 820, false );
    add_image_size( '536x456', 536, 456, true );
    add_image_size( '589x736', 589, 736, false );
    add_image_size( '791x579', 791, 579, true );
    add_image_size( '59x51', 59, 51, true );
    add_image_size( '536x536', 536, 536, true );
    add_image_size( '950x540', 950, 540, true );
    add_image_size( '536x716', 536, 716, true );
    add_image_size( '536x748', 536, 748, true );
    add_image_size( '536x782', 536, 782, true );
    add_image_size( '536x799', 536, 799, true );
    add_image_size( '925x581', 925, 581, true );
    add_image_size( '571x504', 571, 504, true );
    add_image_size( '1088x544', 1088, 544, true );
    add_image_size( '640x640', 640, 640, true );
    add_image_size( '529x793', 529, 793, true );
    add_image_size( '882x983', 882, 983, true );
}
add_action( 'after_setup_theme', 'nagoya_setup' );

/**
 * Get All Posts
 *
 * @return WP_Query
 */

function get_post_all_posts($post_type)
{
    $blogPosts = array(
        'post_type' => $post_type,
        'order'     => 'DESC',
        'orderby'   => 'ID',
    );

    return new WP_Query( $blogPosts );
}

function iframeVideoUrlAcf(){

    // Load value.
    $iframe = get_field('tuto_video');

    // Use preg_match to find iframe src.
    preg_match('/src="(.+?)"/', $iframe, $matches);
    $src = $matches[1];

    // Add extra parameters to src and replace HTML.
    $params = array(
        'controls'  => 0,
        'hd'        => 1,
        'autohide'  => 1
    );
    $new_src = add_query_arg($params, $src);
    $iframe = str_replace($src, $new_src, $iframe);

    // Add extra attributes to iframe HTML.
    $attributes = 'frameborder="0"';
    $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

    // Display customized HTML.
    echo $iframe;
}