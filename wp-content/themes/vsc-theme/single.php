<?php
/**
 * Gabarit d'un article — Clinique dentaire Home Dental
 * À placer dans le dossier du thème enfant : /wp-content/themes/ton-theme-enfant/single.php
 * Réutilise les classes .hd-carte du shortcode [hd_blogue] pour les articles connexes.
 */

get_header();

while ( have_posts() ) : the_post();

	$categories = get_the_category();
	$cat        = ! empty( $categories ) ? $categories[0] : null;

	// Temps de lecture (≈ 200 mots/minute)
	$mots    = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $mots / 200 ) );

	// Lien vers la page Blogue (gère la version anglaise si Polylang est actif)
	$page_blogue = get_page_by_path( 'blogue' );
	$url_blogue  = $page_blogue ? get_permalink( function_exists( 'pll_get_post' ) ? ( pll_get_post( $page_blogue->ID ) ?: $page_blogue->ID ) : $page_blogue->ID ) : home_url( '/blogue/' );
?>

<main id="content" class="hd-article">

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
			<?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
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

	<?php
	// Articles connexes : même catégorie, 3 articles
	$connexes = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'category__in'        => $cat ? array( $cat->term_id ) : array(),
		'ignore_sticky_posts' => true,
	) );

	if ( $connexes->have_posts() ) : ?>
		<section class="hd-article__connexes hd-blogue" style="--hd-colonnes: 3;">
			<h2 class="hd-article__connexes-titre">À lire aussi</h2>
			<div class="hd-blogue__grille">
				<?php while ( $connexes->have_posts() ) : $connexes->the_post();
					$img = function_exists( 'hd_blogue_image_url' ) ? hd_blogue_image_url( get_the_ID() ) : get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
				?>
					<article class="hd-carte">
						<a class="hd-carte__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php if ( $img ) : ?>
								<img src="<?php echo esc_url( $img ); ?>" alt="" loading="lazy">
							<?php else : ?>
								<span class="hd-carte__image-vide"></span>
							<?php endif; ?>
						</a>
						<div class="hd-carte__contenu">
							<p class="hd-carte__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
							</p>
							<h3 class="hd-carte__titre"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<a class="hd-carte__lien" href="<?php the_permalink(); ?>">
								Lire l’article<span class="screen-reader-text"> : <?php the_title(); ?></span>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</section>
	<?php endif;
	wp_reset_postdata(); ?>

</main>

<?php
endwhile;

get_footer();