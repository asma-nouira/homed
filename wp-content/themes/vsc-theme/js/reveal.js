
Claude a terminé sa réponse.
voici mon ancien fichier animation.css qu'est ce que je dois ajouter: 

@charset "UTF-8";
.center {
  text-align: center;
}

.uppercase {
  text-transform: uppercase;
}

a {
  text-decoration: none;
  color: inherit;
}

.white {
  color: white;
}

.underline {
  text-decoration: underline;
}

.nowrap {
  white-space: nowrap;
}

@media (max-width: 767px) {
  .nowrap {
    white-space: inherit;
  }
}

p {
  margin-block-start: 0;
  margin-block-end: 0;
}

h1, h2, h3 {
  margin-block-start: 0;
  margin-block-end: 0;
  font-weight: inherit;
}

.vce {
  margin-bottom: 0;
}

ul {
  margin-block-start: 0;
  margin-block-end: 0;
  padding-inline-start: 0;
}

.test-bloc {
  display: flex;
  align-items: center;
  justify-content: center;
}

.full-width .vce.vce-single-image-wrapper {
  width: 100%;
  height: auto;
}

.full-width .vce.vce-single-image-wrapper figure {
  width: 100%;
  height: auto;
}

.full-width .vce.vce-single-image-wrapper figure .vce-single-image-figure-inner {
  width: 100% !important;
  height: auto;
}

/* =========================================================
   ANIMATIONS AU DÉFILEMENT (js/reveal.js)
   L'état « caché » ne s'applique que si JS tourne (html.vsc-anim),
   donc sans JS tout le contenu reste visible.
   ========================================================= */
.vsc-anim .reveal,
.vsc-anim .reveal-left,
.vsc-anim .reveal-right {
  opacity: 0;
  transition: opacity 0.9s cubic-bezier(0.22, 0.61, 0.36, 1), transform 0.9s cubic-bezier(0.22, 0.61, 0.36, 1);
  transition-delay: var(--reveal-delay, 0s);
  will-change: opacity, transform;
}

.vsc-anim .reveal {
  transform: translateY(calc(30vw / 19.2));
}

.vsc-anim .reveal-left {
  transform: translateX(calc(-60vw / 19.2));
}

.vsc-anim .reveal-right {
  transform: translateX(calc(60vw / 19.2));
}

.vsc-anim .reveal.is-visible,
.vsc-anim .reveal-left.is-visible,
.vsc-anim .reveal-right.is-visible {
  opacity: 1;
  transform: none;
}

.vsc-anim .reveal-image {
  overflow: hidden;
  clip-path: inset(100% 0 0 0 round calc(20vw / 19.2));
  transition: clip-path 1.2s cubic-bezier(0.22, 0.61, 0.36, 1);
  transition-delay: var(--reveal-delay, 0s);
}

.vsc-anim .reveal-image img,
.vsc-anim .reveal-image .vce-asset-background-simple-item {
  transform: scale(1.12);
  transition: transform 1.6s cubic-bezier(0.22, 0.61, 0.36, 1);
  transition-delay: var(--reveal-delay, 0s);
}

.vsc-anim .reveal-image.is-visible {
  clip-path: inset(0 0 0 0 round calc(20vw / 19.2));
}

.vsc-anim .reveal-image.is-visible img,
.vsc-anim .reveal-image.is-visible .vce-asset-background-simple-item {
  transform: scale(1);
}

.vsc-anim .reveal-line {
  transform: scaleX(0);
  transform-origin: left center;
  transition: transform 1s cubic-bezier(0.22, 0.61, 0.36, 1);
  transition-delay: var(--reveal-delay, 0s);
}

.vsc-anim .reveal-line.is-visible {
  transform: scaleX(1);
}

.vsc-anim .delay-1 {
  --reveal-delay: 0.15s;
}

.vsc-anim .delay-2 {
  --reveal-delay: 0.3s;
}

.vsc-anim .delay-3 {
  --reveal-delay: 0.45s;
}

.vsc-anim .delay-4 {
  --reveal-delay: 0.6s;
}

.vsc-anim .delay-5 {
  --reveal-delay: 0.75s;
}

.vsc-anim .delay-6 {
  --reveal-delay: 0.9s;
}

@media (max-width: 767px) {
  .vsc-anim .reveal {
    transform: translateY(20px);
  }
  .vsc-anim .reveal-left {
    transform: translateX(-30px);
  }
  .vsc-anim .reveal-right {
    transform: translateX(30px);
  }
}

.count-up {
  font-variant-numeric: tabular-nums;
}

@media (prefers-reduced-motion: reduce) {
  .vsc-anim .reveal,
.vsc-anim .reveal-left,
.vsc-anim .reveal-right,
.vsc-anim .reveal-image,
.vsc-anim .reveal-line {
    opacity: 1 !important;
    transform: none !important;
    clip-path: none !important;
    transition: none !important;
  }
}

/*# sourceMappingURL=../css/animations.css.map */
Adding animation effects and checking compiled utility class dependencies.
Adding animation effects and checking compiled utility class dependencies.
Il faut ajouter deux choses dans ton animations.css. Tout le reste est déjà bon.

1. Juste avant .count-up {, ajoute :

css
/* ---------- Texte qui s'allume mot par mot (reveal-words) ---------- */
.vsc-anim .rw-word {
  opacity: calc(0.18 + 0.82 * var(--rw, 0));
  transition: opacity 0.2s linear;
}

/* ---------- Parallaxe douce (ne pas combiner avec reveal sur le même élément) ---------- */
.vsc-anim .parallax {
  transform: translate3d(0, var(--parallax-y, 0px), 0);
  will-change: transform;
}
2. Dans le bloc @media (prefers-reduced-motion: reduce) à la fin, ajoute deux lignes après la règle existante, avant le } de fermeture du @media :

css
@media (prefers-reduced-motion: reduce) {
  .vsc-anim .reveal,
  .vsc-anim .reveal-left,
  .vsc-anim .reveal-right,
  .vsc-anim .reveal-image,
  .vsc-anim .reveal-line {
    opacity: 1 !important;
    transform: none !important;
    clip-path: none !important;
    transition: none !important;
  }

  /* ↓ à ajouter */
  .vsc-anim .rw-word { opacity: 1 !important; }
  .vsc-anim .parallax { transform: none !important; }
}
Deux points importants

Le CSS seul ne suffit pas : il faut aussi remplacer ton js/reveal.js par celui du zip animations-maj. C'est lui qui découpe la citation en mots (rw-word) et calcule --rw et --parallax-y pendant le défilement. Sans lui, la citation reste pâle.
Modifie aussi le SCSS, sinon ton ajout disparaîtra : ce fichier est généré par gulp à partir de sass/animations.scss, et au prochain gulp animations il sera écrasé. Le plus simple est de remplacer ton sass/animations.scss par celui du zip, qui contient déjà ces ajouts, puis de recompiler.
Screenshot_3.png
voici comment s'affiche les liste sans les puces et le text à droite est un peut transparent

A exécuté une commande, fichiers partagés
Il y a deux problèmes différents. J'ai corrigé le texte transparent, et pour les puces il faut ajouter quelques lignes de CSS.

1. Le texte de droite est à moitié transparent

C'est l'effet « mot par mot » qui était trop lent. Il n'allumait la citation au complet que lorsqu'elle arrivait presque en haut de l'écran. Sur ta capture, elle est au milieu de l'écran et elle est encore à moitié pâle. Si la section est près du bas de la page, on ne peut même pas défiler assez pour tout allumer.

J'ai corrigé reveal.js, le fichier est ci-dessous :

la citation est maintenant entièrement allumée quand elle arrive vers le milieu de l'écran ;
si on atteint le bas de la page, elle s'allume au complet de toute façon.
Je l'ai testé dans la même position que ta capture : tous les mots sont allumés.

Si tu préfères qu'elle soit simplement lisible, sans effet, retire la classe reveal-words de la citation.

2. Les puces de la liste n'apparaissent pas

Ton animations.css contient ul { padding-inline-start: 0; }, et Visual Composer enlève aussi les puces par défaut. D'après ta capture, tu as monté la liste dans un bloc de texte Visual Composer, donc mes classes equipe__list ne sont pas dans ton HTML.

Pour corriger ça :

Dans Visual Composer, mets puces dans le champ Extra class du bloc de texte qui contient la liste.
Ajoute ceci dans ton style.scss :
scss
// Liste avec puces brunes (classe « puces » sur le bloc de texte Visual Composer)
.puces ul {
  margin: 0;
  padding: 0;
  list-style: none;

  li {
    position: relative;
    padding-left: 1em;
    list-style: none;

    &::marker {
      content: none;
    }

    &::before {
      content: '';
      position: absolute;
      left: .15em;
      top: .72em;
      width: .32em;
      height: .32em;
      background: #7a4535;
      border-radius: 50%;
    }
  }
}
Recompile avec gulp styles : chaque ligne aura sa petite puce ronde brune. Si tu veux que les puces arrivent une par une, mets aussi reveal-group sur le bloc de texte et reveal sur chaque li, en mode HTML du bloc.

Un rappel sur le texte : ta liste affiche encore « Une expertise reconnue en implantologie, orthodontie et apnée du sommeil ». Comme je te l'expliquais, cette formulation est à faire valider par la clinique, parce qu'elle peut laisser croire à un titre de spécialiste. Je te suggère plutôt « Une expérience en implantologie, orthodontie et apnée du sommeil ».


Reveal
JS 





Claude est une IA et peut faire des erreurs. Veuillez vérifier les réponses.
Reveal · JS
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
 





