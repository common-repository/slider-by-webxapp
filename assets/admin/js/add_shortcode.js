jQuery(function($){
    $('body').on('click', '.wxas_open_shortcode_popup', function(e){
        e.preventDefault();
        $("body").append("<div class='wxas_shortcode_editor'><div class='wxas_overlay'></div><div class='wxas_window'><iframe src='"+wxas_ajax.iframe_url+"'></iframe></div></div>")
    });

    $('body').on('click', '.wxas_overlay', function(e){
        $(".wxas_shortcode_editor").remove();
    });


    window.wxas_send_to_editor = function (html) {
        send_to_editor(html);
    }
    window.wxas_remove_editor = function(){
        $(".wxas_shortcode_editor").remove();
    }
});
