<?php
/**
 * Plugin Name: Juliepr Remove NBSP
 * Description: Replaces non-breaking spaces with regular spaces in post and page titles, content, and excerpts when saving.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: Sergei Ashmarin
 * Author URI: https://github.com/sashmarin
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: juliepr-remove-nbsp
 * Domain Path: /languages
 *
 * @package Juliepr_Remove_NBSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register bundled translations for just-in-time loading.
 *
 * @return void
 */
function juliepr_remove_nbsp_load_textdomain() {
	load_plugin_textdomain( 'juliepr-remove-nbsp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'juliepr_remove_nbsp_load_textdomain' );

/**
 * Normalize non-breaking spaces before a post or page is written to the database.
 *
 * @param array $data Slashed, sanitized post data supplied by WordPress.
 * @return array Filtered post data, still slashed.
 */
function juliepr_remove_nbsp_filter_post_data( $data ) {
	if ( ! isset( $data['post_type'] ) || ! in_array( $data['post_type'], array( 'post', 'page' ), true ) ) {
		return $data;
	}

	foreach ( array( 'post_title', 'post_content', 'post_excerpt' ) as $field ) {
		if ( ! isset( $data[ $field ] ) || ! is_string( $data[ $field ] ) ) {
			continue;
		}

		// Match UTF-8 U+00A0 and its named, decimal, and hexadecimal HTML entities.
		// Leave slashes and all other HTML entities intact.
		$data[ $field ] = str_replace( "\xC2\xA0", ' ', $data[ $field ] );
		$data[ $field ] = preg_replace( '/&(?:nbsp|#0*160|#x0*a0);/i', ' ', $data[ $field ] );
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'juliepr_remove_nbsp_filter_post_data' );
