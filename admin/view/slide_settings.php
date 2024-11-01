<?php
global $post;

$wxas_slide_settings = get_post_meta($post->ID, "wxas_slide_settings", true);
$theme_args = array( 'post_type' => "wxas_themes" );
$wxas_slide_themes = get_posts( $theme_args );
$wxas_theme_id = get_post_meta($post->ID, "wxas_theme_id", true);



$wxas_settings_list = array(
    "wxas_filmstrip" =>array(
        "name" => "filmstrip",
        "type" => "checkbox",
        "label" => "Filmstrip",
        "default" => "",
    ),
    "wxas_slide_infinite" =>array(
        "name" => "infinite",
        "type" => "checkbox",
        "label" => "Infinite",
        "default" => "checked",
    ),
    "wxas_slide_dots" =>array(
        "name" => "dots",
        "type" => "checkbox",
        "label" => "Dots",
        "default" => "checked",
    ),
    "wxas_slide_buttons" =>array(
        "name" => "slide_buttons",
        "type" => "checkbox",
        "label" => "Next prev buttons",
        "default" => "checked",
    ),
    "wxas_slide_touch" =>array(
        "name" => "touch_effect",
        "type" => "checkbox",
        "label" => "Touch effect",
        "default" => "checked",
    ),
    "wxas_slide_centerMode" =>array(
        "name" => "centerMode",
        "type" => "checkbox",
        "label" => "Center Mode",
        "default" => "",
    ),
    "wxas_slide_adaptiveHeight" =>array(
        "name" => "adaptiveHeight",
        "type" => "checkbox",
        "label" => "Adaptive Height",
        "default" => "checked",
    ),
    "wxas_slide_fade" =>array(
        "name" => "fade",
        "type" => "checkbox",
        "label" => "Fade",
        "default" => "",
        "value" => ""
    ),
    "wxas_slide_rtl" =>array(
        "name" => "rtl",
        "type" => "checkbox",
        "label" => "Right to Left",
        "default" => "",
    ),
    "wxas_slide_autoplay" =>array(
        "name" => "autoplay",
        "type" => "checkbox",
        "label" => "Autoplay",
        "default" => "",
    ),

    "wxas_slide_autoplaySpeed" =>array(
        "name" => "autoplaySpeed",
        "type" => "number",
        "label" => "Autoplay Speed",
        "default" => 2000,
    ),
    "wxas_slide_slidesToShow" =>array(
        "name" => "slidesToShow",
        "type" => "number",
        "label" => "Slides To Show",
        "default" => 1,
    ),
    "wxas_slide_slidesToScroll" =>array(
        "name" => "slidesToScroll",
        "type" => "number",
        "label" => "slides To Scroll",
        "default" => 1,
    ),
);
?>


<div class="wxas_slide_settings">

 <?php
 foreach ($wxas_settings_list as $key=>$element){
     if($element["type"] === "checkbox"){
         echo wxas_checkbox($element, $wxas_slide_settings);
     }elseif ($element["type"] === "number"){
        echo  wxas_number($element, $wxas_slide_settings);
     }elseif ($element["type"] === "text"){

     }

 }
 echo '<div class="wxas_settings_input">
            <label>Select theme</label>
            <select name="wxas_theme_id">
            <option value="0">Default</option>';

 $wxas_selected_theme = "";
 foreach ($wxas_slide_themes as $theme){
     if(intval($wxas_theme_id) === intval($theme->ID)){
         $wxas_selected_theme = "selected";
     }else{
         $wxas_selected_theme = "";
     }
     echo "<option ".$wxas_selected_theme." value='".$theme->ID."'>".$theme->post_title."</option>";
 }
 echo   "</select>
        </div>";
 ?>

</div>


<?php
function wxas_number($data, $wxas_slide_settings){
    $return_data = "";
    if(isset($wxas_slide_settings[$data["name"]]) && $wxas_slide_settings[$data["name"]] != ""){
        $val = $wxas_slide_settings[$data["name"]];
    }else{
        $val = $data["default"];
    }
    if($data["type"] === "number"){
        $return_data ='<div class="wxas_settings_input">
                            <label for="wxas_slide_slidesToScroll">'.$data["label"].'</label>
                            <input min="1" value='.$val.' id="wxas_slide_slidesToScroll" name="wxas_slide_settings['.$data["name"].']" type="'.$data["type"].'">
                        </div>';
    }
    return $return_data;
}

function wxas_checkbox($data, $wxas_slide_settings){
    $return_data = "";
    if(isset($wxas_slide_settings[$data["name"]]) && $wxas_slide_settings[$data["name"]] === "on"){
        $checked = "checked";
    }else{
        if($wxas_slide_settings === ""){
            $checked = $data["default"];
        }else{
            $checked = '';
        }

    }
    if($data["type"] === "checkbox"){
        $return_data = '
                        <div class="wxas_settings_input">
                            <div class="pretty p-switch">
                                <input '.$checked.' name="wxas_slide_settings['.$data["name"].']" type="'.$data["type"].'" />
                                <div class="state">
                                    <label>'.$data["label"].'</label>
                                </div>
                            </div>
                        </div>
                        ';
    }
    return $return_data;
}

?>