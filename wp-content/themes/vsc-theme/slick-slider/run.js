jQuery(function ($) {

    /* =========================================================
       Numéros de pagination : 01, 02, 03…
       ========================================================= */
    function numeroPagination(slider, i) {
        var num = (i + 1 < 10 ? '0' : '') + (i + 1);
        return '<button type="button">' + num + '</button>';
    }

    /* =========================================================
       1. SLIDER D'ACCUEIL (hero)
       ========================================================= */
    $(".hero-slider > .vce-row-content").not('.slick-initialized').slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 20000,
        infinite: true,
        dots: true,
        arrows: false,
        customPaging: numeroPagination,
        responsive: [
            {
                breakpoint: 1366,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 766,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 400,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            }
        ]
    })
    .on('setPosition', function (event, slick) {
        slick.$slides.css('height', slick.$slideTrack.height() + 'px');
    });

    /* =========================================================
       2. CARROUSEL SERVICES
       6 colonnes, 3 visibles, avance par 3 → pagination 01 / 02
       ========================================================= */
    $(".services-slider__track > .vce-row-content").not('.slick-initialized').slick({
        centerMode: false,     // commence par la 1re colonne, à gauche
        slidesToShow: 3,
        slidesToScroll: 3,     // un numéro par groupe de 3 cartes
        infinite: false,       // pas de copies : ordre 1 → 6
        autoplay: false,
        speed: 700,
        cssEase: 'cubic-bezier(.22, .61, .36, 1)',
        dots: true,
        arrows: false,
        customPaging: numeroPagination,
        responsive: [
            {
                breakpoint: 1200,   // tablette : 2 cartes
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,    // mobile : 1 carte
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
        ]
    });

});