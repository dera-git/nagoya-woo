<?php

//Liste des fichiers à inclure (les customs posts, options)

$page_includes = [
  'inc/enqueue.php',              // Enqueue files 
  'inc/menu.php',              
  'inc/default.php',              // Default theme child functions 
  'inc/setup.php',              // Setup theme child functions 
  'inc/customizer.php',           // Theme child customizer 
  'inc/widgets.php',           // Register widgets
  'inc/posttype.php',           // Register post types
  'inc/woocommerce-setup.php',           // Register post types
  'inc/custom-field.php',       //ACF
];

foreach ($page_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }
  require_once $filepath;
}

unset($file, $filepath);	



/* remplace le prix du haut de page par celui de la variation selectionnée*/
add_action( 'woocommerce_before_single_product', 'move_variations_single_price', 1 );
function move_variations_single_price(){
  global $product, $post;
  if ( $product->is_type( 'variable' ) ) {
    add_action( 'woocommerce_single_product_summary', 'replace_variation_single_price', 10 );
  }
}

function replace_variation_single_price() {
  ?>
    <style>
      .woocommerce-variation-price {
        display: none;
      }
    </style>
    <script>
      jQuery(document).ready(function($) {
        var priceselector = '.product p.price';
        var originalprice = $(priceselector).html();

        $( document ).on('show_variation', function() {
          $(priceselector).html($('.single_variation .woocommerce-variation-price').html());
        });
        $( document ).on('hide_variation', function() {
          $(priceselector).html(originalprice);
        });
      });
    </script>
  <?php
}


/*déplace le titre du produit au dessus de l'image sur mobile*/
// add_action('init', 'product_change_title_position');

// function product_change_title_position()
// {
//     if (wp_is_mobile()) 
//     {
//         remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
//         add_action('woocommerce_before_single_product', 'woocommerce_template_single_title', 5);
//     }
// }

add_action('init', 'product_change_rating_position');

function product_change_rating_position()
{
    if (wp_is_mobile()) 
    {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
        add_action('woocommerce_before_single_product', 'woocommerce_template_single_rating', 5);
    }
}

// function apply_discount_to_specific_product($cart) {
//   if (is_admin() || !is_cart()) {
//       return;
//   }
  
//   $page_id = 438;
  
//   $product_id = get_field('le_produit_joaillerie', $page_id)->ID;

//   // Réduction à appliquer (40%)
//   $discount_percentage = 0.4;

//   foreach ($cart->get_cart() as $cart_item_key => $cart_item) {

//       $product_in_cart_id = $cart_item['product_id'];

//       if ($product_in_cart_id === $product_id) {
//           $product = $cart_item['data'];

//           // Calculer le nouveau prix avec la réduction
//           $new_price = $product->get_price() * $discount_percentage;

//           // Appliquer le nouveau prix au produit dans le panier
//           $product->set_price($new_price);
//       }
//   }
// }
// add_action('woocommerce_before_calculate_totals', 'apply_discount_to_specific_product');

function apply_discount_to_specific_product($cart) {
  if (is_admin() && !defined('DOING_AJAX')) {
      return;
  }

  $page_id = 438;
  $discount_percentage = 0.4;
  $product_id = get_field('le_produit_joaillerie', $page_id);

  if ($product_id) {
      foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
          $product_in_cart_id = $cart_item['product_id'];

          if ($product_in_cart_id === $product_id) {
              // Récupérer le prix unitaire du produit
              $product_price = floatval($cart_item['data']->get_price());

              // Calculer le montant de la réduction
              $discount_amount = $product_price * $discount_percentage;

              // Appliquer la réduction sur le produit
              $new_price = $product_price - $discount_amount;
              $cart_item['data']->set_price($new_price);
              $cart_item['data']->set_regular_price($new_price);
              $cart_item['data']->set_sale_price($new_price);

          }
      }
  }
}
add_action('woocommerce_before_calculate_totals', 'apply_discount_to_specific_product');


function add_body_class_on_cart_page($classes) {
  if (is_cart()) {
      $classes[] = 'kl-cart-page';
  }
  return $classes;
}
add_filter('body_class', 'add_body_class_on_cart_page');


// Autoriser les utilisateurs non connectés à passer une commande
/*function allow_guest_checkout() {
  if (!is_user_logged_in()) {
      add_filter('woocommerce_checkout_registration_required', '__return_false');
  }
}
add_action('init', 'allow_guest_checkout');*/

/**Ajout au panier custom page */
function add_custom_cart() {
    if (is_page(467) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
            $quantity = 1;
            WC()->cart->add_to_cart($product_id, $quantity);
            wp_safe_redirect(wc_get_cart_url());
            exit;
    }
}
add_action('template_redirect', 'add_custom_cart');

/**prix 40% à payer*/
function change_price($cart) {
  $product_id_to_apply_discount = get_field('produit_haute_joaillerie', 'option')->ID;
  $has_product_discount = false;

  foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
      if ($cart_item['product_id'] == $product_id_to_apply_discount) {
          $has_product_discount = true;
          $cart_item['data']->set_price($cart_item['data']->get_price() * 0.4);
          break;
      }
  }

  if (!$has_product_discount) {
      foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
          $cart_item['data']->set_price($cart_item['data']->get_price());
      }
  }
}

add_action('woocommerce_before_calculate_totals', 'change_price');

add_action( 'after_setup_theme', 'product_img_view_setup' );
function product_img_view_setup() {
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

add_filter( 'woocommerce_single_product_carousel_options', 'sf_update_woo_flexslider_options' );
function sf_update_woo_flexslider_options( $options ) {
    $options['controlNav'] = true;
    $options['directionNav'] = true;
    return $options;
}