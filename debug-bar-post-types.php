<?php
/*
Plugin Name: Debug Bar Post Types
Plugin URI: http://wordpress.org/extend/plugins/debug-bar-post-types/
Description: Debug Bar Post Types adds a new panel to the Debug Bar that displays detailed information about the registered post types for your site. Requires "Debug Bar" plugin.
Version: 1.0
Author: Juliette Reinders Folmer
Author URI: http://www.adviesenzo.nl/
Text Domain: debug-bar-post-types
Domain Path: /languages/

Copyright 2013 Juliette Reinders Folmer
*/
/*
GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Avoid direct calls to this file
if ( !function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Show admin notice & de-activate itself if debug-bar plugin not active
 */
add_action( 'admin_init', 'dbpt_has_parent_plugin' );

if ( !function_exists( 'dbpt_has_parent_plugin' ) ) {
	/**
	 * Check for parent plugin
	 */
	function dbpt_has_parent_plugin() {
		if ( is_admin() && ( !class_exists( 'Debug_Bar' ) && current_user_can( 'activate_plugins' ) ) ) {
			add_action( 'admin_notices', create_function( null, 'echo \'<div class="error"><p>\' . sprintf( __( \'Activation failed: Debug Bar must be activated to use the <strong>Debug Bar Post Types</strong> Plugin. <a href="%s">Visit your plugins page to activate</a>.\', \'debug-bar-post-types\' ), admin_url( \'plugins.php#debug-bar\' ) ) . \'</p></div>\';' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}
}



if ( !function_exists( 'debug_bar_post_types_panel' ) ) {
	/**
	 * Add the Debug Bar Post Types panel to the Debug Bar
	 *
	 * @param   array   $panels     Existing debug bar panels
	 * @return  array
	 */
	function debug_bar_post_types_panel( $panels ) {
		if ( !class_exists( 'Debug_Bar_Post_Types' ) ) {
			require_once 'class-debug-bar-post-types.php';
		}
		$panels[] = new Debug_Bar_Post_Types();
		return $panels;
	}
	add_filter( 'debug_bar_panels', 'debug_bar_post_types_panel' );
}