<?php
function nagoya_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Newsletter in Footer', 'wp-bootstrap-starter' ),
        'id'            => 'newsletter',
        'description'   => esc_html__( 'Add widgets here.', 'wp-bootstrap-starter' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'nagoya_widgets_init' );