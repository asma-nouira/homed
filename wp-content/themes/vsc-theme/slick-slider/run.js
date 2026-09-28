jQuery(function ($) {

    /* =========================================================
       1. SLIDER D'ACCUEIL (hero)
       ========================================================= */
    $(".hero-slider > .vce-row-content").slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 20000,
        infinite: true,
        dots: true,
        arrows: false,
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
        ],
        customPaging: function (slider, i) {
            var num = (i + 1 < 10 ? '0' : '') + (i + 1);
            return '<button type="button">' + num + '</button>';
        }
    })
    .on('setPosition', function (event, slick) {
        slick.$slides.css('height', slick.$slideTrack.height() + 'px');
    });

    /* =========================================================
       2. CARROUSEL « Des services dentaires complets »
       ========================================================= */
    $('.services-slider__track').each(function () {
        var $track = $(this);
        var $dots  = $track.closest('.services-slider').find('.services-slider__dots');

        // Ordre d'apparition des cartes (0, 1, 2, 0, 1, 2…) pour l'effet en cascade
        $track.children('.ss-card-wrap').each(function (i) {
            this.style.setProperty('--i', i % 3);
        });

        $track.slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: false,
            speed: 700,
            cssEase: 'cubic-bezier(.22, .61, .36, 1)',
            arrows: false,
            dots: true,
            appendDots: $dots,
            customPaging: function (slider, i) {
                var num = (i + 1 < 10 ? '0' : '') + (i + 1);
                return '<button type="button">' + num + '</button>';
            },
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 768,  settings: { slidesToShow: 1, slidesToScroll: 1 } }
            ]
        });
    });

});