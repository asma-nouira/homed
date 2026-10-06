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
 *   Adresse / horaire / réseaux :
 *       [vsc_adresse]  [vsc_adresse carte="oui"]  [vsc_horaire]  [vsc_reseaux]
 *
 *   Réservation en ligne (lien du bouton « Prendre rendez-vous ») :
 *       [vsc_rdv]                          → bouton brun « Prendre rendez-vous »
 *       [vsc_rdv texte="Réserver en ligne" class="btn-beige"]
 *       [vsc_rdv_lien]                     → l'adresse seule (texte)
 *       /reserver/                         → lien à mettre dans N'IMPORTE QUEL bouton Visual Composer :
 *                                            il redirige vers l'adresse réglée dans le Customizer
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

function vsc_coord_horaire() {
	return get_theme_mod( 'vsc_horaire', "Lundi | 8 h 00 – 17 h 00\nMardi | 8 h 00 – 17 h 00\nMercredi | 7 h 00 – 16 h 00\nJeudi | 8 h 00 – 17 h 00\nVendredi | 8 h 00 – 17 h 00\nSamedi | Fermé\nDimanche | Fermé" );
}

function vsc_coord_instagram() {
	return esc_url( get_theme_mod( 'vsc_instagram', '' ) );
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
		'vsc_horaire'          => array( 'Horaire détaillé — une ligne par jour : « Lundi | 8 h 00 – 17 h 00 »', "Lundi | 8 h 00 – 17 h 00\nMardi | 8 h 00 – 17 h 00\nMercredi | 7 h 00 – 16 h 00\nJeudi | 8 h 00 – 17 h 00\nVendredi | 8 h 00 – 17 h 00\nSamedi | Fermé\nDimanche | Fermé", 'sanitize_textarea_field', 'textarea' ),
		'vsc_facebook'         => array( 'Lien de la page Facebook', '', 'esc_url_raw', 'url' ),
		'vsc_instagram'        => array( 'Lien du compte Instagram', '', 'esc_url_raw', 'url' ),
		'vsc_header_rdv_url'   => array( 'Lien de réservation en ligne (bouton « Prendre rendez-vous »)', '', 'esc_url_raw', 'url' ),
		'vsc_header_rdv_label' => array( 'Texte du bouton de réservation', 'Prendre rendez-vous', 'sanitize_text_field', 'text' ),
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

/* ---------- Adresse, horaire, réseaux sociaux ---------- */

// [vsc_adresse] → adresse sur plusieurs lignes ; [vsc_adresse carte="oui"] → lien vers Google Maps
add_shortcode( 'vsc_adresse', function ( $atts ) {
	$atts    = shortcode_atts( array( 'carte' => 'non' ), $atts, 'vsc_adresse' );
	$adresse = vsc_coord_adresse();
	$html    = vsc_lignes( $adresse );

	if ( 'oui' === $atts['carte'] ) {
		$query = rawurlencode( preg_replace( '/\s+/', ' ', $adresse ) );
		$html  = '<a class="coord-link" href="https://www.google.com/maps/search/?api=1&amp;query=' . $query . '" target="_blank" rel="noopener">' . $html . '</a>';
	}
	return '<span class="coord-adresse">' . $html . '</span>';
} );

// [vsc_horaire] → tableau des heures, jour par jour ; la journée actuelle est mise en évidence
add_shortcode( 'vsc_horaire', function () {
	$jours   = array( 1 => 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche' );
	$today   = $jours[ (int) current_time( 'N' ) ];
	$lines   = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) vsc_coord_horaire() ) ) );

	$html = '<dl class="horaire">';
	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$jour  = $parts[0];
		$heure = isset( $parts[1] ) ? $parts[1] : '';
		$cls   = ( 0 === strpos( remove_accents( mb_strtolower( $jour ) ), remove_accents( $today ) ) ) ? ' is-today' : '';
		$html .= '<div class="horaire__row reveal' . $cls . '"><dt>' . esc_html( $jour ) . '</dt><dd>' . esc_html( $heure ) . '</dd></div>';
	}
	return $html . '</dl>';
} );

// [vsc_reseaux] → icônes Facebook / Instagram (seulement celles dont le lien est rempli)
add_shortcode( 'vsc_reseaux', function () {
	$reseaux = array(
		'Facebook'  => array( vsc_coord_facebook(), '<path d="M13.5 21v-7.5h2.5l.4-3h-2.9V8.6c0-.9.3-1.5 1.5-1.5h1.5V4.4c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.4H8v3h2.6V21h2.9z" fill="currentColor"/>' ),
		'Instagram' => array( vsc_coord_instagram(), '<rect x="4" y="4" width="16" height="16" rx="4.5" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3.6" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="16.8" cy="7.2" r="1" fill="currentColor"/>' ),
	);
	$html = '';
	foreach ( $reseaux as $nom => $r ) {
		if ( ! $r[0] ) {
			continue;
		}
		$html .= '<a class="reseau" href="' . esc_url( $r[0] ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $nom ) . '"><svg viewBox="0 0 24 24" aria-hidden="true">' . $r[1] . '</svg></a>';
	}
	return $html ? '<span class="reseaux">' . $html . '</span>' : '';
} );

/* ---------- Réservation en ligne ---------- */

// Le lien s'ouvre dans un nouvel onglet s'il mène vers un autre site (plateforme de réservation)
function vsc_rdv_externe( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return $host && $host !== wp_parse_url( home_url(), PHP_URL_HOST );
}

add_shortcode( 'vsc_rdv', function ( $atts ) {
	$atts = shortcode_atts( array(
		'texte' => vsc_rdv_label(),
		'class' => 'btn-brun',
	), $atts, 'vsc_rdv' );

	$url = vsc_rdv_url();
	return sprintf(
		'<a class="%s" href="%s"%s>%s</a>',
		esc_attr( $atts['class'] ),
		esc_url( $url ),
		vsc_rdv_externe( $url ) ? ' target="_blank" rel="noopener"' : '',
		esc_html( $atts['texte'] )
	);
} );

add_shortcode( 'vsc_rdv_lien', function () {
	return esc_url( vsc_rdv_url() );
} );

// /reserver/ → redirige vers le lien de réservation réglé dans le Customizer.
// Pratique pour les boutons Visual Composer, où l'on ne peut pas mettre de shortcode dans le lien.
add_action( 'template_redirect', function () {
	if ( ! is_404() ) {
		return; // une vraie page /reserver/ existe : on ne touche à rien
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( $home && 0 === strpos( $path, $home ) ) {
		$path = trim( substr( $path, strlen( $home ) ), '/' );
	}
	if ( 'reserver' === $path ) {
		$url = vsc_rdv_url();
		if ( vsc_rdv_externe( $url ) ) {
			wp_redirect( $url, 302 );        // vers la plateforme de réservation
		} else {
			wp_safe_redirect( $url, 302 );   // vers une page du site
		}
		exit;
	}
} );

/* ---------- Contact Form 7 : pas de <p> et <br> ajoutés automatiquement ---------- */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/* ---------- Contact Form 7 : autoriser [vsc_telephone] / [vsc_courriel] dans le formulaire ---------- */
add_filter( 'wpcf7_form_elements', 'do_shortcode' );