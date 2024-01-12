<?php
/**
* The admin-specific functionality of the plugin.
*
* @link       https://themehigh.com
* @since      1.0.0
*
* @package    product-variation-swatches-for-woocommerce
* @subpackage product-variation-swatches-for-woocommerce/admin
*/
if(!defined('WPINC')){  die; }

if(!class_exists('THWVSF_Admin')):

    class THWVSF_Admin {
     private $plugin_name;
     private $version;
     private $taxonomy;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action( 'admin_init', array($this,'define_admin_hooks'));
    }

    public function enqueue_styles_and_scripts($hook) {
        if(strpos($hook, 'product_page_th_product_variation_swatches_for_woocommerce') === false){
            if(!($hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit-tags.php' || $hook == 'term.php' || $hook == 'product_page_product_attributes')){
                return;
            }
        }

        $debug_mode = apply_filters('thwvsf_debug_mode', false);
        $suffix = $debug_mode ? '' : '.min';
        
        $this->enqueue_styles($suffix,$hook);
        $this->enqueue_scripts($suffix);
    }

    private function enqueue_styles($suffix,$hook) {

        wp_enqueue_style('woocommerce_admin_styles', THWVSF_WOO_ASSETS_URL.'css/admin.css');
        wp_enqueue_style('thwvsf-admin-style', THWVSF_ASSETS_URL_ADMIN . 'css/thwvsf-admin'.$suffix.'.css', $this->version);
        //wp_enqueue_style('roboto','//fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
    }

    private function enqueue_scripts($suffix) {
        $deps = array('jquery', 'jquery-ui-dialog', 'jquery-tiptip','wc-enhanced-select', 'select2', 'wp-color-picker',);
        wp_enqueue_media();
        wp_enqueue_script( 'thwvsf-admin-script', THWVSF_ASSETS_URL_ADMIN . 'js/thwvsf-admin'.$suffix.'.js', $deps, $this->version, false ); 

        $placeholder_image = THWVSF_ASSETS_URL_ADMIN . '/images/placeholder.svg';
        $thwvsf_var = array(

            'admin_url' => admin_url(),
            'admin_path'=> plugins_url( '/', __FILE__ ),
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajax_banner_nonce' => wp_create_nonce('thwvsf_upgrade_notice'),
            'placeholder_image' => $placeholder_image,
            'upload_image'      => esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/upload.svg'),
            'remove_image'      =>  esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/remove.svg'),
        );
        
        wp_localize_script('thwvsf-admin-script','thwvsf_var',$thwvsf_var);
    }

    public function admin_menu() {
        $page_title = __('WooCommerce Product Variation Swatches', 'product-variation-swatches-for-woocommerce');
        $menu_title = __('Swatches Options','product-variation-swatches-for-woocommerce');
        $capability = THWVSF_Utils::thwvsf_capability();
        $this->screen_id = add_submenu_page('edit.php?post_type=product', $page_title, $menu_title, $capability, 'th_product_variation_swatches_for_woocommerce', array($this, 'output_settings'));
    }
    
    public function add_screen_id($ids){
        $ids[] = 'woocommerce_page_th_product_variation_swatches_for_woocommerce';
        $ids[] = strtolower( __('WooCommerce', 'woocommerce') ) .'_page_th_product_variation_swatches_for_woocommerce';
        return $ids;
    }

    public function plugin_action_links($links) {
        $premium_link = '<a href="https://www.themehigh.com/product/woocommerce-product-variation-swatches">'. __('Premium plugin') .'</a>';
        $settings_link = '<a href="'.admin_url('edit.php?post_type=product&page=th_product_variation_swatches_for_woocommerce').'">'. __('Settings','product-variation-swatches-for-woocommerce') .'</a>';
        array_unshift($links, $premium_link);
        array_unshift($links, $settings_link);

        if (array_key_exists('deactivate', $links)) {
            $links['deactivate'] = str_replace('<a', '<a class="thwvs-deactivate-link"', $links['deactivate']);
        }

        return $links;

        return $links;
    }

    public function dismiss_thwvsf_review_request_notice(){
        check_ajax_referer( 'thwvsf_notice_security', 'thwvsf_review_nonce' );
        $capability = THWVSF_Utils::thwvsf_capability();
        if(!current_user_can($capability)){
            wp_die(-1);
        }
         
        $now = time();
        update_user_meta( get_current_user_id(), 'thwvsf_review_skipped', true );
        update_user_meta( get_current_user_id(), 'thwvsf_review_skipped_time', $now );
    }

    public function skip_thwvsf_review_request_notice(){
        if(! check_ajax_referer( 'thwvsf_review_request_notice', 'security' )){
            die();
        }
        set_transient('thwvsf_skip_review_request_notice', true, apply_filters('thwvsf_skip_review_request_notice_lifespan',1 * DAY_IN_SECONDS));
    }

    public function plugin_row_meta( $links, $file ) {
        if(THWVSF_BASE_NAME == $file) {
            $doc_link = esc_url('https://www.themehigh.com/help-guides/');
            $support_link = esc_url('https://www.themehigh.com/help-guides/');
                
            $row_meta = array(
                'docs' => '<a href="'.$doc_link.'" target="_blank" aria-label="'.__('View plugin documentation','product-variation-swatches-for-woocommerce').'">'.__('Docs','product-variation-swatches-for-woocommerce').'</a>',
                'support' => '<a href="'.$support_link.'" target="_blank" aria-label="'. __('Visit premium customer support','product-variation-swatches-for-woocommerce' ) .'">'. __('Premium support','product-variation-swatches-for-woocommerce') .'</a>',
            );

            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }

    public function output_settings(){ 

        $tab  = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'global_product_attributes';

        if($tab == 'general_settings'){

            $general_settings = THWVSF_Admin_Settings_General::instance();   
            $general_settings->render_page(); 
        }else if($tab == 'swatches_design_settings'){
            $design_settings = THWVSF_Admin_Settings_Design::instance();  
            $design_settings->render_page();

        }else if($tab == 'pro'){
            
            $pro_details = THWVSF_Admin_Settings_Pro::instance();  
            $pro_details->render_page();

        }else{

            $attribute_settings = THWVSF_Admin_Settings_Attributes::instance();  
            $attribute_settings->render_page();

        }
    
    }
    
    public function define_admin_hooks(){

        add_action( 'admin_head', array( $this, 'review_banner_custom_css') );
        add_action( 'admin_init', array( $this, 'wvsf_notice_actions' ), 20 );
        add_action( 'admin_notices' ,array($this,'output_review_request_link'));
        add_action( 'admin_footer', array( $this, 'review_banner_custom_js') );

        add_action( 'admin_head', array( $this,  'sib_form_banner_custom_css') );
        add_action( 'admin_notices' ,array($this,'output_sib_form_popup'));
        add_action( 'admin_footer', array( $this,'sib_form_banner_custom_js') );
        add_action('admin_footer', array($this,'quick_links'));

        add_action( 'wp_ajax_dismiss_thwvsf_review_request_notice', array($this, 'dismiss_thwvsf_review_request_notice'));
        add_action( 'wp_ajax_dismiss_thwvsf_sib_form', array($this, 'dismiss_thwvsf_sib_form'));
        add_action( 'wp_ajax_subscribed_thwvsf_sib_form', array($this, 'subscribed_thwvsf_sib_form'));
       
        add_filter( 'product_attributes_type_selector', array( $this,'add_attribute_types' ) );
        //Create select field in attribute to choose design
        add_action( 'woocommerce_after_edit_attribute_fields', array($this,'edit_design_types'));
        add_action( 'woocommerce_after_add_attribute_fields',array($this,'add_design_types') );
        //save design types
        add_action( 'woocommerce_attribute_added',array($this,'save_attribute_type_design'),10,2);
        add_action( 'woocommerce_attribute_updated',array($this,'update_attribute_type_design'),10,3);

        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $this->attr_taxonomies = $attribute_taxonomies;

        foreach ($attribute_taxonomies as $tax) {
            $this->product_attr_type = $tax->attribute_type;

            add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
            add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );
            add_filter( 'manage_edit-pa_'.$tax->attribute_name.'_columns', array( $this, 'add_attribute_column' ));
            add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
        }
        add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 3 );
        add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 3 );

        add_action( 'woocommerce_product_options_attributes',array($this,'thwvsf_popup_fields'));
        add_action( 'woocommerce_product_option_terms',array($this,'thwvsf_product_option_terms'), 20, 2 );

        add_filter('woocommerce_product_data_tabs',array($this,'new_tabs_for_swatches_settings') );
        add_action('woocommerce_product_data_panels',array($this,'output_custom_swatches_settings'));
        add_action('woocommerce_process_product_meta', array( $this, 'save_custom_fields' ), 10, 2);

        add_action('admin_footer-plugins.php', array($this, 'thwvs_deactivation_form'));
        add_action('wp_ajax_thwvs_deactivation_reason', array($this, 'thwvs_deactivation_reason'));

        //add_action( 'admin_footer' ,array($this,'output_sendinblue_form'));
    }

    public function add_attribute_types( $types ) {
        $more_types = array(
          'color' => __( 'Color', 'product-variation-swatches-for-woocommerce' ),
          'image' => __( 'Image', 'product-variation-swatches-for-woocommerce' ),
          'label' => __( 'Button/Label', 'product-variation-swatches-for-woocommerce' ), 
          'radio' => __( 'Radio', 'product-variation-swatches-for-woocommerce' ),
        );

        $types = array_merge( $types, $more_types );
        return $types;
    }

    public function get_design_types(){

        $default_design_types = THWVSF_Admin_Utils::$sample_design_labels;

        $designs = THWVSF_Admin_Utils::get_design_styles();
      
        $design_types = $designs ?  $designs : $default_design_types;
       
        return $design_types;
    }

    //Add design Profiles
    public function add_design_types(){
        $free_design_keys = array('swatch_design_default', 'swatch_design_1', 'swatch_design_2', 'swatch_design_3');
        ?>
            <div class="form-field">
                <h2> <?php esc_html_e( 'Swatches Options', 'product-variation-swatches-for-woocommerce' ); ?> </h2>
            </div>
            <div class="form-field">
                <label for="attribute_design_type"><?php esc_html_e( 'Design Types', 'product-variation-swatches-for-woocommerce' ); ?></label>
                     <select name="attribute_design_type" id="attribute_design_type">

                        <?php foreach ($this->get_design_types() as $key => $value ) : 
                            if (in_array($key, $free_design_keys)){ ?>
                                <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>

                            <?php } 
                        endforeach; ?>
                                       
                     </select>
                <p class="description"><?php esc_html_e( "Determines how this attribute types are displayed.", 'product-variation-swatches-for-woocommerce' ); ?></p>
            </div>
        <?php
    }

    public function edit_design_types(){

        $free_design_keys = array('swatch_design_default', 'swatch_design_1', 'swatch_design_2', 'swatch_design_3');
        $attribute_id     = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
        $attr_design      = THWVSF_Utils::get_design_swatches_settings($attribute_id);
        ?>
        <tr class="form-field ">
            <th scope="row" valign="top">
                <label for="attribute_design_type"><?php esc_html_e( 'Design Types', 'product-variation-swatches-for-woocommerce' ); ?></label>
            </th>
            <td>
               <select name="attribute_design_type" id="attribute_design_type">
                    <?php foreach ( $this->get_design_types() as $key => $value ) : 
                        if (in_array($key, $free_design_keys)){ ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected($attr_design , $key ); ?>><?php echo esc_html( $value ); ?></option>
                        <?php } 
                    endforeach; ?>
                </select> 
                <p class="description"><?php esc_html_e( "Determines how this attribute's types are displayed.", 'product-variation-swatches-for-woocommerce' ); ?></p>
            </td>
        </tr>
        <?php
    }

    //save design types
    public function save_attribute_type_design($id, $data){
        $design_type          = isset( $_POST['attribute_design_type'] ) ? wc_clean( wp_unslash( $_POST['attribute_design_type'] ) ) : '';
        $design_settings      = THWVSF_Utils::get_design_swatches_settings();
        $design_settings      = is_array($design_settings) ? $design_settings : array();
        $design_settings[$id] = $design_type;

        update_option(THWVSF_Utils::OPTION_KEY_DESIGN_SETTINGS, $design_settings); 
    }

    public function update_attribute_type_design($id, $data, $old_slug){

        $design_type          = isset( $_POST['attribute_design_type'] ) ? wc_clean( wp_unslash( $_POST['attribute_design_type'] ) ) : '';
        $design_settings      = THWVSF_Utils::get_design_swatches_settings();
        $design_settings      = is_array($design_settings) ? $design_settings : array();
        $design_settings[$id] = $design_type;
        update_option(THWVSF_Utils::OPTION_KEY_DESIGN_SETTINGS, $design_settings);
    }

    public function add_attribute_fields($taxonomy){

        $attribute_type = $this->get_attribute_type($taxonomy);
        $this->product_attribute_fields($taxonomy,$attribute_type, 'new', 'add');                       
    }

    public function edit_attribute_fields($term, $taxonomy){
        $attribute_type  = $this->get_attribute_type($taxonomy);
        $term_fields     = array();
        $term_type_field = get_term_meta($term->term_id,'product_'.$taxonomy, true);

        $term_fields = array(
            'term_type_field' => $term_type_field ? $term_type_field : '',
        );
        $this->product_attribute_fields($taxonomy,$attribute_type, $term_fields,'edit');
    }

    public function get_attribute_type($taxonomy){
        foreach ($this->attr_taxonomies as $tax) {
            if('pa_'.$tax->attribute_name == $taxonomy){
                return($tax->attribute_type);
                break;
            }
        }
    }

    public function product_attribute_fields($taxonomy, $type, $value, $form){
        switch ( $type ) {
            case 'color':
                $this->add_color_field($value,$taxonomy);
                break;
            case 'image':
                $this->add_image_field($value,$taxonomy);
                break;
            case 'label' :
                $this->add_label_field($value,$taxonomy);
                break;
            default:
                break;
        }
    }

    private function add_color_field($value, $taxonomy){

        $term_type_field = is_array($value) && $value['term_type_field'] ? $value['term_type_field']:'';
        $label = __( 'Color', 'product-variation-swatches-for-woocommerce' );
        if($value == 'new'){ 
            ?>  
            <div class="thwvsf-types gbl-attr-color gbl-attr-terms gbl-attr-terms-new">
                <label><?php echo esc_html($label); ?></label>
                <div class="thwvsf_settings_fields_form thwvs-col-div">
                    <span class="thpladmin-colorpickpreview color_preview"></span>
                    <input type="text" name= "<?php echo'product_'.esc_attr($taxonomy) ; ?>" class="thpladmin-colorpick"/>
                </div> 
            </div>
            <?php

        } else {
            ?>
            <tr class="gbl-attr-terms gbl-attr-terms-edit" > 
                <th><?php echo esc_html($label); ?></th>
                <td>
                    <div class="thwvsf_settings_fields_form thwvs-col-div">
                        <span class="thpladmin-colorpickpreview color_preview" style="background:<?php echo esc_attr($term_type_field) ?>;"></span>
                        <input type="text"  name= "<?php echo'product_'.esc_attr($taxonomy ); ?>" class="thpladmin-colorpick" value="<?php echo esc_attr($term_type_field) ?>"/>
                    </div>         
                </td>
            </tr> 
            <?Php
        }
    }

    private function add_image_field($value, $taxonomy){
        $image = is_array($value) && $value['term_type_field'] ? wp_get_attachment_image_src( $value['term_type_field'] ) : '';
        $image = $image ? $image[0] : THWVSF_ASSETS_URL_ADMIN . '/images/placeholder.svg';
        $label = __( 'Image', 'product-variation-swatches-for-woocommerce' );

        if($value == 'new'){ 
            ?>
            <div class="thwvsf-types gbl-attr-img gbl-attr-terms gbl-attr-terms-new">
                <div class='thwvsf-upload-image'>
                    <label><?php echo esc_html($label); ?></label>
                    <div class="tawcvs-term-image-thumbnail">
                        <img class="i_index_media_img" src="<?php echo ( esc_url( $image )); ?>" width="50px" height="50px" alt="term-image"/>  <?php  ?>
                    </div>
                    <div style="line-height:60px;">
                        <input type="hidden" class="i_index_media" name="product_<?php echo esc_attr($taxonomy) ?>" value="">
           
                        <button type="button" class="thwvsf-upload-image-button button " onclick="thwvsf_upload_icon_image(this,event)">
                            <img class="thwvsf-upload-button" src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/upload.svg') ?>" alt="upload-button">
                            <?php // esc_html_e( 'Upload image', 'thwcvs' ); ?>
                        </button>

                        <button type="button" style="display:none" class="thwvsf_remove_image_button button " onclick="thwvsf_remove_icon_image(this,event)">
                            <img class="thwvsf-remove-button" src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/remove.svg')?>" alt="remove-button">
                        </button>
                    </div>
                </div>
            </div>
            <?php 

        }else{
            ?>
            <tr class="form-field gbl-attr-img gbl-attr-terms gbl-attr-terms-edit">
                <th><?php echo esc_html($label); ?></th>
                <td>
                    <div class = 'thwvsf-upload-image'>
                        <div class="tawcvs-term-image-thumbnail">
                            <img  class="i_index_media_img" src="<?php echo ( esc_url( $image )); ?>" width="50px" height="50px" alt="term-image"/>  <?php  ?>
                        </div>
                        <div style="line-height:60px;">
                            <input type="hidden" class="i_index_media"  name= "product_<?php echo esc_attr($taxonomy) ?>" value="">
               
                            <button type="button" class="thwvsf-upload-image-button  button" onclick="thwvsf_upload_icon_image(this,event)">
                                <img class="thwvsf-upload-button" src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/upload.svg') ?>" alt="upload-button">
                                <?php // esc_html_e( 'Upload image', 'thwcvs' ); ?>
                            </button>

                            <button type="button" style="<?php echo (is_array($value) && $value['term_type_field']  ? '' :'display:none'); ?> "  class="thwvsf_remove_image_button button " onclick="thwvsf_remove_icon_image(this,event)">
                                <img class="thwvsf-remove-button" src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/remove.svg')?>" alt="remove-button">
                            </button>
                        </div>
                    </div>
                </td>
            </tr> 
            <?Php
        }   
    }

    public function add_label_field($value, $taxonomy){  

        $label = __( 'Label', 'product-variation-swatches-for-woocommerce' );
        if($value == 'new'){
            ?>
            <div class="thwvsf-types gbl-attr-label gbl-attr-terms gbl-attr-terms-new">
                <label><?php echo esc_html($label); ?></label> 
                <input type="text" class="i_label" name="product_<?php echo esc_attr($taxonomy) ?>" value="" />
            </div>
            <?php
        }else{
            ?>
            <tr class="form-field gbl-attr-label gbl-attr-terms gbl-attr-terms-edit" > 
                <th><?php echo  esc_html($label); ?></th>
                <td>
                    <input type="text" class="i_label" name="product_<?php echo esc_attr($taxonomy) ?>" value="<?php echo esc_attr($value['term_type_field']) ?>" />
                </td>
            </tr> 
            <?Php
        } 
    }

    public function save_term_meta($term_id, $tt_id, $taxonomy){
        if( isset($_POST['product_'.$taxonomy] )  && !empty($_POST['product_'.$taxonomy] ) ){
            update_term_meta( $term_id,'product_'.$taxonomy, wc_clean(wp_unslash($_POST['product_'.$taxonomy])));
        }   
    }

    public function add_attribute_column($columns){
        $new_columns = array();

        if ( isset( $columns['cb'] ) ) {
            $new_columns['cb'] = $columns['cb'];
            unset( $columns['cb'] );
        }

        $new_columns['thumb'] = __( '', 'woocommerce' );

        $columns = array_merge( $new_columns, $columns );
       
        return $columns;
    }

    public function add_attribute_column_content($columns, $column, $term_id){
        $taxonomy = $_REQUEST['taxonomy'];
        $attr_type = $this->get_attribute_type($_REQUEST['taxonomy']);

        $value = get_term_meta( $term_id,'product_'.$taxonomy,true);

        switch ( $attr_type) {
            case 'color':
                printf( '<span class="th-term-color-preview" style="background-color:%s;"></span>', esc_attr( $value ) );
                break;

            case 'image':
                $image = $value ? wp_get_attachment_image_src( $value ) : '';
                $image = $image ? $image[0] : THWVSF_URL . 'admin/assets/images/placeholder.png';
                printf( '<img class="swatch-preview swatch-image" src="%s" width="44px" height="44px" alt="preview-image">', esc_url( $image ) );
                break;

            case 'label':
                printf( '<div class="swatch-preview swatch-label">%s</div>', esc_html( $value ) );
                break;
        }
    }

    public function get_attribute_by_taxonomy($taxonomy){

        global $wpdb;
        $attr = substr( $taxonomy, 3 );
        $attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" );
    }

    public function thwvsf_product_option_terms($attribute_taxonomy, $i ) {

        if ( 'select' !== $attribute_taxonomy->attribute_type ) {
            global $post, $thepostid, $product_object;
            $taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
            
            $product_id = $thepostid;
            if ( is_null( $thepostid ) && isset( $_POST[ 'post_id' ] ) ) {
                $product_id = absint( $_POST[ 'post_id' ] );
            }

            ?>
            <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woocommerce' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
            <?php
                $args      = array(
                    'orderby'    => 'name',
                    'hide_empty' => 0,
                );
            
                $all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
                    if ( $all_terms ) :
                        $options = array();
                        foreach ($all_terms as $key ) {
                            $options[] = $key->term_id;
                        }

                        foreach ( $all_terms as $term ) :
                        
                            $options = ! empty( $options ) ? $options : array();

                            echo '<option value="' . esc_attr( $term->term_id ) . '" ' . wc_selected( has_term( absint( $term->term_id ), $taxonomy, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
                        endforeach;
                    endif;
                ?>
            </select>
           
            <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woocommerce' ); ?></button>
            <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woocommerce' ); ?></button>
            
            <?php
             $taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
             $attr_type = $attribute_taxonomy->attribute_type;

            if ( (  $attribute_taxonomy->attribute_type == 'label' || $attribute_taxonomy->attribute_type == 'image' || $attribute_taxonomy->attribute_type == 'color')){ ?>
                <button class="button fr plus thwvsf_add_new_attribute"  data-attr_taxonomy="<?php echo esc_attr($taxonomy); ?>"  data-attr_type="<?php echo esc_attr($attr_type )?>"  data-dialog_title="<?php printf( esc_html__( 'Add new %s', '' ), esc_attr($attribute_taxonomy->attribute_label ) ) ?>">  <?php esc_html_e( 'Add new', '' ); ?>  </button> 

             <?php  

            }else{?>
                <button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'woocommerce' ); ?></button> <?php
            }
        }
    }

    public function new_tabs_for_swatches_settings($tabs){
        $tabs['thwvs_swatches_settings']     = array(
            'label'    => __( 'Swatches Settings', 'product-variation-swatches-for-woocommerce' ),
            'target'   => 'thwvs-product-attribute-settings',
            'class'    => array('variations_tab', 'show_if_variable', ),
            'priority' => 65,
        );
        return $tabs;
    }

    public function output_custom_swatches_settings(){
        
        global $post, $thepostid, $product_object,$wc_product_attributes;

        $saved_settings = get_post_meta($thepostid,'th_custom_attribute_settings', true);

        $type_options = array(

            'select' =>  __('Select', 'product-variation-swatches-for-woocommerce' ), 
            'color'  =>  __('Color', 'product-variation-swatches-for-woocommerce' ),
            'label'  =>  __('Button/Label', 'product-variation-swatches-for-woocommerce' ),
            'image'  =>  __('Image' , 'product-variation-swatches-for-woocommerce' ),
            'radio'  =>  __('Radio', 'product-variation-swatches-for-woocommerce')
        );

        $default_design_types = THWVSF_Admin_Utils::$sample_design_labels;
        $designs = THWVSF_Admin_Utils::get_design_styles();
        $design_types = $designs ?  $designs : $default_design_types;

        ?>
        <div id="thwvs-product-attribute-settings" class="panel wc-metaboxes-wrapper hidden">
            <div id="custom_variations_inner">
                <h2><?php esc_html_e( 'Custom Attribute Settings', 'product-variation-swatches-for-woocommerce' ); ?></h2>
                
                <?php 
                $attributes = $product_object->get_attributes();
                $i = -1;
                $has_custom_attribute = false;
                
                foreach ($attributes as $attribute){ 
                    $attribute_name = sanitize_title($attribute->get_name());
                    $type = '';
                    
                    $i++;
                    if ($attribute->is_taxonomy() == false){
                        $has_custom_attribute = true;
                        ?>
                    <div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox closed" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
               
                        <h3>
                            <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'woocommerce' ); ?>"></div>
                            <strong class="attribute_name"><?php echo wc_attribute_label($attribute_name); ?></strong>
                        </h3>
                        <div class="thwvsf_custom_attribute wc-metabox-content  <?php echo 'thwvs-'.esc_attr($attribute_name); ?> hidden">
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            
                                            <p class="form-row form-row-full ">
                                                <label for="custom_attribute_type"><?php esc_html_e('Swatch Type','product-variation-swatches-for-woocommerce'); ?></label>
                                                <span class="woocommerce-help-tip" data-tip=" Determines how this custom attribute's values are displayed">
                                                </span>
                                                   <!--  <?php //echo wc_help_tip(" Determines how this custom attributes are displayed"); // WPCS: XSS ok. ?> -->
                    
                                                <select   name="<?php echo ('th_attribute_type_'.esc_attr($attribute_name)); ?>" class="select short th-attr-select" value = '' onchange="thwvsf_change_term_type(this,event)">
                                                    <?php 
                                                    $type = $this->get_custom_fields_settings($thepostid,$attribute_name,'type');
                                                   
                                                    foreach ($type_options as $key => $value) { 
                                                        $default = (isset($type) &&  $type == $key) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo esc_attr($key); ?>" <?php echo $default ?> > <?php echo esc_html($value); ?> </option>
                                                    <?php
                                                    }?>
                                                </select>
                                             
                                            </p>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td colspan="2">
                                            
                                            <p class="form-row form-row-full ">
                                                <label for="custom_attribute_type"><?php esc_html_e('Swatch Design Type','product-variation-swatches-for-woocommerce'); ?> </label>
                                                <span class="woocommerce-help-tip" data-tip=" Determines how this custom attribute types are displayed">
                                                   
                                                </span>   
                                                <select   name="<?php echo esc_attr('th_attribute_design_type_'. $attribute_name); ?>" class="select short th-attr-select" value = ''>
                                                    <?php 
                                                    $design_type = $this->get_custom_fields_settings($thepostid,$attribute_name,'design_type');
                                                    //$design_type = '';
                                                    foreach ($design_types as $key => $value) { 

                                                        $default = (isset($design_type) &&  $design_type == $key) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo esc_attr($key); ?>" <?php echo $default ?> > <?php echo esc_html($value); ?> </option>
                                                    <?php
                                                    }?>
                                                </select>
                                             
                                            </p>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <th></th>
                                        
                                    </tr>
                                    
                                        <tr>
                                       <td>
                                         
                                         <?php  $this->custom_attribute_settings_field($attribute,$thepostid); ?>
                                       </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                     </div>
                    <?php }
                }

                if(!$has_custom_attribute){
                    ?>
                    <div class="inline notice woocommerce-message">

                        <p><?php esc_html_e('No custom attributes added yet.','woocommerce-product-variation-swatches');
                       esc_html_e(' You can add custom attributes from the', 'woocommerce-product-variation-swatches'); ?> <a onclick="thwvsfTriggerAttributeTab(this)" href="#woocommerce-product-data"><?php  esc_html_e(' Attributes','woocommerce-product-variation-swatches'); ?> </a> <?php esc_html_e('tab','woocommerce-product-variation-swatches'); ?></p>
                    </div>
                   <?php
                }
                ?>

            </div>
        </div> <?php
    }

    public function custom_attribute_settings_field($attribute, $post_id){

        $attribute_name = sanitize_title($attribute->get_name());
        $type = $this->get_custom_fields_settings($post_id,$attribute_name,'type');        
        $this->output_field_label($type,$attribute,$post_id);
        $this->output_field_image($type,$attribute,$post_id);
        $this->output_field_color($type,$attribute,$post_id);
    }

    public function output_field_label($type, $attribute, $post_id){
        $attribute_name = sanitize_title($attribute->get_name());
        $display_status = $type == 'label' ?'display: table': 'display: none' ;
        ?>
        <table class="thwvsf-custom-table thwvsf-custom-table-label" style="<?php echo $display_status ; ?>">
            <?php
            $i= 0;
            foreach ($attribute->get_options() as $term) {
                $css = $i==0 ? 'display:table-row-group' :'';
                $open = $i==0 ? 'open' :'';
                ?>
                <tr class="thwvsf-term-name">
                    <td colspan="2">
                        <h3 class="thwvsf-local-head <?php echo $open;?>" data-type="<?php echo esc_attr($type); ?>" data-term_name="<?php echo  esc_attr($term); ?>" onclick="thwvsf_open_body(this,event)"><?php echo esc_html($term); ?></h3>
                        <table class="thwvsf-local-body-table">
                            <tbody class="thwvsf-local-body thwvsf-local-body-<?php echo esc_attr($term); ?>" style="<?php echo esc_attr($css); ?>">
                                <tr> 
                                    <td width="30%"><?php _e('Term Name', 'product-variation-swatches-for-woocommerce') ?></td>
                                    <td width="70%"><?php echo esc_html($term); ?></td>
                                </tr>
                                <tr class="form-field"> 
                                    <td><?php esc_html_e('Label Text', 'product-variation-swatches-for-woocommerce') ?></td>
                                    <td>
                                        <?php $term_field = $type == 'label' ? $this->get_custom_fields_settings($post_id,$attribute_name,$term,'term_value') : ''; 
                                            $term_field = ($term_field) ? $term_field : '';
                                        ?>
                                        <input type="text" class="i_label" name="<?php echo esc_attr(sanitize_title('label_'.$attribute_name.'_term_'.$term)); ?>" style="width:275px;" value="<?php echo esc_attr($term_field); ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                
                <?php 
                $i++;
            }
            ?>
        </table>
        <?php
    }

    public function output_field_image($type, $attribute, $post_id){
        $attribute_name = sanitize_title($attribute->get_name());
        $display_status = $type == 'image' ?'display:table': 'display: none' ;
        ?>
        <table class="thwvsf-custom-table thwvsf-custom-table-image" style="<?php echo esc_attr($display_status); ?>">
        <?php
            $i = 0;
            foreach ($attribute->get_options() as $term) {
                $css = $i==0 ? 'display:table-row-group' :'';
                $open = $i==0 ? 'open' :'';
                ?>
                <tr class="thwvsf-term-name">
                    <td colspan="2">
                        <h3 class="thwvsf-local-head <?php echo $open;?>" data-term_name="<?php echo $term; ?>" onclick="thwvsf_open_body(this,event)"><?php echo esc_html($term); ?></h3>
                        <table class="thwvsf-local-body-table">
                            <tbody class="thwvsf-local-body thwvsf-local-body-<?php echo esc_attr($term); ?>" style="<?php echo $css; ?>">
                                <tr> 
                                    <td width="30%">Term Name</td>
                                    <td width="70%"><?php echo $term; ?></td>
                                </tr>
                                <tr class="form-field"> <td><?php _e('Term Image', 'product-variation-swatches-for-woocommerce') ?></td>
                                    <td>
                                        <?php $term_field = $this->get_custom_fields_settings($post_id,$attribute_name,$term,'term_value'); 

                                            $term_field = ($term_field) ? $term_field : '';

                                            $image =  $type == 'image' ?  $this->get_custom_fields_settings($post_id,$attribute_name,$term,'term_value') : ''; 
                                            $image = ($image) ? wp_get_attachment_image_src($image) : ''; 
                                            $remove_img = ($image)  ? 'display:inline' :'display:none';
                                            // $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                                            $image = $image ? $image[0] : THWVSF_ASSETS_URL_ADMIN . '/images/placeholder.svg';
                                        ?>

                                        <div class = 'thwvsf-upload-image'>
                                    
                                            <div class="tawcvs-term-image-thumbnail" style="float:left;margin-right:10px;">
                                                <img  class="i_index_media_img" src="<?php echo ( esc_url( $image )); ?>" width="60px" height="60px" alt="term-image"/>  <?php  ?>
                                            </div>

                                            <div style="line-height:30px;">
                                                <input type="hidden" class="i_index_media"  name= "<?php echo esc_attr(sanitize_title('image_'.$attribute_name.'_term_'.$term)); ?>" value="<?php echo $term_field; ?>">
                                   
                                                <button type="button" class="thwvsf-upload-image-button button " onclick="thwvsf_upload_icon_image(this,event)">
                                                    <img class="thwvsf-upload-button" src="<?php echo ( esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/upload.svg')) ?>" alt="upload-button">
                                                </button>
                                                <button type="button" style="<?php echo $remove_img; ?>" class="thwvsf_remove_image_button button " onclick="thwvsf_remove_icon_image(this,event)">
                                                    <img class="thwvsf-remove-button" src="<?php echo ( esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/remove.svg'))?>" alt="remove-button">
                                                </button> 
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                
                <?php
                $i++;
            }?>    
        </table>
        <?php
    }

    public function output_field_color($type, $attribute, $post_id){

        $attribute_name = sanitize_title($attribute->get_name());
        $display_status = $type == 'color' ?'display: table': 'display: none' ;
        ?>
        <table class="thwvsf-custom-table thwvsf-custom-table-color" style="<?php echo $display_status; ?>">
            <?php
            $i = 0;
            foreach ($attribute->get_options() as $term) {
                $css = $i==0 ? 'display:table-row-group' :'';
                $open = $i==0 ? 'open' :'';
                ?>
                <tr class="thwvsf-term-name">
                    <td colspan="2">
                        <h3 class="thwvsf-local-head <?php echo $open;?>" data-term_name="<?php echo esc_attr($term); ?>" onclick="thwvsf_open_body(this,event)"><?php echo esc_html($term); ?></h3>
                        <table class="thwvsf-local-body-table">
                            <tbody class="thwvsf-local-body thwvsf-local-body-<?php echo $term; ?>" style="<?php echo $css; ?>">
                                <tr>
                                    <td width="30%"><?php esc_html_e('Term Name', 'product-variation-swatches-for-woocommerce') ?></td>
                                    <td width="70%"><?php echo esc_html($term); ?></td>
                                </tr>
                                <?php 
                                $color_type = $this->get_custom_fields_settings($post_id,$attribute_name,$term,'color_type');
                                $color_type = $color_type ? $color_type : '';
                                ?>

                                <tr>
                                    <td>Term Color</td>
                                    <td class = "th-custom-attr-color-td"><?php
                                        $term_field = $type == 'color' ? $this->get_custom_fields_settings($post_id,$attribute_name,$term,'term_value') : ''; 
                                        $term_field = ($term_field) ? $term_field : '' ; ?>

                                        <div class="thwvsf_settings_fields_form thwvs-col-div" style="margin-bottom: 5px">
                                            <span class="thpladmin-colorpickpreview color_preview" style="background-color: <?php echo $term_field; ?> ;"></span>
                                            <input type="text"   name= "<?php echo esc_attr(sanitize_title('color_'.$attribute_name.'_term_'.$term)); ?>" class="thpladmin-colorpick" value="<?php echo esc_attr($term_field); ?>" style="width:250px;"/>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
                $i++;
            } ?>
        </table><?php
    }

    public function get_custom_fields_settings($post_id, $attribute=false, $term=false, $term_key=false){

        $saved_settings = get_post_meta($post_id,'th_custom_attribute_settings', true);

        if(is_array($saved_settings)){
            if($attribute){
                if(isset($saved_settings[$attribute])){
                    $attr_settings = $saved_settings[$attribute];

                    if(is_array($attr_settings) && $term){
                        if($term === 'type' || $term ==='tooltip_type' || $term ==='radio-type' ||  $term ==='design_type'){
                            $term_types =  (isset($attr_settings[$term])) ?   $attr_settings[$term] :  false;
                            return $term_types; 
                        }else{
                            $term_settings = isset($attr_settings[$term]) ? $attr_settings[$term] : '';
                            if(is_array($term_settings) && $term_key){
                                $settings_value = isset($term_settings[$term_key]) ? $term_settings[$term_key]: '';
                                return  $settings_value;
                            }else{
                                return false;
                            }
                            return $term_settings;
                        }                       
                    }
                    return $attr_settings;
                }
                return false;
            }
            return $saved_settings;
        }else{
            return false;
        }
    }
 
    public function thwvsf_popup_fields(){
      
        $image = THWVSF_ASSETS_URL_ADMIN . '/images/placeholder.svg';
        ?>
        <div class="thwvsf-attribte-dialog thwvsf-attribte-dialog-color " style = "display:none;">
            <table>
     
                <tr>
                    <td><span><?php _e('Name:', 'product-variation-swatches-for-woocommerce');?></span></td>
                    <td><input type="text"  name= "attribute_name" class="thwvsf-class" value="" style="width:225px; height:40px;"/></td>
                </tr>
                <tr>
                    <td><span><?php _e('Color:', 'product-variation-swatches-for-woocommerce');?></span></td>
                    <td class="locl-attr-terms">
                        <div class="thwvsf_settings_fields_form thwvs-col-div">
                            <span class="thpladmin-colorpickpreview color_preview"></span>
                            <input type="text" name= "attribute_type" class="thpladmin-colorpick" style="width:225px; height:40px;"/>
                        </div> 
                    </td>
                </tr>
            </table>
        </div>

        <div class="thwvsf-attribte-dialog thwvsf-attribte-dialog-image" style = "display:none;">
            <table>
                <tr>
                    <td> <span><?php esc_html_e('Name:', 'product-variation-swatches-for-woocommerce');?></span></td>
                    <td><input type="text" name= "attribute_name" class="thwvsf-class" value="" style="width:216px"/></td>
                </tr>
                <tr valign="top">
                    <td><span><?php esc_html_e('Image:', 'product-variation-swatches-for-woocommerce');?></span> </td>
                    <td>
                        <div class = 'thwvsf-upload-image'>
                            <div class="thwvsf-term-image-thumbnail" style="float:left; margin-right:10px;">
                                <img  class="i_index_media_img" src="<?php echo ( esc_url( $image )); ?>" width="60px" height="60px" alt="term-images"/>
                            </div>

                            <input type="hidden" class="i_index_media thwvsf-class"  name= "attribute_type" value="">
                            <button type="button" class="thwvsf-upload-image-button button " onclick="thwvsf_upload_icon_image(this,event)">
                                <img class="thwvsf-upload-button" src="<?php echo ( esc_url(THWVSF_ASSETS_URL_ADMIN .'/images/upload.svg')) ?>" alt="upload-button">
                            </button>
                            <button type="button" style="display:none" class="thwvsf_remove_image_button button " onclick="thwvsf_remove_icon_image(this,event)">
                                <img class="thwvsf-remove-button" src="<?php echo ( esc_url( THWVSF_ASSETS_URL_ADMIN .'/images/remove.svg'))?>" alt="remove-button">
                            </button> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="thwvsf-attribte-dialog thwvsf-attribte-dialog-label" style = "display:none;">
            <table>
                <tr>
                    <td><span><?php  esc_html_e('Name:', 'product-variation-swatches-for-woocommerce');?></span></td>
                    <td><input type="text" name= "attribute_name" class="thwvsf-class" value="" /></td>
                </tr>
                <tr>
                    <td><span><?php  esc_html_e('Label:', 'product-variation-swatches-for-woocommerce');?></span> </td>
                    <td>
                        <input type="text" name="attribute_type" class="thwvsf-class" value="" />
                    </td>
                </tr>    
            </table>
        </div>

        <?php
    }

    public function save_custom_fields($post_id, $post){
        
        $product = wc_get_product( $post_id );
        $local_attr_settings = array();

        foreach ($product->get_attributes() as $attribute ) {

            if ($attribute->is_taxonomy() == false) {

                $attr_settings         = array();
                $attr_name             = sanitize_title($attribute->get_name());
                $type_key              = 'th_attribute_type_'.$attr_name;
                $attr_settings['type'] = isset($_POST[$type_key]) ? sanitize_text_field($_POST[$type_key]) : '';

                $tt_key = sanitize_title('th_tooltip_type_'.$attr_name);
                $attr_settings['tooltip_type'] = isset($_POST[$tt_key]) ? sanitize_text_field($_POST[$tt_key]) : '';

                $design_type_key = sanitize_title('th_attribute_design_type_'.$attr_name);
                $attr_settings['design_type']   = isset($_POST[$design_type_key]) ? sanitize_text_field($_POST[$design_type_key]) : '';

                if($attr_settings['type'] == 'radio'){
                   $radio_style_key = sanitize_title($attr_name.'_radio_button_style');
                    $attr_settings['radio-type'] = isset($_POST[$radio_style_key ]) ? sanitize_text_field($_POST[$radio_style_key]) : '';
                }else{
                    $term_settings = array();
                    foreach ($attribute->get_options() as $term) {
                        $term_settings['name'] = $term;

                        if($attr_settings['type'] == 'color'){
                            $color_type_key        = sanitize_title($attr_name.'_color_type_'.$term);
                            $term_settings['color_type'] = isset($_POST[ $color_type_key]) ? sanitize_text_field($_POST[$color_type_key]) : '';
                        }

                        $term_key = sanitize_title($attr_settings['type'].'_'.$attr_name.'_term_'.$term);
                        $term_settings['term_value'] = isset($_POST[$term_key]) ? sanitize_text_field($_POST[$term_key]): '';
                        $attr_settings[$term] = $term_settings;
                    }
                }

                $local_attr_settings[$attr_name] = $attr_settings;
            }
        }

        update_post_meta( $post_id,'th_custom_attribute_settings',$local_attr_settings);     
    }

    /*public function remove_admin_notices(){

       $current_screen = get_current_screen();
       if($current_screen->id === 'product_page_th_product_variation_swatches_for_woocommerce'){

            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }*/

    public function wvsf_notice_actions(){

        if( !(isset($_GET['thwvsf_remind']) || isset($_GET['thwvsf_dismiss']) || isset($_GET['thwvsf_reviewed']))) {
            return;
        }

        $nonse      = isset($_GET['thwvsf_review_nonce']) ? $_GET['thwvsf_review_nonce'] : false;
        $capability = THWVSF_Utils::thwvsf_capability();

        if(!wp_verify_nonce($nonse, 'thwvsf_notice_security') || !current_user_can($capability)){
            die();
        }

        $now = time();

        $thwvsf_remind = isset($_GET['thwvsf_remind']) ? sanitize_text_field( wp_unslash($_GET['thwvsf_remind'])) : false;
        if($thwvsf_remind){
            update_user_meta( get_current_user_id(), 'thwvsf_review_skipped', true );
            update_user_meta( get_current_user_id(), 'thwvsf_review_skipped_time', $now );
        }

        $thwvsf_dismiss = isset($_GET['thwvsf_dismiss']) ? sanitize_text_field( wp_unslash($_GET['thwvsf_dismiss'])) : false;
        if($thwvsf_dismiss){
            update_user_meta( get_current_user_id(), 'thwvsf_review_dismissed', true );
            update_user_meta( get_current_user_id(), 'thwvsf_review_dismissed_time', $now );
        }

        $thwvsf_reviewed = isset($_GET['thwvsf_reviewed']) ? sanitize_text_field( wp_unslash($_GET['thwvsf_reviewed'])) : false;
        if($thwvsf_reviewed){
            update_user_meta( get_current_user_id(), 'thwvsf_reviewed', true );
        }
    }

    public function output_review_request_link(){

        $capability = THWVSF_Utils::thwvsf_capability();
        if(!current_user_can($capability)){
            return;
        }

        if(!apply_filters('thwvsf_show_dismissable_admin_notice', true)){
            return;
        }

        /*$current_screen = get_current_screen();
        if($current_screen->id !== 'product_page_th_product_variation_swatches_for_woocommerce'){
            return;
        }*/

        $thwvsf_reviewed = get_user_meta( get_current_user_id(), 'thwvsf_reviewed', true );
        if($thwvsf_reviewed){
            return;
        }

        $now = time();
        $dismiss_life  = apply_filters('thwvsf_dismissed_review_request_notice_lifespan', 6 * MONTH_IN_SECONDS);
        $reminder_life = apply_filters('thwvsf_skip_review_request_notice_lifespan',  7 * DAY_IN_SECONDS );

        $is_dismissed   = get_user_meta( get_current_user_id(), 'thwvsf_review_dismissed', true );
        $dismisal_time  = get_user_meta( get_current_user_id(), 'thwvsf_review_dismissed_time', true );
        $dismisal_time  = $dismisal_time ? $dismisal_time : 0;
        $dismissed_time = $now - $dismisal_time;
        if( $is_dismissed && ($dismissed_time < $dismiss_life) ){
            return;
        }

        $is_skipped    = get_user_meta( get_current_user_id(), 'thwvsf_review_skipped', true );
        $skipping_time = get_user_meta( get_current_user_id(), 'thwvsf_review_skipped_time', true );
        $skipping_time = $skipping_time ? $skipping_time : 0;
        $remind_time   = $now - $skipping_time;
        if($is_skipped && ($remind_time < $reminder_life) ){
            return;
        }
        
        $thwvsf_since = get_option('thwvsf_since');
        if(!$thwvsf_since){
            $now = time();
            update_option('thwvsf_since', $now, 'no' );
        }

        $thwvsf_since = $thwvsf_since ? $thwvsf_since : $now;
        $render_time  = apply_filters('thwvsf_show_review_banner_render_time' , 7 * DAY_IN_SECONDS);
        $render_time  = $thwvsf_since + $render_time;
        if($now > $render_time ){
            $this->render_review_request_notice();
        }
    }

    public function review_banner_custom_css(){

        ?>
        <style>
            .thwvsf-review-wrapper {
                padding: 15px 28px 26px 10px !important;
                margin-top: 35px;
            }

            #thwvsf_review_request_notice{
                margin-bottom: 20px;
            }

            .thwvsf-review-image {
                float: left;
            }

            .thwvsf-review-content {
                padding-right: 180px;
            }
            .thwvsf-review-content p {
                padding-bottom: 14px;
            }
            .thwvsf-notice-action{ 
                padding: 8px 18px 8px 18px;
                background: #fff;
                color:#007cba;
                border-radius: 5px;
                border: 1px solid  #007cba;
            }
            .thwvsf-notice-action.thwvsf-yes {
                background-color: #007cba;
                color: #fff;
            }
            .thwvsf-notice-action:hover:not(.thwvsf-yes) {
                background-color: #f2f5f6;
            }
            .thwvsf-notice-action.thwvsf-yes:hover {
                opacity: .9;
            }

            .thwvsf-themehigh-logo {
                position: absolute;
                right: 20px;
                top: calc(50% - 13px);
            }
            .thwvsf-notice-action {
                background-repeat: no-repeat;
                padding-left: 40px;
                background-position: 18px 8px;
                cursor: pointer;
            }
            .thwvsf-yes{
                background-image: url(<?php echo THWVSF_ASSETS_URL_ADMIN; ?>images/tick.svg);
            }
            .thwvsf-remind{
                background-image: url(<?php echo THWVSF_ASSETS_URL_ADMIN; ?>images/reminder.svg);
            }
            .thwvsf-dismiss{
                background-image: url(<?php echo THWVSF_ASSETS_URL_ADMIN; ?>images/close.svg);
            }
            .thwvsf-done{
                background-image: url(<?php echo THWVSF_ASSETS_URL_ADMIN; ?>images/done.svg);
            }

        </style>
        <?php    
    }
    public function review_banner_custom_js(){
        ?>
        <script type="text/javascript">
            (function($, window, document){

                $( document ).on( 'click', '.thpladmin-notice .notice-dismiss', function() {
                    var wrapper = $(this).closest('div.thpladmin-notice');
                    var nonce   = wrapper.data("nonce");
                   
                    var data = {
                        thwvsf_review_nonce: nonce,
                        action: 'dismiss_thwvsf_review_request_notice',
                    };
                    $.post( ajaxurl, data, function() {

                    });
                });
            
            }(window.jQuery, window, document))
        </script>
        <?php
    }

    private function render_review_request_notice(){

        /*$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general_settings'; 
        $url = 'edit.php?post_type=product&page=th_product_variation_swatches_for_woocommerce';
        if($tab && !empty($tab)){
            $url    .= '&tab='. $tab;
        }
        $admin_url  = admin_url($url);*/

        $remind_url   = add_query_arg(array('thwvsf_remind' => true , 'thwvsf_review_nonce' => wp_create_nonce('thwvsf_notice_security')));
        $dismiss_url  = add_query_arg(array('thwvsf_dismiss' => true, 'thwvsf_review_nonce' => wp_create_nonce( 'thwvsf_notice_security')));
        $reviewed_url = add_query_arg(array('thwvsf_reviewed' => true , 'thwvsf_review_nonce' => wp_create_nonce( 'thwvsf_notice_security')));
        ?>
        <div id="thwvsf_review_request_notice" class="notice notice-info thpladmin-notice is-dismissible thwvsf-review-wrapper" data-nonce="<?php echo wp_create_nonce( 'thwvsf_notice_security'); ?>">
            <div class="thwvsf-review-image">
                <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN .'images/review-left.png'); ?>" alt="themehigh">
            </div>
            <div class="thwvsf-review-content">
                <h3><?php esc_html_e('Tell us how it was!', 'woocommerce-product-variation-swatches'); ?></h3>
                <p><?php  esc_html_e('Thank you for choosing the Variation Swatches Plugin. We would love to hear about your experience while using it. Could you please leave us a review on WordPress to help us spread the word and boost our motivation?', 'product-variation-swatches-for-woocommerce'); ?></p>
                <div class="action-row">
                    <a class="thwvsf-notice-action thwvsf-yes" onclick="window.open('https://wordpress.org/support/plugin/product-variation-swatches-for-woocommerce/reviews/?rate=5#new-post', '_blank')" style="margin-right:16px; text-decoration: none">
                        <?php esc_html_e("Ok, You deserve it", 'product-variation-swatches-for-woocommerce'); ?>
                    </a>
                    <a class="thwvsf-notice-action thwvsf-done" href="<?php echo esc_url($reviewed_url); ?>" style="margin-right:16px; text-decoration: none">
                        <?php _e('Already, Did', 'product-variation-swatches-for-woocommerce'); ?>
                    </a>

                    <a class="thwvsf-notice-action thwvsf-remind" href="<?php echo esc_url($remind_url); ?>" style="margin-right:16px; text-decoration: none">
                        <?php esc_html_e('Maybe later', 'product-variation-swatches-for-woocommerce'); ?></a>

                    <a class="thwvsf-notice-action thwvsf-dismiss" href="<?php echo esc_url($dismiss_url); ?>" style="margin-right:16px; text-decoration: none"><?php esc_html_e("Nah, Never", 'product-variation-swatches-for-woocommerce'); ?></a>
                </div>
            </div>
            <div class="thwvsf-themehigh-logo">
                <span class="logo" style="float: right">
                    <a target="_blank" href="https://www.themehigh.com">
                        <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/logo.svg'); ?>" style="height:19px;margin-top:4px;" alt="themehigh"/>
                    </a>
                </span>
            </div>
        </div>
        <?php
    }

    public function thwvs_deactivation_form(){
        $is_snooze_time = get_user_meta( get_current_user_id(), 'thwvsf_deactivation_snooze', true );
        $now = time();

        if($is_snooze_time && ($now < $is_snooze_time)){
            return;
        }

        $deactivation_reasons = $this->get_deactivation_reasons();
        ?>
        <div id="thwvs_deactivation_form" class="thpladmin-modal-mask">
            <div class="thpladmin-modal">
                <div class="modal-container">
                    <!-- <span class="modal-close" onclick="thwvsfCloseModal(this)">×</span> -->
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="model-header">
                                <img class="th-logo" src="<?php echo esc_url(THWVSF_URL .'admin/assets/images/themehigh.svg'); ?>" alt="themehigh-logo">
                                <span><?php echo __('Quick Feedback', 'product-variation-swatches-for-woocommerce'); ?></span>
                            </div>

                            <!-- <div class="get-support-version-b">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                <a class="thwvs-link thwvs-right-link thwvs-active" target="_blank" href="https://help.themehigh.com/hc/en-us/requests/new"><?php echo __('Get Support', 'product-variation-swatches-for-woocommerce'); ?></a>
                            </div> -->

                            <main class="form-container main-full">
                                <p class="thwvs-title-text"><?php echo __('If you have a moment, please let us know why you want to deactivate this plugin', 'product-variation-swatches-for-woocommerce'); ?></p>
                                
                                <p class="thwvs-privacy-cnt"><?php echo __('In case your swatches are not working properly, click ', 'product-variation-swatches-for-woocommerce'); ?> 
                                    <a class="thwvs-privacy-link" target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/product-variation-swatches-for-woocommerce/#what%20else%20to%20do%20if%20swatches%20do%20not%20work%20on%20particular%20pages%2Fpages%20created%20with%20the%20builder%3F');?>"><?php echo __('here', 'product-variation-swatches-for-woocommerce'); ?></a> <?php echo __('to get an appropriate solution', 'product-variation-swatches-for-woocommerce' ) ?>
                                </p>

                                <ul class="deactivation-reason" data-nonce="<?php echo wp_create_nonce('thwvs_deactivate_nonce'); ?>">
                                    <?php 
                                    if($deactivation_reasons){
                                        foreach($deactivation_reasons as $key => $reason){
                                            $reason_type = isset($reason['reason_type']) ? $reason['reason_type'] : '';
                                            $reason_placeholder = isset($reason['reason_placeholder']) ? $reason['reason_placeholder'] : '';
                                            ?>
                                            <li data-type="<?php echo esc_attr($reason_type); ?>" data-placeholder="<?php echo esc_attr($reason_placeholder); ?> ">
                                                <label>
                                                    <input type="radio" name="selected-reason" value="<?php echo esc_attr($key); ?>">
                                                    <span><?php echo esc_html($reason['radio_label']); ?></span>
                                                </label>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <p class="thwvs-privacy-cnt"><?php echo __('This form is only for getting your valuable feedback. We do not collect your personal data. To know more read our ', 'product-variation-swatches-for-woocommerce'); ?> <a class="thwvs-privacy-link" target="_blank" href="<?php echo esc_url('https://www.themehigh.com/privacy-policy/');?>"><?php echo __('Privacy Policy', 'product-variation-swatches-for-woocommerce'); ?></a></p>
                            </main>
                            <footer class="modal-footer">
                                <div class="thwvs-left">
                                    <a class="thwvs-link thwvs-left-link thwvs-deactivate" href="#"><?php echo __('Skip & Deactivate', 'product-variation-swatches-for-woocommerce'); ?></a>
                                </div>
                                <div class="thwvs-right">
                                    <a class="thwvs-link thwvs-right-link thwvs-active" target="_blank" href="https://help.themehigh.com/hc/en-us/requests/new"><?php echo __('Get Support', 'product-variation-swatches-for-woocommerce'); ?></a>
                                    <a class="thwvs-link thwvs-right-link thwvs-active thwvs-submit-deactivate" href="#"><?php echo __('Submit and Deactivate', 'product-variation-swatches-for-woocommerce'); ?></a>
                                    <a class="thwvs-link thwvs-right-link thwvs-close" href="#"><?php echo __('Cancel', 'product-variation-swatches-for-woocommerce'); ?></a>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            .th-logo{
                margin-right: 10px;
            }
            .thpladmin-modal-mask{
                position: fixed;
                background-color: rgba(17,30,60,0.6);
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                overflow: scroll;
                transition: opacity 250ms ease-in-out;
            }
            .thpladmin-modal-mask{
                display: none;
            }
            .thpladmin-modal .modal-container{
                position: absolute;
                background: #fff;
                border-radius: 2px;
                overflow: hidden;
                left: 50%;
                top: 50%;
                transform: translate(-50%,-50%);
                width: 50%;
                max-width: 960px;
                /*min-height: 560px;*/
                /*height: 70vh;*/
                /*max-height: 640px;*/
                animation: appear-down 250ms ease-in-out;
                border-radius: 15px;
            }
            .model-header {
                padding: 21px;
            }
            .thpladmin-modal .model-header span {
                font-size: 18px;
                font-weight: bold;
            }
            .thpladmin-modal .model-header {
                padding: 21px;
                background: #ECECEC;
            }
            .thpladmin-modal .form-container {
                margin-left: 23px;
                clear: both;
            }
            .thpladmin-modal .deactivation-reason input {
                margin-right: 13px;
            }
            .thpladmin-modal .thwvs-privacy-cnt {
                color: #919191;
                font-size: 12px;
                margin-bottom: 31px;
                margin-top: 18px;
                max-width: 75%;
            }
            .thpladmin-modal .deactivation-reason li {
                margin-bottom: 17px;
            }
            .thpladmin-modal .modal-footer {
                padding: 20px;
                border-top: 1px solid #E7E7E7;
                float: left;
                width: 100%;
                box-sizing: border-box;
            }
            .thwvs-left {
                float: left;
            }
            .thwvs-right {
                float: right;
            }
            .thwvs-link {
                line-height: 31px;
                font-size: 12px;
            }
            .thwvs-left-link {
                font-style: italic;
            }
            .thwvs-right-link {
                padding: 0px 20px;
                border: 1px solid;
                display: inline-block;
                text-decoration: none;
                border-radius: 5px;
            }
            .thwvs-right-link.thwvs-active {
                background: #0773AC;
                color: #fff;
            }
            .thwvs-title-text {
                color: #2F2F2F;
                font-weight: 500;
                font-size: 15px;
            }
            .reason-input {
                margin-left: 31px;
                margin-top: 11px;
                width: 70%;
            }
            .reason-input input {
                width: 100%;
                height: 40px;
            }
            .reason-input textarea {
                width: 100%;
                min-height: 80px;
            }
            input.th-snooze-checkbox {
                width: 15px;
                height: 15px;
            }
            input.th-snooze-checkbox:checked:before {
                width: 1.2rem;
                height: 1.2rem;
            }
            .th-snooze-select {
                margin-left: 20px;
                width: 172px;
            }

            /* Version B */
            .get-support-version-b {
                width: 100%;
                padding-left: 23px;
                clear: both;
                float: left;
                box-sizing: border-box;
                background: #0673ab;
                color: #fff;
                margin-bottom: 20px;
            }
            .get-support-version-b p {
                font-size: 12px;
                line-height: 17px;
                width: 70%;
                display: inline-block;
                margin: 0px;
                padding: 15px 0px;
            }
            .get-support-version-b .thwvs-right-link {
                background-image: url(<?php echo esc_url(THWVSF_URL .'admin/assets/css/get_support_icon.svg'); ?>);
                background-repeat: no-repeat;
                background-position: 11px 10px;
                padding-left: 31px;
                color: #0773AC;
                background-color: #fff;
                float: right;
                margin-top: 17px;
                margin-right: 20px;
            }
            .thwvs-privacy-link {
                font-style: italic;
            }
        </style>

        <script type="text/javascript">
            (function($){
                var popup = $("#thwvs_deactivation_form");
                var deactivation_link = '';

                $('.thwvs-deactivate-link').on('click', function(e){
                    e.preventDefault();
                    deactivation_link = $(this).attr('href');
                    popup.css("display", "block");
                    popup.find('a.thwvs-deactivate').attr('href', deactivation_link);
                });

                popup.on('click', 'input[type="radio"]', function () {
                    var parent = $(this).parents('li:first');
                    popup.find('.reason-input').remove();

                    var type = parent.data('type');
                    var placeholder = parent.data('placeholder');

                    var reason_input = '';
                    if('text' == type){
                        reason_input += '<div class="reason-input">';
                        reason_input += '<input type="text" placeholder="'+ placeholder +'">';
                        reason_input += '</div>';
                    }else if('textarea' == type){
                        reason_input += '<div class="reason-input">';
                        reason_input += '<textarea row="5" placeholder="'+ placeholder +'">';
                        reason_input += '</textarea>';
                        reason_input += '</div>';
                    }else if('checkbox' == type){
                        reason_input += '<div class="reason-input ">';
                        reason_input += '<input type="checkbox" id="th-snooze" name="th-snooze" class="th-snooze-checkbox">';
                        reason_input += '<label for="th-snooze">Snooze this panel while troubleshooting</label>';
                        reason_input += '<select name="th-snooze-time" class="th-snooze-select" disabled>';
                        reason_input += '<option value="<?php echo HOUR_IN_SECONDS ?>">1 Hour</option>';
                        reason_input += '<option value="<?php echo 12*HOUR_IN_SECONDS ?>">12 Hour</option>';
                        reason_input += '<option value="<?php echo DAY_IN_SECONDS ?>">24 Hour</option>';
                        reason_input += '<option value="<?php echo WEEK_IN_SECONDS ?>">1 Week</option>';
                        reason_input += '<option value="<?php echo MONTH_IN_SECONDS ?>">1 Month</option>';
                        reason_input += '</select>';
                        reason_input += '</div>';
                    }else if('reviewlink' == type){
                        reason_input += '<div class="reason-input wpvs-review-link">';
                        reason_input += '<input type="hidden" value="<?php _e('Upgraded', 'product-variation-swatches-for-woocommerce');?>">';
                        reason_input += '</div>';
                    }

                    if(reason_input !== ''){
                        parent.append($(reason_input));
                    }
                });

                popup.on('click', '.thwvs-close', function () {
                    popup.css("display", "none");
                });

                popup.on('click', '.thwvs-submit-deactivate', function (e) {
                    e.preventDefault();
                    var button = $(this);
                    if (button.hasClass('disabled')) {
                        return;
                    }
                    var radio = $('.deactivation-reason input[type="radio"]:checked');
                    var parent_li = radio.parents('li:first');
                    var parent_ul = radio.parents('ul:first');
                    var input = parent_li.find('textarea, input[type="text"], input[type="hidden"]');
                    var wvs_deacive_nonce = parent_ul.data('nonce');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'thwvs_deactivation_reason',
                            reason: (0 === radio.length) ? 'none' : radio.val(),
                            comments: (0 !== input.length) ? input.val().trim() : '',
                            security: wvs_deacive_nonce,
                        },
                        beforeSend: function () {
                            button.addClass('disabled');
                            button.text('Processing...');
                        },
                        complete: function () {
                            window.location.href = deactivation_link;
                        }
                    });
                });

                popup.on('click', '#th-snooze', function () {
                    if($(this).is(':checked')){
                        popup.find('.th-snooze-select').prop("disabled", false);
                    }else{
                        popup.find('.th-snooze-select').prop("disabled", true);
                    }
                });

            }(jQuery))
        </script>

        <?php 
    }

    private function get_deactivation_reasons(){
        return array(

            'upgraded_to_wpvs_pro' => array(
                'radio_val'          => 'upgraded_to_wpvs_pro',
                'radio_label'        => __('Upgraded to premium.', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'reviewlink',
                'reason_placeholder' => '',
            ),

            'feature_missing'=> array(
                'radio_val'          => 'feature_missing',
                'radio_label'        => __('A specific feature is missing', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'text',
                'reason_placeholder' => __('Type in the feature', 'product-variation-swatches-for-woocommerce'),
            ),

            'error_or_not_working'=> array(
                'radio_val'          => 'error_or_not_working',
                'radio_label'        => __('Found an error in the plugin/ Plugin was not working', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'text',
                'reason_placeholder' => __('Specify the issue', 'product-variation-swatches-for-woocommerce'),
            ),

            'found_better_plugin' => array(
                'radio_val'          => 'found_better_plugin',
                'radio_label'        => __('I found a better Plugin', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'text',
                'reason_placeholder' => __('Could you please mention the plugin?', 'product-variation-swatches-for-woocommerce'),
            ),

            'hard_to_use' => array(
                'radio_val'          => 'hard_to_use',
                'radio_label'        => __('It was hard to use', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'text',
                'reason_placeholder' => __('How can we improve your experience?', 'product-variation-swatches-for-woocommerce'),
            ),


            // 'not_working_as_expected'=> array(
            //     'radio_val'          => 'not_working_as_expected',
            //     'radio_label'        => __('The plugin didn’t work as expected', 'product-variation-swatches-for-woocommerce'),
            //     'reason_type'        => 'text',
            //     'reason_placeholder' => __('Specify the issue', 'product-variation-swatches-for-woocommerce'),
            // ),

            'temporary' => array(
                'radio_val'          => 'temporary',
                'radio_label'        => __('It’s a temporary deactivation - I’m troubleshooting an issue', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'checkbox',
                'reason_placeholder' => __('Could you please mention the plugin?', 'product-variation-swatches-for-woocommerce'),
            ),

            'other' => array(
                'radio_val'          => 'other',
                'radio_label'        => __('Not mentioned here', 'product-variation-swatches-for-woocommerce'),
                'reason_type'        => 'textarea',
                'reason_placeholder' => __('Kindly tell us your reason, so that we can improve', 'product-variation-swatches-for-woocommerce'),
            ),
        );
    }

    public function thwvs_deactivation_reason(){
        global $wpdb;

        check_ajax_referer('thwvs_deactivate_nonce', 'security');

        if(!isset($_POST['reason'])){
            return;
        }

        if($_POST['reason'] === 'temporary'){

            $snooze_period = isset($_POST['th-snooze-time']) && $_POST['th-snooze-time'] ? $_POST['th-snooze-time'] : MINUTE_IN_SECONDS ;
            $time_now = time();
            $snooze_time = $time_now + $snooze_period;

            update_user_meta(get_current_user_id(), 'thwvsf_deactivation_snooze', $snooze_time);

            return;
        }
        
        $data = array(
            'plugin'        => 'wpvs',
            'reason'        => sanitize_text_field($_POST['reason']),
            'comments'      => isset($_POST['comments']) ? sanitize_textarea_field(wp_unslash($_POST['comments'])) : '',
            'date'          => gmdate("M d, Y h:i:s A"),
            'software'      => $_SERVER['SERVER_SOFTWARE'],
            'php_version'   => phpversion(),
            'mysql_version' => $wpdb->db_version(),
            'wp_version'    => get_bloginfo('version'),
            'wc_version'    => (!defined('WC_VERSION')) ? '' : WC_VERSION,
            'locale'        => get_locale(),
            'multisite'     => is_multisite() ? 'Yes' : 'No',
            'plugin_version'=> THWVSF_VERSION
        );

        $response = wp_remote_post('https://feedback.themehigh.in/api/add_feedbacks', array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => false,
            'headers'     => array( 'Content-Type' => 'application/json' ),
            'body'        => json_encode($data),
            'cookies'     => array()
                )
        );

        wp_send_json_success();
    }

    public function dismiss_thwvsf_sib_form(){
        check_ajax_referer( 'thwvsf_sib_security', 'security' );
        $capability = THWVSF_Utils::thwvsf_capability();
        if(!current_user_can($capability)){
            wp_die(-1);
        }

        update_user_meta( get_current_user_id(), 'thwvsf_sib_dismissed', true );
        
    }

    public function subscribed_thwvsf_sib_form(){

        check_ajax_referer( 'thwvsf_sib_security', 'security' );
        $capability = THWVSF_Utils::thwvsf_capability();
        if(!current_user_can($capability)){
            wp_die(-1);
        }

        update_user_meta( get_current_user_id(), 'thwvsf_sib_subscribed', true );
    }

    public function output_sib_form_popup(){

        $current_screen = get_current_screen();
        if($current_screen->id !== 'product_page_th_product_variation_swatches_for_woocommerce'){
            return;
        }

        $thwvsf_subscribed = get_user_meta( get_current_user_id(), 'thwvsf_sib_subscribed', true );
        $thwvsf_dismissed  = get_user_meta( get_current_user_id(), 'thwvsf_sib_dismissed', true );
        if($thwvsf_subscribed || $thwvsf_dismissed){
            return;
        }
        ?>
        <div id="thwvsf_subscription_request_notice" class="notice notice-info thpladmin-notice is-dismissible thwvsf-sib-wrapper" data-nonce="<?php echo wp_create_nonce( 'thwvsf_sib_security'); ?>">

            <div class="thwvsf-sub-img">
                <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/speaker-image.png'); ?>" style="height:100px;width:100px; margin-bottom:4px;" alt="themehigh"/>
            </div>
            <div class="thwvsf-sub-content">
                <h3 style="margin: 0;"><?php esc_html_e('Subscribe and Stay Competitive!', 'woocommerce-product-variation-swatches'); ?></h3>
                <p><?php  esc_html_e('Get Exclusive tips,help articles, and early access to ThemeHigh products and services', 'product-variation-swatches-for-woocommerce'); ?></p>
            </div>
             <div class="sub-pop-action-row">
                <a class="thwvsf-sub-pop-btn" href="" style=" text-decoration: none;"><?php esc_html_e("Yes, I am in!", 'product-variation-swatches-for-woocommerce'); ?></a>
            </div>
            <div class="thwvsf-th-logo">
                <span>
                    <a target="_blank" href="https://www.themehigh.com">
                        <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/themehigh.svg'); ?>" style="height:20px;margin-top:0px;" alt="themehigh"/>
                    </a>
                </span>
            </div>
        </div>

        <?php
        $this->output_sendinblue_form();
    }

    public function output_sendinblue_form(){
    
        // $current_screen = get_current_screen();
        // if($current_screen->id !== 'product_page_th_product_variation_swatches_for_woocommerce'){
        //     return;
        // }

        // $thwvsf_subscribed = get_user_meta( get_current_user_id(), 'thwvsf_sib_subscribed', true );
        // $thwvsf_dismissed = get_user_meta( get_current_user_id(), 'thwvsf_sib_dismissed', true );
        // if($thwvsf_subscribed || $thwvsf_dismissed){
        //     return;
        // }
       
        ?>  
        <div id="error-message" class="sib-form-message-panel ">
            <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/error-img.svg'); ?>" alt="themehigh"/>
                <span class="sib-form-message-panel__inner-text">
                    Your subscription could not be saved. Please try again.
                </span>
            </div>
        </div>
        <div></div>
        <div id="success-message" class="sib-form-message-panel">
            <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/success-img.svg'); ?>"  alt="themehigh"/>
                <span class="sib-form-message-panel__inner-text">
                    Your subscription has been successful.
                </span>
            </div>
        </div>
        <div></div>
        <div id="thwvsf_subsription_form" class="thpladmin-modal-mask">
            <div class="thpladmin-modal">
                      
                <div class="sib-form">
                    
                    <button type="button" class="sib-close"><span class="screen-reader-text">Dismiss this popup.</span></button>

                        <div class="modal-content">  
                        <main class="sib-left-col">

                            <form id="sib-form" method="POST" action="https://b9ac2cbe.sibforms.com/serve/MUIEAFuWZlrzQyCD6gq9TAPnjpRZJF7nzEbPR2-yh5gywGgrje-5h-DgvkDKmelDm7o1SZsNCVKxK6A65MntvYRlM6QSgbNLztICGQe4RpOpPcRPr6ctUzr0ntpVWDCk9pDmPp5golHMSs5djtWeA8LcCf3Vp8vArl23Fbws_TSL1040ny0YZ01jBSPC0XeKt5KyquAoL9hS0Mo7" data-type="subscription" data-nonce="<?php echo wp_create_nonce( 'thwvsf_sib_security'); ?>">

                                <div>
                                    <div class="sib-mail-img">
                                        <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/mail-box.svg'); ?>" style="height:120px;width:155px; margin-bottom:4px;" alt="themehigh"/>
                                    </div>
                                    <div class="sib-form-block sib-form-title">
                                        <p>&nbsp;Rightful Help Right into your Inbox</p>
                                    </div>
                                </div>
                                
                                <div class="sib-form-block" >
                                    <div class="sib-text-form-block sib-form-subtitle">
                                        <p>Subscribe and stay updated.</p>
                                    </div>
                                </div>
                               
                                <div class="sib-input sib-form-block">
                                    <div class="form__entry entry_block">
                                        <div class="form__label-row ">

                                            <div class="entry__field">
                                                <input class="input" maxlength="200" type="text" id="FIRSTNAME" name="FIRSTNAME" autocomplete="off" placeholder="First Name" data-required="true" required />
                                            </div>
                                        </div>

                                        <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="sib-input sib-form-block">
                                    <div class="form__entry entry_block">
                                        <div class="form__label-row ">

                                            <div class="entry__field">
                                                <input class="input" type="text" id="EMAIl" name="EMAIL" autocomplete="off" placeholder="Email" data-required="true" required />
                                            </div>
                                        </div>

                                        <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                                        </label>
                                    </div>
                                </div>
                               
                                <div class="sib-optin sib-form-block">
                                    <div class="form__entry entry_mcq">
                                        <div class="form__label-row ">
                                            <div class="entry__choice">
                                                <label>
                                                    <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN" />
                                                    <span class="checkbox checkbox_tick_positive"></span><span><p>I agree to receive your emails and accept the data privacy statement.</p></span> 
                                                </label>
                                            </div>
                                        </div>
                                        <label class="entry__specification">
                                            You may unsubscribe at any time using the link in our emails.
                                        </label>
                                    </div>
                                </div>
                               

                                <div class="sib-form-block sib-form-btn">
                                    <button class="sib-form-block__button sib-form-block__button-with-loader" form="sib-form" type="submit">
                                        SUBSCRIBE
                                    </button>
                                </div>
                                
                                <input type="text" name="email_address_check" value="" class="input--hidden">
                                <input type="hidden" name="locale" value="en">
                            </form>
                        </main>

                            <aside class="sib-right-col">
                                <div class="sib-form__declaration">
                                    <div class="declaration-block-icon">
                                        <svg class="icon__SVG" width="0" height="0" version="1.1">
                                            <defs>
                                                <symbol id="svgIcon-sphere" viewBox="0 0 63 63">
                                                    <path class="path1" d="M31.54 0l1.05 3.06 3.385-.01-2.735 1.897 1.05 3.042-2.748-1.886-2.738 1.886 1.044-3.05-2.745-1.897h3.393zm13.97 3.019L46.555 6.4l3.384.01-2.743 2.101 1.048 3.387-2.752-2.1-2.752 2.1 1.054-3.382-2.745-2.105h3.385zm9.998 10.056l1.039 3.382h3.38l-2.751 2.1 1.05 3.382-2.744-2.091-2.743 2.091 1.054-3.381-2.754-2.1h3.385zM58.58 27.1l1.04 3.372h3.379l-2.752 2.096 1.05 3.387-2.744-2.091-2.75 2.092 1.054-3.387-2.747-2.097h3.376zm-3.076 14.02l1.044 3.364h3.385l-2.743 2.09 1.05 3.392-2.744-2.097-2.743 2.097 1.052-3.377-2.752-2.117 3.385-.01zm-9.985 9.91l1.045 3.364h3.393l-2.752 2.09 1.05 3.393-2.745-2.097-2.743 2.097 1.05-3.383-2.751-2.1 3.384-.01zM31.45 55.01l1.044 3.043 3.393-.008-2.752 1.9L34.19 63l-2.744-1.895-2.748 1.891 1.054-3.05-2.743-1.9h3.384zm-13.934-3.98l1.036 3.364h3.402l-2.752 2.09 1.053 3.393-2.747-2.097-2.752 2.097 1.053-3.382-2.743-2.1 3.384-.01zm-9.981-9.91l1.045 3.364h3.398l-2.748 2.09 1.05 3.392-2.753-2.1-2.752 2.096 1.053-3.382-2.743-2.102 3.384-.009zM4.466 27.1l1.038 3.372H8.88l-2.752 2.097 1.053 3.387-2.743-2.09-2.748 2.09 1.053-3.387L0 30.472h3.385zm3.069-14.025l1.045 3.382h3.395L9.23 18.56l1.05 3.381-2.752-2.09-2.752 2.09 1.053-3.381-2.744-2.1h3.384zm9.99-10.056L18.57 6.4l3.393.01-2.743 2.1 1.05 3.373-2.754-2.092-2.751 2.092 1.053-3.382-2.744-2.1h3.384zm24.938 19.394l-10-4.22a2.48 2.48 0 00-1.921 0l-10 4.22A2.529 2.529 0 0019 24.75c0 10.47 5.964 17.705 11.537 20.057a2.48 2.48 0 001.921 0C36.921 42.924 44 36.421 44 24.75a2.532 2.532 0 00-1.537-2.336zm-2.46 6.023l-9.583 9.705a.83.83 0 01-1.177 0l-5.416-5.485a.855.855 0 010-1.192l1.177-1.192a.83.83 0 011.177 0l3.65 3.697 7.819-7.916a.83.83 0 011.177 0l1.177 1.191a.843.843 0 010 1.192z" fill="#FFFFFF"></path>
                                                </symbol>
                                            </defs>
                                        </svg>
                                        <svg class="svgIcon-sphere" style="width:63px; height:63px;">
                                            <use xlink:href="#svgIcon-sphere"></use>
                                        </svg>
                                    </div>
                                    <div class="declaration-text">
                                        <p>
                                          We use Sendinblue as our marketing platform. By Clicking below to submit this form, you acknowledge that the information you provided will be transferred to Sendinblue for processing in accordance with their <a target="_blank" class="clickable_link" href="https://www.sendinblue.com/legal/termsofuse/">terms of use</a>
                                        </p>
                                    </div>
                                    <div class="sib-mail-terms">
                                        <img src="<?php echo esc_url(THWVSF_ASSETS_URL_ADMIN.'images/mail-terms.svg'); ?>" alt="themehigh"/>
                                    </div>

                                </div>
                            </aside>
                        </div>
                </div>
                        
            </div>
        </div>
        <?php
    }

    public function sib_form_banner_custom_js(){
        ?>
 
        <script type="text/javascript">

            (function($){

                var popup = $("#thwvsf_subsription_form");
               
                $('.thwvsf-sub-pop-btn').on('click', function(e){
                    e.preventDefault();
                    popup.css("display", "block");
                });

                $( document ).on( 'click','.sib-close', function() {

                    popup.css("display", "none");
                    
                    // var wrapper = $(this).closest('div.sib-form');
                    // wrapper.css('display','none');

                    // var nonce   = wrapper.data("nonce");
   
                    // var data = {
                    //     thwvsf_sib_nonce: nonce,
                    //     action: 'dismiss_thwvsf_sib_form',
                    // };
                    // $.post( ajaxurl, data, function() {

                    // });
                });

                $( document ).on( 'click', '#thwvsf_subscription_request_notice.thpladmin-notice .notice-dismiss', function() {
                    
                    var wrapper = $(this).closest('div.thpladmin-notice');
                    var nonce   = wrapper.data("nonce");
                    var data = {

                        security : nonce,
                        action   : 'dismiss_thwvsf_sib_form',
                        
                    };
                    $.post( ajaxurl, data, function() {

                    });

                });

                $('#sib-form').on('submit', function(event) {

                    event.preventDefault();

                    const container     = $("#sib-form-container"),
                        success_msg     = $("#success-message"),
                        error_msg       = $("#error-message");

                    
                    $('#thwvsf_subscription_request_notice').css('display','none');

                    const _form    = this,
                        _form_data = new FormData(_form),
                        xhr        = new XMLHttpRequest();
                   
                    xhr.open("POST", `${_form.getAttribute("action")}?isAjax=1`),
                    xhr.send(_form_data);

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {

                            var response = JSON.parse(xhr.responseText);
                            
                            if (xhr.status === 200) {

                                success_msg.css('display','block');
                                error_msg.css('display','none');
                                update_database_sib(_form);

                            } else {

                                popup.css("display", "none");
                                $('#thwvsf_subscription_request_notice').css('display','flex');
                                success_msg.css('display','none');
                                error_msg.css('display','block');
                            } 
                        }
                    }
                    popup.css("display", "none");
                });

                function update_database_sib(_form){

                    var nonce   = $(_form).data("nonce");
   
                    var data = {
                        security: nonce,
                        action: 'subscribed_thwvsf_sib_form',
                    };
                    $.post( ajaxurl, data, function() {

                    });
                }

            }(jQuery))
        </script>
        <?php
    }

    public function quick_links(){

       $current_screen = get_current_screen();
       if($current_screen->id !== 'product_page_th_product_variation_swatches_for_woocommerce'){
            return;
        } 
        ?>

        <div class="th_quick_widget-float">
            <div id="myDIV" class="th_quick_widget">
                <div class="th_whead">
                    <div class="th_whead_close_btn" onclick="thwvsfwidgetClose()">
                        <svg
                        width="8"
                        height="8"
                        viewBox="0 0 8 8"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        >
                        <path
                        d="M1 1.25786C1.00401 1.25176 1.00744 1.24529 1.01024 1.23853C1.08211 0.993028 1.35531 0.919956 1.53673 1.10012C1.86493 1.42605 2.19216 1.75313 2.51843 2.08137L3.95107 3.51439C3.96595 3.52947 3.9816 3.54378 4.01406 3.57471C4.02464 3.55274 4.0376 3.53199 4.0527 3.51285C4.84988 2.71382 5.64738 1.91551 6.4452 1.11791C6.53582 1.02724 6.63706 0.978916 6.76632 1.01139C6.81535 1.02341 6.86066 1.04735 6.89822 1.08109C6.93579 1.11484 6.96444 1.15734 6.98165 1.20483C6.99885 1.25231 7.00407 1.30332 6.99684 1.35331C6.98961 1.4033 6.97016 1.45073 6.9402 1.49139C6.92103 1.51599 6.90004 1.53912 6.87741 1.56059C6.08049 2.35691 5.28376 3.15329 4.48723 3.94973C4.46465 3.96936 4.44437 3.99147 4.42675 4.01565C4.4484 4.02488 4.46879 4.03683 4.48742 4.05122C5.28717 4.85024 6.08661 5.64927 6.88572 6.44829C6.95508 6.51749 7.0001 6.59501 6.99836 6.69592C6.9976 6.7567 6.97884 6.81588 6.94444 6.86599C6.91005 6.91609 6.86158 6.95486 6.80515 6.97738C6.78757 6.98434 6.77037 6.99227 6.75279 6.99961H6.63571C6.54702 6.97371 6.48114 6.91688 6.41584 6.85231C5.62845 6.06424 4.8408 5.27604 4.05289 4.48772C4.0382 4.46942 4.02575 4.44943 4.0158 4.42818L3.98759 4.43282C3.97559 4.45322 3.96196 4.47262 3.94682 4.49081C3.16072 5.27708 2.37481 6.06366 1.58909 6.85057C1.5234 6.91649 1.45578 6.97564 1.36381 7H1.24653C1.18653 6.98245 1.1322 6.94939 1.08903 6.90415C1.04585 6.85891 1.01534 6.8031 1.00058 6.74231V6.63677C1.02879 6.54011 1.09409 6.46975 1.16365 6.40035C1.94576 5.61975 2.72722 4.83871 3.50804 4.05721C3.52747 4.04089 3.54836 4.02639 3.57045 4.01391V3.98549C3.54826 3.97322 3.5273 3.95885 3.50785 3.94258C2.72613 3.16121 1.94486 2.38023 1.16403 1.59964C1.09448 1.53024 1.02879 1.46046 1.00058 1.36341L1 1.25786Z"
                        fill="white"
                        stroke="white"
                        stroke-width="0.5"
                        />
                        </svg>
                    </div>
                    <!-- -----------------------------Widget head icon ----------------------------->
                    <div class="th_whead_icon">
                        <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.4 0H0V3.00675H10.4V0Z" fill="#EF4C85" />
                        <path
                        d="M15.7352 4.66816H3.38049V7.68066H15.7352V4.66816Z"
                        fill="#AC3092"
                        />
                        <path
                        d="M15.7355 9.34228H12.0734V12.3548H15.724L15.7355 9.34228Z"
                        fill="#AC3092"
                        />
                        <path
                        d="M10.4003 9.34228H6.74963V12.3548H10.4003V9.34228Z"
                        fill="#AC3092"
                        />
                        <path
                        d="M15.7355 0H12.0734V3.00675H15.724L15.7355 0Z"
                        fill="#AC3092"
                        />
                        </svg>
                    </div>
                    <!--------------------------Whidget heading section ---------------------------->
                    <div class="th_quick_widget_heading">
                        <div class="th_whead_t1"><p>Welcome, we're</p><p><b style="font-size: 28px;">ThemeHigh</b></p></div>
                        </div>
                    </div>
                    <!-- --------------------Widget Body--------------------------------------- -->
                    <div class="th_quick_widget_body">
                        <ul>
                            <li>
                                <div class="list_icon" style="background-color: rgba(199, 0, 255, 0.15);">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.91072 14.9998C6.70136 14.914 6.47074 14.8589 6.28742 14.736C5.91747 14.4906 5.75542 14.1145 5.74075 13.6743C5.73818 13.5961 5.74075 13.518 5.74075 13.4398C5.74075 13.1529 5.77045 12.8748 5.52773 12.6327C5.33157 12.4364 5.30041 12.1458 5.30041 11.8652C5.30041 11.233 5.08922 10.6945 4.64448 10.2289C2.62793 8.12188 3.48405 4.66072 6.25222 3.74646C7.04263 3.48069 7.89711 3.47384 8.69167 3.7269C9.48624 3.97997 10.1796 4.47977 10.671 5.15381C11.6654 6.51127 11.7123 8.25396 10.8005 9.66829C10.6527 9.88635 10.4864 10.0913 10.3037 10.281C9.88934 10.7304 9.69465 11.2602 9.69465 11.8652C9.69465 12.1513 9.62976 12.4155 9.46733 12.6525C9.45927 12.6646 9.4556 12.6822 9.4446 12.6892C9.22792 12.8429 9.25651 13.0696 9.25138 13.2938C9.24551 13.5459 9.25358 13.8074 9.18832 14.047C9.04459 14.5742 8.67501 14.8798 8.13641 14.9789C8.11799 14.9841 8.10017 14.9912 8.08325 15.0002L6.91072 14.9998ZM7.49002 12.363C7.76317 12.363 8.03632 12.363 8.30947 12.363C8.64238 12.363 8.79087 12.1913 8.81947 11.8575C8.84457 11.5134 8.89829 11.172 8.98006 10.8368C9.11536 10.3133 9.46074 9.913 9.81051 9.51236C10.5071 8.71403 10.74 7.77225 10.4705 6.75452C10.1368 5.49501 9.29721 4.72126 8.01139 4.47802C6.38788 4.1713 4.80874 5.24517 4.49159 6.85614C4.3211 7.72565 4.47179 8.53609 4.98693 9.26288C5.15522 9.50062 5.37081 9.70314 5.55046 9.93464C5.99484 10.5029 6.17376 11.16 6.17779 11.8732C6.17779 12.1888 6.35378 12.3608 6.66873 12.3627C6.94335 12.3645 7.21686 12.363 7.49002 12.363ZM6.6218 13.2487C6.6218 13.4174 6.61043 13.5789 6.62473 13.7363C6.6345 13.8397 6.68214 13.9358 6.75849 14.0062C6.83485 14.0766 6.93451 14.1162 7.03831 14.1175C7.34482 14.1255 7.65146 14.1255 7.95822 14.1175C8.06198 14.1153 8.16127 14.0748 8.23696 14.0037C8.31264 13.9327 8.35937 13.8361 8.36813 13.7326C8.3817 13.5745 8.3707 13.4145 8.3707 13.2487H6.6218Z" fill="#C700FF"/>
                                    <path d="M0 7.38305C0.11696 7.1299 0.317515 7.04992 0.589199 7.05653C1.13 7.07047 1.6708 7.05653 2.21161 7.0624C2.53719 7.0657 2.74764 7.36691 2.63288 7.65674C2.56322 7.83138 2.42793 7.93447 2.24094 7.93631C1.63671 7.94181 1.03101 7.94034 0.428609 7.93631C0.210455 7.93631 0.0923946 7.78955 0 7.61602V7.38305Z" fill="#C700FF"/>
                                    <path d="M7.93549 1.341C7.93549 1.62423 7.93732 1.90747 7.93549 2.1907C7.93292 2.46659 7.75217 2.66104 7.50358 2.66508C7.24693 2.66911 7.05921 2.47136 7.05811 2.18813C7.05615 1.61677 7.05615 1.04566 7.05811 0.474795C7.05811 0.1989 7.24143 0.004086 7.49002 5.02997e-05C7.74667 -0.00361851 7.93292 0.193764 7.93549 0.476996C7.93732 0.764998 7.93549 1.053 7.93549 1.341Z" fill="#C700FF"/>
                                    <path d="M13.6692 7.06071C13.9523 7.06071 14.235 7.05851 14.518 7.06071C14.7934 7.06328 14.9877 7.24415 14.991 7.49437C14.9939 7.75118 14.7967 7.93719 14.5143 7.93829C13.9436 7.94049 13.3727 7.94049 12.8017 7.93829C12.5268 7.93829 12.3321 7.75485 12.3291 7.50464C12.3262 7.25443 12.5231 7.06438 12.8058 7.06071C13.0885 7.05704 13.3814 7.06071 13.6692 7.06071Z" fill="#C700FF"/>
                                    <path d="M4.21244 3.71903C4.19521 3.95346 4.11602 4.09214 3.94919 4.16809C3.78237 4.24403 3.61921 4.22349 3.48575 4.10095C3.32333 3.9542 3.17117 3.7946 3.01571 3.63941C2.7499 3.37416 2.48261 3.10964 2.21973 2.84108C2.15821 2.78388 2.1153 2.7095 2.09653 2.6276C2.07777 2.5457 2.08403 2.46004 2.1145 2.38174C2.1739 2.21591 2.29782 2.10034 2.47125 2.10108C2.59224 2.10108 2.7444 2.1407 2.82799 2.22031C3.25697 2.62608 3.67127 3.0469 4.08155 3.47138C4.15451 3.54696 4.18421 3.66326 4.21244 3.71903Z" fill="#C700FF"/>
                                    <path d="M11.2831 4.21644C11.0558 4.20397 10.9164 4.13243 10.8365 3.97504C10.7566 3.81764 10.7632 3.64998 10.8791 3.50873C10.9714 3.39573 11.08 3.29594 11.183 3.19211C11.5008 2.87415 11.8185 2.55619 12.1363 2.23822C12.2672 2.10835 12.4234 2.05478 12.6041 2.11495C12.6816 2.13889 12.7505 2.18474 12.8025 2.24697C12.8546 2.30919 12.8875 2.38515 12.8974 2.46569C12.9094 2.53348 12.9044 2.60319 12.8828 2.66856C12.8613 2.73394 12.8238 2.79294 12.7739 2.84027C12.3614 3.25595 11.9497 3.67199 11.5291 4.0796C11.4536 4.15224 11.3377 4.18489 11.2831 4.21644Z" fill="#C700FF"/>
                                    <path d="M5.36065 6.98437C5.42475 6.66988 5.56528 6.37604 5.76982 6.1288C6.1966 5.60159 6.75463 5.3312 7.43109 5.30515C7.71488 5.29451 7.92093 5.46695 7.93303 5.72193C7.94513 5.97691 7.75264 6.16806 7.47142 6.18237C6.84813 6.21355 6.43382 6.52613 6.23033 7.11645C6.14343 7.36776 5.94508 7.50021 5.72216 7.45508C5.61453 7.43496 5.51812 7.3758 5.45141 7.28893C5.38469 7.20207 5.35237 7.09361 5.36065 6.98437Z" fill="#C700FF"/>
                                    </svg>

                                </div>
                                <a href="https://app.loopedin.io/variation-swatches-for-woocommerce/ideas" target="_blank" class="quick-widget-doc-link">Request a feature</a></li>
                            <li>
                                <div class="list_icon" style="background-color: rgba(255, 183, 67, 0.15);">
                                    <svg width="15" height="12" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.137 4.21931C11.7996 3.86585 12.4434 3.51681 13.0919 3.18102C13.8532 2.7878 14.7977 3.17218 14.9716 3.95863C15.0139 4.14862 14.9998 4.36953 14.9481 4.55952C14.5299 6.20311 14.0975 7.84229 13.6652 9.48146C13.5712 9.83492 13.3973 9.96747 13.0026 9.96747C11.9124 9.96747 10.8221 9.96747 9.73192 9.96747C7.17083 9.96747 4.60974 9.96747 2.04395 9.96747C1.58343 9.96747 1.42365 9.8526 1.31557 9.43286C0.892638 7.80694 0.469705 6.18544 0.0420739 4.55952C-0.0942043 4.047 0.0326754 3.60076 0.488502 3.27822C0.94433 2.95127 1.43775 2.9336 1.93587 3.19428C2.52328 3.50355 3.11068 3.81725 3.69339 4.13095C3.74508 4.15746 3.79677 4.18397 3.85786 4.21931C3.89546 4.17071 3.93305 4.12211 3.96595 4.06909C4.76482 2.92034 5.56369 1.7716 6.36256 0.627265C6.94057 -0.207786 8.04019 -0.207786 8.6229 0.627265C9.42177 1.7716 10.2112 2.91151 11.0101 4.05584C11.0571 4.10444 11.09 4.15304 11.137 4.21931ZM12.6643 8.93802C12.6784 8.91151 12.6925 8.88942 12.6972 8.86291C13.0966 7.33861 13.4913 5.81872 13.8861 4.29C13.9002 4.22815 13.8767 4.12211 13.8297 4.08676C13.7827 4.05142 13.684 4.07351 13.6041 4.07793C13.5712 4.08235 13.543 4.10886 13.5101 4.12211C12.7911 4.5065 12.0721 4.88647 11.3532 5.27086C10.9208 5.50061 10.6765 5.44317 10.4086 5.05878C9.51576 3.77307 8.6182 2.48735 7.72534 1.20164C7.55617 0.958635 7.44339 0.958635 7.27422 1.19722C6.37196 2.49177 5.4744 3.79074 4.57215 5.08529C4.32779 5.43433 4.06463 5.49619 3.67929 5.29295C2.93681 4.89531 2.19433 4.49766 1.44245 4.10886C1.36726 4.06909 1.23098 4.05142 1.17459 4.08676C1.1229 4.12211 1.1041 4.25466 1.1229 4.32977C1.40956 5.44759 1.70091 6.56541 1.99226 7.68323C2.10034 8.09855 2.20843 8.51386 2.31651 8.9336C5.77986 8.93802 9.21971 8.93802 12.6643 8.93802Z" fill="#FFB743"/>
                                        <path d="M7.50435 12.0001C5.81732 12.0001 4.12559 12.0001 2.43856 12.0001C2.10491 12.0001 1.87935 11.8454 1.83235 11.5848C1.77596 11.2976 1.98743 11.0281 2.29758 10.9927C2.35397 10.9839 2.41506 10.9839 2.47145 10.9839C5.82671 10.9839 9.17728 10.9839 12.5325 10.9839C12.8991 10.9839 13.1246 11.1297 13.1763 11.3992C13.2327 11.6952 13.0119 11.9692 12.6923 11.9912C12.6218 11.9957 12.5513 11.9957 12.4809 11.9957C10.822 12.0001 9.16318 12.0001 7.50435 12.0001Z" fill="#FFB743"/>
                                    </svg>
                                </div>
                                <a href="https://www.themehigh.com/product/woocommerce-product-variation-swatches/?utm_source=free&utm_medium=quicklinks&utm_campaign=wpvs_upgrade_link" target="_blank" class="quick-widget-doc-link">Upgrade to Premium</a></li>
                            <li>

                            <div class="list_icon" style="background-color: rgba(5, 15, 250, 0.15);">
                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                    d="M0 3.38284C0.0365282 3.2037 0.0639243 3.02455 0.109585 2.84791C0.474866 1.41869 1.66897 0.371758 3.16005 0.190821C4.2822 0.0543117 5.26626 0.369967 6.10166 1.11593C6.34019 1.33091 6.56776 1.5552 6.82858 1.80206C6.87606 1.74509 6.91698 1.68705 6.96738 1.63724C7.33267 1.27609 7.69283 0.909913 8.13081 0.626504C9.82352 -0.468078 11.8512 -0.070732 13.0939 1.36996C13.7076 2.08117 14.0261 2.90596 13.9983 3.84576C13.9735 4.68452 13.652 5.41507 13.1315 6.06716C13.0336 6.19006 12.927 6.3065 12.7827 6.4749C12.8287 6.50034 12.8923 6.51897 12.931 6.55946C13.3072 6.94104 13.438 7.39428 13.2802 7.90198C13.1249 8.40359 12.7724 8.73142 12.2468 8.82673C12.0393 8.86435 11.9816 8.93135 11.9458 9.12626C11.8362 9.7307 11.312 10.156 10.6812 10.195C10.6633 10.195 10.6447 10.1993 10.6147 10.2029C10.5442 10.812 10.2418 11.2645 9.62846 11.4491C9.01515 11.6336 8.50376 11.4197 8.08149 10.9374C7.92625 11.0922 7.77794 11.2405 7.62891 11.3882C7.50362 11.5121 7.37942 11.6368 7.25157 11.7579C7.08572 11.9155 6.88143 12.0286 6.65823 12.0866C6.43504 12.1445 6.20042 12.1453 5.97682 12.0889C5.75323 12.0324 5.54814 11.9206 5.3812 11.7642C5.21426 11.6078 5.09104 11.4119 5.02335 11.1954C4.99298 11.08 4.9686 10.9632 4.9503 10.8453C4.09554 10.6694 3.72295 10.3079 3.6309 9.56123C3.15238 9.52934 2.77066 9.32332 2.53505 8.90233C2.29945 8.48133 2.32757 8.05425 2.57158 7.62072C2.50948 7.56483 2.44264 7.50535 2.37725 7.4448C1.96595 7.06465 1.55318 6.68629 1.14479 6.30328C0.509202 5.70816 0.131502 4.98405 0.0277618 4.12558C0.0218314 4.10007 0.0132612 4.07522 0.00218937 4.05141L0 3.38284ZM9.90169 6.39249C10.0843 5.91668 10.4032 5.59064 10.9059 5.47992C11.4085 5.36921 11.8508 5.49856 12.2209 5.85828C12.2574 5.8246 12.2881 5.7988 12.3155 5.77014C13.0245 5.02669 13.2853 4.15209 13.0968 3.15461C12.8079 1.62542 11.2087 0.582434 9.65695 0.868709C8.6769 1.04786 8.0435 1.69099 7.40901 2.36207C7.85064 2.78306 8.29043 3.19796 8.72402 3.61788C8.9613 3.85006 9.11138 4.15423 9.14979 4.4808C9.1882 4.80738 9.11267 5.13708 8.93552 5.41615C8.58193 5.97652 7.86342 6.22481 7.2187 6.01306C6.99754 5.94105 6.79715 5.81821 6.63425 5.65477C6.48193 5.50393 6.32777 5.35452 6.17837 5.20834C6.16397 5.21522 6.15016 5.22325 6.1371 5.23234C5.19906 6.15148 4.26247 7.07193 3.32735 7.9937C3.25608 8.07398 3.21248 8.1743 3.20279 8.28033C3.1827 8.47166 3.28607 8.61319 3.46068 8.69882C3.54384 8.7429 3.6395 8.75882 3.73283 8.74412C3.82616 8.72941 3.91193 8.6849 3.97682 8.61749C4.24129 8.36668 4.49954 8.1062 4.76145 7.8511C4.95578 7.66192 5.20818 7.64974 5.3795 7.81885C5.55082 7.98797 5.53548 8.23483 5.34297 8.42544C5.10846 8.65654 4.8714 8.88585 4.63762 9.11766C4.40384 9.34948 4.39617 9.65761 4.61095 9.87007C4.81916 10.0768 5.1512 10.0671 5.37804 9.84535C5.61438 9.61569 5.84816 9.38351 6.0845 9.15385C6.277 8.96682 6.53343 8.95679 6.70256 9.12698C6.87168 9.29716 6.8567 9.53794 6.66931 9.72353C6.43188 9.95857 6.19152 10.1893 5.95482 10.4258C5.73565 10.6443 5.72835 10.9632 5.93546 11.17C6.14258 11.3767 6.46877 11.3738 6.69269 11.1574C6.94839 10.9095 7.20043 10.6558 7.45978 10.41C7.51786 10.3545 7.52444 10.3111 7.49156 10.237C7.3469 9.91676 7.33535 9.55381 7.45938 9.22538C7.58341 8.89695 7.83322 8.62897 8.15564 8.47847C8.18998 8.46235 8.22286 8.44264 8.25683 8.42544C7.93319 7.89732 7.92807 7.32692 8.25683 6.88909C8.67361 6.34018 9.23067 6.19221 9.90169 6.395V6.39249ZM3.1396 7.04673C3.17941 6.99944 3.20827 6.95824 3.24333 6.92384C4.0097 6.17119 4.77679 5.41878 5.54461 4.6666C5.9077 4.31189 6.42421 4.31153 6.7884 4.66194C6.93451 4.80275 7.07733 4.94858 7.22417 5.0876C7.37102 5.22661 7.54964 5.30257 7.75749 5.27498C8.02341 5.24094 8.21628 5.10157 8.30541 4.85076C8.39491 4.59387 8.32514 4.36492 8.12898 4.17753C7.27093 3.35704 6.41946 2.52939 5.54899 1.72216C4.85898 1.08153 4.02613 0.840046 3.0932 1.02206C2.03389 1.22915 1.30332 1.85294 0.968724 2.85651C0.628646 3.87191 0.844163 4.80705 1.5897 5.58741C2.07151 6.09224 2.60665 6.54799 3.1396 7.04673ZM10.6184 9.39319C10.7214 9.39015 10.8214 9.35814 10.9064 9.30098C10.9914 9.24381 11.0579 9.16389 11.098 9.07072C11.1941 8.86614 11.171 8.66048 11.0107 8.49746C10.5924 8.07467 10.1705 7.65691 9.73622 7.24845C9.51705 7.03993 9.19305 7.06931 8.97936 7.29037C8.88103 7.38892 8.82655 7.52168 8.82785 7.65957C8.82915 7.79745 8.88613 7.9292 8.9863 8.02595C9.39176 8.42974 9.80233 8.82852 10.2115 9.22909C10.2646 9.28212 10.3281 9.32401 10.3983 9.35222C10.4684 9.38043 10.5437 9.39437 10.6195 9.39319H10.6184ZM11.9381 8.08829C12.1938 8.05246 12.3662 7.94246 12.4641 7.73537C12.562 7.52828 12.5277 7.32154 12.3637 7.15422C12.1116 6.89649 11.8545 6.64366 11.5922 6.39572C11.4914 6.29957 11.356 6.24643 11.2154 6.24791C11.0748 6.24939 10.9406 6.30536 10.8419 6.4036C10.6228 6.61177 10.6071 6.93423 10.816 7.14849C11.0717 7.40969 11.3299 7.66765 11.6006 7.91237C11.6927 7.99406 11.8256 8.03096 11.9392 8.08829H11.9381ZM8.16624 9.7049C8.21628 9.81561 8.24368 9.94567 8.32112 10.0338C8.49452 10.229 8.68098 10.4126 8.87927 10.5834C9.09844 10.7726 9.41477 10.7443 9.61787 10.5372C9.71522 10.441 9.7707 10.3115 9.77261 10.1759C9.77452 10.0404 9.72271 9.90941 9.6281 9.8106C9.46031 9.63456 9.28656 9.46413 9.10684 9.29931C8.93771 9.14417 8.73316 9.11479 8.52458 9.20723C8.31601 9.29967 8.2035 9.46198 8.16733 9.7049H8.16624Z"
                                    fill="#0060FE"
                                  />
                                </svg>
                            </div><a href="https://www.facebook.com/groups/740534523911091" target="_blank" class="quick-widget-community-link">Join our Community</a></li>
                            <li>
                                <div class="list_icon" style="background-color: rgba(152, 190, 0, 0.15);">
                                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.1991 6.08468C14.088 6.32262 13.8965 6.40304 13.6394 6.39229C13.3061 6.37858 12.975 6.39007 12.6424 6.38859C12.3746 6.38859 12.1838 6.21551 12.1798 5.97645C12.1757 5.7374 12.3698 5.55728 12.6461 5.55691C12.9831 5.55691 13.3205 5.56765 13.6569 5.5532C13.9121 5.54208 14.0935 5.62732 14.1991 5.86267V6.08468Z" fill="#98BE00"/>
                                        <path d="M6.16027 9.20862C6.12768 9.26199 6.10101 9.30424 6.07619 9.3476C5.4369 10.4553 4.79786 11.5631 4.15907 12.671C4.13833 12.7081 4.11796 12.7451 4.09611 12.7785C3.95943 12.9971 3.73609 13.062 3.51052 12.9367C2.90902 12.6032 2.30924 12.2668 1.71119 11.9275C1.46414 11.7867 1.41191 11.5569 1.56081 11.2975C2.00527 10.5251 2.45035 9.75319 2.89605 8.98179L2.97606 8.84133C2.94568 8.81909 2.91902 8.79611 2.88902 8.77758C2.6578 8.64004 2.46256 8.44949 2.31936 8.22164C2.27047 8.1427 2.21602 8.1212 2.12898 8.12157C1.78749 8.12157 1.44488 8.13084 1.10412 8.11008C0.812864 8.08923 0.539677 7.96105 0.33738 7.75034C0.135082 7.53963 0.0180427 7.26135 0.00888928 6.9693C-0.00296309 6.30415 -0.00296309 5.63851 0.00888928 4.97237C0.0203713 4.32748 0.554839 3.82677 1.22376 3.81565C1.52859 3.81046 1.83378 3.81195 2.13861 3.81565C2.17423 3.8191 2.21007 3.81214 2.24182 3.79563C2.27357 3.77912 2.29986 3.75377 2.31751 3.72262C2.65345 3.22784 3.12199 2.97581 3.72164 2.97544C4.40538 2.97544 5.08911 2.97804 5.77285 2.97285C5.85424 2.97098 5.93375 2.948 6.0036 2.90614C7.53255 1.9729 9.05978 1.0372 10.5853 0.0990233C10.7423 0.00266077 10.8983 -0.0410736 11.0668 0.0497295C11.2427 0.144239 11.289 0.30509 11.289 0.49448C11.2875 4.14192 11.2875 7.78925 11.289 11.4364C11.289 11.6247 11.2479 11.7859 11.0734 11.8845C10.899 11.9831 10.7401 11.9316 10.5797 11.8356C9.1525 10.9839 7.72441 10.1342 6.29546 9.28645C6.25583 9.26347 6.21546 9.24086 6.16027 9.20862ZM10.446 10.7856V1.17013C10.4053 1.19237 10.3771 1.20719 10.3505 1.22276C9.07089 2.00675 7.79244 2.79198 6.5151 3.57845C6.48989 3.59787 6.46914 3.62247 6.45425 3.65061C6.43936 3.67874 6.43068 3.70975 6.4288 3.74153C6.42436 5.25331 6.42621 6.76509 6.42324 8.27686C6.42013 8.31507 6.42879 8.35332 6.44806 8.38645C6.46733 8.41959 6.49629 8.44602 6.53103 8.46218C7.65947 9.1293 8.7868 9.79853 9.91303 10.4699C10.0853 10.5729 10.2586 10.6748 10.446 10.7856V10.7856ZM5.58099 8.21571V3.80935H3.76942C3.22051 3.80935 2.86457 4.15737 2.86309 4.70441C2.86087 5.54993 2.86087 6.39545 2.86309 7.24097C2.86309 7.75984 3.24051 8.18866 3.75646 8.21015C4.35982 8.23424 4.9654 8.21571 5.58099 8.21571ZM3.57349 12.0194C4.1424 11.0343 4.70835 10.0533 5.28653 9.05073H5.14467C4.74317 9.05073 4.34167 9.0537 3.94017 9.04851C3.90247 9.04443 3.86444 9.05238 3.83151 9.07122C3.79859 9.09005 3.77245 9.11881 3.75683 9.15339C3.35237 9.86277 2.94198 10.5692 2.53456 11.2767C2.5127 11.3138 2.49381 11.3553 2.47085 11.3998L3.57349 12.0194ZM2.02601 4.65141C1.73415 4.65141 1.45784 4.64177 1.18227 4.65437C1.09032 4.65622 1.00279 4.69426 0.938663 4.76023C0.874534 4.82621 0.838966 4.91481 0.839667 5.00684C0.834481 5.64926 0.834481 6.29167 0.839667 6.93409C0.839667 7.10124 0.960042 7.26209 1.11486 7.27099C1.41636 7.28878 1.71933 7.27618 2.02601 7.27618V4.65141Z" fill="#98BE00"/>
                                        <path d="M13.0036 2.6377C13.2259 2.6477 13.3566 2.72146 13.43 2.87971C13.5033 3.03797 13.4892 3.19549 13.3718 3.31853C13.0824 3.62195 12.7861 3.91845 12.4829 4.20803C12.3229 4.36073 12.0729 4.33849 11.9206 4.18246C11.8463 4.10788 11.8038 4.00742 11.8018 3.90213C11.7999 3.79684 11.8388 3.69489 11.9103 3.61763C12.1947 3.32113 12.4847 3.03167 12.7821 2.74888C12.8492 2.68328 12.9559 2.65919 13.0036 2.6377Z" fill="#98BE00"/>
                                        <path d="M12.2749 7.63037C12.3249 7.65631 12.4331 7.68596 12.5045 7.75379C12.792 8.02657 13.0712 8.30973 13.3475 8.59288C13.3869 8.63063 13.4182 8.67589 13.4398 8.72599C13.4613 8.77608 13.4726 8.82999 13.4729 8.88452C13.4733 8.93906 13.4627 8.9931 13.4418 9.04347C13.4209 9.09383 13.3901 9.13949 13.3512 9.17773C13.1872 9.34414 12.9327 9.34599 12.7564 9.17328C12.4764 8.89902 12.1995 8.62179 11.9256 8.3416C11.8643 8.28409 11.823 8.20852 11.8075 8.12588C11.7921 8.04324 11.8033 7.95782 11.8397 7.88202C11.9093 7.71561 12.0434 7.64149 12.2749 7.63037Z" fill="#98BE00"/>
                                    </svg>

                                </div><a href="https://wordpress.org/support/plugin/product-variation-swatches-for-woocommerce/" target="_blank" class="quick-widget-support-link">Get support</a></li>
                            <li>
                                <div class="list_icon" style="background-color: rgba(255, 0, 0, 0.15);">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path 
                                        d="M13.9983 7.40916C13.9622 7.67875 13.9348 7.95008 13.8878 8.21793C13.7211 9.17181 13.3549 10.0797 12.8131 10.882C12.7742 10.9427 12.7235 10.9949 12.664 11.0356C12.6046 11.0763 12.5376 11.1047 12.467 11.1191C12.3964 11.1334 12.3236 11.1335 12.253 11.1193C12.1824 11.1051 12.1153 11.0768 12.0558 11.0362C11.9954 10.9969 11.9435 10.9459 11.9032 10.8861C11.8629 10.8264 11.8349 10.7592 11.821 10.6884C11.8071 10.6177 11.8075 10.5449 11.8222 10.4743C11.8368 10.4037 11.8655 10.3368 11.9065 10.2775C12.3466 9.60203 12.6721 8.87731 12.7992 8.07987C13.1801 5.68448 12.404 3.72461 10.4885 2.24601C9.3895 1.3976 8.11206 1.04395 6.72667 1.09883C4.02075 1.20597 1.69089 3.23857 1.20167 5.91182C0.661531 8.86164 2.36682 11.7287 5.22464 12.6272C6.88249 13.1498 8.47853 12.9538 9.9897 12.0924C10.1325 12.0109 10.2757 11.9513 10.4428 11.9861C10.5509 12.0075 10.6501 12.0608 10.7277 12.1391C10.8053 12.2174 10.8576 12.3172 10.878 12.4255C10.898 12.5363 10.8832 12.6505 10.8357 12.7525C10.7881 12.8546 10.7102 12.9393 10.6125 12.9952C10.0244 13.3524 9.38561 13.6186 8.71792 13.7848C6.95193 14.2334 5.08069 13.9747 3.50246 13.0637C1.92422 12.1526 0.763611 10.6613 0.267631 8.90693C-0.831799 5.07692 1.56597 1.03089 5.44705 0.17421C5.77479 0.101913 6.11211 0.0731673 6.44463 0.0235174C6.48468 0.0178556 6.52428 0.00827498 6.56389 0H7.4383C7.47355 0.00783946 7.50837 0.0174192 7.54363 0.0226455C7.90271 0.0783928 8.26701 0.109752 8.62042 0.192502C11.3155 0.80877 13.436 3.06697 13.8921 5.79379C13.9356 6.04858 13.9631 6.30641 13.9979 6.56293L13.9983 7.40916Z" fill="#FF0000"/>
                                        <path d="M5.84092 4.22803C6.02808 4.29118 6.23046 4.32689 6.39934 4.42227C7.31771 4.94142 8.23172 5.46971 9.14138 6.00715C9.31609 6.10769 9.46115 6.25263 9.56188 6.4273C9.6626 6.60198 9.71542 6.80018 9.71498 7.00185C9.71453 7.20351 9.66085 7.40148 9.55936 7.57571C9.45787 7.74994 9.31218 7.89425 9.13703 7.99402C8.23259 8.52826 7.32366 9.05496 6.41022 9.5741C5.62156 10.0218 4.68535 9.47306 4.6823 8.5676C4.67882 7.51537 4.67882 6.46329 4.6823 5.41135C4.68236 5.26059 4.71211 5.11132 4.76986 4.97208C4.82761 4.83283 4.91221 4.70634 5.01885 4.59984C5.12548 4.49334 5.25206 4.40891 5.39132 4.35139C5.53059 4.29387 5.67981 4.26438 5.83048 4.26461L5.84092 4.22803ZM5.77259 6.99884C5.77259 7.4997 5.77259 8.00055 5.77259 8.5014C5.77259 8.65166 5.7974 8.66603 5.92754 8.59069C6.78961 8.09216 7.6514 7.59319 8.51289 7.09379C8.65043 7.01408 8.65043 6.9836 8.51289 6.9039C7.65169 6.40478 6.7899 5.90596 5.92754 5.40743C5.79696 5.33208 5.77259 5.34602 5.77259 5.49454C5.77085 5.99655 5.77085 6.49799 5.77259 6.99884V6.99884Z" fill="#FF0000"/>
                                    </svg>

                                </div>
                            <a href="https://www.themehigh.com/docs/variation-swatches-free-documentation/" target="_blank" class="quick-widget-youtube-link" >Video Tutorial</a></li>
                        </ul>
                    </div>
                </div>
            <div id="myWidget" class="widget-popup" onclick="thwvsfwidgetPopUp()">
                <span id="th_quick_border_animation"></span>
                <div class="widget-popup-icon" id="th_arrow_head">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                        d="M10.1279 5.1957L0 15.3236L2.92808 18.2517L13.056 8.12378L10.1279 5.1957Z"
                        fill="white"
                        />
                        <path
                        d="M19.8695 4.54623L7.83801 16.5777L10.7717 19.5113L22.8031 7.47991L19.8695 4.54623Z"
                        fill="white"
                        />
                        <path
                        d="M24.4214 9.0978L20.8551 12.6641L23.7888 15.5978L27.3439 12.0427L24.4214 9.0978Z"
                        fill="white"
                        />
                        <path
                        d="M19.226 14.2932L15.6709 17.8483L18.6046 20.782L22.1597 17.2268L19.226 14.2932Z"
                        fill="white"
                        />
                        <path
                        d="M15.3236 -7.92623e-06L11.7573 3.56631L14.6854 6.49439L18.2405 2.93927L15.3236 -7.92623e-06Z"
                        fill="white"
                        />
                    </svg>
                </div>
            </div>
            </div>
        <?php
    }

    public function sib_form_banner_custom_css(){
        ?>
        <style>

            div#thwvsf_subscription_request_notice {

                border: none;
                background: linear-gradient(288.17deg, #45108A 2.28%, #3D065F 29.57%, #10054D 101.35%);
                border-radius: 10px;
                /*padding: 10px 25px 15px 0px;*/
                overflow: hidden;
                display: flex;
                align-items: center;
                margin-bottom: 20px;
                /*font-family: 'Roboto';*/
            }

            #thwvsf_subscription_request_notice .notice-dismiss::before{
                color: #FFFFFF;
            }

            .thwvsf-sub-img{
                float: left;
                margin: 0 20px;
            }

            .thwvsf-sub-content{
                max-width: 60%;
                max-width: 50%;
            }
           
            .thwvsf-sub-content h3{

                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 700;
                font-size: 17px;
                line-height: 20px;
                color: #FFFFFF;
            }
            .thwvsf-sub-content p{

                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 300;
                font-size: 14px;
                line-height: 22px;
                color: #FFFFFF;
            }
            .sub-pop-action-row{

                margin-left: 20px;
                position: absolute;
                right: 20%;
            }
            .thwvsf-sub-pop-btn{

                text-decoration: none;
                background-color: #2271b1;
                background: #FFFFFF;
                border-radius: 5px;
                padding: 15px 30px 15px 30px;
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 500;
                font-size: 14px;
                line-height: 16px;
                color: #3E0763;
            }

            #thwvsf_subscription_request_notice a.thwvsf-sub-pop-btn:hover{
                color: #3E0763;
            }

            .thwvsf-th-logo{
                position: absolute;
                float: right;
                width: 120px;
                height: 120px;
                background: #ffffff;
                border-radius: 50%;
                right: -3%;
                top: 50%;

            }
            .thwvsf-th-logo span{
                position: absolute;
                /* top: 31px; */
                right: 50%;
                /* border: none; */
                /* margin: 0; */
                /* padding: 10px; */
                background: none;
                color: #787c82;
                cursor: pointer;
                bottom: 60%;
            }

            .thpladmin-modal-mask{
                position: fixed;
                background-color: rgba(17,30,60,0.6);
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                overflow: scroll;
                transition: opacity 250ms ease-in-out;
            }


            .thpladmin-modal-mask{
                display: none;
            }
            .thpladmin-modal .sib-form{

                /*position: absolute;
                background: #fff;
                border-radius: 2px;
                overflow: hidden;
                left: 50%;
                top: 50%;
                transform: translate(-50%,-50%);
                width: 50%;
                max-width: 800px;
                min-height: 560px;
                height: 70vh;
                max-height: 640px;
                animation: appear-down 250ms ease-in-out;
                border-radius: 15px;*/

                position: absolute;
               /* background: #fff;*/
                overflow: hidden;
                left: 50%;
                top: 50%;
                transform: translate(-50%,-50%);
                width: 80%;
                max-width: 960px;
                min-height: 560px;
                /*height: 80vh;*/
                max-height: 640px;
                animation: appear-down 250ms ease-in-out;
                border-radius: 15px;

            }

            .sib-form .modal-content{

                max-width: 960px;
                /*height: 80vh;*/
                max-height: 640px;
                min-height: 560px;
                position: relative;
            }
            
            .sib-left-col{

                /*margin: 24px 0;
                background: #fff;
                position: absolute;
                height: 100%;
                overflow: auto;*/
                padding: 30px;
                background: #fff;
                position: absolute;
                border-right: 1px solid #eee;
                overflow: auto;
                top: 0;
                bottom: 0;
                right: 320px;
                border-radius: 15px 0 0 15px;
            }

            .sib-right-col{
                /*left: 490px;*/
                width: 260px;
                right: 0;
                padding: 30px;
                position: absolute;
                top: 0;
                bottom: 0;
                background: #6354A1;
                text-align: left;
                border-radius: 0 15px 15px 0;
            }

            .declaration-text{
                background-color: transparent;
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 400;
                font-size: 13px;
                line-height: 16px;
                color: #FFFFFF;
            }

            .sib-mail-img{
                text-align: center;
            }
            .sib-form-title, .sib-form-title p{
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 21px;
                color: #000000;
                text-align: center;
            }

            .sib-form-subtitle p{

                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 400;
                font-size: 14px;
                line-height: 2px;
                color: #5E5E5E;
                text-align: center;
            }
            .sib-input.sib-form-block{
                padding: 8px 8px 8px 8px;
                word-wrap: break-word;
                outline: none;
            }

            .form__entry.entry_block{
                border: 0;
                margin: 0;
                padding: 0;
                position: relative;
            }
            .form__label-row {
                display: flex;
                flex-direction: column;
                justify-content: stretch;
            }
            .entry__field{
                -webkit-align-items: center;
                align-items: center;
                background: #fff;
                border: 1px solid #c0ccda;
                border-radius: 3px;
                display: -webkit-inline-flex;
                display: inline-flex;
                margin: 0.25rem 0;
                max-width: 100%;
                background: #FBFBFB;
                border: 1px solid #d0d0d0;
                border-radius: 5px;
            }
         
            #sib-form .input{

                height: calc(2.5rem - 2px);
                box-sizing: content-box;
                color: inherit;
                outline: 0;
                width: calc(100% - 1rem);
                box-shadow: none;
                min-width: 1px;
                padding: 5px;
                box-sizing: content-box;
                color: inherit;
                outline: 0;
                background: none;
                border: 0;
                font: inherit;
                margin: 0;
                width: calc(100% - 1rem);
                min-height: 30px;
                border-radius: 4px;
                padding-left: 15px;
                font-weight: 400;
                font-size: 14px;
                line-height: 16px;

            }

            #sib-container input::placeholder {
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 400;
                font-size: 14px;
                line-height: 16px;
                color: #BDBDBD;
            }
            .sib-optin.sib-form-block{
                padding: 8px 16px 8px 8px;
            }

            .entry__choice p{
                display: inline-block;
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 400;
                font-size: 13px;
                line-height: 15px;
                color: #000000;
            }
            .entry__specification{
                /*font-family: 'Roboto';*/
                font-style: normal;
                font-weight: 400;
                font-size: 12px;
                line-height: 14px;
                color: #ADADAD;
            }
            .sib-form-block__button.sib-form-block__button-with-loader{

                font-style: normal;
                font-weight: 500;
                font-size: 14px;
                line-height: 16px;
                color: #FFFFFF;

                background-color: #6354A1;;
                border-radius: 5px;
                border-width: 0px;
                width: calc(100%);
                /* height: 40px; */
                min-height: 30px;
                padding: 15px;
                text-align: center
            }

            .input--hidden {
                display: none !important;
            }
            /*.sib-close{
                position: absolute;
                color: #ffffff;
                font-size: 15px;
                right: 8px;
                top: 8px;
                cursor: pointer;
                z-index: 10;
                border: 1px solid #ffffff;
                border-radius: 100%;
                padding: 0px 5px 4px;
            }*/

            .sib-close{

                position: absolute;
                top: 0;
                right: 1px;
                border: none;
                margin: 0;
                padding: 9px;
                background: none;
                color: #787c82;
                cursor: pointer;
                z-index: 20;
            }

            .sib-close::before{
                background: none;
                color: #FFFFFF;
                content: "\f335";
                display: block;
                font: normal 20px/20px dashicons;
                height: 20px;
                text-align: center;
                width: 20px;
                -webkit-font-smoothing: antialiased;
            }

            .checkbox_tick_positive span{
                display: inline-block;
                font-style: normal;
                font-weight: 400;
                font-size: 13px;
                line-height: 15px;
                color: #000000;
            }

            .sib-form__declaration {
                position: absolute;
                top: 30%;
                padding-right: 30px;
            }
            .sib-form__declaration .declaration-text a{
                font-style: italic;
                color: #ffffff;
                font-weight: bold;
            }

            .sib-mail-terms{
                position: absolute;
                right: 50%;
            }

            .sib-form-message-panel{ 
                
                border-radius: 10px;
                padding: 5px 25px 5px 0px;
                border: 1px solid red;
                margin: 5px 15px 2px;
                border-radius: 10px;
                border: 1px solid ;
                border-left-width: 10px;
                
            }

            .sib-form-btn{
                /*margin-top: 20px;*/
                padding: 8px 16px 8px 8px;
            }

            #error-message{
               
                background: #FFF1F0;
                border-color: #E14046;
                display: none;

            }
            #success-message{
               
                background: #F3FAEF;
                border-color: #79B05E;
                display: none;
            }
            .sib-form-message-panel__inner-text{
                
                font-style: normal;
                font-weight: 400;
                font-size: 14px;
                line-height: 16px;
            }

            #error-message .sib-form-message-panel__inner-text{
                color: #E14046;
            }
            #success-message .sib-form-message-panel__inner-text{
                color: #246D00;
            }
            .sib-form-message-panel__text{
                display: flex;
                align-items: center;
                margin: 0;
                padding: 0.5rem;
                margin-left: 15px;
            }

            .sib-form-message-panel__text img{
               
                height: 1.5em;
                width: 1.5em;
                flex-shrink: 0;
                margin-right: calc(1rem - 1px);
            }

            @media only screen and (min-width: 1760px) {
                .thwvsf-th-logo {
                    width: 150px;
                    right: -2%;
                }
            }

        </style> 
        <?php
    }
 
}
endif;