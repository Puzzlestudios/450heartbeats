<?php
/**
 * Child theme functions and definitions
 *
 * @package HeartbeatsChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HEARTBEATS_CHILD_BASE',     __DIR__ );
define( 'HEARTBEATS_CHILD_BASE_URL', get_stylesheet_directory_uri() );
define( 'HEARTBEATS_CHILD_INC_PATH', HEARTBEATS_CHILD_BASE . '/includes' );

define( 'HEARTBEATS_CHILD_ELEMENTOR_URL', HEARTBEATS_CHILD_BASE_URL . '/includes/elementor/widgets' );

define( 'HEARTBEATS_CHILD_ASSETS',   HEARTBEATS_CHILD_BASE . '/assets' );

add_action( 'wp_enqueue_scripts', 'child_scripts', 11 );
function child_scripts() {

    wp_enqueue_style( 'HEARTBEATS_CHILD_STYLE', get_stylesheet_directory_uri() .'/style.css' );
    wp_enqueue_script( 'HEARTBEATS_CHILD_FUNCTIONS', get_stylesheet_directory_uri() .'/assets/js/functions.js' , array('jquery') );

    /**
     * Add custom files
     */
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js' , array('jquery') );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js' , array('gsap') );
    wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js' , array('jquery') );

    wp_enqueue_script( 'gsap_utils', get_stylesheet_directory_uri() .'/assets/js/gsap_utils.js' , array('gsap-scrolltrigger') );
}

/**
 * Initialize child theme
 */
require HEARTBEATS_CHILD_INC_PATH . '/theme.php';


/********************************************************************************************************************************************************************************************************
 *
 * Custom functions.php codes goes beyond this line
 *
 ********************************************************************************************************************************************************************************************************/


add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
function add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( '450hb-script' != $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}
add_filter( 'big_image_size_threshold', '__return_false' );


function reading_time() {
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    $readingtime = ceil($word_count / 260);
    if ($readingtime == 1) {
        $timer = " Minute Lesezeit";
    } else {
        $timer = " Minuten Lesezeit";
    }
    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}
add_shortcode('wpbread', 'reading_time');

/**
 * Will shorten a post_tile to a given length
 * @author Felix
 *
 * @param WP_OBJECT
 * @param INT
 *
 * @return STRING
 */
function post_title_shortened($post, $length = 30){
    $post_title = strlen($post->post_title) > $length ? mb_substr($post->post_title, 0, $length )."..." : $post->post_title;

    return htmlspecialchars(
        $post_title,
        ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
