<?php
/**
 * COORDONNÉES DE LA CLINIQUE — valeurs globales
 *
 * Réglées une seule fois dans Apparence > Personnaliser > Coordonnées de la clinique,
 * puis réutilisées partout :
 *   - dans le header (bouton téléphone)
 *   - dans les pages Visual Composer avec les shortcodes :
 *       [vsc_telephone]            → lien cliquable (514) 697-9045
 *       [vsc_telephone lien="non"] → texte seulement
 *       [vsc_courriel]             → lien mailto
 *       [vsc_courriel lien="non"]  → texte seulement
 *   - dans le footer (adresse, heures, Facebook, boutons)
 *
 * À inclure dans functions.php :  require get_template_directory() . '/inc/coordonnees.php';
 *
 * @package vsc-theme
 */

/* ---------- Valeurs (avec valeurs par défaut) ---------- */
function vsc_coord_telephone() {
	// Même réglage que le bouton du header : on le change à un seul endroit
	return get_theme_mod( 'vsc_header_phone', '(514) 697-9045' );
}

function vsc_coord_telephone_tel() {
	$tel = get_theme_mod( 'vsc_header_phone_tel', '' );
	if ( ! $tel ) {
		// Construit le lien à partir du numéro affiché : (514) 697-9045 → +15146979045
		$digits = preg_replace( '/\D/', '', vsc_coord_telephone() );
		$tel    = ( 10 === strlen( $digits ) ? '+1' : '+' ) . $digits;
	}
	return preg_replace( '/[^0-9+]/', '', $tel );
}

function vsc_coord_courriel() {
	return sanitize_email( get_theme_mod( 'vsc_courriel', 'reception@homedental.ca' ) );
}

function vsc_coord_adresse() {
	return get_theme_mod( 'vsc_adresse', "17112 Chemin Sainte-Marie,\nKirkland, Qc, H9J 2K9" );
}

function vsc_coord_heures() {
	return get_theme_mod( 'vsc_heures', "Lun - Ven : 08.00 - 17.00\nMercredi : 07.00 - 16.00" );
}

function vsc_coord_facebook() {
	return esc_url( get_theme_mod( 'vsc_facebook', '' ) );
}

// Lien du bouton « Prendre rendez-vous » (header + footer) : réglage du Customizer, sinon page Contactez-nous
function vsc_rdv_url() {
	$url = get_theme_mod( 'vsc_header_rdv_url', '' );
	if ( ! $url ) {
		$contact = get_page_by_path( 'contactez-nous' );
		$url     = $contact ? get_permalink( $contact ) : home_url( '/' );
	}
	return $url;
}

function vsc_rdv_label() {
	return get_theme_mod( 'vsc_header_rdv_label', 'Prendre rendez-vous' );
}

// Texte multiligne → lignes séparées par <br>, sécurisé
function vsc_lignes( $text ) {
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) );
	return implode( '<br>', array_map( 'esc_html', $lines ) );
}

/* ---------- Réglages dans le Customizer ---------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'vsc_coordonnees', array(
		'title'    => __( 'Coordonnées de la clinique', 'vsc-theme' ),
		'priority' => 25,
	) );

	$fields = array(
		'vsc_header_phone'     => array( 'Téléphone (affiché)', '(514) 697-9045', 'sanitize_text_field', 'text' ),
		'vsc_header_phone_tel' => array( 'Téléphone (lien tel:) — laisser vide pour le calculer automatiquement', '', 'sanitize_text_field', 'text' ),
		'vsc_courriel'         => array( 'Courriel', 'reception@homedental.ca', 'sanitize_email', 'email' ),
		'vsc_adresse'          => array( 'Adresse (une ligne par ligne affichée)', "17112 Chemin Sainte-Marie,\nKirkland, Qc, H9J 2K9", 'sanitize_textarea_field', 'textarea' ),
		'vsc_heures'           => array( 'Heures d\'ouverture (une ligne par ligne affichée)', "Lun - Ven : 08.00 - 17.00\nMercredi : 07.00 - 16.00", 'sanitize_textarea_field', 'textarea' ),
		'vsc_facebook'         => array( 'Lien de la page Facebook', '', 'esc_url_raw', 'url' ),
	);

	foreach ( $fields as $id => $f ) {
		// Si le réglage existe déjà (section Header), on le déplace ici
		if ( $wp_customize->get_setting( $id ) ) {
			$wp_customize->remove_control( $id );
		} else {
			$wp_customize->add_setting( $id, array(
				'default'           => $f[1],
				'sanitize_callback' => $f[2],
			) );
		}
		$wp_customize->add_control( $id, array(
			'label'   => $f[0],
			'section' => 'vsc_coordonnees',
			'type'    => $f[3],
		) );
	}
}, 20 );

/* ---------- Emplacement de menu pour le footer ---------- */
add_action( 'after_setup_theme', function () {
	register_nav_menus( array(
		'menu-footer' => esc_html__( 'Footer', 'vsc-theme' ),
	) );
} );

/* ---------- Shortcodes ---------- */
add_shortcode( 'vsc_telephone', function ( $atts ) {
	$atts = shortcode_atts( array( 'lien' => 'oui', 'class' => '' ), $atts, 'vsc_telephone' );
	$num  = esc_html( vsc_coord_telephone() );

	if ( 'non' === $atts['lien'] ) {
		return '<span class="nowrap">' . $num . '</span>';
	}
	return sprintf(
		'<a class="coord-link nowrap %s" href="tel:%s">%s</a>',
		esc_attr( $atts['class'] ),
		esc_attr( vsc_coord_telephone_tel() ),
		$num
	);
} );

add_shortcode( 'vsc_courriel', function ( $atts ) {
	$atts  = shortcode_atts( array( 'lien' => 'oui', 'class' => '' ), $atts, 'vsc_courriel' );
	$email = vsc_coord_courriel();

	if ( 'non' === $atts['lien'] ) {
		return esc_html( antispambot( $email ) );
	}
	// antispambot() encode l'adresse pour limiter la collecte par les robots
	return sprintf(
		'<a class="coord-link %s" href="mailto:%s">%s</a>',
		esc_attr( $atts['class'] ),
		esc_attr( antispambot( $email, 1 ) ),
		esc_html( antispambot( $email ) )
	);
} );

/* ---------- Contact Form 7 : pas de <p> et <br> ajoutés automatiquement ---------- */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/* ---------- Contact Form 7 : autoriser [vsc_telephone] / [vsc_courriel] dans le formulaire ---------- */
add_filter( 'wpcf7_form_elements', 'do_shortcode' );