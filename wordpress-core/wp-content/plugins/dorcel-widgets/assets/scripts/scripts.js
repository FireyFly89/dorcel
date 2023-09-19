(function ($) {
    var $homepage_slider = $('.post_slider_widget__wrapper');

    if ($homepage_slider.length > 0) {
        $homepage_slider.slick({
            dots: true,
            speed: 800,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            autoplay: true,
            autoplaySpeed: 5000,
        });
    }

    var $magazine_slider = $('#magazine_slider');

    if ($magazine_slider.length > 0) {
        $magazine_slider.slick({
            infinite: true,
            slidesToShow: 12,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            swipeToSlide: true,
            initialSlide: 0,
            responsive: [
                {
                    breakpoint: 1366,
                    settings: {
                        slidesToShow: 8
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    }

    var $video_slider = $('#video_slider');

    if ($video_slider.length > 0) {
        $video_slider.slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            swipeToSlide: true,
            initialSlide: 0,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    var $article_slider = $('#article_slider');

    if ($article_slider.length > 0) {
        $article_slider.slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            swipeToSlide: true,
            initialSlide: 0,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
})(jQuery);
