<?php

/**
 * Our comments template, the form is actually loaded via ajax from within inline_comments_load_template()
 */
if ( ! is_single() ) return;
if ( !defined( 'ABSPATH' ) ) die( 'You cannot access this template file directly' );
?>
<noscript>JavaScript is required to load the comments.</noscript>
<div id="inline_comments_ajax_handle" class="row last-child" data-post_id="<?php echo $post->ID; ?>">
    <div id="inline_comments_ajax_target" style="display: none;"></div>
    <div class="inline-comments-loading-icon" style="display: ;">Loading Comments&#8230;</div>
    <input type="hidden" name="inline_comments_nonce" value="<?php print wp_create_nonce('inline_comments_nonce'); ?>" id="inline_comments_nonce" />
</div>
