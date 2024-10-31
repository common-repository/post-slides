<?php

	function post_slides_slides_default($skin_attrs) {

		wp_register_style( 'font-awesome', POSTSLIDES_URL . 'font-awesome/css/font-awesome.min.css');
		wp_enqueue_style( 'font-awesome');
		wp_register_style( 'skin-default', POSTSLIDES_URL . 'skins/css/skin-default.css');
		wp_enqueue_style( 'skin-default');

		/* ----------------------------------
		Available settings and shortcode attr
		To be used in the skinning process

		$GLOBALS['global_skin_attrs_default']['categories']
		$GLOBALS['global_skin_attrs_default']['posts']
		$GLOBALS['global_skin_attrs_default']['excerpt_length']
		$GLOBALS['global_skin_attrs_default']['excerpt_tail']
		$GLOBALS['global_skin_attrs_default']['skin']
		$GLOBALS['global_skin_attrs_default']['thumbnails']
		$GLOBALS['global_skin_attrs_default']['category_key']
		$GLOBALS['global_skin_attrs_default']['icons']
		$GLOBALS['global_skin_attrs_default']['loop']
		$GLOBALS['global_skin_attrs_default']['center']
		$GLOBALS['global_skin_attrs_default']['margin']
		$GLOBALS['global_skin_attrs_default']['nav']
		$GLOBALS['global_skin_attrs_default']['dots']
		$GLOBALS['global_skin_attrs_default']['autoplay']
		$GLOBALS['global_skin_attrs_default']['stage_padding']
		$GLOBALS['global_skin_attrs_default']['autoplay_timeout']
		$GLOBALS['global_skin_attrs_default']['autoplay_hover_pause']
		$GLOBALS['global_skin_attrs_default']['query']
		---------------------------------- */

		// Variable Reset
		$output = '';
		$temp_title = '';
		$temp_link = '';
		$post_counter = 0;

		// Run the query
		$the_query = new WP_Query($skin_attrs['query']);

		// the loop
		if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

			// Get Post Elements
			$temp_title = get_the_title();
			$temp_link = get_permalink();
			$temp_excerpt = get_the_excerpt();
			$theID = get_the_ID();
			$theDate = get_the_date();
			$theComments = get_comments_number();

			// Truncates the excerpt length (Not using default wordpress for plugin conflicts)
			$words = explode(' ', $temp_excerpt);
			if (count($words) > $GLOBALS['global_skin_attrs_default']['excerpt_length']){
				array_splice($words, $GLOBALS['global_skin_attrs_default']['excerpt_length']);
				$temp_excerpt = implode(' ', $words);
				$temp_excerpt .= $GLOBALS['global_skin_attrs_default']['excerpt_tail'];
			}

			// Grabs the categories then assigns the first category in the string to the class.
			$category = get_the_category();
			$category_name = $category[0]->cat_name;
			$category_slug = $category[0]->slug;

			// Recovering Saved Color Values
			// Define the Settings for recording
			$cat_var = "cat_".$category_slug;

			// Get's the category options which are the hexadecimal colors
			$cat_var_key_val = get_option($cat_var);

			// Get the thumbnail
			if ( $skin_attrs['thumbnails'] == 'true') {
				if ( has_post_thumbnail()) {
						// Retrieve the featured image.
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
					// Strip featured image down to the url.
					$thumbnail = '<div class="image" style="background: url('.$thumb['0'].') center center no-repeat; background-size: cover;"></div>';
				} else {
					$thumbnail = '';
				}
			} else {
				$thumbnail = '';
			}

			if ( $skin_attrs['icons'] == 'true') {
				$icon = '<div class="slide-icon" style="background-color: '.$cat_var_key_val.';"><i class="fa fa-angle-right"></i></div>';
			} else {
				$icon = '';
			}

			// Each output creates the li > link > title > exerpt
			$output .= '<div class="skin-default item '.$cat_var.'" id="'.$theID.'" style="background-color: '.$cat_var_key_val.';">
										'.$thumbnail.'
											<slide>
												<div class="slide-content">
													<h2>'.$temp_title.'</h2>
													<p>
														<span>'.$temp_excerpt.'</span>
													</p>
												</div>
												'.$icon.'
												<a href='.$temp_link.'>View more</a>
											</slide>
										</div>';

		endwhile; else:

			$output .= "<strong>No posts available.</strong> Double check your the post-slides shortcode, selected categories, and number of posts.";

		endif;


		// Return the slides
		return $output;

		// Reset the query so it doesn't interupt the normal query
		wp_reset_query();

	}

	function post_slides_categories_default($category_attrs) {

		//List categories
		$categories = get_categories($category_attrs['categories']);
		// Set the key_items as an empty variable
		$key_items = '';
		//Loops through each category and displays key and color.
		foreach($categories as $category) {
				// Set's category names
				$cat_var = $category->name;
				// Sets the slug
				$cat_slug = $category->slug;
				// Cleans up category names that have spaces
				$cat_var_key = "cat_".$cat_slug;
				// Get's the category options which are the hexadecimal colors
				$cat_var_key_val = get_option($cat_var_key);
				// loops through the each category and prints them
				$key_items .= "<a href='#' class='key' id='".$cat_var_key."' style='background-color:".$cat_var_key_val.";'>".$cat_var."</a>\n";
		}

		// Creates the finished key
		$key_finished = "<div id='category-key' class='skin-default'>\n".$key_items."<a href='#' class='key' id='category-all'>All</a></div>\n\n";

		// Return the categories
		return $key_finished;

	}

		// Call the following js for the footer. This insures jquery is loaded
		function post_slides_footer_default() {

				?>
					<script>
						(function($) {
							$(document).ready(function() {
								$('#ps.default').animate({opacity: 1}, 500); // Fade in gallery to skip slide flashing
								$('.post-slides.default').owlCarousel({

									// See Owl's Custimization documentation - http://owlgraphic.com/owlcarousel/#customizing

										loop: <?= $GLOBALS['global_skin_attrs_default']['loop']; ?>,
										center: <?= $GLOBALS['global_skin_attrs_default']['center']; ?>,

										margin: <?= $GLOBALS['global_skin_attrs_default']['margin']; ?>,
										stagePadding: <?= $GLOBALS['global_skin_attrs_default']['stage_padding']; ?>,

										nav: <?= $GLOBALS['global_skin_attrs_default']['nav']; ?>,
										dots: <?= $GLOBALS['global_skin_attrs_default']['dots']; ?>,

										autoplay: <?= $GLOBALS['global_skin_attrs_default']['autoplay']; ?>,
										autoplayTimeout: <?= $GLOBALS['global_skin_attrs_default']['autoplay_timeout']; ?>,
										autoplayHoverPause: <?= $GLOBALS['global_skin_attrs_default']['autoplay_hover_pause']; ?>,

										// Fixed Settings
										mouseDrag: true,
										touchDrag: true,
										pullDrag: true,
										freeDrag: false,

										merge: false,
										mergeFit: true,
										autoWidth: false,

										startPosition: 0,
										rtl: false,

										smartSpeed: 250,
										fluidSpeed: false,
										dragEndSpeed: false,

										responsive:{
											<?= $GLOBALS['global_skin_attrs_default']['min_slide_width_calc']; ?>
										},
										responsiveRefreshRate: 200,
										responsiveBaseElement: '.post-slides.default',
										responsiveClass: false,

										fallbackEasing: 'swing',

										info: false,

										nestedItemSelector: false,
										itemElement: 'div',
										stageElement: 'div',

										// Classes and Names
										themeClass: 'owl-theme',
										baseClass: 'owl-carousel',
										itemClass: 'owl-item',
										centerClass: 'center',
										activeClass: 'active'

								});
								$('.key').on('click', function(e){
									e.preventDefault();
								});
							}); // End Document Ready
						})(jQuery);
					</script>
				<?php
		}
		add_action('wp_footer', 'post_slides_footer_default');
		add_action('admin_print_footer_scripts', 'post_slides_footer_default');

?>
