/**
 * Animations au défilement — vsc-theme
 *
 * Classes à mettre dans « Extra class » (Visual Composer) :
 *   reveal        fondu + montée
 *   reveal-left   glisse depuis la gauche
 *   reveal-right  glisse depuis la droite
 *   reveal-image  rideau du bas vers le haut + léger zoom arrière
 *   reveal-line   séparateur qui se dessine de gauche à droite
 *   reveal-group  les éléments animés à l'intérieur apparaissent un par un
 *   count-up      le chiffre compte de 0 à sa valeur (ex. « 5K », « 13 », « 30+ »)
 *   delay-1 … delay-6  retard manuel (0,15 s par cran)
 */
( function () {
	'use strict';

	window.vscReveal = true; // signale au filet de sécurité (functions.php) que le script tourne

	var html   = document.documentElement;
	var SEL    = '.reveal, .reveal-left, .reveal-right, .reveal-image, .reveal-line';
	var STEP   = 150; // ms entre deux éléments d'un reveal-group
	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function showAll() {
		Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
			el.classList.add( 'is-visible' );
		} );
		html.classList.remove( 'vsc-anim' );
	}

	// Pas d'animation : mouvement réduit, vieux navigateur, ou éditeur Visual Composer
	if ( reduce || ! ( 'IntersectionObserver' in window ) || document.body.classList.contains( 'vcv-editor-theme-hfs' ) || window.location.search.indexOf( 'vcv-editable' ) > -1 ) {
		showAll();
		return;
	}

	/* ---------- Retards en cascade dans les groupes ---------- */
	Array.prototype.forEach.call( document.querySelectorAll( '.reveal-group' ), function ( group ) {
		Array.prototype.forEach.call( group.querySelectorAll( SEL ), function ( el, i ) {
			if ( ! el.style.getPropertyValue( '--reveal-delay' ) ) {
				el.style.setProperty( '--reveal-delay', ( i * STEP ) + 'ms' );
			}
		} );
	} );

	/* ---------- Compteur ---------- */
	// Anime seulement le 1er texte qui contient un chiffre : le reste du bloc (titres, liens, gras) reste intact.
	function firstNumberNode( root ) {
		var walker = document.createTreeWalker( root, NodeFilter.SHOW_TEXT, null, false );
		var node;
		while ( ( node = walker.nextNode() ) ) {
			if ( /\d/.test( node.nodeValue ) ) {
				return node;
			}
		}
		return null;
	}

	var counters = [];

	// Prépare le compteur et affiche 0 tout de suite (évite de voir « 5K » puis « 0 »)
	function prepareCounter( el ) {
		var node = firstNumberNode( el );
		if ( ! node ) {
			return;
		}
		var raw = node.nodeValue;

		// « 5K » → 5 + « K » ; « 1 500+ » → 1500 + « + » ; « 4,8 » → 4.8
		var match = raw.match( /^([^\d]*?)(\d[\d\s.,]*\d|\d)(.*)$/ );
		if ( ! match ) {
			return;
		}
		var prefix   = match[ 1 ];
		var numStr   = match[ 2 ].replace( /\s/g, '' );
		var suffix   = match[ 3 ];
		var decimals = ( numStr.match( /[.,](\d+)$/ ) || [ '', '' ] )[ 1 ].length;
		var sep      = numStr.indexOf( ',' ) > -1 ? ',' : '.';
		var target   = parseFloat( decimals ? numStr.replace( ',', '.' ) : numStr.replace( /[.,]/g, '' ) );
		var duration = parseInt( el.getAttribute( 'data-duration' ), 10 ) || 1600;
		var start    = null;

		if ( isNaN( target ) ) {
			return;
		}

		function format( v ) {
			return prefix + v.toFixed( decimals ).replace( '.', sep ) + suffix;
		}

		node.nodeValue = format( 0 );

		counters.push( { el: el, run: function () {
			window.requestAnimationFrame( frame );
		} } );

		function frame( t ) {
			if ( ! start ) {
				start = t;
			}
			var p     = Math.min( ( t - start ) / duration, 1 );
			var eased = 1 - Math.pow( 1 - p, 3 ); // ralentit à la fin
			node.nodeValue = p < 1 ? format( target * eased ) : raw;
			if ( p < 1 ) {
				window.requestAnimationFrame( frame );
			}
		}

	}

	function countUp( el ) {
		counters.forEach( function ( c ) {
			if ( c.el === el ) {
				c.run();
			}
		} );
	}

	/* ---------- Observateur ---------- */
	var observer = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			if ( ! entry.isIntersecting ) {
				return;
			}
			var targets = entry.target.__revealTargets || [ entry.target ];
			targets.forEach( function ( t ) {
				t.classList.add( 'is-visible' );
			} );
			var el = entry.target;

			if ( el.classList.contains( 'count-up' ) ) {
				// Le compteur démarre avec le retard du bloc qui le contient
				var host  = el.closest( SEL );
				var delay = host ? parseInt( getComputedStyle( host ).getPropertyValue( '--reveal-delay' ), 10 ) || 0 : 0;
				window.setTimeout( function () {
					countUp( el );
				}, delay + 200 );
			}
			observer.unobserve( entry.target );
		} );
	}, {
		threshold: 0.15,
		rootMargin: '0px 0px -8% 0px'
	} );

	Array.prototype.forEach.call( document.querySelectorAll( '.count-up' ), prepareCounter );

	Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
		// Un élément entièrement masqué par clip-path n'est jamais « visible » pour l'observateur :
		// pour reveal-image, on observe son parent à la place.
		if ( el.classList.contains( 'reveal-image' ) && el.parentElement ) {
			var parent = el.parentElement;
			parent.__revealTargets = ( parent.__revealTargets || [] ).concat( el );
			observer.observe( parent );
		} else {
			observer.observe( el );
		}
	} );
} )();
