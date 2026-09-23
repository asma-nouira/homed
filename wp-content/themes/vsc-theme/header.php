<?php
/**
 * The header for our theme
 *
 * @package vsc-theme
 */

$vsc_phone     = get_theme_mod( 'vsc_header_phone', '(514) 697-9045' );
$vsc_phone_tel = preg_replace( '/[^0-9+]/', '', get_theme_mod( 'vsc_header_phone_tel', '+15146979045' ) );
$vsc_rdv_label = get_theme_mod( 'vsc_header_rdv_label', 'Prendre rendez-vous' );
$vsc_rdv_url   = get_theme_mod( 'vsc_header_rdv_url', '' );

// Pas de lien configuré → on pointe vers la page Contactez-nous (ou l'accueil)
if ( ! $vsc_rdv_url ) {
	$vsc_contact = get_page_by_path( 'contactez-nous' );
	$vsc_rdv_url = $vsc_contact ? get_permalink( $vsc_contact ) : home_url( '/' );
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Aller au contenu', 'vsc-theme' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php
			}
			?>
		</div><!-- .site-branding -->

		<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false">
			<span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'vsc-theme' ); ?></span>
		</button>

		<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'vsc-theme' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'container'      => false,
				'fallback_cb'    => false,
			) );
			?>

			<div class="header-actions">
				<?php if ( $vsc_phone ) : ?>
					<a class="header-btn header-btn--light" href="tel:<?php echo esc_attr( $vsc_phone_tel ); ?>"><?php echo esc_html( $vsc_phone ); ?></a>
				<?php endif; ?>

				<?php if ( $vsc_rdv_label ) : ?>
					<a class="header-btn header-btn--dark" href="<?php echo esc_url( $vsc_rdv_url ); ?>"<?php echo ( false === strpos( $vsc_rdv_url, home_url() ) ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $vsc_rdv_label ); ?></a>
				<?php endif; ?>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
