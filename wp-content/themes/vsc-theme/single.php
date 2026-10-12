<?php
/**
 * Gabarit d'un article — Clinique dentaire Home Dental
 * Mise en page : contenu à gauche, « À lire aussi » à droite.
 * À placer dans le thème enfant : /wp-content/themes/ton-theme-enfant/single.php
 */

get_header();

while ( have_posts() ) : the_post();

	$categories = get_the_category();
	$cat        = ! empty( $categories ) ? $categories[0] : null;

	// Temps de lecture (≈ 200 mots/minute)
	$mots    = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $mots / 200 ) );

	// Page Blogue (version traduite si Polylang est actif)
	$page_blogue = get_page_by_path( 'blogue' );
	if ( $page_blogue ) {
		$id_blogue  = function_exists( 'pll_get_post' ) ? ( pll_get_post( $page_blogue->ID ) ?: $page_blogue->ID ) : $page_blogue->ID;
		$url_blogue = get_permalink( $id_blogue );
	} else {
		$url_blogue = home_url( '/blogue/' );
	}
?>

<main id="content" class="hd-article" style="background-color:#DED7CD;">
	<div class="hd-article__mise-en-page">

		<!-- Colonne principale -->
		<div class="hd-article__principal">

			<header class="hd-article__entete">
				<nav class="hd-article__fil" aria-label="Fil d’Ariane">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( $url_blogue ); ?>">Blogue</a>
				</nav>

				<?php if ( $cat ) : ?>
					<a class="hd-article__cat" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endif; ?>

				<h1 class="hd-article__titre"><?php the_title(); ?></h1>

				<p class="hd-article__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						Publié le <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
					</time>
					<span><?php echo esc_html( $minutes ); ?> min de lecture</span>
				</p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="hd-article__image">
					<?php
					// Taille « full » : on charge l'image originale, jamais une version réduite
					the_post_thumbnail( 'full', array(
						'alt'   => esc_attr( get_the_title() ),
						'sizes' => '(max-width: 1024px) 100vw, 760px',
					) );
					?>
				</figure>
			<?php endif; ?>

			<article <?php post_class( 'hd-article__contenu' ); ?>>
				<?php the_content(); ?>
			</article>

			<aside class="hd-article__rdv">
				<div>
					<h2>Une question sur votre santé buccodentaire ?</h2>
					<p>Notre équipe à Kirkland peut vous conseiller et planifier votre prochain rendez-vous.</p>
				</div>
				<div class="hd-article__rdv-actions">
					<a class="hd-bouton" href="<?php echo esc_url( home_url( '/contactez-nous/#contact' ) ); ?>">Prendre rendez-vous</a>
					<a class="hd-bouton hd-bouton--contour" href="tel:+15146979045">(514) 697-9045</a>
				</div>
			</aside>

			<?php
			$precedent = get_previous_post();
			$suivant   = get_next_post();
			if ( $precedent || $suivant ) : ?>
				<nav class="hd-article__nav" aria-label="Articles précédent et suivant">
					<?php if ( $precedent ) : ?>
						<a class="hd-article__nav-lien hd-article__nav-lien--prec" href="<?php echo esc_url( get_permalink( $precedent ) ); ?>">
							<span class="hd-article__nav-label">Article précédent</span>
							<span class="hd-article__nav-titre"><?php echo esc_html( get_the_title( $precedent ) ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $suivant ) : ?>
						<a class="hd-article__nav-lien hd-article__nav-lien--suiv" href="<?php echo esc_url( get_permalink( $suivant ) ); ?>">
							<span class="hd-article__nav-label">Article suivant</span>
							<span class="hd-article__nav-titre"><?php echo esc_html( get_the_title( $suivant ) ); ?></span>
						</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>

		</div>

		<!-- Colonne de droite : À lire aussi -->
		<?php
		$connexes = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => 4,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => $cat ? array( $cat->term_id ) : array(),
			'ignore_sticky_posts' => true,
		) );

		if ( $connexes->have_posts() ) : ?>
			<aside class="hd-article__lateral" aria-labelledby="hd-a-lire-aussi">
				<div class="hd-article__lateral-collant">
					<h2 id="hd-a-lire-aussi" class="hd-article__lateral-titre">À lire aussi</h2>

					<ul class="hd-mini-liste">
						<?php while ( $connexes->have_posts() ) : $connexes->the_post();
							$img = function_exists( 'hd_blogue_image_url' )
								? hd_blogue_image_url( get_the_ID() )
								: get_the_post_thumbnail_url( get_the_ID(), 'medium' );
						?>
							<li class="hd-mini">
								<a class="hd-mini__lien" href="<?php the_permalink(); ?>">
									<span class="hd-mini__image">
										<?php if ( $img ) : ?>
											<img src="<?php echo esc_url( $img ); ?>" alt="" loading="lazy">
										<?php endif; ?>
									</span>
									<span class="hd-mini__texte">
										<span class="hd-mini__titre"><?php the_title(); ?></span>
										<time class="hd-mini__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
											<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
										</time>
									</span>
								</a>
							</li>
						<?php endwhile; ?>
					</ul>

					<a class="hd-article__tous" href="<?php echo esc_url( $url_blogue ); ?>">Voir tous les articles</a>
				</div>
			</aside>
		<?php endif;
		wp_reset_postdata(); ?>

	</div>
</main>

<?php
endwhile;

get_footer();