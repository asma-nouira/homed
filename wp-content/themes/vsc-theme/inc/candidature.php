<?php
/**
 * FORMULAIRE DE CANDIDATURE (Contact Form 7)
 * - Le CV est obligatoire : fichier, lien OU texte (au moins un des trois)
 * - Le lien doit être une adresse https
 *
 * À inclure dans functions.php : require get_template_directory() . '/inc/candidature.php';
 *
 * @package vsc-theme
 */

add_filter( 'wpcf7_validate', function ( $result, $tags ) {
	$cv_tag = null;
	foreach ( $tags as $tag ) {
		if ( 'cv' === $tag->name ) {
			$cv_tag = $tag;
			break;
		}
	}
	if ( ! $cv_tag ) {
		return $result; // ce n'est pas le formulaire de candidature
	}

	$submission = WPCF7_Submission::get_instance();
	$files      = $submission ? $submission->uploaded_files() : array();
	$has_file   = ! empty( $files['cv'] );
	$has_link   = ! empty( trim( (string) ( $_POST['cv-lien'] ?? '' ) ) );
	$has_text   = ! empty( trim( (string) ( $_POST['cv-texte'] ?? '' ) ) );

	if ( ! $has_file && ! $has_link && ! $has_text ) {
		$result->invalidate( $cv_tag, __( 'Ajoutez votre CV : joignez un fichier, collez un lien ou saisissez-le.', 'vsc-theme' ) );
	}

	foreach ( array( 'cv-lien', 'lettre-lien' ) as $name ) {
		$value = trim( (string) ( $_POST[ $name ] ?? '' ) );
		if ( $value && 0 !== strpos( $value, 'https://' ) ) {
			foreach ( $tags as $tag ) {
				if ( $name === $tag->name ) {
					$result->invalidate( $tag, __( 'Le lien doit commencer par https://', 'vsc-theme' ) );
				}
			}
		}
	}

	return $result;
}, 20, 2 );

/* Charge le petit script seulement sur les pages qui contiennent le formulaire */
add_action( 'wp_enqueue_scripts', function () {
	$post = get_post();
	if ( $post && false !== strpos( $post->post_content, 'contact-form-7' ) ) {
		wp_enqueue_script(
			'vsc-candidature',
			get_template_directory_uri() . '/js/candidature.js',
			array(),
			function_exists( 'vsc_theme_ver' ) ? vsc_theme_ver( '/js/candidature.js' ) : null,
			true
		);
	}
} );
