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
 * Vrai quand la page est affichée dans l'éditeur Visual Composer.
 * Dans ce cas, on ne charge ni Slick, ni run.js, ni reveal.js :
 * ils modifient la page et l'éditeur perd ses blocs.
 */
function vsc_is_vc_editor() {
	return isset( $_GET['vcv-editable'] )
		|| isset( $_GET['vcv-action'] )
		|| isset( $_GET['vcv-source-id'] )
		|| isset( $_GET['vcv-ajax'] );
}

/**
 * Enqueue scripts and styles.
 *
 * CSS : dans le <head>.
 * JS  : en pied de page (dernier paramètre à true).
 */
function vsc_theme_scripts() {
	$uri = get_template_directory_uri();

	// ---------- CSS
	wp_enqueue_style( 'vsc-theme-fonts',  $uri . '/css/fonts/fonts.css', array(), vsc_theme_ver( '/css/fonts/fonts.css' ) );
	wp_enqueue_style( 'vsc-header-style', $uri . '/css/header.css',      array( 'vsc-theme-fonts' ), vsc_theme_ver( '/css/header.css' ) );
	wp_enqueue_style( 'vsc-theme-style',  $uri . '/css/style.css',       array(), vsc_theme_ver( '/css/style.css' ) );
	wp_enqueue_style( 'vsc-mobile-style', $uri . '/css/mobile.css',      array( 'vsc-theme-style' ), vsc_theme_ver( '/css/mobile.css' ) ); // toujours APRÈS style.css
	wp_enqueue_style( 'vsc-animations',   $uri . '/css/animations.css',  array(), vsc_theme_ver( '/css/animations.css' ) );

	//////////////// Slick Slider CSS ///////////////
	wp_enqueue_style( 'vsc-theme-slick', $uri . '/slick-slider/slick-theme.css', array(), vsc_theme_ver( '/slick-slider/slick-theme.css' ) );

	// ---------- JS (pied de page)
	wp_enqueue_script( 'vsc-theme-navigation', $uri . '/js/navigation.js', array(), vsc_theme_ver( '/js/navigation.js' ), true );
	wp_enqueue_script( 'vsc-theme-skip-link-focus-fix', $uri . '/js/skip-link-focus-fix.js', array(), vsc_theme_ver( '/js/skip-link-focus-fix.js' ), true );

	// Animations + sliders : seulement sur le site, jamais dans l'éditeur Visual Composer
	if ( ! vsc_is_vc_editor() ) {
		wp_enqueue_script( 'vsc-reveal',    $uri . '/js/reveal.js',              array(), vsc_theme_ver( '/js/reveal.js' ), true );
		wp_enqueue_script( 'vsc-slick-js',  $uri . '/slick-slider/slick.min.js', array( 'jquery' ), vsc_theme_ver( '/slick-slider/slick.min.js' ), true );
		wp_enqueue_script( 'vsc-slick-run', $uri . '/slick-slider/run.js',       array( 'jquery', 'vsc-slick-js' ), vsc_theme_ver( '/slick-slider/run.js' ), true );
	}
wp_enqueue_script( 'api-google-maps',  'https://maps.googleapis.com/maps/api/js?key=AIzaSyBVMO7oJGbpoNzFxFCwJwh-FW8DlljIY4s', array(), null, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vsc_theme_scripts' );

/**
 * Précharge Manrope Regular (police du menu).
 * L'état « caché » des animations est activé par reveal.js lui-même (jamais dans l'éditeur).
 */
add_action( 'wp_head', function () {
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
 * Coordonnées de la clinique (téléphone, courriel, adresse, heures, Facebook).
 */
require get_template_directory() . '/inc/coordonnees.php';

/**
 * Formulaire de candidature (validation du CV + chargement de candidature.js).
 */
require get_template_directory() . '/inc/candidature.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


if ( ! function_exists( 'hd_blogue_shortcode' ) ) {

	function hd_blogue_shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'par_page'  => 6,
			'categorie' => '',
			'colonnes'  => 3,
			'extrait'   => 22,
		), $atts, 'hd_blogue' );

		// Page courante (fonctionne sur une page statique comme /blogue/page/2/)
		$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => absint( $atts['par_page'] ),
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
		);

		if ( ! empty( $atts['categorie'] ) ) {
			$args['category_name'] = sanitize_text_field( $atts['categorie'] );
		}

		$requete  = new WP_Query( $args );
		$colonnes = min( 4, max( 2, absint( $atts['colonnes'] ) ) );

		ob_start();

		if ( $requete->have_posts() ) : ?>

			<div class="hd-blogue" style="--hd-colonnes: <?php echo esc_attr( $colonnes ); ?>;">
				<div class="hd-blogue__grille">

					<?php while ( $requete->have_posts() ) : $requete->the_post();
						$categories = get_the_category();
						$cat        = ! empty( $categories ) ? $categories[0] : null;
					?>

						<article class="hd-carte">
							<a class="hd-carte__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php if ( has_post_thumbnail() ) :
									the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => '' ) );
								else : ?>
									<span class="hd-carte__image-vide"></span>
								<?php endif; ?>
							</a>

							<div class="hd-carte__contenu">
								<p class="hd-carte__meta">
									<?php if ( $cat ) : ?>
										<a class="hd-carte__cat" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
											<?php echo esc_html( $cat->name ); ?>
										</a>
									<?php endif; ?>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
									</time>
								</p>

								<h3 class="hd-carte__titre">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>

								<p class="hd-carte__extrait">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), absint( $atts['extrait'] ), '…' ) ); ?>
								</p>

								<a class="hd-carte__lien" href="<?php the_permalink(); ?>">
									Lire l’article<span class="screen-reader-text"> : <?php the_title(); ?></span>
								</a>
							</div>
						</article>

					<?php endwhile; ?>

				</div>

				<?php
				$liens = paginate_links( array(
					'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
					'format'    => '?paged=%#%',
					'current'   => $paged,
					'total'     => $requete->max_num_pages,
					'mid_size'  => 1,
					'end_size'  => 1,
					'prev_text' => '<span aria-hidden="true">‹</span><span class="hd-pagination__txt"> Précédent</span>',
					'next_text' => '<span class="hd-pagination__txt">Suivant </span><span aria-hidden="true">›</span>',
					'type'      => 'array',
				) );

				if ( ! empty( $liens ) ) : ?>
					<nav class="hd-pagination" aria-label="Pagination du blogue">
						<ul>
							<?php foreach ( $liens as $lien ) : ?>
								<li><?php echo $lien; // déjà échappé par WordPress ?></li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>

			</div>

		<?php else : ?>

			<p class="hd-blogue__vide">Aucun article publié pour le moment. Revenez bientôt.</p>

		<?php endif;

		wp_reset_postdata();

		return ob_get_clean();
	}

	add_shortcode( 'hd_blogue', 'hd_blogue_shortcode' );
}

include_once 'integrated_vc.php';