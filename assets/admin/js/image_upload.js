jQuery(function($){
    /*
     * Select/Upload image(s) event
     */
    $('body').on('click', '.wxas_upload_item_button', function(e){
        e.preventDefault();

        var button = $(this);
        var custom_uploader = wp.media({
                title: 'Insert Media',
                library : {
                    type : [ 'video', 'image' ]
                },
                button: {
                    text: 'Use this media'
                },
                multiple: true
            }).on('select', function() {
            var attachments = custom_uploader.state().get('selection');
            attachments.each(function(attachment) {
                var image_url = "";
                var image_title = attachment.attributes.title;
                if(attachment.attributes.type === "video"){
                    image_url = attachment.attributes.thumb.src;
                }else if(attachment.attributes.type === 'image'){
                    image_url = attachment.attributes.sizes.medium.url;
                }
                if(image_url!=""){
                    $(".wxas_items_list").append('<li class="wxas_admin_item">' +
                        '<img class="wxas_admin_slider_item" src="'+image_url+'">' +
                        '<span class="wxas_media_title">'+image_title+'</span>'+
                        '<span class="wxas_image_settings">' +
                        '<span class="dashicons dashicons-edit wxas_edit_item"></span>' +
                        '<span class="dashicons dashicons-trash wxas_delete_item"></span>' +
                        '</span>' +
                        '<input type="hidden" name="wxas_items_id[]" value="'+attachment.id+'">' +
                        '<textarea class="wxas_slide_element_content" type="text" name="wxas_slide_element_content_'+attachment.id+'"></textarea> '+
                        '</li>');
                }
            });

            }).open();
    });
    $('body').on('click', '.wxas_add_post_button', function(e){
        e.preventDefault();
    });
    //wxas_delete_item
    $('body').on('click', ".wxas_delete_item", function () {
        $(this).closest(".wxas_admin_item").remove();
    });


});