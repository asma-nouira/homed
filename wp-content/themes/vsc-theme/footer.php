<?php
/**
 * The template for displaying the footer
 *
 * Coordonnées : Apparence > Personnaliser > Coordonnées de la clinique
 * Menu        : Apparence > Menus > emplacement « Footer »
 *
 * @package vsc-theme
 */

$vsc_tel      = function_exists( 'vsc_coord_telephone' ) ? vsc_coord_telephone() : '(514) 697-9045';
$vsc_tel_lien = function_exists( 'vsc_coord_telephone_tel' ) ? vsc_coord_telephone_tel() : '+15146979045';
$vsc_courriel = function_exists( 'vsc_coord_courriel' ) ? vsc_coord_courriel() : 'reception@homedental.ca';
$vsc_adresse  = function_exists( 'vsc_coord_adresse' ) ? vsc_coord_adresse() : '';
$vsc_heures   = function_exists( 'vsc_coord_heures' ) ? vsc_coord_heures() : '';
$vsc_facebook = function_exists( 'vsc_coord_facebook' ) ? vsc_coord_facebook() : '';
$vsc_rdv      = function_exists( 'vsc_rdv_url' ) ? vsc_rdv_url() : home_url( '/contactez-nous/' );
$vsc_rdv_txt  = function_exists( 'vsc_rdv_label' ) ? vsc_rdv_label() : 'Prendre rendez-vous';
$vsc_externe  = ( false === strpos( $vsc_rdv, home_url() ) );
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-footer__inner">

			<!-- Logo -->
			<div class="site-footer__brand">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a class="site-footer__name" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
					<?php
				}
				?>
			</div>

			<!-- Adresse, courriel, heures -->
			<div class="site-footer__infos">
				<?php if ( $vsc_adresse ) : ?>
					<p class="site-footer__title"><?php esc_html_e( 'Notre adresse', 'vsc-theme' ); ?></p>
					<address class="site-footer__text"><?php echo vsc_lignes( $vsc_adresse ); // déjà échappé ?></address>
				<?php endif; ?>

				<?php if ( $vsc_courriel ) : ?>
					<p class="site-footer__mail">
						<a href="mailto:<?php echo esc_attr( antispambot( $vsc_courriel, 1 ) ); ?>">
							<?php esc_html_e( 'Courriel :', 'vsc-theme' ); ?> <?php echo esc_html( antispambot( $vsc_courriel ) ); ?>
						</a>
					</p>
				<?php endif; ?>

				<?php if ( $vsc_heures ) : ?>
					<p class="site-footer__title"><?php esc_html_e( 'Heures d’ouverture', 'vsc-theme' ); ?></p>
					<p class="site-footer__text"><?php echo vsc_lignes( $vsc_heures ); // déjà échappé ?></p>
				<?php endif; ?>
			</div>

			<!-- Menu + Facebook -->
			<div class="site-footer__nav">
				<nav aria-label="<?php esc_attr_e( 'Menu du pied de page', 'vsc-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'menu-footer',
						'menu_class'     => 'site-footer__menu',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				</nav>

				<?php if ( $vsc_facebook ) : ?>
					<a class="site-footer__social" href="<?php echo esc_url( $vsc_facebook ); ?>" target="_blank" rel="noopener">
						<span><?php esc_html_e( 'Suivez-nous sur Facebook', 'vsc-theme' ); ?></span>
						<span class="site-footer__social-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5h2.5l.4-3h-2.9V8.6c0-.9.3-1.5 1.5-1.5h1.5V4.4c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.4H8v3h2.6V21h2.9z"/></svg>
						</span>
					</a>
				<?php endif; ?>
			</div>

			<!-- Boutons -->
			<div class="site-footer__actions">
				<?php if ( $vsc_tel ) : ?>
					<a class="footer-btn footer-btn--light" href="tel:<?php echo esc_attr( $vsc_tel_lien ); ?>"><?php echo esc_html( $vsc_tel ); ?></a>
				<?php endif; ?>
				<?php if ( $vsc_rdv_txt ) : ?>
					<a class="footer-btn footer-btn--dark" href="<?php echo esc_url( $vsc_rdv ); ?>"<?php echo $vsc_externe ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $vsc_rdv_txt ); ?></a>
				<?php endif; ?>
			</div>

		</div>

		<!-- Mentions (Loi 25 : lien vers la politique de confidentialité toujours accessible) -->
		<div class="site-footer__legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
			<?php
			$vsc_privacy = get_privacy_policy_url();
			if ( $vsc_privacy ) :
				?>
				<a href="<?php echo esc_url( $vsc_privacy ); ?>"><?php esc_html_e( 'Politique de confidentialité', 'vsc-theme' ); ?></a>
			<?php endif; ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>