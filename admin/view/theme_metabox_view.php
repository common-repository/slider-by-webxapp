<?php
global $post;

$wxas_theme_meta = get_post_meta($post->ID, "wxas_theme_meta", true);
$wxas_slide_theme_filed = array(
        'wxas_next_prev_color' => array(
            'name' => 'wxas_next_prev_color',
            'type' => 'text_color',
            'default' => '#cc0000',
            'label' => 'Next prev buttons color'
        ),
/*        'wxas_next_prev_hover_color' => array(
            'name' => 'wxas_next_prev_hover_color',
            'type' => 'text_color',
            'default' => '#cc0000',
            'label' => 'Next prev buttons hover color'
        ),*/
        'wxas_dots_color' => array(
            'name' => 'wxas_dots_color',
            'type' => 'text_color',
            'default' => '#cc0000',
            'label' => 'Dots color'
        ),
        'wxas_dots_hover_color' => array(
            'name' => 'wxas_dots_hover_color',
            'type' => 'text_color',
            'default' => '#cc0000',
            'label' => 'Dots hover color'
        ),
        'wxas_buttons_position' => array(
            'multiple' => true,
            'name' => 'wxas_buttons_position',
            'type' => 'radio',
            'default' => '#cc0000',
            'label' => 'Prev next buttons position',
            'selected' => 0,
            'fileds' => array(
                array(
                    'name' => 'wxas_buttons_position',
                    'label' => 'Next to slider',
                    'val' => 0,
                ),
                array(
                    'name' => 'wxas_buttons_position',
                    'label' => 'Inside of slider',
                    'val' => 1,
                ),
            ),

        ),
);


echo '<div class="wxas_theme_meta">';
foreach ($wxas_slide_theme_filed as $filed){
    if($filed["type"] === "text_color"){
        echo wxas_text_color($filed, $wxas_theme_meta);
    }elseif ($filed["type"] === "radio"){
        echo wxas_radio($filed, $wxas_theme_meta);
    }
}
echo '</div>';


function wxas_text_color($filed ,$wxas_theme_meta){
    $val = $filed["default"];
    if(isset($wxas_theme_meta) && isset($wxas_theme_meta[$filed["name"]]) && !empty($wxas_theme_meta[$filed["name"]])){
        $val = $wxas_theme_meta[$filed["name"]];
    }
    return '<div class="wxas_input_block">
                <label for="wxas_next_prev_color">'.$filed["label"].'</label>
                <input type="text" onload="" name="wxas_theme_color['.$filed["name"].']" class="wxas_color_picker" id="'.$filed["name"].'" value="'.$val.'" />
            </div>';
}
function wxas_radio($filed ,$wxas_theme_meta){
    $selected = $filed["selected"];
    if(isset($wxas_theme_meta[$filed["name"]])){
        $selected = $wxas_theme_meta[$filed["name"]];
    }
    $element_html = '<div class="wxas_input_block">
       <label>'.$filed['label'].'</label>';
    foreach ($filed["fileds"] as $element){
        $checked = '';
        if(intval($selected) === intval($element["val"])){
            $checked = 'checked';
        }

        $element_html .= '<div class="pretty p-default p-round">
                            <input '.$checked.' type="radio" name="'.$element["name"].'" value="'.$element["val"].'">
                            <div class="state">
                                <label>'.$element["label"].'</label>
                            </div>
                           </div>';
    }
    $element_html .= '</div>';

    return $element_html;
}
?>

