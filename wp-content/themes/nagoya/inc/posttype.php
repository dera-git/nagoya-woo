<?php

    // création du custom post type
    //add_action('init', 'create_posttype');
    function create_posttype()
    {
        register_post_type(
            'valeurs-savoir-faire',
            array(
                'labels' => array(
                    'name' => __('Valeurs / Savoir faire'),
                    'singular_name' => __('Valeur / Savoir faire')
                ),
                'public' => true,
                'has_archive' => true,
                'hierarchical' => true,
                //'rewrite' => array('slug' => 'risques'),
                'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
                'taxonomies' => array('post_tag')
            )
        );
    }