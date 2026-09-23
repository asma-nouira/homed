jQuery(document).on('ready', function() {
    jQuery(".hero-slider > .vce-row-content").slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 20000,
        infinite: true,
        dots: true,
        arrows:false,
        
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

});

