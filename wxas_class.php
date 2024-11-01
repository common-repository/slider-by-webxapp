<?php
class WXAS{
    protected static $instance = null;
    function __construct () {
        require_once ("includes/wxas_shortcode.php");
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 5);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }



    public function enqueue_scripts(){
        wp_enqueue_script('wxas_front_script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), WXAS_VERSION, true);
        wp_enqueue_script('wxas_slick_js', plugins_url('assets/js/slick.min.js', __FILE__), array('jquery','wxas_front_script'), WXAS_VERSION, true);

    }
    public function enqueue_styles(){
        wp_enqueue_style('wxas_front_style', plugins_url('assets/css/style.css', __FILE__), '', WXAS_VERSION, 'all');
        wp_enqueue_style('wxas_slick_css', plugins_url('assets/css/slick/slick.css', __FILE__), '', WXAS_VERSION, 'all');
        wp_enqueue_style('wxas_slick_theme_css', plugins_url('assets/css/slick/slick-theme.css', __FILE__), '', WXAS_VERSION, 'all');

    }

    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}


