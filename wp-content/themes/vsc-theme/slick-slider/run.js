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
       2. CARROUSEL SERVICES (rangée Visual Composer « services-slider__track »)
       Chaque colonne VC = une carte
       ========================================================= */
      $(".services-slider__track > .vce-row-content").slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 3,
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
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 766,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 2
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

});