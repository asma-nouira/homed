<?php
/**
 * Page 404 — page introuvable
 *
 * @package vsc-theme
 */

get_header();

$vsc_tel      = function_exists( 'vsc_coord_telephone' ) ? vsc_coord_telephone() : '(514) 697-9045';
$vsc_tel_lien = function_exists( 'vsc_coord_telephone_tel' ) ? vsc_coord_telephone_tel() : '+15146979045';
$vsc_rdv_txt  = function_exists( 'vsc_rdv_label' ) ? vsc_rdv_label() : 'Prendre rendez-vous';

// Liens utiles : pages principales (si elles existent)
$vsc_liens = array();
foreach ( array(
	'nos-services'   => __( 'Nos services', 'vsc-theme' ),
	'notre-clinique' => __( 'Notre clinique', 'vsc-theme' ),
	'nouveau-patient' => __( 'Nouveau patient', 'vsc-theme' ),
	'contactez-nous' => __( 'Contactez-nous', 'vsc-theme' ),
) as $slug => $label ) {
	$page = get_page_by_path( $slug );
	if ( $page && 'publish' === $page->post_status ) {
		$vsc_liens[ get_permalink( $page ) ] = $label;
	}
}
?>

<main id="primary" class="page-404">
	<section class="page-404__inner">

		<p class="page-404__code reveal" aria-hidden="true">404</p>

		<h1 class="page-404__title reveal"><?php esc_html_e( 'Cette page est introuvable', 'vsc-theme' ); ?></h1>

		<p class="page-404__text reveal">
			<?php esc_html_e( 'Le lien que vous avez suivi est peut-être incorrect, ou la page a été déplacée. Pas d’inquiétude : voici quelques pistes pour retrouver votre chemin.', 'vsc-theme' ); ?>
		</p>

		<div class="page-404__actions reveal">
			<a class="page-404__btn page-404__btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l’accueil', 'vsc-theme' ); ?></a>
			<a class="page-404__btn page-404__btn--light" href="<?php echo esc_url( home_url( '/contactez-nous/#contact' ) ); ?>"><?php echo esc_html( $vsc_rdv_txt ); ?></a>
		</div>

		<?php if ( $vsc_liens ) : ?>
			<nav class="page-404__links reveal" aria-label="<?php esc_attr_e( 'Pages utiles', 'vsc-theme' ); ?>">
				<?php foreach ( $vsc_liens as $url => $label ) : ?>
					<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<p class="page-404__help reveal">
			<?php esc_html_e( 'Besoin d’aide ? Appelez-nous au', 'vsc-theme' ); ?>
			<a href="tel:<?php echo esc_attr( $vsc_tel_lien ); ?>"><?php echo esc_html( $vsc_tel ); ?></a>.
		</p>

	</section>
</main>

<?php
get_footer();
