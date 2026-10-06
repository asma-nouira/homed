/**
 * Formulaire de candidature
 * - « Lien » / « Saisir manuellement » ouvrent le champ correspondant
 * - « Joindre » affiche le nom du fichier choisi
 */
( function () {
	'use strict';

	document.querySelectorAll( '.cand-doc' ).forEach( function ( doc ) {

		// Boutons qui ouvrent un champ
		doc.querySelectorAll( '[data-show]' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var panel = doc.querySelector( '[data-panel="' + btn.getAttribute( 'data-show' ) + '"]' );
				if ( ! panel ) {
					return;
				}
				var open = panel.hasAttribute( 'hidden' );

				// un seul champ ouvert à la fois
				doc.querySelectorAll( '[data-panel]' ).forEach( function ( p ) { p.setAttribute( 'hidden', '' ); } );
				doc.querySelectorAll( '[data-show]' ).forEach( function ( b ) {
					b.setAttribute( 'aria-expanded', 'false' );
					b.classList.remove( 'is-active' );
				} );

				if ( open ) {
					panel.removeAttribute( 'hidden' );
					btn.setAttribute( 'aria-expanded', 'true' );
					btn.classList.add( 'is-active' );
					var field = panel.querySelector( 'input, textarea' );
					if ( field ) {
						field.focus();
					}
				}
			} );
		} );

		// Nom du fichier choisi
		var fileBtn = doc.querySelector( '.cand-doc__btn--file' );
		var input   = fileBtn ? fileBtn.querySelector( 'input[type="file"]' ) : null;
		var label   = fileBtn ? fileBtn.querySelector( '.cand-doc__label' ) : null;
		if ( input && label ) {
			input.addEventListener( 'change', function () {
				var name = input.files && input.files[ 0 ] ? input.files[ 0 ].name : '';
				label.textContent = name || label.getAttribute( 'data-default' );
				fileBtn.classList.toggle( 'is-active', !! name );
			} );
		}
	} );

	// Après un envoi réussi, CF7 vide le formulaire : on remet les boutons à zéro
	document.addEventListener( 'wpcf7mailsent', function ( e ) {
		e.target.querySelectorAll( '.cand-doc__label' ).forEach( function ( l ) {
			l.textContent = l.getAttribute( 'data-default' );
		} );
		e.target.querySelectorAll( '.is-active' ).forEach( function ( el ) { el.classList.remove( 'is-active' ); } );
		e.target.querySelectorAll( '[data-panel]' ).forEach( function ( p ) { p.setAttribute( 'hidden', '' ); } );
	} );
} )();
