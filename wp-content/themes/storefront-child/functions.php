<?php

function wpb_hook_javascript_footer() {
    if (is_page ('2')){
        echo "<h1>Hello</h1>";
    }
}

add_action('wp_body_open', 'wpb_hook_javascript_footer');



add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Store front theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

add_action('save_post', 'log_when_saved');
function log_when_saved($post_id) {

    if ( ! (wp_is_post_revision($post_id) ) || wp_is_post_autosave( $post_id ) ){
        return; 
    }
    // $post_log = get_stylesheet_directory() . '/post_log.txt';
    $post_log = get_theme_root() . '/post_log.txt';
    $message = get_the_title($post_id) . ' was just saved!';

    if ( file_exists ($post_log)){

        $file = fopen( $post_log, 'a');
        fwrite($file, $message."\n");

    } 
    else {
        $file = fopen( $post_log, 'w');
        fwrite($file, $message."\n");
    }

    fclose($file);
}

add_action('template_redirect', 'members_only');
function members_only(){

        if( is_page('super-secret') && ! is_user_logged_in() ){
            do_action('user_redirected', date("F j, Y, g:i a"));
            wp_redirect( home_url() );
            die();
        }
}

add_action('user_redirected', 'log_when_accessed');
function log_when_accessed($date){
    $post_log = get_theme_root() . '/post_log.txt';
    $message = 'Someone just tried to access our super secret page on ' . $date;

    if ( file_exists ($post_log)){

        $file = fopen( $post_log, 'a');
        fwrite($file, $message."\n");

    } 
    else {
        $file = fopen( $post_log, 'w');
        fwrite($file, $message."\n");
    }

    fclose($file);

}

//WooCommerce https://www.youtube.com/watch?v=haHlrf2BiQE&list=PLxcHNQye0eOmv4-TIKtNAJbovRf3nEsJu
add_filter ('woocommerce_product_tabs', 'woo_remove_product_tabs');
function woo_remove_product_tabs($tabs){
    // print_r($tabs);
    unset($tabs['reviews']);
    return $tabs;
   
}

// add_filter ('woocommerce_product_tabs', 'woo_add_product_tabs');
// function woo_add_product_tabs($tabs){
//     echo 'Hello';
//     exit;
   
// }

