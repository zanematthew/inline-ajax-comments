jQuery(document).ready(function( $ ){

    // $('#default_add_comment_form textarea').textareaAutoExpand();

    /**
     * Default ajax setup
     */
    $.ajaxSetup({
        type: "POST",
        url: _inline_comments.ajaxurl,
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
     $( document ).on('submit','.default-add-comment-form',function( e ) {
        event.preventDefault();

        var $this = $(this);
        $this.css('opacity','0.5');
		var full_id = this.id;
		var explode_post_id = full_id.split("-",2);
		var post_id = explode_post_id[1];

        data = {
            action: "inline_comments_add_comment",
            post_id: post_id,
            user_name: $('#inline_comments_user_name_'+post_id).val(),
            user_email: $('#inline_comments_user_email_'+post_id).val(),
            user_url: $('#inline_comments_user_url_'+post_id).val(),
            comment: $( '#comment_'+post_id ).val(),
            security: $('#inline_comments_nonce_'+post_id).val()
        };

        $.ajax({
            data: data,
            global: false,
            success: function( msg ){
                inline_comments_ajax_load_template({
                    "target_div": "#inline_comments_ajax_target_"+post_id,
                    "template": $( '#inline_comments_ajax_handle' ).attr( 'data-template' ),
                    "post_id": post_id,
                    "security": $( 'inline_comments_nonce_' +post_id).val()
                }, false );
                $('textarea').val('');
                $this.css('opacity','1');
            },
			fail: function(){
			},
				always: function(){
			}
        });

    });

    /**
     * Allow Comment form to be submitted when the user
     * presses the "enter" key.
     */
	$(document).on('keypress', '.default-add-comment-form',function (e) {
	  if (e.which == 13) {
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
        if ( $( '#inline_comments_ajax_handle_'+post_id ).length ) {
            $( '.inline-comments-loading-icon').show();

            data = {
                "action": "inline_comments_load_template",
                "target_div": '#inline_comments_ajax_target_'+post_id,
                "template": $( '#inline_comments_ajax_handle').attr( 'data-template' ),
                "post_id": post_id,
                "security": $('#inline_comments_nonce_'+post_id).val()
            };
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

	$( document ).on('click','.inline-comments-more-handle', function( e ){
		event.preventDefault();

        //Get the post id
		var full_id = this.id;
		var explode_post_id = full_id.split("_",2);
		var post_id = explode_post_id[1];

        $( '.inline-comments-more-handle a' ).toggleClass('closed');
        $( '.inline-comments-more-handle a' ).toggleClass('open');


		if ( $( this ).hasClass('inline-comments-more-open_'+post_id) ){
            // $( '.inline-comments-more-handle a' ).toggleClass('closed');
        	$( 'a', this ).html( _inline_comments.custom_more.more );
   		    $('#comment_'+post_id).animate({
                height: 17
            },
            250);
        } else {
            // $( '.inline-comments-more-handle a' ).toggleClass('open');
            $( 'a', this ).html( _inline_comments.custom_more.less );
            $('#comment_'+post_id).animate({
                height: '100'
            },
            250);
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


// BETA: If newly loaded Ajax content has javascript then execute.
// This helps inline-ajax-comments work if loaded by something like Infinite Scroll.
// You MUST run this callback after ajax success. see jQuery docs.

function ajaxLoadedCallback() {
    scriptx = document.getElementsByTagName("script");


    scripts = new Array();
    for (var idx=0; idx<scriptx.length; idx++) {

		if (jQuery(scriptx[idx]).is(".inline-comments-script")) {
			scripts.push(scriptx[idx].innerHTML);
		}

	}

      // execute each script in turn
      for(idx=0; idx<scripts.length; ++idx) {
		var content = scripts[idx];
	        if (content.length) {
	            try {
              // create a function for the script & execute it
              f = new Function(content);
              f();
            } catch(se) {

            } // end try-catch
         } // end if
      } // end for

}
