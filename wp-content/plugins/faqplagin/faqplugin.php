<?php
/**
Plugin Name: FAQ Plugin
Plugin URI: http://www.google.com
description: a plugin that create custom post type of FAQ and shows those
Version: 1.0
Author: Mr. Utshab Roy
Author URI: http://www.github.com/utshab-roy
License: GPL2
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function frequently_asked_questions(){
    ob_start();

	global $post;
	$args = array(
		'posts_per_page' => 5,
		'post_type'      => 'cbxfaq',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	);
    $posts_array = get_posts( $args );


    if ( $posts_array ) {
        foreach ( $posts_array as $post ) :
            setup_postdata( $post ); ?>
            <h3><?php the_title(); ?></h3>
            <?php the_content(); ?>
        <?php
        endforeach;
        wp_reset_postdata();
    }

    $faq_posts = ob_get_contents();
    ob_end_clean();

    return $faq_posts;
}

add_shortcode('showfaq', 'frequently_asked_questions');

//creating custom post type for FAQ
function faq_custom_post_type()
{
    register_post_type(
        'cbxfaq',
	    array(
		    'labels'             => array(
			    'name'          => __( 'FAQ' ),
			    'singular_name' => __( 'FAQ' ),
		    ),
		    'public'             => true,
		    'has_archive'        => true,
		    'rewrite'            => array( 'slug' => 'faq' ), // my custom slug
		    'publicly_queryable' => false,
	    )
    );
}
add_action('init', 'faq_custom_post_type');
