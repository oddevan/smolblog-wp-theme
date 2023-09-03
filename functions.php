<?php
/**
 * Smolblog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Smolblog
 * @since Smolblog 1.0
 */


if ( ! function_exists( 'smolblog_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Smolblog 1.0
	 *
	 * @return void
	 */
	function smolblog_support() {

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Make theme available for translation.
		load_theme_textdomain( 'smolblog' );
	}

endif;

add_action( 'after_setup_theme', 'smolblog_support' );

if ( ! function_exists( 'smolblog_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Smolblog 1.0
	 *
	 * @return void
	 */
	function smolblog_styles() {

		// Register theme stylesheet.
		wp_register_style(
			'smolblog-style',
			get_stylesheet_directory_uri() . '/style.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'smolblog-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'smolblog_styles' );
