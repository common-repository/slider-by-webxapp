<?php
Class WXAS_Admin{
    protected static $instance = null;
    private $prefix = "wxas";
    private function __construct() {
        require_once(WXAS_DIR. '/includes/WXAS_Library.php');
        add_action('init', array($this, 'setup_cpt'));
        add_filter( 'media_buttons_context', array( $this, 'add_shortcode_button' ) );
        add_action('add_meta_boxes', array($this, 'element_upload_metabox'));
        add_action('add_meta_boxes', array($this, 'slide_settings_metabox'));
        add_action('add_meta_boxes', array($this, 'slide_theme_metabox'));
        add_action("admin_enqueue_scripts", array($this, "load_admin_styles"));
        add_action("admin_enqueue_scripts", array($this, "load_admin_scripts"));
        add_action('wp_ajax_wxas_shortcode_popup', array($this ,'ajax_shortcode_popup'));
        add_action('post_updated', array($this, 'save_meta'), 10, 3);
        add_action( 'enqueue_block_editor_assets', array($this,'enqueue_block_editor_assets' ));
    }
    function ajax_shortcode_popup(){
        if(wp_verify_nonce($_GET['nonce'], 'wxas_shortcode_popup')) {
            wp_print_scripts('jquery');
            require_once ("admin/view/add_shortcode_view.php");
        }
        die;
    }
    public function add_shortcode_button($context){
        $context.= '<a onclick="" href="" class="button wxas_open_shortcode_popup" title="">
                        <span class="wp-media-buttons-icon" style="vertical-align: text-bottom; width: 16px; height: 16px;">
                        <img style="width: 100%; height: 100%; vertical-align: unset; padding: 0;" src="'.plugins_url('/assets/images/wxas_logo_small.svg', WXAS_MAIN_FILE).'">
                        </span>
                        Add Slider
                    </a>';
        return $context;
    }

    public function save_meta($post_id, $post, $post_before) {
      if($post->post_type === "wxas_slider"){
            if($post->post_content === ""){
                $updated_post_data = array(
                    'ID'           => $post_id,
                    'post_content' => '[wxas id="'.$post_id.'"]'
                );
                wp_update_post( $updated_post_data );
            }
          if(isset($_POST["wxas_items_id"]) && is_array($_POST["wxas_items_id"])){
              $wxas_items_id = array_map(array('WXAS_Library','validate_number'), $_POST["wxas_items_id"] );
              foreach ($wxas_items_id as $item_id){
                  if(isset($_POST["wxas_slide_element_content_".$item_id])){
                      $item_content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST["wxas_slide_element_content_".$item_id]);
                      $updated_post_data = array(
                          'ID'           => $item_id,
                          'post_content' => $item_content,
                      );
                      wp_update_post( $updated_post_data );
                  }
              }
              update_post_meta($post_id, "wxas_items_list", $wxas_items_id);
          }else{
              update_post_meta($post_id, "wxas_items_list", "");
          }
          if(isset($_POST["wxas_slide_settings"]) && is_array($_POST["wxas_slide_settings"])){
              $wxas_slide_settings = array_map(array('WXAS_Library','validate_string'), $_POST["wxas_slide_settings"] );
              update_post_meta($post_id, "wxas_slide_settings", $wxas_slide_settings);
          }
          if(isset($_POST["wxas_theme_id"])){
              $wxas_theme_id = WXAS_Library::validate_number($_POST["wxas_theme_id"]);
              update_post_meta($post_id, "wxas_theme_id", $wxas_theme_id);
          }
      }elseif ($post->post_type === "wxas_themes"){
            if(isset($_POST["wxas_theme_color"]) && is_array($_POST["wxas_theme_color"]) && isset($_POST["wxas_buttons_position"]) ){
                $wxas_buttons_position = intval($_POST["wxas_buttons_position"]);
                $wxas_theme_meta = array_map(array('WXAS_Library','validate_hex_color'), $_POST["wxas_theme_color"] );
                $wxas_theme_meta['wxas_buttons_position'] = $wxas_buttons_position;
                update_post_meta($post_id, "wxas_theme_meta", $wxas_theme_meta);
                WXAS_Library::wxas_generate_theme($post_id, $wxas_theme_meta);
            }

      }
    }
    public function load_admin_styles(){

        wp_enqueue_style( 'load_admin_styles', WXAS_URL . '/assets/admin/css/wxas_admin.css',"", WXAS_VERSION );
        wp_enqueue_style( 'pretty-checkbox_css', WXAS_URL . '/assets/admin/css/pretty-checkbox.css', "", WXAS_VERSION);

        /*for Themes*/
        wp_enqueue_style( 'spectrum_css', WXAS_URL . '/assets/admin/css/spectrum.css',"", WXAS_VERSION );


    }
    public function load_admin_scripts(){
        wp_enqueue_script( 'jquery' );
        wp_enqueue_media();

        wp_register_script( 'image_upload_js', WXAS_URL . '/assets/admin/js/image_upload.js', array( 'jquery') , WXAS_VERSION, true );
        wp_register_script( 'add_shortcode_js', WXAS_URL . '/assets/admin/js/add_shortcode.js', array( 'jquery') , WXAS_VERSION, true );
        wp_register_script( 'wxa_admin_js', WXAS_URL . '/assets/admin/js/wxa_admin.js', array( 'jquery') , WXAS_VERSION, true );


        wp_register_script( 'spectrum_js', WXAS_URL . '/assets/admin/js/spectrum.js', array( 'jquery','wxa_admin_js') , WXAS_VERSION, true );
        wp_enqueue_script( 'image_upload_js' );
        wp_enqueue_script( 'add_shortcode_js' );
        wp_enqueue_script( 'wxa_admin_js' );
        wp_enqueue_script( 'spectrum_js' );

        $nonce = wp_create_nonce("wxas_shortcode_popup");
        wp_localize_script( 'image_upload_js', 'wxas_ajax',
            array(
                'url' => admin_url('admin-ajax.php'),
                'iframe_url' => add_query_arg(array('action' => 'wxas_shortcode_popup', 'nonce'=>$nonce), admin_url('admin-ajax.php')),
            )
        );
    }

    public function setup_cpt() {
        $labels = array(
            'name' => 'Slider',
            'singular_name' => 'Slider',
            'name_admin_bar' => 'Slider',
            'add_new' => 'Add New Slider',
            'add_new_item' => 'Add New Slider',
            'new_item' => 'New Slider',
            'edit_item' => 'Edit Slider',
            'view_item' => 'View Slider',
            'all_items' => 'Sliders',
            'search_items' => 'Search Slider',
            'not_found' => 'No sliders found.',
            'not_found_in_trash' => 'No sliders found in Trash.'
        );
        $slider_supports = array();
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => '27,11',
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon'=>plugins_url('/assets/images/wxas_logo_small.svg', WXAS_MAIN_FILE),
            'supports' => array_merge(array(
                'title',
            ), $slider_supports),
        );

        register_post_type("wxas_slider", $args);


        $theme_labels = array(
            'name' => 'Themes',
            'singular_name' => 'Themes',
            'name_admin_bar' => 'Themes',
            'add_new' => 'Add New theme',
            'add_new_item' => 'Add New theme',
            'new_item' => 'New theme',
            'edit_item' => 'Edit theme',
            'view_item' => 'View theme',
            'all_items' => 'Themes',
            'search_items' => 'Search theme',
            'not_found' => 'No theme found.',
            'not_found_in_trash' => 'No themes found in Trash.'
        );
        $themes_args = array(
            'labels' => $theme_labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=wxas_slider',
            'query_var' => true,
            'capability_type' => 'post',
            'taxonomies' => array(),
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => '',
            'supports' => array(
                'title',
            ),
            'rewrite' => false
        );

        register_post_type('wxas_themes', $themes_args);
    }

    public function element_upload_metabox() {
        add_meta_box(
            'wxas_meta_box-1',       // $id
            'Media list',                  // $title
            array($this,'add_element_view'),  // $callback
            'wxas_slider',                 // $page
            'normal',                  // $context
            'high'                     // $priority
        );
    }
    public function slide_settings_metabox() {
        add_meta_box(
            'wxas_meta_box-2',       // $id
            'Slide settings',                  // $title
            array($this,'slide_settings_view'),  // $callback
            'wxas_slider',                 // $page
            'normal',                  // $context
            'high'                     // $priority
        );
    }

    public function slide_theme_metabox() {
        add_meta_box(
            'wxas_meta_box-3',       // $id
            'Theme settings',                  // $title
            array($this,'slide_theme_metabox_view'),  // $callback
            'wxas_themes',                 // $page
            'normal',                  // $context
            'high'                     // $priority
        );
    }
    public function slide_theme_metabox_view(){
        require_once ("admin/view/theme_metabox_view.php");
    }
    public function add_element_view(){
        require_once ("admin/view/wxas_upload.php");
    }
    public function slide_settings_view(){
        require_once ("admin/view/slide_settings.php");
    }
    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }



    public function set_thumbnail_image($img_url, $parent_post_id = 0) {
        $wp_upload_dir = wp_upload_dir();
        $filetype = wp_check_filetype(basename($img_url), null);
        $filename = $wp_upload_dir['path'] . '/' . wp_unique_filename($wp_upload_dir['path'], basename($img_url));
        copy($img_url, $filename);
        $attachment = array(
            'guid'           => $img_url,
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

        /*require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;*/
    }
    public function enqueue_block_editor_assets() {
        $key = 'wxa/wxas';
        $plugin_name = "Slider WX";
        $icon_url = plugins_url('/assets/images/wxas_logo_small.svg', WXAS_MAIN_FILE);
        $icon_svg = plugins_url('/assets/images/wxas_logo_small.svg', WXAS_MAIN_FILE);
        $data = WXAS_Library::get_shortcode_data();


        ?>
        <script>
            if ( !window['wxas_gb'] ) {
                window['wxas_gb'] = {};
            }
            if ( !window['wxas_gb']['<?php echo $key; ?>'] ) {
                window['wxas_gb']['<?php echo $key; ?>'] = {
                    title: '<?php echo $plugin_name; ?>',
                    titleSelect: '<?php echo $plugin_name; ?>',
                    iconUrl: '<?php echo $icon_url; ?>',
                    iconSvg: {
                        width: '30',
                        height: '30',
                        src: '<?php echo $icon_svg; ?>'
                    },
                    data: '<?php echo $data; ?>',
                };
            }
        </script>
        <?php
        wp_enqueue_style('wxa_gb_wxas_css', WXAS_URL . '/assets/admin/css/wxas_block.css', array( 'wp-edit-blocks' ), WXAS_VERSION );
        wp_enqueue_script( 'wxa_gb_wxas_js', WXAS_URL . '/assets/admin/js/wxas_block.js', array( 'wp-blocks', 'wp-element' ), WXAS_VERSION );
        wp_localize_script('wxa_gb_wxas_js', 'wxa_obj', array(
            'nothing_selected' => 'Nothing selected.',
            'empty_item' => '- Select -',
        ));
    }

}


