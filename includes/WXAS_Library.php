<?php
class WXAS_Library {
    public static function get_shortcode_data() {
        $args = array( 'post_type' => "wxas_slider" );
        $wxas_posts = get_posts( $args );
        $post_data = array();
        foreach ($wxas_posts as $post_el){
            $post_el = array(
              'id'=>  $post_el->ID,
              'name'=>  $post_el->post_title
            );
            array_push($post_data, $post_el);
        }
        $data = array();
        $data['shortcode_prefix'] = "wxas";
        $data['inputs'][] = array(
            'type' => 'select',
            'id' => 'wxas' . '_id',
            'name' => 'wxas' . '_id',
            'shortcode_attibute_name' => 'id',
            'options'  => $post_data,
        );
        return json_encode($data);
    }

    public static function validate_hex_color($data){
        $return_data = sanitize_hex_color($data);
        return $return_data;
    }
    public static function validate_number($data){
        return intval($data);
    }
    public static function validate_string($data){
        $return_data = sanitize_text_field($data);
        return $return_data;
    }
    public static function wxas_generate_theme($post_id, $wxas_theme_meta){
        $upload_dir = wp_upload_dir();
        $upload_base = $upload_dir["basedir"];

        $wxas_themes_path = $upload_base."/wxas_thems/";
        if (!file_exists($wxas_themes_path) && !is_dir($wxas_themes_path)) {
            mkdir($wxas_themes_path);
        }
        $file_name = "wxas_theme_".$post_id;
        $file_content = "";
        if(isset($wxas_theme_meta["wxas_next_prev_color"]) && !empty($wxas_theme_meta["wxas_next_prev_color"])){
            $wxas_next_prev_color = $wxas_theme_meta["wxas_next_prev_color"];
            $file_content .= "
            .wxas_slider.".$file_name." .slick-prev:before, .wxas_slider.".$file_name." .slick-next:before{
                color:".$wxas_next_prev_color." !important;
            }";
        }
        if(isset($wxas_theme_meta["wxas_dots_color"]) && !empty($wxas_theme_meta["wxas_dots_color"])){
            $wxas_dots_color = $wxas_theme_meta["wxas_dots_color"];
            $file_content .= "
            .wxas_slider.".$file_name." .slick-dots li.slick-active button:before{
                color:".$wxas_dots_color." !important;
            }";
        }
        if(isset($wxas_theme_meta["wxas_dots_hover_color"]) && !empty($wxas_theme_meta["wxas_dots_hover_color"])){
            $wxas_dots_hover_color = $wxas_theme_meta["wxas_dots_hover_color"];
            $file_content .= "
            .wxas_slider.".$file_name." .slick-dots li button:before{
                color:".$wxas_dots_hover_color." !important;
            }";
        }
        if(isset($wxas_theme_meta["wxas_buttons_position"]) && !empty($wxas_theme_meta["wxas_buttons_position"])){
            $wxas_buttons_position = $wxas_theme_meta["wxas_buttons_position"];
            if($wxas_buttons_position === 1){
                $file_content .= "
                    .wxas_slider.".$file_name." .slick-prev{
                        left: 12px !important;
                        z-index: 99999999 !important;
                    } 
                    .wxas_slider.".$file_name." .slick-next{
                        right: 12px !important;
                        z-index: 99999999 !important;
                    }";
            }
        }

        $fp = fopen($wxas_themes_path . $file_name . '.css', 'wb');

        fwrite($fp, $file_content);
        fclose($fp);
    }
    public static function wp_get_attachment_medium_url( $id )
    {
        $medium_array = image_downsize( $id, 'medium' );
        $medium_path = $medium_array[0];

        return $medium_path;
    }
    public static function type_video($type){
        if(isset($type)){
            $arr = explode("/",$type);
            if($arr[0] === "video"){
                return true;
            }
        }
        return false;
    }

}