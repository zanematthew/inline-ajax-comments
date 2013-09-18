<?php

/**
 * Our comments form template, the comments loop is loaded via html from inline_comments_load_template()
 */
if ( !defined( 'ABSPATH' ) ) die( 'You cannot access this template file directly' );

?>
<?php
    $name = 'Name&#8230';
    $email = 'Email&#8230';
    $website = 'Website&#8230';
    $user_email = null;
    $user_website = null;
    $user_name = null;
    $keep_open = get_option('keep_open');
    $custom_more = get_option('custom_more');
    $more = inline_comments_options( 'custom_more', empty( $custom_more ) ? 'default' : $custom_more );

    if ( is_user_logged_in() ){
        $current_user = wp_get_current_user();
        $user_name = $current_user->display_name;
        $user_email = $current_user->user_email;
        $user_website = $current_user->user_url;
    }
?>

<noscript>JavaScript is required to load the comments.</noscript>
<div class="inline-comments-container" id="inline-comments-container_<?php echo $post->ID; ?>" name="comments" >
    <div id="inline_comments_ajax_handle_<?php echo $post->ID; ?>" id="inline_comments_ajax_handle" class="inline_comments_ajax_handle last-child" data-post_id="<?php echo $post->ID; ?>">
    <div id="inline_comments_ajax_target_<?php echo $post->ID; ?>" style="display: none;" ></div>
    <div class="inline-comments-loading-icon">Loading Comments&#8230;</div>
    <input type="hidden" name="inline_comments_nonce" value="<?php print wp_create_nonce('inline_comments_nonce'); ?>" id="inline_comments_nonce" />
    <?php if ( get_option('comment_registration') != 1 || is_user_logged_in() ) : ?>
        <div class="inline-comments-content inline-comments-content-comment-fields">
            <div class="inline-comments-p">
                <form action="javascript://" method="POST" id="default_add_comment_form-<?php echo $post->ID; ?>" class="default-add-comment-form">
                    <input type="hidden" name="inline_comments_nonce_<?php echo $post->ID; ?>" value="<?php print wp_create_nonce('inline_comments_nonce_'.$post->ID); ?>" id="inline_comments_nonce_<?php echo $post->ID; ?>" />
                    <?php inline_comments_profile_pic(); ?>
                    <textarea placeholder="Press enter to submit comment&#8230;" tabindex="4" id="comment_<?php echo $post->ID; ?>" name="comment" id="inline-comments-textarea" class="inline-comments-auto-expand submit-on-enter"></textarea>
                    <span id ="inline-comments-more-handle_<?php echo $post->ID; ?>" class="inline-comments-more-handle"><a href="#"><?php echo $more['more']; ?></a></span>
                    <div id = "inline-comments-more-container_<?php echo $post->ID; ?>" class="inline-comments-more-container" <?php if ( $user_email != null && isset( $keep_open ) && $keep_open != "on" ) : ?>style="display: none;"<?php endif; ?>>
                        <div id="inline-comments-allowed-tags-container_<?php echo $post->ID; ?>" class="inline-comments-allowed-tags-container">
                            Allowed <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:
                            <code>&lt;a href="" title=""&gt; &lt;blockquote&gt; &lt;code&gt; &lt;em&gt; &lt;strong&gt;</code>
                        </div>
                        <div class="inline-comments-field"><input type="text" tabindex="5" name="user_name" class="inline_comments_user_name" id="inline_comments_user_name_<?php echo $post->ID; ?>" placeholder="<?php print $name; ?>" value="<?php print $user_name; ?>"  /></div>
                        <div class="inline-comments-field"><input type="email" required tabindex="5" name="user_email" class="inline_comments_user_email" id="inline_comments_user_email_<?php echo $post->ID; ?>" placeholder="<?php print $email; ?>" value="<?php print $user_email; ?>"  /></div>
                        <div class="inline-comments-field"><input type="url" required tabindex="6" name="user_url" class="inline_comments_user_url" id="inline_comments_user_url_<?php echo $post->ID; ?>" placeholder="<?php print $website; ?>" value="<?php print $user_website; ?>" /></div>
                    </div>
                </form>
            </div>
        </div>
    <?php else : ?>
        <div class="callout-container">
            <p>Please <?php echo wp_register('','', false); ?> or <a href="<?php print wp_login_url(); ?>" class="inline-comments-login-handle">Login</a> to leave Comments</p>
        </div>
    <?php endif; ?>
	</div>
</div>
<script class="inline-comments-script" >
	console.log ('<?php echo $post->ID; ?>' + 'has loaded...');
	var tid_<?php echo $post->ID; ?> = setInterval( function () {
    if ( document.readyState !== 'complete' ) return;
		clearInterval( tid_<?php echo $post->ID; ?> );
		inline_comments_ajax_load(<?php echo $post->ID; ?>)
	}, 100 );
</script>
