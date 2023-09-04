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
		
		add_filter( 'the_title_rss', function($title) {
			global $wp_query;
			$type = $wp_query->post->post_type;
			if (in_array($type, [ 'sb-note', 'sb-reblog', 'sb-picture' ])) {
				return null;
			}
			return $title;
		});
		
		add_rewrite_rule(
			'^\.well-known\/webfinger',
			'index.php?rest_route=/smolblog/v2/webfinger',
			'top'
		);
		
		if ( function_exists( 'switch_to_blog' ) ) {
			add_action( 'wp_head', function() {
				$siteId = smolblog_current_site_uuid();
				$micropub_url = get_rest_url( BLOG_ID_CURRENT_SITE, "/smolblog/v2/site/$siteId/micropub" );

				echo '<link rel="micropub" href="' . $micropub_url . '"><!-- hello -->';
			});	
		}	

	}

endif;

add_action( 'init', 'smolblog_post_types' );

if ( ! function_exists( 'smolblog_post_queries' ) ) :

	/**
	 * Add Smolblog post types to the front page and main archive queries.
	 * 
	 * @see https://www.rafaelcardero.com/tutorials/how-to-add-custom-post-types-to-the-main-query-in-wordpress/
	 * @since Smolblog 1.0
	 * 
	 * @param WP_Query $query Current page query.
	 *
	 * @return void
	 */
	function smolblog_post_queries( $query ) {

		if ( is_admin() || !$query->is_main_query() ) {
			return;
		}

		if (
			$query->is_home ||
			$query->is_date || 
			$query->is_author || 
			$query->is_category || 
			$query->is_tag || 
			$query->is_tax
		) {
			$query->set(
				'post_type',
				[
					'post',
					'sb-note',
					'sb-reblog',
					'sb-picture',
				]
			);
    }

	}

endif;

add_action( 'pre_get_posts', 'smolblog_post_queries', 10, 1 );

if ( ! function_exists( 'smolblog_current_site_uuid' ) ) :

	/**
	 * Get the UUID for this site.
	 *
	 * @return string
	 */
	function smolblog_current_site_uuid() {
		if ( ! function_exists( 'get_site_meta' ) ) {
			return '9c41b97e-9ac7-4288-80eb-9551b01d0845';
		}

		$dbid = get_current_blog_id();
		return get_site_meta( $dbid, 'smolblog_site_id', true );
	}

endif;