<?php

function nagoya_customize_register( $wp_customize ) {
    
    /*Coordonnée*/ 
    $wp_customize->add_section(
        'coordonee_contact',
        array(
            'title' => __( 'Coordonnée', 'wp-bootstrap-starter' ),
            'priority' => 30,
        )
    );

    $wp_customize->add_setting( 'address_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'address_setting', array(
        'label' => __( 'Adresse', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'address_setting',
        'type' => 'text'
    ) ) );
    
    $wp_customize->add_setting( 'telephone_setting', array(
        'default' => __( '','wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'telephone_setting', array(
        'label' => __( 'Téléphone', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'telephone_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'mail_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'mail_setting', array(
        'label' => __( 'Email', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'mail_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'instagram_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'instagram_setting', array(
        'label' => __( 'Instagram', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'instagram_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'facebook_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'facebook_setting', array(
        'label' => __( 'Facebook', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'facebook_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'tik_tok_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'tik_tok_setting', array(
        'label' => __( 'Tik tok', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'tik_tok_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'youtube_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'youtube_setting', array(
        'label' => __( 'YouTube', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'youtube_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'linkedin_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'linkedin_setting', array(
        'label' => __( 'LinkedIn', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'linkedin_setting',
        'type' => 'text'
    ) ) );

    $wp_customize->add_setting( 'twitch_setting', array(
        'default' => __( '', 'wp-bootstrap-starter' ),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ) );

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'twitch_setting', array(
        'label' => __( 'Twitch', 'wp-bootstrap-starter' ),
        'section'    => 'coordonee_contact',
        'settings'   => 'twitch_setting',
        'type' => 'text'
    ) ) );


	$wp_customize->add_setting( 'image_menu', array(
        'default' => get_theme_file_uri('assets/img/artisanat-francais.jpg'), // Add Default Image URL 
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'image_menu_control', array(
        'label' => "Ajouter l'image du menu",
        'priority' => 20,
        'section' => 'coordonee_contact',
        'settings' => 'image_menu',
        'button_labels' => array(// All These labels are optional
                    'select' => 'Selectionner Image',
                    'remove' => 'Supprimer Image',
                    'change' => 'Changer Image',
                    )
    )));


	// Display Image using customizer image control
	/*<img src="<?php echo get_theme_mod('diwp_logo'); ?>" />*/

}
add_action( 'customize_register', 'nagoya_customize_register' );