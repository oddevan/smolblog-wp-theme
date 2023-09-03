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

if ( ! function_exists( 'smolblog_post_types' ) ) :

	/**
	 * Register Smolblog post types
	 *
	 * @since Smolblog 1.0
	 *
	 * @return void
	 */
	function smolblog_post_types() {

		$default_cpt_args = [
			'supports'              => array( 'editor', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', 'post-formats' ),
			'taxonomies'            => array( 'post_tag' ),
			'public'                => true,
			'menu_position'         => 5,
			'has_archive'           => true,
			'show_in_rest'          => true,
		];
		
		register_post_type( 'sb-note', [
			'label'                 => __( 'Note', 'smolblog' ),
			'description'           => __( 'A short text post', 'smolblog' ),
			...$default_cpt_args,
		] );
		
		register_post_type( 'sb-reblog', [
			'label'                 => __( 'Reblog', 'smolblog' ),
			'description'           => __( 'A webpage from off-site', 'smolblog' ),
			...$default_cpt_args,
		] );
		register_post_type( 'sb-picture', [
			'label'                 => __( 'Picture', 'smolblog' ),
			'description'           => __( 'A visual medium', 'smolblog' ),
			...$default_cpt_args,
		] );
		
		add_action( 'pre_get_posts', function($query) {
			if ( ! is_admin() && $query->is_main_query() ) {
				$query->set( 'post_type', array( 'post', 'page', 'status', 'reblog' ) );
			}
		});
		
		add_filter( 'the_title_rss', function($title) {
			global $wp_query;
			$type = $wp_query->post->post_type;
			if (in_array($type, [ 'note', 'reblog' ])) {
				return null;
			}
			return $title;
		});
		
		add_rewrite_rule(
			'^\.well-known\/webfinger',
			'index.php?rest_route=/smolblog/v2/webfinger',
			'top'
		);
		
		add_action( 'wp_head', function() {
			$siteId = get_current_site_uuid();
			echo '<link rel="micropub" href="' . get_rest_url( null, "/smolblog/v2/site/$siteId/micropub" ) . '">';
		});		

	}

endif;

add_action( 'init', 'smolblog_post_types' );
