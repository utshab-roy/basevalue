<?php
/**
 * Plugin Name: Dashboard Widget
 * Plugin URI: http://www.github.com/utshab-roy
 * description: a plugin that create custom post type of to-do list and shows those to the admin dashboard section
 * Version: 1.0.0
 * Author: Mr. Utshab Roy
 * Author URI: http://www.github.com/utshab-roy
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class CBXDashboardWidget{

	public function __construct() {
		//adding the custom dashboard notice_board
		add_action( 'wp_dashboard_setup', array($this, 'noticeboard_add_dashboard_widgets' ) );

		add_action('wp_dashboard_setup', array($this, 'example_remove_dashboard_widget' ) );
//		add_action( 'admin_menu', array($this, 'example_remove_dashboard_widget' ) );

		add_action('init', array($this, 'create_cbxnotice_custom_post_type'));

		//adding JS file to admin panel
		add_action( 'admin_enqueue_scripts', array($this, 'basevalue_admin_script') );

		//adding custom metabox for the CBXNotice
		add_action('add_meta_boxes', array($this, 'cbx_notice_add_custom_box'));

		//saving the cbxnotice metabox value
		add_action('save_post', array($this, 'cbxnotice_save_postdata'));
	}


	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	function noticeboard_add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'notice_board',         // Widget slug.
			'Admin Notice Board',         // Title.
			array($this, 'notice_dashboard_widget_function') // Display function.
		);
	}

	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	function notice_dashboard_widget_function() {
//		global $wp_meta_boxes;
//		echo '<pre>';
//		var_dump($wp_meta_boxes['dashboard']['normal']['sorted']["notice_board"]);
//		echo '</pre>';
		// Display whatever it is you want to show.

		global $post;
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => 'cbxnotice',
			'order'          => 'ASC',
			'post_status'    => 'publish',

		);
		$posts_array = get_posts( $args );

//		$value = get_post_meta($posts_array['ID'], 'cbxnotice_role_meta_key', true);
//		var_dump($posts_array);
//		die();

		if ( $posts_array ) {
			foreach ( $posts_array as $post ) :
				$cbxnotice_role = get_post_meta( $post->ID, 'cbxnotice_role_meta_key', true );
//				var_dump($cbxnotice_role);
//				echo '</br>';
				$user              = wp_get_current_user();
				$current_user_role = $user->roles;
//				var_dump($current_user_role);
				setup_postdata( $post ); ?>
				<?php
				if ( in_array( $cbxnotice_role, $current_user_role ) ):
					?>
                    <li class="title-notice"><?php the_title(); ?></li>

                    <div style="display: none;" class="content-notice"><?php the_content(); ?></div>

				<?php
				endif;
			endforeach;

			?>
            <div id="cbx_notice_dialog"></div>
			<?php

			wp_reset_postdata();
		}
	}

	// Removing all the default widgets using 'wp_dashboard_setup' hook
	function example_remove_dashboard_widget() {
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
//		remove_meta_box( 'notice_board', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	}

	// Creating cbxnotice post type
	function create_cbxnotice_custom_post_type(){
		register_post_type(
			'cbxnotice',
			array(
				'labels'             => array(
					'name'          => __( 'CBXNotice' ),
					'singular_name' => __( 'CBXNotice' ),
				),
				'public'             => true,
				'has_archive'        => true,
				'rewrite'            => array( 'slug' => 'notice' ), // my custom slug
				'publicly_queryable' => false,
			)
		);
	}

	function basevalue_admin_script($hook){
		if ($hook != 'index.php'){
			return;
		}
		wp_enqueue_script('main', plugin_dir_url( __FILE__ ). 'main.js', array('jquery'), '1.0.0', true);

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
	}

	//adding metabox for the cbxnotice post type
    function cbx_notice_add_custom_box(){
	    $screens = ['cbxnotice'];
	    foreach ($screens as $screen) {
		    add_meta_box(
			    'cbxnotice_box_id',           // Unique ID
			    'Notice assigned for the role',  // Box title
			    array($this, 'cbxnotice_custom_box_html'),  // Content callback, must be of type callable
			    $screen                   // Post type
		    );
	    }
    }

    function cbxnotice_custom_box_html($post){
	    $value = get_post_meta($post->ID, 'cbxnotice_role_meta_key', true);
	    ?>

        <label for="cbxnotice_role">Who will see the notice:</label>
        <select name="cbxnotice_role" id="cbxnotice_role" >


<!--            <option value="something" --><?php //selected($value, 'something'); ?><!-->Something</option>-->

            <?php $roles = get_editable_roles();?>
            <?php foreach ($roles as  $key => $role_details):?>
            <option value="<?php echo $key; ?>" <?php selected($value, "$key"); ?>><?php echo $key;?></option>
		    <?php endforeach;?>


<!--            <option value="else" --><?php //selected($value, 'else'); ?><!-->Else</option>-->
        </select>
	    <?php
    }

	function cbxnotice_save_postdata($post_id)
	{
		if (array_key_exists('cbxnotice_role', $_POST)) {
			update_post_meta(
				$post_id,
				'cbxnotice_role_meta_key',
				$_POST['cbxnotice_role']
			);
		}
	}




}//end of CBXDashboardWidget class

/**
 * Load Plugin when all plugins loaded
 *
 * @return void
 */
function cbxdashboardwidget_load_plugin() {
	new CBXDashboardWidget();
}

add_action( 'plugins_loaded', 'cbxdashboardwidget_load_plugin', 5 );
