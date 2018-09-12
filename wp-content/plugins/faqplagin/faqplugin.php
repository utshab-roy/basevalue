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

class CBXFaq{
    public function __construct() {

	    add_shortcode('showfaq', array($this, 'frequently_asked_questions'));

	    add_action('init', array($this, 'faq_custom_post_type'));

	    add_action( 'wp_enqueue_scripts', array($this, 'basevalue_scripts') );
    }

    //callable function for short-code
	function frequently_asked_questions(){
		ob_start();

		global $post;
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => 'cbxfaq',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);
		$posts_array = get_posts( $args );


		if ( $posts_array ) {
			foreach ( $posts_array as $post ) :
				setup_postdata( $post ); ?>
                <h4 class="title-faq"><?php the_title(); ?></h4>

				<div style="display: none;"><?php the_content(); ?></div>

			<?php
			endforeach;
			wp_reset_postdata();
		}

		$faq_posts = ob_get_contents();
		ob_end_clean();

		return $faq_posts;
	}

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

	// Add scripts and stylesheets
	function basevalue_scripts() {
		wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ). 'main.js', array( 'jquery' ), '3.3.6', true );
	}

}


/**
 * Load Plugin when all plugins loaded
 *
 * @return void
 */
function cbxfaq_load_plugin() {
	new CBXFaq();
}

add_action( 'plugins_loaded', 'cbxfaq_load_plugin', 5 );

