<?php
Class wxas_register_posts{
    protected static $instance = null;
    private function __construct() {
        add_action('init', array($this, 'setup_cpt'));
    }
    public function setup_cpt() {
        flush_rewrite_rules( false );

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
    }
    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}

?>