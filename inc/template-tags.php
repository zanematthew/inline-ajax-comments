<?php

/**
 * @todo Ajax crawling support -- https://developers.google.com/webmasters/ajax-crawling/docs/getting-started
 * @todo https://developers.google.com/webmasters/ajax-crawling/
 */


/**
 * Perform the following actions/filters when plugins are loaded
 *
 * @since 0.1-alpha
 */
function inline_comments_loaded(){
    add_action( 'wp_ajax_inline_comments_add_comment', 'inline_comments_add_comment' );
    add_action( 'wp_ajax_nopriv_inline_comments_add_comment', 'inline_comments_add_comment' );
    add_action( 'wp_ajax_nopriv_inline_comments_load_template', 'inline_comments_load_template' );
    add_action( 'wp_ajax_inline_comments_load_template', 'inline_comments_load_template' );
    add_filter( 'template_redirect', 'inline_comments_template_redirect' );
}
add_action('plugins_loaded', 'inline_comments_loaded');


/**
 * Load our JavaScript and Stylesheet on single page only
 *
 * @since 0.1-alpha
 */
function inline_comments_template_redirect() {
    if ( is_single() ) {
        add_action( 'wp_enqueue_scripts', 'inline_comments_scripts');
        add_action( 'wp_head', 'inline_comments_head');
    }
}


/**
 * Load our JavaScript and Stylesheet, we include the login-register script only if it is installed.
 *
 * @uses wp_enqueue_script()
 * @uses wp_enqueue_style()
 *
 * @since 0.1-alpha
 */
function inline_comments_scripts(){
    // wp_enqueue_script( 'textarea_auto_expand' );
    wp_enqueue_script( 'ExpandingTextareas-script' );
    wp_enqueue_script( 'inline-ajax-comments-script' );
    wp_enqueue_style( 'inline-ajax-comments-style' );
}


/**
 * Print our AJAX URL
 *
 * @since 0.1-alpha
 */
function inline_comments_head(){
    print '<script type="text/javascript"> var ajaxurl = "'. admin_url("admin-ajax.php") .'";</script>';
}


/**
 * Inserts a comment for the current post if the user is logged in.
 *
 * @since 0.1-alpha
 * @uses check_ajax_referer()
 * @uses is_user_logged_in()
 * @uses wp_insert_comment()
 * @uses wp_get_current_user()
 * @uses current_time()
 * @uses wp_kses()
 * @uses get_option()
 */
function inline_comments_add_comment(){

    check_ajax_referer('inline_comments_nonce', 'security');

    $comment = trim( $_POST['comment'] );

    if ( empty( $comment ) ) die();

    if ( get_option('comment_registration') == 1 && ! is_user_logged_in() ) die();

    $data = array(
        'comment_post_ID' => (int)$_POST['post_id'],
        'comment_content' => wp_kses( $comment, '' ),
        'comment_type' => '',
        'comment_parent' => 0,
        'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
        'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
        'comment_date' => current_time('mysql'),
        'comment_approved' => 1
    );

    if ( is_user_logged_in() ){
        $current_user = wp_get_current_user();

        $author_email = $current_user->user_email;
        $author_url = $current_user->user_url;
        $author_name = $current_user->user_nicename;

        $data['user_id'] = $current_user->ID;
    } else {
        $author_email = empty( $_POST['user_email'] ) ? null : $_POST['user_email'];
        $author_url = empty( $_POST['user_url'] ) ? null : $_POST['user_url'];
        $author_name = empty( $_POST['user_name'] ) ? null : $_POST['user_name'];
    }

    $data['comment_author'] = $author_name;
    $data['comment_author_email'] = wp_kses( $author_email, '' );
    $data['comment_author_url'] = wp_kses( $author_url, '' );

    wp_insert_comment( $data );

    die();
}


/**
 * Load comments and comment form
 *
 * @since 0.1-alpha
 */
function inline_comments_load_template(){

    check_ajax_referer('inline_comments_nonce', 'security');

    $comments = get_comments( array(
        'post_id' => $_POST['post_id'],
        'number'  => 100,
        'status'  => 'approve',
        'order'   => 'ASC'
    ) );


    $name = 'Name&#8230';
    $email = 'Email&#8230';
    $website = 'Website&#8230';
    $user_email = null;
    $user_website = null;
    $user_name = null;

    if ( is_user_logged_in() ){
        $current_user = wp_get_current_user();
        $user_name = $current_user->user_nicename;
        $user_email = $current_user->user_email;
        $user_website = $current_user->user_url;
    }

    ?>
    <div class="inline-comments-container" id="comments_target">
        <?php if ( $comments ) : foreach( $comments as $comment) : ?>
            <div class="inline-comments-content">
                <div class="inline-comments-p">
                    <?php inline_comments_profile_pic( $comment->comment_author_email ); ?>
                    <?php print str_replace("\n", "<br />", $comment->comment_content); ?><br />
                    <time class="meta">
                        <strong><?php $user = get_user_by('login', $comment->comment_author ); if ( ! empty( $user->user_url ) ) : ?>
                            <a href="<?php print $user->user_url; ?>" target="_blank"><?php print $comment->comment_author; ?></a>
                        <?php else : ?>
                            <?php print $comment->comment_author; ?>
                        <?php endif; ?></strong> <?php print human_time_diff( strtotime( $comment->comment_date ), current_time('timestamp') ); ?> ago.
                    </time>
                </div>
            </div>
        <?php endforeach; endif; ?>

        <?php if ( get_option('comment_registration') != 1 || is_user_logged_in() ) : ?>
            <div class="inline-comments-content">
                <div class="inline-comments-p">
                    <form action="javascript://" method="POST" id="default_add_comment_form">
                        <input type="hidden" name="inline_comments_nonce" value="<?php print wp_create_nonce('inline_comments_nonce'); ?>" id="inline_comments_nonce" />
                        <?php inline_comments_profile_pic(); ?>
                        <textarea placeholder="Press enter to send&#8230;" tabindex="4" id="comment" name="comment" class="inline-comments-auto-expand submit-on-enter multiple-lines-no-box-sizing"></textarea>
                        <span class="inline-comments-more-handle"><a href="#">more</a></span>
                        <div class="inline-comments-more-container" <?php if ($user_email != null ) : ?>style="display: none;"<?php endif; ?>>
                            <div class="inline-comments-field"><input type="text" tabindex="5" name="user_name" id="inline_comments_user_name" placeholder="<?php print $name; ?>" value="<?php print $user_name; ?>"  /></div>
                            <div class="inline-comments-field"><input type="email" required tabindex="5" name="user_email" id="inline_comments_user_email" placeholder="<?php print $email; ?>" value="<?php print $user_email; ?>"  /></div>
                            <div class="inline-comments-field"><input type="url" required tabindex="6" name="user_url" id="inline_comments_user_url" placeholder="<?php print $website; ?>" value="<?php print $user_website; ?>" /></div>
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
    <?php die();
}


/**
 * Determine the profile pic for a user, either the FB pic or
 * the gravatar pic. If no ID is passed uses the current logged
 * in user.
 *
 * @uses get_user_meta()
 * @uses get_avatar();
 */
function inline_comments_profile_pic( $id_or_email=null, $email=null ){

    if ( is_null( $id_or_email ) ) {
        global $current_user;
        get_currentuserinfo();
        $id_or_email = $current_user->ID;
    }

    $html = get_avatar( $id_or_email, 32 );

    print '<span class="inline-comments-profile-pic-container">' . $html . '</span>';
}


function inline_comments_tempalte( $file ){
    return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/comments.php';
}
add_filter('comments_template', 'inline_comments_tempalte');