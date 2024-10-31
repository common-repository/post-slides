<?php
/* ***************************** */
// Start Frontend Code
/* ***************************** */
// Sets the "tc_post_slides" function as a shortcode [tc_post_slides]
add_shortcode('post-slides','tc_post_slides');

// Runs Output
function tc_post_slides($atts,$content=null) {

	// Enqueue the owl carousel javascripts and styles
	wp_enqueue_script('owl-js', POSTSLIDES_URL . 'owl/owl.carousel.js', array('jquery'), null, true);
	wp_enqueue_style( 'post-slides', POSTSLIDES_URL . 'post-slides.css' , false );
	wp_enqueue_style( 'post-slides');
	wp_register_style( 'owl-css', POSTSLIDES_URL . 'owl/owl.carousel.css');
	wp_enqueue_style( 'owl-css');


	/* ---------------------- */
	/* Get and set the slide attributes */
		shortcode_atts( array('categories'=>'', 'posts'=>'', 'excerpt_length'=>'', 'excerpt_tail'=>'', 'skin'=>'', 'thumbnails'=>'', 'category_key'=>'', 'icons'=>'', 'loop'=>'', 'center'=>'', 'margin'=>'', 'stage_padding'=>'', 'nav'=>'', 'dots'=>'', 'autoplay'=>'', 'autoplay_timeout'=>'', 'autoplay_hover_pause'=>'' ), $atts);
		// Defaults
		extract(shortcode_atts(array(
		  "categories" => '',
		  "posts" => '',
		  "excerpt_length" => '',
			"excerpt_tail" => '',
			"skin" => '',
			"thumbnails" => '',
			"category_key" => '',
			"icons" => '',
			"loop" => '',
			"center" => '',
			"margin" => '',
			"stage_padding" => '',
			"nav" => '',
			"dots" => '',
			"autoplay" => '',
			"autoplay_timeout" => '',
			"autoplay_hover_pause" => ''
		), $atts));

		// Get and set the excerpt length
		if (isset($atts['excerpt_length'])) {
			$excerpt_length_attr =  $atts['excerpt_length'];
		} else if (get_option('excerpt_length') != false ) {
			$excerpt_length_attr = get_option('excerpt_length');
		}	else {
			$excerpt_length_attr = '12';
		}

		// Get and set the excerpt tail
		if (isset($atts['excerpt_tail'])) {
			$excerpt_tail_attr =  $atts['excerpt_tail'];
		} else if (get_option('excerpt_tail') != false ) {
			$excerpt_tail_attr = get_option('excerpt_tail');
		}	else {
			$excerpt_tail_attr = '&hellip;';
		}

		// Configure Categories For Query
		if (isset($atts['categories'])) {
			$cat_query = '&cat='.$atts['categories'];
			$cat_attr = $atts['categories'];
		} else {
			$cat_attr = '';
			$cat_query = '';
		}

		if (get_option('min_slide_width_calc') != false ) {
			$min_slide_width_calc = get_option('min_slide_width_calc');
		} else {
			$min_slide_width_calc = "0 : {items:1}, 700 : {items:2}, 1050 : {items:3}, 1400 : {items:4}, 1750 : {items:5}, 2100 : {items:6}";
		}

		// Set Category Key True/False
		if (isset($atts['category_key'])) {
			$category_key_attr =  $atts['category_key'];
		} else {
			$category_key_attr = 'true';
		}

		// Configure Number of Posts For Query if empty use 8.
		if (isset($atts['posts'])) {
			$posts_attr = $atts['posts'];
			$posts_query = 'posts_per_page='.$atts['posts'];
		} else {
			$posts_attr = '8';
			$posts_query = 'posts_per_page=8';
		}

		// Configure Categories For Query
		// Check shortcode, then global setting, use fallback
		if (isset($atts['skin'])) {
			$skin_attr = $atts['skin'];
		} else if (get_option('active_skin') != false ) {
			$skin_attr = get_option('active_skin');
		} else {
			$skin_attr = 'default';
		}


		// Set Theumbnails
		if (isset($atts['thumbnails'])) {
			$thumbnail_attr =  $atts['thumbnails'];
		} else {
			$thumbnail_attr = 'true';
		}

		// Set Category Key
		if (isset($atts['category_key'])) {
			$category_key_attr =  $atts['category_key'];
		} else {
			$category_key_attr = 'true';
		}

		// Set icons
		if (isset($atts['icons'])) {
			$icons_attr =  $atts['icons'];
		} else {
			$icons_attr = 'true';
		}

		// Owl Carousel Attr
		// Loop Owl Carousel
		if (isset($atts['loop'])) {
			$loop_attr = $atts['loop'];
		} else if (get_option('loop') != false ) {
			$loop_attr = get_option('loop');
		} else {
			$loop_attr = 'false';
		}

		// Center Carousel
		if (isset($atts['center'])) {
			$center_attr = $atts['center'];
		} else if (get_option('center') != false ) {
			$center_attr = get_option('center');
		} else {
			$center_attr = 'false';
		}

		// Slide Margin
		if (isset($atts['margin'])) {
			$margin_attr = $atts['margin'];
		} else if (get_option('margin') != false ) {
			$margin_attr = get_option('margin');
		} else {
			$margin_attr = '15';
		}

		// Carousel Padding
		if (isset($atts['stage_padding'])) {
			$stage_padding_attr = $atts['stage_padding'];
		} else if (get_option('stage_padding') != false ) {
			$stage_padding_attr = get_option('stage_padding');
		} else {
			$stage_padding_attr = '0';
		}

		// Carousel Nav
		if (isset($atts['nav'])) {
			$nav_attr = $atts['nav'];
		} else if (get_option('nav') != false ) {
			$nav_attr = get_option('nav');
		} else {
			$nav_attr = 'false';
		}

		// Carousel Dots
		if (isset($atts['dots'])) {
			$dots_attr = $atts['dots'];
		} else if (get_option('dots') != false ) {
			$dots_attr = get_option('dots');
		} else {
			$dots_attr = 'true';
		}

		// Carousel Autoplay
		if (isset($atts['autoplay'])) {
			$autoplay_attr = $atts['autoplay'];
		} else if (get_option('autoplay') != false ) {
			$autoplay_attr = get_option('autoplay');
		} else {
			$autoplay_attr = 'false';
		}

		// Carousel Autoplay
		if (isset($atts['autoplay_timeout'])) {
			$autoplay_timeout_attr = $atts['autoplay_timeout'];
		} else if (get_option('autoplay_timeout') != false ) {
			$autoplay_timeout_attr = get_option('autoplay_timeout');
		} else {
			$autoplay_timeout_attr = '5000';
		}

		// Carousel Autoplay
		if (isset($atts['autoplay_hover_pause'])) {
			$autoplay_hover_pause_attr = $atts['autoplay_hover_pause'];
		} else if (get_option('autoplay_hover_pause') != false ) {
			$autoplay_hover_pause_attr = get_option('autoplay_hover_pause');
		} else {
			$autoplay_hover_pause_attr = 'false';
		}

	/* ---------------------- */

	// Configure Both Categories and Number of Posts For Query
	$the_query = $posts_query.$cat_query;

	// Create the array for the skin now that we've retrieved and set all the slide attributes
	$skin_attrs = array(
		"categories" => $cat_attr,
		"posts" => $posts_attr,
		"excerpt_length" => $excerpt_length_attr,
		"excerpt_tail" => $excerpt_tail_attr,
		"skin" => $skin_attr,
		"thumbnails" => $thumbnail_attr,
		"category_key" => $category_key_attr,
		"icons" => $icons_attr,
		"loop" => $loop_attr,
		"center" => $center_attr,
		"margin" => $margin_attr,
		"stage_padding" => $stage_padding_attr,
		"min_slide_width_calc" => $min_slide_width_calc,
		"nav" => $nav_attr,
		"dots" => $dots_attr,
		"autoplay" => $autoplay_attr,
		"autoplay_timeout" => $autoplay_timeout_attr,
		"autoplay_hover_pause" => $autoplay_hover_pause_attr,
		"query" => $the_query
	);

	// Create a dynamic variable for this skin in case two or more skins are in use at the same time on a page
	global ${'global_skin_attrs_' . $skin_attr};
	${'global_skin_attrs_' . $skin_attr} = $skin_attrs;

	// Include the correct skin file
	$skin_url = POSTSLIDES_PATH . 'skins/'. $skin_attr .'.php';
	// If the file exists get it
	if (file_exists($skin_url)) {
		require($skin_url);
	} else {
		// If the active skin is missing fallback to default
		$skin_url = POSTSLIDES_PATH . 'skins/default.php';
		require($skin_url);
	}

	// Build the slides with a dynamic function name
	$slides_function = 'post_slides_slides_' . $skin_attr;
	$skin_output = $slides_function($skin_attrs);

	// Dissabled until solution for owl category sorting solution is found
	$category_key_attr = 'false';

	// Build the category key is set to true
	if ($category_key_attr == 'true') {
			// Create the category array to pass to the function
			$category_attrs = array('categories' => $cat_query);
		// Build the categories
		$categories_function = 'post_slides_categories_' . $skin_attr;
		$categories_output = $categories_function($category_attrs);
	} else {
		// If the key is disabled display an HTML comment in the code
		$categories_output = "<!-- Category Key is set to False. See the admin settings for Post Slides -->";
	}

	// Return the final Slides and Category Key Outputs
	return '<div id="post-slides-container">'.$categories_output.'<div class="post-slides '.$skin_attr.'" id="ps">'.$skin_output.'</div></div>';

}
?>
