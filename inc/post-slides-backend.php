<?php

// Adds the function for the admin menu
add_action('admin_menu', 'tc_post_slides_menu');
// Creates the admin page and title
function tc_post_slides_menu() {
	add_options_page('Post Slides', 'Post Slides', 'manage_options', 'post-slides-identifier', 'tc_post_slides_options');
}

// Adds function for declaring options
add_action( 'admin_init', 'register_post_slides_options' );
function register_post_slides_options(){

	// Registers Non Category Related Settings (it's outside the categories loop)
	register_setting( 'tc-plugin-settings', 'category_key');
	register_setting( 'tc-plugin-settings', 'excerpt_length');
	register_setting( 'tc-plugin-settings', 'excerpt_tail');
	register_setting( 'tc-plugin-settings', 'active_skin');
	register_setting( 'tc-plugin-settings', 'min_slide_width');
	register_setting( 'tc-plugin-settings', 'min_slide_width_calc');
	// Owl Specific Settings
	register_setting( 'tc-plugin-settings', 'loop');
	register_setting( 'tc-plugin-settings', 'center');
	register_setting( 'tc-plugin-settings', 'margin');
	register_setting( 'tc-plugin-settings', 'stage_padding');
	register_setting( 'tc-plugin-settings', 'nav');
	register_setting( 'tc-plugin-settings', 'dots');
	register_setting( 'tc-plugin-settings', 'autoplay');
	register_setting( 'tc-plugin-settings', 'autoplay_timeout');
	register_setting( 'tc-plugin-settings', 'autoplay_hover_pause');


	// Get all Post Categories
	$categories = get_categories();

	//Loop Through Each Category
	foreach($categories as $category) {
		// Get category slug incase of special characters
		$cat_slug = "cat_".$category->slug;
		// Creates a registered setting for each category.
		register_setting('tc-plugin-settings', $cat_slug);
	}
}

// Adds the color picker
add_action('init', 'load_skin_scripts');
function load_skin_scripts() {
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
}

function tc_post_slides_options() {

	// Checks to make sure the viewer is the admin
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	// Start the page output
	// Get the plugin Url to attatch CSS and images etc.
	$plugin_url = plugins_url()."/post-slides";
	wp_enqueue_style( 'post-slides', $plugin_url.'/post-slides.css' , false );
	wp_enqueue_script('post-slides', $plugin_url.'/post-slides.js' );

	// Retrieve additional options before the categories loop
	$show_key = get_option('category_key');
	if(empty($show_key)){
		$show_key = "false";
	}
	$pagination_key = get_option('pagination-key');
	if(empty($pagination_key)){
		$pagination_key = "bottom";
	}
	$min_slide_width = get_option('min_slide_width');
	if(empty($min_slide_width)){
		$min_slide_width = "350";
	}
	$min_slide_width_calc = get_option('min_slide_width_calc');
	if(empty($min_slide_width_calc)){
		$min_slide_width_calc = "0 : {items:1}, 700 : {items:2}, 1050 : {items:3}, 1400 : {items:4}, 1750 : {items:5}, 2100 : {items:6}";
	}
	$excerpt_length = get_option('excerpt_length');
	if(empty($excerpt_length)){
		$excerpt_length = "12";
	}
	$excerpt_tail = get_option('excerpt_tail');
	if(empty($excerpt_tail)){
		$excerpt_tail = "&hellip;";
	}
	$active_skin = get_option('active_skin');
	if(empty($active_skin)){
		$active_skin = "Wanderlust";
	}
	// Owl default settings
	$loop = get_option('loop');
	if(empty($loop)){
		$loop = "false";
	}
	$center = get_option('center');
	if(empty($center)){
		$center = "false";
	}
	$margin = get_option('margin');
	if(empty($margin)){
		$margin = "15";
	}
	$stage_padding = get_option('stage_padding');
	if(empty($stage_padding)){
		$stage_padding = "0";
	}
	$nav = get_option('nav');
	if(empty($nav)){
		$nav = "false";
	}
	$dots = get_option('dots');
	if(empty($dots)){
		$dots = "true";
	}
	$autoplay = get_option('autoplay');
	if(empty($autoplay)){
		$autoplay = "false";
	}
	$autoplay_timeout = get_option('autoplay_timeout');
	if(empty($autoplay_timeout)){
		$autoplay_timeout = "5000";
	}
	$autoplay_hover_pause = get_option('autoplay_hover_pause');
	if(empty($autoplay_hover_pause)){
		$autoplay_hover_pause = "false";
	}
	?>

	<div class="wrap post-slides-admin">
		<form method='POST' action='options.php' id="post-slides-form">

		<div class="intro">
			<h2 class="intro-header">Post Slides</h2>
				<div class="intro-section">
					<?php
					// Loop through the skins
					$skins = array();
					if ($getskins = opendir(POSTSLIDES_PATH . '/skins')) {
						// As long as this directory isn't false
						while (false !== ($skin = readdir($getskins))) {
							if ($skin != "." && $skin != "..") {
								// Check if is the css folder
								if ($skin != 'css') {
									// Strip out the .php
									$skin = str_replace('.php', '', $skin);
									// Add the skins to the array
									array_push($skins, $skin);
								}
							}
						}
						// Close the directory
						closedir($getskins);
					}
					?>
					<div class="select-skin">
						Select skin <a href="http://www.postslides.com/skins" target="_blank" title="Learn about skins">?</a>
						<select name="active_skin">
						<?php
							//Loop through skins
							foreach ($skins as $skin) {
								// Make the skin names nice
								$skinName = ucwords(str_replace('-', ' ', $skin));
								if ($active_skin == $skin) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								// Echo out the skin options
								echo '<option value="'. $skin .'" '. $selected .'>' . $skinName . '</option>';
							}
						?>
						</select>
				</div>
				<p class="intro-paragraph">Add the shortcode <span class="shortcode-example">[post-slides]</span> to your page. <a href="#settings-anchor">Configure settings below.</a></p>
			</div>
			<? echo do_shortcode( '[post-slides posts="6"]' ); ?>
			<span id="settings-anchor"> <span>
		</div>

	<div id='color-container'>
		<span class="cat-id">Cat ID</span>
		<h3>Category Slide Colors</h3>
			<div class="color-wrapper">
			<div id='picker'></div>

			<table border="0">
				<tbody>
					<?php
					// Define the settings for recording
					settings_fields('tc-plugin-settings');

					// Get all Post Categories
					$categories = get_categories();

					//Loops through each category and displays color inputs.
					foreach($categories as $category) {
						$cat_var = "cat_".$category->name;
						$cat_slug = "cat_".$category->slug;
						// Make lowercase
						$cat_var = strtolower($cat_var);
						$cat_var = str_replace(" ", "_", $cat_var);
						$cat = $category->name;
						$id = $category->cat_ID;
						// Retrieves option value
						$cat_var_value = get_option($cat_slug);
						// Checks the value to see if it's empty. If it is use default.
						if (empty($cat_var_value)){
							$cat_var_value = "#afe2f8";
						}

						// Echo out each list Category Name > Id > Input
						echo "<tr><td><input type='text' class='colorwell' name='".$cat_slug."' value='".$cat_var_value."' /><strong>".$cat." </strong> <em class='category-id'>".$id."</em></td>
						</tr>";
					}
					?>
				</tbody>
		  </table>
		</div>
	</div>

	<div class="global-settings">
		<table border="0" id="general-settings">
			<tbody>
				<tr>
					<th>General</th>
					<th></th>
				</tr>
				<tr>
					<td>
						Excerpt:
					</td>
					<td>
						<input type='text' name='excerpt_length' class='small-input' value='<?= $excerpt_length; ?>' />
					</td>
				</tr>
				<tr>
					<td>
						Excerpt Tail:
					</td>
					<td>
						<input type='text' name='excerpt_tail' class='small-input' value='<?= $excerpt_tail; ?>' />
					</td>
				</tr>
				<tr>
					<td>
						Loop:
					</td>
					<td>
						<input type="radio" name="loop" value="true" <?php if($loop == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="loop" value="false" <?php if($loop == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
				<tr>
					<td>
						Center:
					</td>
					<td>
						<input type="radio" name="center" value="true" <?php if($center == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="center" value="false" <?php if($center == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
				<tr>
					<td>
						Margin:
					</td>
					<td>
						<input type="text" name="margin" value="<?= $margin; ?>" class='small-input'><span class="px">px</span>
					</td>
				</tr>
				<tr>
					<td>
						Stage Padding:
					</td>
					<td>
						<input type="text" name="stage_padding" value="<?= $stage_padding; ?>" class='small-input'><span class="px">px</span>
					</td>
				</tr>
				<tr class="min-slide-width">
					<td>
						Min Slide Width:
					</td>
					<td>
						<input type="text" name="min_slide_width" value="<?= $min_slide_width; ?>" class='small-input'><span class="px">px</span>
					</td>
				</tr>
				<tr class="min-slide-width-calc">
					<td colspan="2">
						<div>
							Number of slides (items) per width
							<em><?= $min_slide_width_calc; ?></em>
							<input type="hidden" name="min_slide_width_calc" value="<?= $min_slide_width_calc; ?>" class='small-input'>
						</div>
					</td>
				</tr>
			</tbody>
		</table>


		<table>
			<tbody>
				<tr>
					<th>Navigation</th>
					<th></th>
				</tr>
				<tr>
					<td>Nav:</td>
					<td>
						<input type="radio" name="nav" value="true" <?php if($nav == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="nav" value="false" <?php if($nav == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
				<tr>
					<td>Dots:</td>
					<td>
						<input type="radio" name="dots" value="true" <?php if($dots == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="dots" value="false" <?php if($dots == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<th>Auto Play</th>
					<th></th>
				</tr>
				<tr>
					<td>Autoplay:</td>
					<td>
						<input type="radio" name="autoplay" value="true" <?php if($autoplay == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="autoplay" value="false" <?php if($autoplay == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
				<tr>
					<td>Autoplay Timeout:</td>
					<td>
						<input type="text" name="autoplay_timeout" value="<?= $autoplay_timeout; ?>" class='small-input'>
					</td>
				</tr>
				<tr>
					<td>Autoplay Hover Pause:</td>
					<td>
						<input type="radio" name="autoplay_hover_pause" value="true" <?php if($autoplay_hover_pause == 'true') echo 'checked="checked"'; ?>>true
						<input type="radio" name="autoplay_hover_pause" value="false" <?php if($autoplay_hover_pause == 'false') echo 'checked="checked"'; ?>>false
					</td>
				</tr>
			</tvody>
		</table>
		<div class="submit-wrapper">
			<input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>'/>
		</div>

	</div>

	<div class="shortcode-tips">
		<h3>Shortcode</h3>
		<ul>
			<li>1.) To add posts slides to a page or post copy and paste the following shortcode. Example: <strong>[post-slides]</strong></li>
			<li>2.) By default 8 posts are displayed. To change the amount of posts to display, use the <strong>posts=' '</strong> attribute in the shortcode. Example: <strong>[post-slides posts='10']</strong></li>
			<li>3.) By default all post categories are called for the slides. To specify the categories use the <strong>categories=' '</strong> attribute separating them by commas. Example: <strong>[post-slides categories='1,2,4']</strong></li>
			<li><div class='tc-note'><em><strong>NOTE:</strong> The category id numbers are listed above, to the right of the category names. You can use both the categories and posts attributes Example: <strong>[post-slides categories='1,2,4' posts='8']</strong></em></div></li>
		</ul>
	</div>

	<div id='donate-box'>
		<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
		<input type='hidden' name='cmd' value='_s-xclick'>
		<input type='hidden' name='hosted_button_id' value='HH7L4BWHALHLA'>
		<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
		<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
		</form>
		<h3>Consider Donating</h3>
		<em>Consider buying me a cup of coffee for the time dedicated to the development of Post Slides.</em>
	</div>

	<div class='posttiles-footer'>The Post slides Wordpress plugin was created by <a href="http://www.ethanhackett.com" target="_blank" title="Designed and Developed by Ethan Hackett www.ethanhackett.com">Ethan Hackett</a>. Also checkout the <a href="http://www.posttiles.com" target="_blank">Post Tiles</a> plugin.</div>

		</form>
	</div> <!-- End .wrap -->
	<?php
}
// End Admin Page
?>
