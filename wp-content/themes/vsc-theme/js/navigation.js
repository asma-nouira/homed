/**
 * Menu principal — vsc-theme
 *
 * - Bouton hamburger (mobile / tablette)
 * - Boutons chevron injectés sur chaque item parent (ouvre/ferme le sous-menu)
 * - Desktop : ouverture au survol (CSS) + au clic / clavier (JS)
 * - Échap et clic à l'extérieur ferment tout
 */
( function () {
	'use strict';

	var BREAKPOINT = 1199; // doit correspondre à $menu-breakpoint dans header.scss

	var header = document.getElementById( 'masthead' );
	var nav    = document.getElementById( 'site-navigation' );
	var toggle = header ? header.querySelector( '.menu-toggle' ) : null;
	var menu   = document.getElementById( 'primary-menu' );

	if ( ! header || ! nav || ! menu ) {
		if ( toggle ) {
			toggle.style.display = 'none';
		}
		return;
	}

	var isMobile = function () {
		return window.innerWidth <= BREAKPOINT;
	};

	/* ---------- Boutons chevron sur les items parents ---------- */
	var parents = menu.querySelectorAll( '.menu-item-has-children' );

	Array.prototype.forEach.call( parents, function ( li, index ) {
		var link = li.querySelector( ':scope > a' );
		var sub  = li.querySelector( ':scope > .sub-menu' );
		if ( ! link || ! sub ) {
			return;
		}

		var subId = sub.id || 'sub-menu-' + index;
		sub.id = subId;

		var btn = document.createElement( 'button' );
		btn.type = 'button';
		btn.className = 'submenu-toggle';
		btn.setAttribute( 'aria-expanded', 'false' );
		btn.setAttribute( 'aria-controls', subId );
		btn.innerHTML = '<span class="screen-reader-text">Ouvrir le sous-menu ' + link.textContent.trim() + '</span>';

		link.insertAdjacentElement( 'afterend', btn );

		btn.addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			var open = li.classList.contains( 'is-open' );

			// Sur desktop, un seul sous-menu de 1er niveau ouvert à la fois
			if ( ! open && ! isMobile() && li.parentNode === menu ) {
				closeAll();
			}
			setOpen( li, ! open );
		} );
	} );

	function setOpen( li, open ) {
		li.classList.toggle( 'is-open', open );
		var btn = li.querySelector( ':scope > .submenu-toggle' );
		if ( btn ) {
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
		if ( ! open ) {
			Array.prototype.forEach.call( li.querySelectorAll( '.is-open' ), function ( child ) {
				setOpen( child, false );
			} );
		}
	}

	function closeAll() {
		Array.prototype.forEach.call( menu.querySelectorAll( ':scope > li.is-open' ), function ( li ) {
			setOpen( li, false );
		} );
	}

	/* ---------- Hamburger ---------- */
	function setNavOpen( open ) {
		header.classList.toggle( 'nav-open', open );
		document.documentElement.classList.toggle( 'nav-locked', open );
		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
		if ( ! open ) {
			closeAll();
		}
	}

	if ( toggle ) {
		toggle.addEventListener( 'click', function () {
			setNavOpen( ! header.classList.contains( 'nav-open' ) );
		} );
	}

	/* ---------- Fermetures ---------- */
	document.addEventListener( 'click', function ( e ) {
		if ( ! header.contains( e.target ) ) {
			closeAll();
			setNavOpen( false );
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' !== e.key ) {
			return;
		}
		var openTop = menu.querySelector( ':scope > li.is-open' );
		closeAll();
		if ( header.classList.contains( 'nav-open' ) ) {
			setNavOpen( false );
			if ( toggle ) {
				toggle.focus();
			}
		} else if ( openTop ) {
			var b = openTop.querySelector( ':scope > .submenu-toggle' );
			if ( b ) {
				b.focus();
			}
		}
	} );

	// Desktop : fermer quand le focus clavier sort du menu
	menu.addEventListener( 'focusout', function ( e ) {
		if ( ! isMobile() && e.relatedTarget && ! menu.contains( e.relatedTarget ) ) {
			closeAll();
		}
	} );

	// Réinitialiser en changeant de format (rotation tablette, redimensionnement)
	var wasMobile = isMobile();
	window.addEventListener( 'resize', function () {
		var now = isMobile();
		if ( now !== wasMobile ) {
			wasMobile = now;
			closeAll();
			setNavOpen( false );
		}
	} );
} )();