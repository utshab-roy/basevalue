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

	    //adding JS file to admin panel
	    add_action( 'admin_enqueue_scripts', array($this, 'basevalue_admin_script') );

	    /**
	     * register our wporg_options_page to the admin_menu action hook
	     */
	    add_action( 'admin_menu', array($this, 'wporg_options_page') );

	    /**
	     * register our wporg_settings_init to the admin_init action hook
	     */
	    add_action( 'admin_init', array($this, 'wporg_settings_init'));


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
//        var_dump($hook);
		wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ). 'main.js', array( 'jquery' ), '3.3.6', true );
	}

	// Add scripts and css for admin panel
	function basevalue_admin_script($hook){
//		var_dump($hook);
//        die();
		if ($hook != 'toplevel_page_wporg'){
			return;
		}
		wp_enqueue_script('main', plugin_dir_url( __FILE__ ). 'main.js', array('jquery', 'wp-color-picker'), '1.0.0', true);

		wp_enqueue_script( 'jquery-ui-datepicker' );


		wp_register_script( 'timepicker_js', 'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js' );
		wp_enqueue_script('timepicker_js');

		wp_register_style( 'timepicker_style', 'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css' );
		wp_enqueue_style('timepicker_style');

		wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
		wp_enqueue_style('jquery-ui-css');


		wp_enqueue_script('date-time-js', plugin_dir_url( __FILE__ ). 'jquery-ui-timepicker-addon.min.js', array('jquery'), '1.0.0', true);

		wp_register_style('date-time-css', plugin_dir_url( __FILE__ ). 'jquery-ui-timepicker-addon.min.css');
		wp_enqueue_style('date-time-css');

		//for color picker
		wp_enqueue_style( 'wp-color-picker' );
        // the script dependencies has been added to the main.js file. so that we can use color-picker
	}


	/**
	 * top level menu
	 */
	function wporg_options_page() {
		// add top level menu page
		add_menu_page(
			'WPOrg',
			'WPOrg Options',
			'manage_options',
			'wporg',
			array($this, 'wporg_options_page_html')
		);


		/**
		 * custom option and settings:
		 * callback functions
		 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
		function wporg_section_developers_cb( $args ) {
			?>
            <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
			<?php
		}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
        //dropdown
		function wporg_field_pill_cb( $args ) {
			// get the value of the setting we've registered with register_setting()
			$options = get_option( 'wporg_options' );

			?>

            <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
                    data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                    name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            >
                <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'red pill', 'wporg' ); ?>
                </option>
                <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'blue pill', 'wporg' ); ?>
                </option>
            </select>
            <p class="description">
				<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
            </p>
            <p class="description">
				<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
            </p>
			<?php
		}
        //text input
		function wporg_field_name_cb( $args ) {
			$options = get_option( 'wporg_options' );


			?>
            <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>

			<?php
		}
        //multiple checkbox
		function wporg_field_places_cb( $args ) {
			$options = get_option( 'wporg_options' );

			$wporg_field_place = isset( $options['wporg_field_place'] ) ? $options['wporg_field_place'] : array();


			echo '<pre>';
//			print_r( $wporg_field_place );
//			var_dump(my_get_option('radio','my_settings',''));
//			var_dump(get_option('wporg_options'));
			echo '</pre>';


			?>

            <input <?php echo in_array( 'dhaka', $wporg_field_place ) ? 'checked' : '' ?>
                    id="<?php echo $args['label_for'] ?>-dhaka" type='checkbox'
                    name="wporg_options[<?php echo $args['label_for'] ?>][]" value='dhaka'/> <label
                    for="<?php echo $args['label_for'] ?>-dhaka">Dhaka</label>

            <input <?php echo in_array( 'barisal', $wporg_field_place ) ? 'checked' : '' ?>
                    id="<?php echo $args['label_for'] ?>-barisal" type='checkbox'
                    name="wporg_options[<?php echo $args['label_for'] ?>][]" value='barisal'/> <label
                    for="<?php echo $args['label_for'] ?>-barisal">Barisal</label>

            <input <?php echo in_array( 'narail', $wporg_field_place ) ? 'checked' : '' ?>
                    id="<?php echo $args['label_for'] ?>-narail" type='checkbox'
                    name="wporg_options[<?php echo $args['label_for'] ?>][]" value='narail'/> <label
                    for="<?php echo $args['label_for'] ?>-narail">Narail</label>

			<?php
		}
        //textarea
		function wporg_field_blog_cb( $args ) {
			$options = get_option( 'wporg_options' );

//			echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>" rows="4" cols="50"><?php  esc_attr_e(trim($options[ $args['label_for'] ])); ?></textarea>

			<?php
		}
        //number field
		function wporg_field_age_cb( $args ) {
			$options = get_option( 'wporg_options' );

//			echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <input type="number" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>

			<?php
		}
		//radio button
		function wporg_field_shift_cb( $args ) {
			// get the value of the setting we've registered with register_setting()
			$options = get_option( 'wporg_options' );
//            echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <input type="radio" name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]-day" value="day" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 'day') ) : ( '' ); ?>/>
            <label for="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]-day">Day</label>
            <br/>
            <input type="radio" name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]-night" value="night" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 'night' ) ) : ( '' ); ?>/>
            <label for="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]-night">Night</label>

			<?php
		}
		//color field
		function wporg_field_color_cb( $args ) {
			$options = get_option( 'wporg_options' );

//            echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>

            <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>
			<?php
		}
		//date-picker using jQuery UI
		function wporg_field_date_cb( $args ) {
			$options = get_option( 'wporg_options' );

//            echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php if (isset($options[ $args['label_for'] ])) echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>

			<?php
		}
		//time-picker using timepicker.js
		function wporg_field_time_cb( $args ) {
			$options = get_option( 'wporg_options' );

//            echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php if (isset($options[ $args['label_for'] ])) echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>

			<?php
		}

		//time-picker using date-time picker
		function wporg_field_datetime_cb( $args ) {
			$options = get_option( 'wporg_options' );

//            echo '<pre>';
//			var_dump($options[ $args['label_for'] ]);
//			echo '</pre>';
			?>
            <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
                   data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
                   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]>"
                   value="<?php if (isset($options[ $args['label_for'] ])) echo esc_attr( $options[ $args['label_for'] ] ); ?>"/>

			<?php
		}

	}

	/**
	 * custom option and settings
	 */
	function wporg_settings_init() {
		// register a new setting for "wporg" page
		register_setting( 'wporg', 'wporg_options' );

		// register a new section in the "wporg" page
		add_settings_section(
			'wporg_section_developers',
			__( 'The Matrix has you.', 'wporg' ),
			'wporg_section_developers_cb',
			'wporg'
		);

		// register a new field in the "wporg_section_developers" section, inside the "wporg" page
        //input drop-down
		add_settings_field(
			'wporg_field_pill', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Pill', 'wporg' ),
			'wporg_field_pill_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_pill',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom',
			]
		);

		//add settings for input text fiend
		add_settings_field(
			'wporg_field_name', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Name', 'wporg' ),
			'wporg_field_name_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_name',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom name field',
			]
		);

		//add settings for checkbox for places
		add_settings_field(
			'wporg_field_place', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Placess', 'wporg' ),
			'wporg_field_places_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_place',
				'class'             => 'wporg_row'
			]
		);

		//add settings for textarea for blog
		add_settings_field(
			'wporg_field_blog', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Blog', 'wporg' ),
			'wporg_field_blog_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_blog',
				'class'             => 'wporg_row'
			]
		);

		//add settings for number field for age
		add_settings_field(
			'wporg_field_age', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Age', 'wporg' ),
			'wporg_field_age_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_age',
				'class'             => 'wporg_row'
			]
		);

		//add settings radio input for shift of work
		add_settings_field(
			'wporg_field_shift', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Shift of work', 'wporg' ),
			'wporg_field_shift_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_shift',
				'class'             => 'wporg_row'
			]
		);

		//add setting for color input
		add_settings_field(
			'wporg_field_color', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Favorite Color', 'wporg' ),
			'wporg_field_color_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_color',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom color field',
			]
		);

		//add setting for date input
		add_settings_field(
			'wporg_field_date', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Date', 'wporg' ),
			'wporg_field_date_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_date',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom name field',
			]
		);

		//add setting for time input
		add_settings_field(
			'wporg_field_time', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Time', 'wporg' ),
			'wporg_field_time_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_time',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom time field',
			]
		);

		//add setting for date-time input
		add_settings_field(
			'wporg_field_datetime', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Date and Time', 'wporg' ),
			'wporg_field_datetime_cb',
			'wporg',
			'wporg_section_developers',
			[
				'label_for'         => 'wporg_field_datetime',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom datetime field',
			]
		);
	}

	/**
	 * top level menu:
	 * callback functions
	 */
	function wporg_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// add error/update messages

		// check if the user have submitted the settings
		// wordpress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
		}

		// show error/update messages
		settings_errors( 'wporg_messages' );
		?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wporg"
				settings_fields( 'wporg' );
				// output setting sections and their fields
				// (sections are registered for "wporg", each field is registered to a specific section)
				do_settings_sections( 'wporg' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
            </form>
        </div>
		<?php
	}



}//end of CBXFaq class


/**
 * Load Plugin when all plugins loaded
 *
 * @return void
 */
function cbxfaq_load_plugin() {
	new CBXFaq();
}

add_action( 'plugins_loaded', 'cbxfaq_load_plugin', 5);

function my_get_option( $option, $section, $default = '' ) {

	$options = get_option( $section );

	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}

	return $default;
}


//die();

