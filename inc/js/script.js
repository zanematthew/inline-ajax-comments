jQuery(document).ready(function( $ ){

    /**
     * Default ajax setup
     */
    $.ajaxSetup({
        type: "POST",
        url: ajaxurl
    });

    window.inline_comments_ajax_load_template = function( params, my_global ) {

        var my_global;
        var request_in_process = false;

        params.action = "inline_comments_load_template";

        $.ajax({
            data: params,
            global: my_global,
            success: function( msg ){
                $( params.target_div ).fadeIn().html( msg );
                request_in_process = false;
                if (typeof params.callback === "function") {
                    params.callback();
                }
            }
        });
    }

    /**
     * Submit new comment, note comments are loaded via ajax
     */
     $( document ).on( 'submit', '#default_add_comment_form', function( event ){
        $(this).css('opacity','0.5');
        event.preventDefault();

        data = {
            action: "inline_comments_add_comment",
            post_id: $('#inline_comments_ajax_handle').attr( 'data-post_id' ),
            user_email: $('#zm_user_email').val(),
            user_url: $('#inline_comments_user_url').val(),
            comment: $( '#comment' ).val(),
            security: $('#inline_comments_nonce').val()
        };

        $.ajax({
            data: data,
            global: false,
            success: function( msg ){
                inline_comments_ajax_load_template({
                    "target_div": "#inline_comments_ajax_target",
                    "template": $( '#inline_comments_ajax_handle' ).attr( 'data-template' ),
                    "post_id": $( '#inline_comments_ajax_handle' ).attr( 'data-post_id' ),
                    "security": $( '#inline_comments_nonce' ).val()
                }, false );
            }
        });
    });

    /**
     * Allow Comment form to be submitted when the user
     * presses the "enter" key.
     */
    $( document ).on('keypress', '#default_add_comment_form textarea, #inline_comments_user_email, #inline_comments_user_url', function( event ){
        if ( event.keyCode == '13' ) {
            event.preventDefault();
            $('#default_add_comment_form').submit();
        }
    });

    $( window ).load(function(){
        if ( $( '#inline_comments_ajax_handle' ).length ) {
            $( '.inline-comments-loading-icon').show();

            data = {
                "action": "inline_comments_load_template",
                "target_div": "#inline_comments_ajax_target",
                "template": $( '#inline_comments_ajax_handle' ).attr( 'data-template' ),
                "post_id": $( '#inline_comments_ajax_handle' ).attr( 'data-post_id' ),
                "security": $('#inline_comments_nonce').val()
            };

            $.ajax({
                data: data,
                success: function( msg ){
                    $( '.inline-comments-loading-icon').fadeOut();
                    $( "#inline_comments_ajax_target" ).fadeIn().html( msg ); // Give a smooth fade in effect
                }
            });
        }
    });

    $( document ).on('click', '.inline-comments-more-handle', function( event ){
        event.preventDefault();
        $('.inline-comments-more-container').toggle();
    });
});