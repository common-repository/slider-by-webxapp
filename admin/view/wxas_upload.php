<?php

global $post;

$wxas_items_list = get_post_meta($post->ID, "wxas_items_list", true);
?>
<?php
$content = "";
$editor_id = 'wxas_slide_el_content';
$settings = array(
    'wpautop'       => 0,
    'media_buttons' => 0,
    'textarea_name' => '',
    'textarea_rows' => 20,
    'tabindex'      => null,
    'editor_css'    => '',
    'editor_class'  => '',
    'teeny'         => 0,
    'dfw'           => 0,
    'tinymce'       => 0,
    'quicktags'     => 1,
    'drag_drop_upload' => false
);


$posts_query = new WP_Query;
$posts_list = $posts_query->query( array(
    'post_type' => array('page','post')
));


?>
<div class="wxas_add_desc_popup wxas_admin_popup wxas_hide_admin_popup">
    <div class="wxas_admin_popup_overlay"></div>
    <div class="wxas_slide_item_content">
        <h3>Slide description</h3>
        <span class="wxas_close_admin_popup dashicons dashicons-no-alt"></span>
        <?php
        wp_editor( $content, $editor_id,$settings );
        ?>
        <button class="wxas_add_el_content button button-primary button-large">Save</button>
    </div>
</div>

<div class="wxas_add_post_popup wxas_admin_popup wxas_hide_admin_popup">
    <div class="wxas_admin_popup_overlay"></div>
    <div class="wxas_slide_item_content">
        <h3>Add post</h3>
        <span class="wxas_close_admin_popup dashicons dashicons-no-alt"></span>
        <select class="wxas_select_post">
            <?php
            foreach ($posts_list as $el){
                echo "<option data-id='".$el->ID."'>$el->post_title</option>";
            }
            ?>
        </select>
        <button class="wxas_add_el_content button button-primary button-large">Add</button>
    </div>
</div>

<div class="wxas_container">
    <button class="wxas_upload_item_button wxas_add_item">
        <span class="dashicons dashicons-format-gallery"></span>
        Add Media
        <span class="dashicons dashicons-plus"></span>
    </button>
    <button class="wxas_add_post_button wxas_add_item">
        <span class="dashicons dashicons-media-text"></span>
        Add post
        <span class="dashicons dashicons-plus"></span>
    </button>

    <div class="wxas_selected_items">
        <ul class="wxas_items_list">
            <?php
                if(isset($wxas_items_list) && is_array($wxas_items_list)){
                    foreach ($wxas_items_list as $wxas_item_id){
                        $wxas_item_id = intval($wxas_item_id);
                        $item_url = WXAS_Library::wp_get_attachment_medium_url($wxas_item_id);
                        $attachment_type = get_post_mime_type($wxas_item_id);
                        $attachment_data = get_post($wxas_item_id);
                        $is_video = WXAS_Library::type_video($attachment_type);
                        if($is_video){
                            $item_url = plugins_url('/assets/images/video_logo.png', WXAS_MAIN_FILE);
                        }
                        $wxas_edit_url = get_edit_post_link($wxas_item_id);
                        $wxas_media_title = get_the_title($wxas_item_id);
                        if(!isset($item_url)){
                            $slide_item_img = '<div class="wxas_admin_slider_post"></div>';
                        }else{
                            $slide_item_img = '<img class="wxas_admin_slider_item" src="'.$item_url.'">';
                        }
                        echo '<li class="wxas_admin_item">
                                '.$slide_item_img.'
                                <span class="wxas_media_title">'.$wxas_media_title.'</span>
                                <span class="wxas_image_settings">
                                   <span class="dashicons dashicons-edit wxas_edit_item"></span>
                                   <span class="dashicons dashicons-trash wxas_delete_item"></span>
                                </span>
                                <input type="hidden" name="wxas_items_id[]" value="'.$wxas_item_id.'"> 
                                <textarea class="wxas_slide_element_content" type="text" name="wxas_slide_element_content_'.$wxas_item_id.'">'.$attachment_data->post_content.'</textarea> 
                              </li>';
                    }
                }
            ?>
        </ul>
    </div>

</div>


