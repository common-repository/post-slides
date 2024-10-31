<?php
/*
Plugin Name: Post slides
Plugin URI: http://www.postslides.com
Description: Post Slides displays posts in a skinnable Owl carousel. Example shortcode: [post-slides] or [post-slides categories='1,2,4,10' posts='8' excerpt='18'].
Author: Ethan Hackett
Version: 1.0.1
Author URI: http://www.postslides.com

Copyright (C) 2012 Ethan Hackett

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
// Checks to See Wordpress Version
global $wp_version;

// If it's not compatable with wordpress version 3.0 die and display message.
if ( !version_compare($wp_version,"3.0",">=") )
{
	die("<h2>You need at lease version 3.0 of Wordpress to use the TinCrate plugin.</h2><a href='http:www/wordpress.org' target='_blank'>Visit Wordpress.org to upgrade your current version of Wordpress.");
}

// Set global variables for including files
define( 'POSTSLIDES_PATH', plugin_dir_path( __FILE__ ) );
define( 'POSTSLIDES_URL', plugin_dir_url( __FILE__ ) );

// Include Admin Settings Page
require_once( POSTSLIDES_PATH . 'inc/post-slides-backend.php');

// Include Frontend Resources
require_once( POSTSLIDES_PATH . 'inc/post-slides-frontend.php');
?>
