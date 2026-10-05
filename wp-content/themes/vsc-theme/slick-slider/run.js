jQuery(function ($) {

    /* =========================================================
       Ne rien faire dans l'éditeur Visual Composer
       (sinon Slick modifie le HTML et VC l'enregistre tel quel)
       ========================================================= */
    if (/vcv-(editable|action|source-id)/.test(window.location.search) || window.vcvIsEditor) {
        return;
    }

    /* =========================================================
       Numéros de pagination : 01, 02, 03…
       ========================================================= */
    function numeroPagination(slider, i) {
        var num = (i + 1 < 10 ? '0' : '') + (i + 1);
        return '<button type="button">' + num + '</button>';
    }

    /* =========================================================
       Nettoie un slider enregistré « déjà transformé » par l'éditeur :
       remet les colonnes d'origine, enlève pistes, copies et numéros figés
       ========================================================= */
    function nettoyer($el) {
        if (!$el.length) {
            return $el;
        }
        // Un vrai Slick actif : on l'arrête proprement
        if ($el[0].slick) {
            $el.slick('unslick');
            return $el;
        }
        // Restes enregistrés dans le HTML
        if ($el.find('.slick-track').length) {
            $el.find('.slick-cloned').remove();
            var $cols = $el.find('.slick-track').children();
            $el.find('.slick-dots, .slick-arrow').remove();
            $el.children('.slick-list').remove();
            $el.prepend($cols);

            $cols.removeClass('slick-slide slick-current slick-active slick-center slick-cloned')
                 .removeAttr('style tabindex aria-hidden role aria-describedby data-slick-index')
                 .each(function () {
                     if (/^slick-slide/.test(this.id)) {
                         this.removeAttribute('id');
                     }
                 });
        }
        $el.removeClass('slick-initialized slick-slider slick-dotted');
        return $el;
    }

    /* =========================================================
       1. SLIDER D'ACCUEIL (hero)
       ========================================================= */
    nettoyer($(".hero-slider > .vce-row-content")).slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 20000,
        infinite: true,
        dots: true,
        arrows: false,
        customPaging: numeroPagination
    })
    .on('setPosition', function (event, slick) {
        slick.$slides.css('height', slick.$slideTrack.height() + 'px');
    });

    /* =========================================================
       2. CARROUSEL SERVICES : 3 cartes, avance par 3 → 01 / 02
       ========================================================= */
    nettoyer($(".services-slider__track > .vce-row-content")).slick({
        centerMode: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: false,
        speed: 700,
        dots: true,
        arrows: false,
        customPaging: numeroPagination,
        responsive: [
            {
                breakpoint: 768,
                settings: { slidesToShow: 1, slidesToScroll: 1 }
            }
        ]
    });

 /* =========================================================
       3. GALERIE PHOTOS : photo centrale + voisines coupées
       ========================================================= */
    nettoyer($(".galerie-slider > .vce-row-content")).slick({
        centerMode: true,
        centerPadding: '19.2vw',   // largeur visible des photos voisines (≈ 370px @1920)
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        speed: 800,
        cssEase: 'cubic-bezier(.22, .61, .36, 1)',
        dots: true,
        arrows: false,
        customPaging: numeroPagination,
        responsive: [
            {
                breakpoint: 1200,   // tablette
                settings: { centerPadding: '80px' }
            },
            {
                breakpoint: 768,    // mobile
                settings: { centerPadding: '28px' }
            }
        ]
    });


});

   