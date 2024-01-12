<?php
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
    register_options_page('Global');
    register_options_page('En tête de page');
    register_options_page('Produits');
}