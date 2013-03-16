<?php

/**
 * Plugin Name: Inline Ajax Comments
 * Plugin URI: http://zanematthew.com/blog/plugins/inline-comments/
 * Description: Displays a single line textarea for entering comments, users can press "enter/return", and comments are loaded and submitted via AJAX.
 * Tags: comments, ajax, security, ajax comments, comment, inline, comment form
 * Version: 0.1-alpha
 * Author: Zane Matthew
 * Author URI: http://zanematthew.com/
 * License: GPL
 */


/***********************************************
 * YOU SHOULD NOT HAVE TO EDIT BELOW THIS LINE *
 ***********************************************/


/**
 * From the WordPress plugin headers above we derive the version number, and plugin name
 */
$plugin_headers = get_file_data( __FILE__, array( 'Version' => 'Version', 'Name' => 'Plugin Name' ) );


/**
 * We store our plugin data in the following global array.
 * $my_unique_name with your unique name
 */
global $my_unique_name;
$my_unique_name = array();
$my_unique_name['version_key'] = strtolower( str_replace( ' ', '_', $plugin_headers['Name'] ) ) . '_version';
$my_unique_name['version_value'] = $plugin_headers['Version'];


/**
 * When the user activates the plugin we add the version number to the
 * options table as "my_plugin_name_version" only if this is a newer version.
 */
$activate_fn = function(){

    global $my_unique_name;

    if ( get_option( $my_unique_name['version_key'] ) && get_option( $my_unique_name['version_key'] ) > $my_unique_name['version_value'] )
        return;

    update_option( $my_unique_name['version_key'], $my_unique_name['version_value'] );

    $date = date('F j, Y, g:i a');

    $files = array(
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'admin/admin-tags.php',
                    'desc' => "<?php\n/**\n * This file is automatically created for you. \n * The admin-tags.php file is where you should place all generic admin related functions. \n * \n * Created On: {$date} \n */"
                ),
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'inc/template-tags.php',
                    'desc' => "<?php\n/** \n * This file is automatically created for you. \n * The template-tags.php file is where you should place all generic template related functions. \n * \n * Created On: {$date} \n */"
                ),
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'inc/functions.php',
                    'desc' => "<?php\n/** \n * This file is automatically created for you. \n * The functions.php file is where you should place all generic misc. functions. \n * \n * Created On: {$date} \n */"
                ),
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'inc/css/style.css',
                    'desc' => "<?php\n/** \n * This file is automatically created for you. \n * The functions.php file is where you should place all generic misc. functions. \n * \n * Created On: {$date} \n */"
                ),
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'inc/js/script.js',
                    'desc' => "<?php\n/** \n * This file is automatically created for you. \n * The functions.php file is where you should place all generic misc. functions. \n * \n * Created On: {$date} \n */"
                ),
                array(
                    'file' => plugin_dir_path( __FILE__ ) . 'readme.txt',
                    'desc' => "\n=== My Plugin Name ===\n\nContributors:\nDonate link:\nTags:\nRequires at least:\nTested up to:\nStable tag:\nLicense:\nLicense URI:\n\n== Description ==\n\n== Installation ==\n\n== Frequently Asked Questions ==\n\n== Screenshots ==\n\n== Changelog =="
                )
          );

    foreach( $files as $file ){
        if ( ! file_exists( $file['file'] ) ){
            wp_mkdir_p( dirname( $file['file'] ) );

            @file_put_contents( $file['file'], $file['desc'] );
        }
    }
};
register_activation_hook( __FILE__, $activate_fn );


/**
 * Shared functions between admin and theme
 */
if ( file_exists( plugin_dir_path( __FILE__ ) . 'inc/functions.php' ) )
    require_once plugin_dir_path( __FILE__ ) . 'inc/functions.php';


/**
 * Admin only functions
 */
if ( is_admin() && file_exists( plugin_dir_path( __FILE__ ) . 'admin/admin-tags.php' ) )
    require_once plugin_dir_path( __FILE__ ) . 'admin/admin-tags.php';


/**
 * Theme only functions
 */
if ( file_exists( plugin_dir_path( __FILE__ ) . 'inc/template-tags.php' ) )
    require_once plugin_dir_path( __FILE__ ) . 'inc/template-tags.php';

$enqueue_scripts_fn = function(){
    $plugin_headers = get_file_data( __FILE__, array( 'Version' => 'Version', 'Name' => 'Plugin Name' ) );

    $clean_name = strtolower( str_replace( ' ', '-', $plugin_headers['Name'] ) );
    wp_register_style( $clean_name . '-style', plugin_dir_url( __FILE__ ) . 'inc/css/style.css' );
    wp_register_script( $clean_name . '-script', plugin_dir_url( __FILE__ ) . 'inc/js/script.js' );
};
add_action('wp_enqueue_scripts', $enqueue_scripts_fn, 2);
