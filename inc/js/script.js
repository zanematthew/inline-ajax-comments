jQuery(document).ready(function( $ ){

    // $('#default_add_comment_form textarea').textareaAutoExpand();

    /**
     * Default ajax setup
     */
    $.ajaxSetup({
        type: "POST",
        url: ajaxurl,
        dataType: "html"
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
        event.preventDefault();

        var $this = $(this);
        $this.css('opacity','0.5');

        data = {
            action: "inline_comments_add_comment",
            post_id: $('#inline_comments_ajax_handle').attr( 'data-post_id' ),
            user_name: $('#inline_comments_user_name').val(),
            user_email: $('#inline_comments_user_email').val(),
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
                $('textarea').val('');
                $this.css('opacity','1');
            }
        });

    });

    /**
     * Allow Comment form to be submitted when the user
     * presses the "enter" key.
     */
    $( document ).on('keypress', '#default_add_comment_form textarea, #default_add_comment_form input', function( event ){
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
					$( "#inline_comments_ajax_target_"+data.post_id).fadeIn().html( msg ); // Give a smooth fade in effect
                    if ( location.hash ){
                        $('html, body').animate({
                            scrollTop: $( location.hash ).offset().top
                        });
                        $( location.hash ).addClass( 'inline-comments-highlight' );
                    }
                }
            });

            $( document ).on('click', '.inline-comments-time-handle', function( e ){
                $( '.inline-comments-content' ).removeClass('inline-comments-highlight')
                comment_id = '#comment-' + $( this ).attr('data-comment_id');
                $( comment_id ).addClass('inline-comments-highlight');
            });
        }
    });

    $( document ).on('click', '.inline-comments-more-handle', function( event ){
        event.preventDefault();
        if ( $( this ).hasClass('inline-comments-more-open') ){
            $( 'a', this ).html('more');
            $('#comment').css('height', '32');
        } else {
            $( 'a', this ).html('less');
            $('#comment').css('height', '150');
        }
        $( this ).toggleClass('inline-comments-more-open');
        $('.inline-comments-more-container').toggle();
    });
});