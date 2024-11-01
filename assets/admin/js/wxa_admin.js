jQuery(document).ready(function () {
    var wxas_edited_el = "";
    jQuery(".wxas_color_picker").spectrum({
        preferredFormat: "hex",
    });



    jQuery('body').on('click', '.wxas_edit_item', function(e){
        wxas_edited_el = jQuery(this).closest(".wxas_admin_item").find(".wxas_slide_element_content");
        var el_content = wxas_edited_el.val();
        jQuery("#wxas_slide_el_content").val(el_content);
        jQuery(".wxas_add_desc_popup").removeClass("wxas_hide_admin_popup");
    });
    jQuery(".wxas_add_desc_popup .wxas_add_el_content").click(function (e) {
        e.preventDefault();
        var wxas_slide_el_content = jQuery("#wxas_slide_el_content").val();
        wxas_edited_el.val(wxas_slide_el_content);
        jQuery(".wxas_add_desc_popup").addClass("wxas_hide_admin_popup");
    });


    jQuery(".wxas_admin_popup_overlay").click(function () {
        jQuery(this).closest(".wxas_admin_popup").addClass("wxas_hide_admin_popup");
    });
    jQuery(".wxas_close_admin_popup").click(function () {
        jQuery(this).closest(".wxas_admin_popup").addClass("wxas_hide_admin_popup");
    });




    jQuery('body').on('click', '.wxas_add_post_button', function(e){
        jQuery(".wxas_add_post_popup").removeClass("wxas_hide_admin_popup");
    });

    jQuery(".wxas_add_post_popup .wxas_add_el_content").click(function (e) {
        e.preventDefault();
        var wxas_selected_post = jQuery(".wxas_select_post");
        var wxas_selected_post_id = wxas_selected_post.find(':selected').data('id');
        jQuery(".wxas_items_list").append('<li class="wxas_admin_item">' +
            '<div class="wxas_admin_slider_post"></div>' +
            '<span class="wxas_media_title">'+wxas_selected_post.val()+'</span>'+
            '<span class="wxas_image_settings">' +
            '<span class="dashicons dashicons-edit wxas_edit_item"></span>' +
            '<span class="dashicons dashicons-trash wxas_delete_item"></span>' +
            '</span>' +
            '<input type="hidden" name="wxas_items_id[]" value="'+wxas_selected_post_id+'">' +
            '<textarea class="wxas_slide_element_content" type="text" name="wxas_slide_element_content_"></textarea> '+
            '</li>');
        jQuery(this).closest(".wxas_admin_popup").addClass("wxas_hide_admin_popup");
    });
});