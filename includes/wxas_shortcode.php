<?php
function wxas_shortcode($attr) {
    if(isset($attr["id"])){
        require_once ("wxas_print_slider.php");
        $wxas_post_id = intval($attr["id"]);
        $wxas_theme_id = get_post_meta($wxas_post_id, "wxas_theme_id", true);
        $upload_dir = wp_upload_dir();
        $upload_base = $upload_dir["basedir"];
        $upload_url = $upload_dir["baseurl"];
        $wxas_themes_path = $upload_base."/wxas_thems/wxas_theme_".$wxas_theme_id.".css";
        $wxas_themes_url = $upload_url."/wxas_thems/wxas_theme_".$wxas_theme_id.".css";
        if(file_exists($wxas_themes_path)){
            wp_enqueue_style('wxas-theme_' . $wxas_theme_id, $wxas_themes_url, '', uniqid());
        }

        $WXAS_print_slider = new WXAS_print_slider();
        return $WXAS_print_slider->print_slider($attr["id"]);
    }
}
add_shortcode(WXAS_PLUGIN_PREFIX, WXAS_PLUGIN_PREFIX.'_shortcode');