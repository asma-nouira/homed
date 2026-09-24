<?php
/**
 * vsc-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package vsc-theme
 */

if ( ! function_exists( 'vsc_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function vsc_theme_setup() {
		load_theme_textdomain( 'vsc-theme', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'vsc-theme' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'custom-background', apply_filters( 'vsc_theme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'vsc_theme_setup' );

/**
 * Set the content width in pixels.
 */
function vsc_theme_content_width() {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'vsc_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'vsc_theme_content_width', 0 );

/**
 * Register widget area.
 */
function vsc_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'vsc-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'vsc-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
}
add_action( 'widgets_init', 'vsc_theme_widgets_init' );

/**
 * Version = date de modification du fichier.
 * Le navigateur recharge le fichier dès qu'il change (fini les problèmes de cache).
 */
function vsc_theme_ver( $path ) {
	$file = get_template_directory() . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : null;
}

/**
 * Enqueue scripts and styles.
 *
 * CSS : dans le <head> (avant le contenu) → pas de « flash » et les animations
 *       partent bien de l'état caché.
 * JS  : en pied de page (dernier paramètre à true).
 */
function vsc_theme_scripts() {
	$uri = get_template_directory_uri();

	// ---------- CSS
	wp_enqueue_style( 'vsc-theme-fonts',  $uri . '/css/fonts/fonts.css', array(), vsc_theme_ver( '/css/fonts/fonts.css' ) );
	wp_enqueue_style( 'vsc-header-style', $uri . '/css/header.css',      array( 'vsc-theme-fonts' ), vsc_theme_ver( '/css/header.css' ) );
	wp_enqueue_style( 'vsc-mobile-style', $uri . '/css/mobile.css',      array(), vsc_theme_ver( '/css/mobile.css' ) );
	wp_enqueue_style( 'vsc-theme-style',  $uri . '/css/style.css',       array(), vsc_theme_ver( '/css/style.css' ) );
	wp_enqueue_style( 'vsc-animations',   $uri . '/css/animations.css',  array(), vsc_theme_ver( '/css/animations.css' ) );

	//////////////// Slick Slider CSS ///////////////
	wp_enqueue_style( 'vsc-theme-slick', $uri . '/slick-slider/slick-theme.css', array(), vsc_theme_ver( '/slick-slider/slick-theme.css' ) );

	// ---------- JS (pied de page)
	wp_enqueue_script( 'vsc-theme-navigation', $uri . '/js/navigation.js', array(), vsc_theme_ver( '/js/navigation.js' ), true );
	wp_enqueue_script( 'vsc-theme-skip-link-focus-fix', $uri . '/js/skip-link-focus-fix.js', array(), vsc_theme_ver( '/js/skip-link-focus-fix.js' ), true );
	wp_enqueue_script( 'vsc-reveal', $uri . '/js/reveal.js', array(), vsc_theme_ver( '/js/reveal.js' ), true );

	///////////// Slick Slider JS ///////////
	wp_enqueue_script( 'vsc-slick-js',  $uri . '/slick-slider/slick.min.js', array( 'jquery' ), vsc_theme_ver( '/slick-slider/slick.min.js' ), true );
	wp_enqueue_script( 'vsc-slick-run', $uri . '/slick-slider/run.js',       array( 'jquery', 'vsc-slick-js' ), vsc_theme_ver( '/slick-slider/run.js' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vsc_theme_scripts' );

/**
 * Dans le <head>, tout en haut :
 * 1. Active l'état « caché » des animations AVANT l'affichage (classe vsc-anim sur <html>).
 *    Filet de sécurité : si reveal.js ne se charge pas, tout redevient visible après 3 s.
 * 2. Précharge Manrope Regular (police du menu).
 */
add_action( 'wp_head', function () {
	echo "<script>document.documentElement.classList.add('vsc-anim');setTimeout(function(){if(!window.vscReveal){document.documentElement.classList.remove('vsc-anim');}},3000);</script>\n";
	echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/css/fonts/Manrope/Manrope-Regular.ttf' ) . '" as="font" type="font/ttf" crossorigin>' . "\n";
}, 0 );

/**
 * Retire seulement la version de WordPress des URL (sécurité),
 * en gardant les versions du thème (utiles pour vider le cache).
 */
function vc_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

include_once 'integrated_vc.php';