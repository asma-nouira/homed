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
    $('.services-slider__track').each(function () {
        var $row     = $(this);
        var $content = $row.children('.vce-row-content');

        if (!$content.length || $content.hasClass('slick-initialized')) {
            return;
        }

        // Conteneur de la pagination, placé juste après la rangée
        var $dots = $row.next('.services-slider__dots');
        if (!$dots.length) {
            $dots = $('<div class="services-slider__dots"></div>').insertAfter($row);
        }

        $content.children('.vce-col').each(function (i) {
            // Ordre d'apparition des cartes (0, 1, 2, 0, 1, 2…)
            this.style.setProperty('--i', i % 3);

            // Charge tout de suite les images d'arrière-plan des cartes de la page 2
            // (le chargement différé de VC ne les voit pas tant qu'elles sont hors écran)
            $(this).find('.vce-asset-background-simple-item[data-background-image]').each(function () {
                if (!this.style.backgroundImage) {
                    this.style.backgroundImage = 'url("' + this.getAttribute('data-background-image') + '")';
                    this.setAttribute('data-loaded', 'true');
                }
            });
        });

        // Nombre de cartes selon la largeur d'écran (3 / 2 / 1)
        function nbCartes() {
            var w = Math.min(window.innerWidth, document.documentElement.clientWidth);
            return w < 768 ? 1 : (w < 1200 ? 2 : 3);
        }

        // On (re)lance Slick nous-mêmes au lieu d'utiliser l'option « responsive » (plus fiable
        // avec les colonnes Visual Composer : pas de boucle au changement de taille d'écran).
        function demarrer() {
            var n = nbCartes();
            $content.data('nb-cartes', n).slick({
                rows: 0,   // pas de <div> ajoutée autour des colonnes
                slidesToShow: n,
                slidesToScroll: n,
                infinite: false,
                speed: 700,
                cssEase: 'cubic-bezier(.22, .61, .36, 1)',
                arrows: false,
                dots: true,
                appendDots: $dots,
                customPaging: function (slider, i) {
                    var num = (i + 1 < 10 ? '0' : '') + (i + 1);
                    return '<button type="button">' + num + '</button>';
                }
            });
        }

        demarrer();

        var timer;
        $(window).on('resize', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                if (nbCartes() !== $content.data('nb-cartes')) {
                    $content.slick('unslick');
                    demarrer();
                }
            }, 150);
        });
    });

});