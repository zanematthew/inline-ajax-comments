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
     $('.default-add-comment-form').submit(function() {
        event.preventDefault();

        var $this = $(this);
        $this.css('opacity','0.5');
		var full_id = this.id;
		var explode_post_id = full_id.split("-",2);
		var post_id = explode_post_id[1];
		console.log ("posting a comment for post id: #"+post_id);

        data = {
            action: "inline_comments_add_comment",
            post_id: post_id,
            user_name: $('#inline_comments_user_name_'+post_id).val(),
            user_email: $('#inline_comments_user_email_'+post_id).val(),
            user_url: $('#inline_comments_user_url_'+post_id).val(),
            comment: $( '#comment_'+post_id ).val(),
            security: $('#inline_comments_nonce_'+post_id).val()
        };
		console.log ("data stream(var array data):");
		console.log ("* action: "+data.action);
		console.log ("* post_id: "+data.post_id);
		console.log ("* user_name: "+data.user_name);
		console.log ("* user_url: "+data.user_url);
		console.log ("* comment: "+data.comment);
		console.log ("* security: "+data.security);
		console.log ("---end");
		
		console.log ("target_div: "+"#inline_comments_ajax_target_"+post_id);
		console.log ("template: " + $( '#inline_comments_ajax_handle_'+post_id ).attr( 'data-template' ));
		console.log ("post_id: " + post_id);
		console.log ("security: " + $( '#inline_comments_nonce_'+post_id ).val());
        $.ajax({
            data: data,
            global: false,
            success: function( msg ){
                inline_comments_ajax_load_template({
                    "target_div": "#inline_comments_ajax_target_"+post_id,
                    "template": $( '#inline_comments_ajax_handle_'+post_id ).attr( 'data-template' ),
                    "post_id": post_id,
                    "security": $( '#inline_comments_nonce_'+post_id ).val()
                }, false );
                $('textarea').val('');
                $this.css('opacity','1');
            },
			fail: function(){
				console.log("ajax failed");
			},
				always: function(){
				console.log(msg);
			}
        });

    });

    /**
     * Allow Comment form to be submitted when the user
     * presses the "enter" key.
     */
	$('.default-add-comment-form').keypress(function (e) {
	  if (e.which == 13) {
		console.log ("Enter Key Pressed - Submitting form");
		
		$(this).submit();
		return false;
	  }
	});

	 
	 /*
    $( document ).on('keypress', '#default_add_comment_form textarea, #default_add_comment_form input', function( event ){
        if ( event.keyCode == '13' ) {
            event.preventDefault();
            $(this).submit();
        }
    });
*/

    window.inline_comments_ajax_load = function(post_id){
		console.log("load comments for post "+post_id+"...");
        if ( $( '#inline_comments_ajax_handle_'+post_id ).length ) {
            $( '.inline-comments-loading-icon').show();

            data = {
                "action": "inline_comments_load_template",
                "target_div": "#inline_comments_ajax_target",
                "template": $( '#inline_comments_ajax_handle' ).attr( 'data-template' ),
                "post_id": post_id,
                "security": $('#inline_comments_nonce').val()
            };
			console.log("loading comments for post: "+data.post_id);
            $.ajax({
                data: data,
                success: function( msg ){
                    $( '.inline-comments-loading-icon').fadeOut();
					$( "#inline_comments_ajax_target_"+post_id).fadeIn().html( msg ); // Give a smooth fade in effect
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
    }

	$('.inline-comments-more-handle').click(function(){
		event.preventDefault();
		//Get the post id
		var full_id = this.id;
		var explode_post_id = full_id.split("_",2);
		var post_id = explode_post_id[1];
		console.log (post_id);
 
		if ( $( this ).hasClass('inline-comments-more-open_'+post_id) ){
            $( 'a', this ).html('●●●');
            $('#comment_'+post_id).css('height', '32');
        } else {
            $( 'a', this ).html('↑↑↑');
            $('#comment_'+post_id).css('height', '150');
        }
			$( this ).toggleClass('inline-comments-more-open_'+post_id);
			$('#inline-comments-more-container_'+post_id).toggle();
	
		
	
	});
	/*
    window.inline-comments-more-toggle = function(post_id){
		
        if ( $( this ).hasClass('inline-comments-more-open_'+post_id) ){
            $( 'a', this ).html('●●●');
            $('#comment').css('height', '32');
        } else {
            $( 'a', this ).html('↑↑↑');
            $('#comment').css('height', '150');
        }
        $( this ).toggleClass('inline-comments-more-open_'+post_id);
        $('#inline-comments-more-container_'+post_id).toggle();
    }
	*/
});

 