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
							<svg xmlns="http://www.w3.org/2000/svg" width="15.907" height="30.615" viewBox="0 0 15.907 30.615">
  <path id="Tracé_430" data-name="Tracé 430" d="M767.886,40.49H762.2V26.521h-4.651V21.1h4.629c.011-.229.025-.408.028-.587.027-1.511-.018-3.027.09-4.532a6.28,6.28,0,0,1,6.352-6.093c1.44-.042,2.884.071,4.326.121a3.4,3.4,0,0,1,.482.09v4.869c-.581,0-1.117-.012-1.652,0-.745.021-1.5.012-2.233.105a1.659,1.659,0,0,0-1.59,1.594c-.08,1.45-.022,2.906-.022,4.412h5.315c-.236,1.834-.465,3.611-.7,5.44h-4.685Z" transform="translate(-757.546 -9.875)" fill="#726248"/>
</svg>

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
	
		<span class="site-footer__legal-item">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
		<?php
		$vsc_privacy = get_privacy_policy_url();
		if ( $vsc_privacy ) :
			?>
			<a href="<?php echo esc_url( $vsc_privacy ); ?>"><?php esc_html_e( 'Politique de confidentialité', 'vsc-theme' ); ?></a>
		<?php endif; ?>


	<!-- Crédit de l'agence -->
	<a class="site-footer__legal-item site-footer__credit" href="https://virussantecommunication.ca/" target="_blank" rel="noopener">
		<span><?php esc_html_e( 'Réalisé par', 'vsc-theme' ); ?></span>
		<img src="<?php echo esc_url('/wp-content/uploads/2026/09/virus-sante-communication.png' ); ?>"
		     alt="Virus Santé Communication" width="120" height="24" loading="lazy">
	</a>
</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>