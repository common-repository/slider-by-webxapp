<?php
$args = array( 'post_type' => "wxas_slider" );
$wxas_posts = get_posts( $args );
?>
<h4 class="popup_title">Select slider</h4>
<div class="wxas_add_shortcode_content">
    <select class="wxas_select_slider">
        <option>Select slider</option>
        <?php
            foreach ($wxas_posts as $wxas_post){
                echo "<option data-id='".$wxas_post->ID."'>".$wxas_post->post_title."</option>";
            }

        ?>
    </select>
    <button onclick="wxas_insert_shortcode(event);" class="wxas_insert_shortcode">Insert</button>
</div>

<script>

    function wxas_insert_shortcode(e) {
        e.preventDefault();
        var wxas_selected_slider = jQuery(".wxas_select_slider").find(':selected').data('id');
        if(typeof wxas_selected_slider === "undefined"){
            return;
        }
        window.parent.wxas_send_to_editor('[wxas id="'+wxas_selected_slider+'"]');
        window.parent.wxas_remove_editor();
    }
</script>
<style>
    body{
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }
    .popup_title{
        background-color: #e6e6e6;
        padding: 10px;
        margin-bottom: 3px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    .wxas_select_slider{
        width: 50%;
        height: 25px;
    }
    .wxas_add_shortcode_content{
        padding: 10px;
    }
    .wxas_insert_shortcode{
        height: 25px;
        padding: 0 15px;
    }
</style>