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
 *   reveal-words  le texte « s'allume » mot par mot pendant le défilement (citations)
 *   parallax      l'élément se déplace plus lentement que la page (data-speed="0.15")
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
	// (l'éditeur est détecté par l'adresse de la page : Visual Composer peut mettre ses classes
	//  sur <body> aussi sur le site public, ce qui désactivait toutes les animations)
	// L'éditeur Visual Composer affiche la page dans une iframe : on ne touche à rien,
	// sinon VC enregistre les classes « is-visible » et les retards dans le HTML de la page.
	var inEditor = /vcv-(editable|action|source-id)/.test( window.location.search ) ||
		window.vcvIsEditor ||
		window.self !== window.top;

	if ( inEditor ) {
		html.classList.remove( 'vsc-anim' ); // tout reste visible pour pouvoir éditer
		return;
	}

	/* ---------- Nettoyage : état enregistré par erreur dans le HTML ---------- */
	// (is-visible et --reveal-delay ajoutés quand le script a tourné dans l'éditeur)
	Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
		el.classList.remove( 'is-visible' );
		if ( el.closest( '.reveal-group' ) ) {
			el.style.removeProperty( '--reveal-delay' );
		}
	} );

	if ( reduce || ! ( 'IntersectionObserver' in window ) ) {
		showAll();
		return;
	}

	/* ---------- Retards en cascade dans les groupes ---------- */
	Array.prototype.forEach.call( document.querySelectorAll( '.reveal-group' ), function ( group ) {
		Array.prototype.forEach.call( group.querySelectorAll( SEL ), function ( el, i ) {
			// on respecte un retard déjà choisi (delay-1 … delay-6 ou style en ligne)
			if ( ! el.style.getPropertyValue( '--reveal-delay' ) && ! /(^|\s)delay-\d/.test( el.className ) ) {
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

	/* ---------- Texte qui s'allume mot par mot (reveal-words) ---------- */
	// Chaque mot passe de pâle à sa couleur normale selon la position dans l'écran.
	var wordBlocks = [];

	function splitWords( el ) {
		var walker = document.createTreeWalker( el, NodeFilter.SHOW_TEXT, null, false );
		var nodes  = [];
		var node;
		while ( ( node = walker.nextNode() ) ) {
			if ( node.nodeValue.trim() ) {
				nodes.push( node );
			}
		}
		var words = [];
		nodes.forEach( function ( textNode ) {
			var frag  = document.createDocumentFragment();
			textNode.nodeValue.split( /(\s+)/ ).forEach( function ( part ) {
				if ( ! part ) {
					return;
				}
				if ( /^\s+$/.test( part ) ) {
					frag.appendChild( document.createTextNode( part ) );
				} else {
					var span = document.createElement( 'span' );
					span.className = 'rw-word';
					span.textContent = part;
					frag.appendChild( span );
					words.push( span );
				}
			} );
			textNode.parentNode.replaceChild( frag, textNode );
		} );
		el.setAttribute( 'aria-label', el.textContent.replace( /\s+/g, ' ' ).trim() );
		return words;
	}

	Array.prototype.forEach.call( document.querySelectorAll( '.reveal-words' ), function ( el ) {
		wordBlocks.push( { el: el, words: splitWords( el ) } );
	} );

	/* ---------- Parallaxe douce (parallax) ---------- */
	var parallaxEls = Array.prototype.slice.call( document.querySelectorAll( '.parallax' ) );

	function onScroll() {
		var vh       = window.innerHeight;
		var atBottom = ( window.innerHeight + window.pageYOffset ) >= ( document.documentElement.scrollHeight - 4 );

		wordBlocks.forEach( function ( block ) {
			var r = block.el.getBoundingClientRect();
			// 0 quand le haut de la citation entre à 95 % de l'écran,
			// 1 (tout allumé) quand elle arrive vers le milieu de l'écran (55 %)
			var start    = vh * .95;
			var end      = vh * .55;
			var progress = ( start - r.top ) / ( start - end );
			// Bas de page atteint (ou citation entièrement visible et en haut) : tout allumé
			if ( atBottom || r.bottom < vh * .6 ) {
				progress = 1;
			}
			progress = Math.max( 0, Math.min( 1, progress ) );
			var lit = progress * block.words.length;
			block.words.forEach( function ( w, i ) {
				var o = Math.max( 0, Math.min( 1, lit - i ) );
				w.style.setProperty( '--rw', o.toFixed( 3 ) );
			} );
		} );

		parallaxEls.forEach( function ( el ) {
			var speed = parseFloat( el.getAttribute( 'data-speed' ) ) || .15;
			var r     = el.getBoundingClientRect();
			var delta = ( r.top + r.height / 2 ) - vh / 2;
			el.style.setProperty( '--parallax-y', ( -delta * speed ).toFixed( 1 ) + 'px' );
		} );
	}

	if ( wordBlocks.length || parallaxEls.length ) {
		var ticking = false;
		var request = function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( function () {
					ticking = false;
					onScroll();
				} );
			}
		};
		window.addEventListener( 'scroll', request, { passive: true } );
		window.addEventListener( 'resize', request );
		onScroll();
	}
} )();