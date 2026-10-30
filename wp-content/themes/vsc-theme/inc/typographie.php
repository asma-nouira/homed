<?php
/**
 * TYPOGRAPHIE FRANÇAISE
 * 1. Remplace l'espace avant ? ! : ; » (et après «) par une espace insécable,
 *    pour que ces signes ne se retrouvent jamais seuls en début de ligne.
 * 2. Met les symboles de marque ® et ™ en petit exposant (<sup class="marque">).
 *
 * Ne touche qu'au texte : jamais aux balises HTML, aux attributs, aux scripts ni aux styles.
 *
 * À inclure dans functions.php : require get_template_directory() . '/inc/typographie.php';
 *
 * @package vsc-theme
 */

/**
 * @param string $html    Le texte ou le HTML à corriger.
 * @param bool   $marques true = entourer ® et ™ d'un <sup class="marque">.
 */
function vsc_typographie( $html, $marques = true ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	// On découpe en « balises » et « texte » : seuls les morceaux de texte sont modifiés
	$parts  = preg_split( '/(<[^>]*>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$skip   = false; // dans un <script>, <style>, <textarea>, <code> ou <pre>
	$in_sup = false; // déjà dans un <sup> : on n'en ajoute pas un deuxième
	$nbsp   = "\xC2\xA0"; // espace insécable

	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}

		// ----- Balise HTML : on la laisse intacte, on note seulement où on se trouve
		if ( '<' === $part[0] ) {
			if ( preg_match( '/^<(script|style|textarea|code|pre)\b/i', $part ) ) {
				$skip = true;
			} elseif ( preg_match( '/^<\/(script|style|textarea|code|pre)>/i', $part ) ) {
				$skip = false;
			}

			if ( preg_match( '/^<sup\b/i', $part ) ) {
				$in_sup = true;
			} elseif ( preg_match( '/^<\/sup>/i', $part ) ) {
				$in_sup = false;
			}
			continue;
		}

		if ( $skip ) {
			continue;
		}

		// ----- Texte
		// espace (ou &nbsp; déjà présent) + ? ! : ; »  →  espace insécable + signe
		$part = preg_replace( '/(?:[ \t]|&nbsp;)+([?!:;»])/u', $nbsp . '$1', $part );
		// « + espace  →  « + espace insécable
		$part = preg_replace( '/«(?:[ \t]|&nbsp;)+/u', '«' . $nbsp, $part );

		// ® et ™ (ou &reg; / &trade;) en petit exposant, sauf s'ils sont déjà dans un <sup>
		if ( $marques && ! $in_sup ) {
			$part = preg_replace( '/(®|™|&reg;|&trade;|&#174;|&#8482;)/u', '<sup class="marque">$1</sup>', $part );
		}

		$parts[ $i ] = $part;
	}

	return implode( '', $parts );
}

/* ---------- Contenu des pages (Visual Composer inclus) ---------- */
add_filter( 'the_content', function ( $html ) {
	return vsc_typographie( $html, true );
}, 99 );

add_filter( 'the_excerpt', function ( $html ) {
	return vsc_typographie( $html, true );
}, 99 );

add_filter( 'widget_text', function ( $html ) {
	return vsc_typographie( $html, true );
}, 99 );

/* ---------- Titres et menus ----------
   Espaces insécables partout. Le <sup> seulement sur le site public :
   dans l'administration, le titre sert aussi dans des champs de texte. */
add_filter( 'the_title', function ( $title ) {
	return vsc_typographie( $title, ! is_admin() );
}, 99 );

add_filter( 'nav_menu_item_title', function ( $title ) {
	return vsc_typographie( $title, true );
}, 99 );