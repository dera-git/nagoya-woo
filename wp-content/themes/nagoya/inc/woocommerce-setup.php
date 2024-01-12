<?php

/*add_action( 'woocommerce_before_shop_loop_item', 'customizing_loop_product_link_open', 9 );
 function customizing_loop_product_link_open() {
     global $product;
     // HERE BELOW, replace clothing' with your product category (can be an ID, a slug or a name)
     if( has_term( array('clothing'), 'product_cat' )){
         remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
         add_action( 'woocommerce_before_shop_loop_item', 'custom_link_for_product_category', 10 );
     }
 }
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'custom_link_for_product_category', 10 );

function custom_link_for_product_category() {
	global $product;
	// HERE BELOW, Define the Link to be replaced
	$link = $product->get_permalink();
	echo '<a href="' . $link . '" class="woocommerce-LoopProduct-link">';
}

/**
 * Add category description/
add_action( 'woocommerce_archive_description', 'wc_category_description' );
function wc_category_description() {
    if ( is_product_category() ) {
        global $wp_query;
        $cat_id = $wp_query->get_queried_object_id();
        $cat_desc = term_description( $cat_id, 'product_cat' );
        $subtit = '<span class="subtitle">'.$cat_desc.'</span>';
        echo $subtit;
    }
}

    global $wp;

    // Get the requested Url category/subcategories
    $request = $wp->request;
    // Set them in an array
    $request = explode( '/', $request );
    // The main category and sub-categories names
    foreach( $request as $category ){
        // Get the category name
        $category_term = get_term_by( 'slug', $category, 'product_cat' );
        // Set category and subcategories in an array
        $categories_name[] = $category_term->name;
    }

// Reorder product data tabs

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {

    $tabs['reviews']['priority'] = 5;           // Reviews first
    $tabs['description']['priority'] = 10;          // Description second
    $tabs['additional_information']['priority'] = 15;   // Additional information third

    return $tabs;
}
// Remove product data tabs

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );          // Remove the description tab
    unset( $tabs['reviews'] );          // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab

    return $tabs;
}
// * Rename product data tabs

add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

    $tabs['description']['title'] = __( 'More Information' );       // Rename the description tab
    $tabs['reviews']['title'] = __( 'Ratings' );                // Rename the reviews tab
    $tabs['additional_information']['title'] = __( 'Product Data' );    // Rename the additional information tab

    return $tabs;

}

 */
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );


add_action( 'woocommerce_after_shop_loop_item_title', 'wc_woocommerce_template_loop_product_description', 10 );

/**
 * Show the product title in the product loop. By default this is an H2.
 */
function wc_woocommerce_template_loop_product_description() {
    global $product;

    $html = '<div class="post-content text-center mx-auto my-4"><p>'.$product->short_description.'</p></div>';
}


add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width' => 536,
        'height' => 436,
        'crop' => 1,
    );
} );

add_filter( 'woocommerce_output_related_products_args', 'wc_related_products_limit', 20 );


/**
 * Change number of related products output
 */ 
function wc_related_products_limit() {
  global $product;
    
    $args['posts_per_page'] = 20;
    return $args;
}

function renderTitleBloc($attr_name){

    switch ($attr_name) {
        case 'pa_pierre':
            // code...
            $titre_bloc = 'Je choisis ma pierre';
            break;

        case 'pa_taille':
            // code...
            $titre_bloc = 'Je choisis ma taille';
            break;

        case 'pa_police':
            // code...
            $titre_bloc = 'Je personnalise mon bijou';
            break;
        
        default:
            // code...
            $titre_bloc = '';
            break;
    }

    return $titre_bloc;
}

// To change add to cart button to read more on single product page
	
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wc_change_add_cart_read_more_product_page' );

function wc_change_add_cart_read_more_product_page() {

	return __( 'Ajouter au panier', 'woocommerce' ); 
}

// To change add to cart text on product archives and the shop page 
//add_filter( 'woocommerce_product_add_to_cart_text', 'wc_change_add_cart_read_more_shop_page' );  

function wc_change_add_cart_read_more_shop_page() {

	return 'Je découvre';
	
}

// First, remove Add to Cart Button
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
 
// Second, add View Product Button
add_action( 'woocommerce_after_shop_loop_item', 'wc_custom_view_product_button', 10);
function wc_custom_view_product_button() {
    global $product;
    $link = $product->get_permalink();
    echo '<a href="' . $link . '" class="button product_type_simple">Je découvre</a>';
}


add_filter( 'woocommerce_page_title', 'wc_shop_page_title');

function wc_shop_page_title( $page_title ) 
{
    global $wp;

    // Get the requested Url category/subcategories
    $request = $wp->request;
    // Set them in an array
    $request = explode( '/', $request );
    // The main category and sub-categories names

    $html = '<div class="archive-title d-flex flex-column align-items-center">';

    foreach( $request as $category ){

        // Get the category name
        $category_term = get_term_by( 'slug', $category, 'product_cat' );

        // Set category and subcategories in an array
        if($category_term && ! is_wp_error( $category_term )){
        	$html .= '<h2 class="kl-title-category">'. $category_term->name . '</h2>';
        }

    }

    $html .= '</div>';

    echo $html;
}


// add_filter( 'template_include', 'wc_archive_template', 99 );
// function wc_archive_template( $template ) {

//     if ( is_woocommerce() && is_archive() ) {
//         $new_template = get_stylesheet_directory() . '/woocommerce/archive-product.php';
//         if ( !empty( $new_template ) ) {
//             return $new_template;
//         }
//     }

//     return $template;
// }

/**
 * Display product attribute archive links 
 */
add_action( 'woocommerce_shop_loop_item_title', 'wc_show_attribute_links' );
// if you'd like to show it on archive page, replace "woocommerce_product_meta_end" with "woocommerce_shop_loop_item_title"

function wc_show_attribute_links() {
	global $product;
    $attrs = $product->get_price_including_tax();
    echo '<div class="attr">' . woocommerce_price($attrs) . '</div>';
}


/**
 * Change number or products per row to 1
 */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 1; // 1 products per row
	}
}

//  Modifier le nombre de produits WooCommerce affichés par page
add_filter ('loop_shop_per_page', 'lw_loop_shop_per_page', 30);

function lw_loop_shop_per_page ($products) {
  $products = 60;
  return $products;
}


/**
 * @snippet       Get Current Variation ID @ WooCommerce Single Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_action( 'woocommerce_before_add_to_cart_quantity', 'wc_display_dropdown_variation_add_cart' );
 
function wc_display_dropdown_variation_add_cart() {
   global $product;

    $attributes = $product->get_attributes();
    $variation_names = array();

   //if ( $product->is_type( 'variable' ) ) {
      wc_enqueue_js( "
         $( 'input.variation_id' ).change( function(){

            var attributes = [];
            var allAttributesSet = true;
            $('table.variations select').each(function() {
                var value = $(this).val();
                if (value) {
                    attributes.push({
                        id: $(this).attr('name'),
                        value: value
                    });
                } else {
                    allAttributesSet = false;
                }
            });

            $.map( attributes, function( n ) {
                var spanToReplace = $('button[data-handle='+n.id+']').find('.texte');
                spanToReplace.html(n.value);
            });
         });
        
      " );
   //}
}

/**
 * Add a custom product data tab
 */
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
    
    // Adds the new tab
    
    $tabs['livraison_tab'] = array(
        'title'     => __( 'Livraison', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'wc_new_product_tab_content'
    );

    return $tabs;

}
function wc_new_product_tab_content() {

    // The new tab content
    $livraison = get_field('livraison');
    echo '<h2>Livraison</h2>';
    if($livraison){
        echo $livraison;
    }
    
}

add_action( 'after_setup_theme', 'my_custom_woocommerce_theme_support' );
function my_custom_woocommerce_theme_support(){
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 573,
        'gallery_thumbnail_image_width' => 573,
        'single_image_width' => 573,
    ) );
}


/**
 * Change number of related products output
 */ 
function woo_related_products_limit() {
    global $product;
      
      $args['posts_per_page'] = 6;
      return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
    function jk_related_products_args( $args ) {
      $args['posts_per_page'] = 3;
      $args['columns'] = 2;
      return $args;
}