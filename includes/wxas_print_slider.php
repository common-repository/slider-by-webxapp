<?php
class WXAS_print_slider{
    private $slider;
    private  $slide_js_data;
    private $slide_buttons = 'wxas_hide_buttons';
    private $touch_effect_class = "";
    function __construct () {

    }
    public function print_slider($slider_id){
        require_once ("WXAS_Library.php");
        $this->slide_js_data = array();
        $this->slide_js_data["id"] = $slider_id;
        $this->slide_js_data["slide_settings"] = "";

        $slider_settings = get_post_meta($slider_id , "wxas_slide_settings", true);



        if(isset($slider_settings["touch_effect"]) && $slider_settings["touch_effect"] === "on"){
            $this->touch_effect_class = "wxas_touch";
            unset($slider_settings["touch_effect"]);
        }
        if(isset($slider_settings["slide_buttons"]) && $slider_settings["slide_buttons"]=== "on"){
            $this->slide_buttons = '';
            unset($slider_settings["slide_buttons"]);
        }



        $slider_theme_id = get_post_meta($slider_id , "wxas_theme_id", true);
        $slide_rel = '';
        $wxas_filmstrip = false;
        $theme_css_class = "wxas_theme_".$slider_theme_id;
        if(isset($slider_settings) && is_array($slider_settings)){
            if(isset($slider_settings["slidesToShow"])){
                $slider_settings["slidesToShow"] = intval($slider_settings["slidesToShow"]);
            }
            if(isset($slider_settings["slidesToScroll"])){
                $slider_settings["slidesToScroll"] = intval($slider_settings["slidesToScroll"]);
            }

            if(isset($slider_settings["infinite"]) && $slider_settings["infinite"] === "on"){
                $slider_settings["infinite"] = true;
            }else{
                $slider_settings["infinite"] = false;
            }
            if(isset($slider_settings["dots"]) && $slider_settings["dots"] === "on"){
                $slider_settings["dots"] = true;
            }
            if(isset($slider_settings["centerMode"]) && $slider_settings["centerMode"] === "on"){
                $slider_settings["centerMode"] = true;
            }
            if(isset($slider_settings["fade"]) && $slider_settings["fade"] === "on"){
                $slider_settings["fade"] = true;
            }
            if(isset($slider_settings["autoplay"]) && $slider_settings["autoplay"] === "on"){
                $slider_settings["autoplay"] = true;
                if(isset($slider_settings["autoplaySpeed"])){
                    $slider_settings["autoplaySpeed"] = intval($slider_settings["autoplaySpeed"]);
                }
            }else{
                unset($slider_settings["autoplaySpeed"]);
            }
            if(isset($slider_settings["adaptiveHeight"]) && $slider_settings["adaptiveHeight"] === "on"){
                $slider_settings["adaptiveHeight"] = true;
            }
            if(isset($slider_settings["rtl"]) && $slider_settings["rtl"] === "on"){
                $slider_settings["rtl"] = true;
                $slide_rel = "dir='rtl'";
            }


            if(isset($slider_settings["filmstrip"]) && $slider_settings["filmstrip"] === "on"){
                unset($slider_settings["filmstrip"]);
                $wxas_filmstrip = true;
            }

        }
        if($wxas_filmstrip){
            $slider_settings_for = $slider_settings;
            $slider_settings_for["slidesToShow"] = 1;
            $slider_settings_for["slidesToScroll"] = 1;
            $slider_settings_for["centerMode"] = false;
            $slider_settings_for["arrows"] = false;
            $slider_settings_for["dots"] = false;


            /*slide nav*/
            $slider_settings["asNavFor"] = ".wxas_slider_for_".$slider_id;
            $slider_settings["focusOnSelect"] = true;
            $data_class = 'wxas_slider_nav_'.$slider_id;
            $slide_for = $this->wxas_get_slide_dada($slider_id ,$slide_rel,$theme_css_class, $slider_settings,$data_class, false);

            /*slide for*/
            $slider_settings_for["asNavFor"] = ".wxas_slider_nav_".$slider_id;
            $data_class = 'wxas_slider_for_'.$slider_id;
            $slide_nav = $this->wxas_get_slide_dada($slider_id ,$slide_rel,$theme_css_class, $slider_settings_for,$data_class, true, "wxas_slider_for");
            $this->slider = $slide_nav.$slide_for;
        }else{
            $data_class = 'wxas_slider_'.$slider_id;
            $this->slider = $this->wxas_get_slide_dada($slider_id ,$slide_rel,$theme_css_class, $slider_settings,$data_class);
        }

        //$this->wxas_run_script($slide_js_data);

        return $this->slider;
    }
    private function wxas_get_slide_dada($slider_id ,$slide_rel, $theme_css_class, $slider_settings,$data_class, $show_content=true , $ell_class=""){
        $this->slide_js_data["slide_settings"] = json_encode($slider_settings);
        $slider_data = "
        <div class='".$this->touch_effect_class." ".$this->slide_buttons." wxas_container'>
        <div data-id='".$slider_id."' data-class='".$data_class."' ".$slide_rel."data-slide_settings='".$this->slide_js_data["slide_settings"]."'class='".$data_class." wxas_slider wxas_slider_".$slider_id." ".$theme_css_class." ".$ell_class."'>";
        $wxas_items_list = get_post_meta($slider_id,"wxas_items_list", true);
        if(isset($wxas_items_list) && !empty($wxas_items_list)){
            foreach ($wxas_items_list as $item){
                $attachment_url = wp_get_attachment_url($item);
                $post_data = get_post($item);
                $attachment_type = get_post_mime_type($item);
                $is_video = WXAS_Library::type_video($attachment_type);
                $slide_desc = "";
                $is_post = false;
                if($attachment_url===false && $show_content===false){
                    $is_post = true;
                    $item_desc = $post_data->post_title;
                }else{
                    $item_desc = $post_data->post_content;
                }

                if((isset($post_data->post_content) && !empty($post_data->post_content) && $show_content) || $is_post){
                    $slide_desc = "<div class='wxas_slide_desc'>".$item_desc."</div>";
                }
                if($is_video){
                    $slider_data.="<div class='wxas_slide_item'><video width='100%' controls controlsList='nodownload'>
                        <source src='".$attachment_url."' type='".$attachment_type."'>
                        Your browser does not support HTML5 video.
                    </video>".$slide_desc."</div>";
                }else{
                    $wxas_slide_img = "";
                    if(isset($attachment_url) && $attachment_url!=false){
                        $wxas_slide_img = "<img class='wxas_slider_img' src='".$attachment_url."'>";
                    }
                    $slider_data.="<div class='wxas_slide_item'>".$wxas_slide_img.$slide_desc."</div>";
                }
            }
        }
        $slider_data.="</div></div>";
        return $slider_data;
    }
    public function wxas_run_script($data){
        $slide_el = '.wxas_slider_'.$data["id"];
        echo '
            <script>
               jQuery(document).ready(function () {
                        wxas_run_slick("'.$slide_el.'", '.$data["settings"].')
                });
            </script>
            ';

        wp_localize_script('wxa_gb_wxas_js', 'wxa_obj', array(
            'nothing_selected' => 'Nothing selected.',
            'empty_item' => '- Select -',
        ));
    }
}