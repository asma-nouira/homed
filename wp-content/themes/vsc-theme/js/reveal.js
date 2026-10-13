/**
 * Animations au défilement — vsc-theme
 *
 * Classes à mettre dans « Extra class » (Visual Composer) :
 *   reveal        fondu + montée
 *   reveal-left   glisse depuis la gauche
 *   reveal-right  glisse depuis la droite
 *   reveal-image  rideau du bas vers le haut + léger zoom arrière
 *   reveal-line   séparateur qui se dessine de gauche à droite
 *   reveal-group  (facultatif) les éléments qui apparaissent ENSEMBLE partent l'un après l'autre
 *   count-up      le chiffre compte de 0 à sa valeur (ex. « 5K », « 13 », « 30+ »)
 *   reveal-words  le texte « s'allume » mot par mot pendant le défilement (citations)
 *   parallax      l'élément se déplace plus lentement que la page (data-speed="0.15")
 *   delay-1 … delay-6  retard manuel (0,15 s par cran)
 *
 * Cascade : le retard est calculé au moment où les éléments apparaissent,
 * seulement entre ceux qui arrivent à l'écran en même temps, et plafonné.
 * → même avec 10 éléments « reveal » dans un bloc, le dernier n'attend jamais longtemps.
 */
( function () {
	'use strict';

	window.vscReveal = true;

	var html      = document.documentElement;
	var SEL       = '.reveal, .reveal-left, .reveal-right, .reveal-image, .reveal-line';
	var STEP      = 110; // ms entre deux éléments qui apparaissent ensemble
	var MAX_DELAY = 440; // ms : retard maximum, peu importe le nombre d'éléments
	var reduce    = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function showAll() {
		Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
			el.classList.add( 'is-visible' );
		} );
		html.classList.remove( 'vsc-anim' );
	}

	// L'éditeur Visual Composer affiche la page dans une iframe : on ne touche à rien,
	// sinon VC enregistre les classes « is-visible » et les retards dans le HTML de la page.
	var inEditor = /vcv-(editable|action|source-id)/.test( window.location.search ) ||
		window.vcvIsEditor ||
		window.self !== window.top;

	if ( inEditor ) {
		html.classList.remove( 'vsc-anim' ); // rien n'est jamais caché dans l'éditeur
		return;
	}

	/* ---------- Nettoyage : état enregistré par erreur dans le HTML ---------- */
	Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
		el.classList.remove( 'is-visible' );
		if ( ! /(^|\s)delay-\d/.test( el.className ) ) {
			el.style.removeProperty( '--reveal-delay' );
		}
	} );

	if ( reduce || ! ( 'IntersectionObserver' in window ) ) {
		showAll();
		return;
	}

	/* ---------- Ce qui est déjà à l'écran reste visible ---------- */
	var vh = window.innerHeight;
	Array.prototype.forEach.call( document.querySelectorAll( SEL ), function ( el ) {
		var r = el.getBoundingClientRect();
		if ( r.top < vh * 0.92 && r.bottom > 0 ) {
			el.classList.add( 'is-visible' );
		}
	} );

	// C'est le script qui active l'état « caché » : s'il ne tourne pas, tout reste visible.
	html.classList.add( 'vsc-anim' );

	/* ---------- Compteur ---------- */
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

	function prepareCounter( el ) {
		var node = firstNumberNode( el );
		if ( ! node ) {
			return;
		}
		var raw   = node.nodeValue;
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

		function frame( t ) {
			if ( ! start ) {
				start = t;
			}
			var p     = Math.min( ( t - start ) / duration, 1 );
			var eased = 1 - Math.pow( 1 - p, 3 );
			node.nodeValue = p < 1 ? format( target * eased ) : raw;
			if ( p < 1 ) {
				window.requestAnimationFrame( frame );
			}
		}

		node.nodeValue = format( 0 );
		counters.push( { el: el, run: function () { window.requestAnimationFrame( frame ); } } );
	}

	function countUp( el, delay ) {
		counters.forEach( function ( c ) {
			if ( c.el === el ) {
				window.setTimeout( c.run, delay + 200 );
			}
		} );
	}

	/* ---------- Observateur : cascade calculée à l'apparition ---------- */
	var observer = new IntersectionObserver( function ( entries ) {
		// Les éléments qui arrivent à l'écran dans le même lot, dans l'ordre de la page
		var batch = [];
		entries.forEach( function ( entry ) {
			if ( ! entry.isIntersecting ) {
				return;
			}
			( entry.target.__revealTargets || [ entry.target ] ).forEach( function ( t ) {
				batch.push( t );
			} );
			observer.unobserve( entry.target );
		} );

		batch.sort( function ( a, b ) {
			return a.compareDocumentPosition( b ) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
		} );

		batch.forEach( function ( el, i ) {
			var manual = /(^|\s)delay-\d/.test( el.className );
			var delay  = Math.min( i * STEP, MAX_DELAY );

			// un retard manuel (delay-1 … delay-6) est toujours respecté
			if ( ! manual ) {
				el.style.setProperty( '--reveal-delay', delay + 'ms' );
			} else {
				delay = parseFloat( getComputedStyle( el ).getPropertyValue( '--reveal-delay' ) ) * 1000 || 0;
			}

			el.classList.add( 'is-visible' );

			if ( el.classList.contains( 'count-up' ) ) {
				countUp( el, delay );
			}
		} );
	}, {
		threshold: 0.15,
		rootMargin: '0px 0px -8% 0px'
	} );

	Array.prototype.forEach.call( document.querySelectorAll( '.count-up' ), prepareCounter );

	Array.prototype.forEach.call( document.querySelectorAll( SEL + ', .count-up' ), function ( el ) {
		if ( el.classList.contains( 'is-visible' ) && ! el.classList.contains( 'count-up' ) ) {
			return; // déjà à l'écran au chargement
		}
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
			var frag = document.createDocumentFragment();
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
		var h        = window.innerHeight;
		var atBottom = ( window.innerHeight + window.pageYOffset ) >= ( document.documentElement.scrollHeight - 4 );

		wordBlocks.forEach( function ( block ) {
			var r        = block.el.getBoundingClientRect();
			var start    = h * .95;
			var end      = h * .55;
			var progress = ( start - r.top ) / ( start - end );
			if ( atBottom || r.bottom < h * .6 ) {
				progress = 1;
			}
			progress = Math.max( 0, Math.min( 1, progress ) );
			var lit = progress * block.words.length;
			block.words.forEach( function ( w, i ) {
				w.style.setProperty( '--rw', Math.max( 0, Math.min( 1, lit - i ) ).toFixed( 3 ) );
			} );
		} );

		parallaxEls.forEach( function ( el ) {
			var speed = parseFloat( el.getAttribute( 'data-speed' ) ) || .15;
			var r     = el.getBoundingClientRect();
			var delta = ( r.top + r.height / 2 ) - h / 2;
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