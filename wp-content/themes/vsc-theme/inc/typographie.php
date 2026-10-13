<?php
/**
 * TYPOGRAPHIE FRANÇAISE
 * Remplace l'espace avant ? ! : ; » (et après «) par une espace insécable,
 * pour que ces signes ne se retrouvent jamais seuls en début de ligne.
 * Ne touche qu'au texte : jamais aux balises HTML, aux scripts ni aux styles.
 *
 * @package vsc-theme
 */

function vsc_espaces_insecables( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	// On découpe en « balises » et « texte » : seuls les morceaux de texte sont modifiés
	$parts   = preg_split( '/(<[^>]*>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$skip    = false;
	$nbsp    = "\xC2\xA0"; // espace insécable

	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			// On ne touche pas au contenu des <script>, <style> et <textarea>
			if ( preg_match( '/^<(script|style|textarea|code|pre)\b/i', $part ) ) {
				$skip = true;
			} elseif ( preg_match( '/^<\/(script|style|textarea|code|pre)>/i', $part ) ) {
				$skip = false;
			}
			continue;
		}
		if ( $skip ) {
			continue;
		}

		// espace (ou &nbsp; déjà présent) + ? ! : ; »  →  espace insécable + signe
		$part = preg_replace( '/(?:[ \t]|&nbsp;)+([?!:;»])/u', $nbsp . '$1', $part );
		// « + espace  →  « + espace insécable
		$part = preg_replace( '/«(?:[ \t]|&nbsp;)+/u', '«' . $nbsp, $part );

		$parts[ $i ] = $part;
	}

	return implode( '', $parts );
}

// Contenu des pages (Visual Composer inclus), titres, extraits et menus
add_filter( 'the_content', 'vsc_espaces_insecables', 99 );
add_filter( 'the_title', 'vsc_espaces_insecables', 99 );
add_filter( 'the_excerpt', 'vsc_espaces_insecables', 99 );
add_filter( 'nav_menu_item_title', 'vsc_espaces_insecables', 99 );
add_filter( 'widget_text', 'vsc_espaces_insecables', 99 );