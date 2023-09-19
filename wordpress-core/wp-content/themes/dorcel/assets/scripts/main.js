(function ($) {
    $(document).ready(function () {
        //Handles the arrow show / hidden state on sliders
        function mouseOverSliders() {
            $('.custom-widget').on('mouseover', function () {
                $('.slick-arrow').removeClass('hide-arrows');
                $('.slick-arrow').addClass('show-arrows');
            });
            $('.custom-widget').on('mouseout', function () {
                $('.slick-arrow').removeClass('show-arrows');
                $('.slick-arrow').addClass('hide-arrows');
            });
        }

        mouseOverSliders();

        // Handles the hamburger menu active / hidden state when clicking on it, or closing it while opened.
        function handle_main_menu() {
            var header_menu = $('.header-section');

            header_menu.on('click', '.header-section__mobile, .header-section__elem__close', function (e) {
                if (e.currentTarget.className.indexOf('close') > 1) {
                    header_menu.removeClass('active').on('transitionend', function () {
                        header_menu.addClass('hidden').unbind('transitionend');
                    });
                    return true;
                }

                header_menu.addClass('active').removeClass('hidden');
            });
        }

        handle_main_menu();

        function handle_language_switcher() {
            $('body').on('click', '.wpml-ls-item-toggle', function () {
                var $sub_menu = $(this).nextAll('.wpml-ls-sub-menu');

                if ($sub_menu.css('visibility') === 'visible') {
                    $sub_menu.slideDown(200);
                } else {
                    $sub_menu.slideUp(200);
                }
            })
        }

        handle_language_switcher();

        // Only used on mobile resolutions
        function handle_header_menu() {
            var header_menu = $('.header-section');
            var activator_class = 'active';

            header_menu.on('click', '.header-section__elem__main__head', function () {
                var full_menu = $(this).siblings('ul');

                if (full_menu.hasClass(activator_class)) {
                    full_menu.fadeOut(200).removeClass(activator_class);
                    return;
                }

                full_menu.addClass(activator_class).fadeIn(250).css('display', 'flex');
            });
        }

        // Only used on tablet resolutions
        function handle_footer_menu() {
            var footer_menu = $('.footer-section');

            footer_menu.on('click', '.footer-section__elem__main__wrapper', function () {
                var full_menu = $(this).find('ul');

                if (full_menu.is(':hidden')) {
                    full_menu.show(300);
                    return true;
                }

                full_menu.hide(300);
            });
        }

        if (window.screen.width <= resolutions.medium) {
            handle_footer_menu();
        }

        handle_header_menu();

        // Disclaimer cookie
        function handle__disclaimer(disclaimerContainer) {
            var disclaimerCookie = Cookies.get('accept_disclaimer');

            if (disclaimerCookie !== 'accepted') {
                disclaimerContainer.fadeIn(300).css('display', 'flex');
            } else {
                disclaimerContainer.fadeOut(300);
            }
        }

        var disclaimerContainer = $('.disclaimer__wrapper');

        if (disclaimerContainer.length > 0) {
            $('.disclaimer-accept').on('click', function (e) {
                e.preventDefault();
                Cookies.set('accept_disclaimer', 'accepted', {expires: 365});
                handle__disclaimer(disclaimerContainer);
            });
        }

        // IMG to SVG
        function renderImgToSvg() {
            $('.svg').each(function () {
                var $img = jQuery(this);
                var imgURL = $img.attr('src');
                var attributes = $img.prop("attributes");

                $.get(imgURL, function (data) {
                    // Get the SVG tag, ignore the rest
                    var $svg = jQuery(data).find('svg');

                    // Remove any invalid XML tags
                    $svg = $svg.removeAttr('xmlns:a');

                    // Loop through IMG attributes and apply on SVG
                    $.each(attributes, function () {
                        $svg.attr(this.name, this.value);
                    });

                    // Replace IMG with SVG
                    $img.replaceWith($svg);
                }, 'xml');
            });
        }

        renderImgToSvg();

        // Showcase Slider
        function showcaseSlider() {
            $('#showcase__slider').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: false,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        }

        if ($('#showcase__slider').length > 0) {
            showcaseSlider();
        }

        // Card list Slider
        function cardListSlider() {
            $('#card-list__slider').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                infinite: false,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        }

        if ($('#card-list__slider').length > 0) {
            cardListSlider();
        }

        // Studios Article Slider
        function actressArticleSlider() {
            $('#actress-article-slider').slick({
                infinite: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                nextArrow: '<button class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
                prevArrow: '<button class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }

        if ($('#actress-article-slider').length > 0) {
            actressArticleSlider();
        }

        // Studios Films Slider
        function filmsSlider() {
            $('#actress-films-slider').slick({
                infinite: false,
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                nextArrow: '<button class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
                prevArrow: '<button class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
                responsive: [
                    {
                        breakpoint: 992,
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

        if ($('#actress-films-slider').length > 0) {
            filmsSlider();
        }

        // Studios Photos Slider
        function photosSlider() {
            $('#actress-photos-slider').slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                nextArrow: '<button class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
                prevArrow: '<button class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
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

        if ($('#actress-photos-slider').length > 0) {
            photosSlider();
        }

        // Fix height problem for horizontal sliders to have equal heights, since we can't use padding hacks, or flex stretch because of slick slider's methods
        var handleEqualSlideHeight = setInterval(function () {
            var $slick_wrappers = $('.slick-initialized');

            if ($slick_wrappers.length > 0) {
                $slick_wrappers.each(function (key, val) {
                    var $slides = $(val).find('.slick-slide');
                    var min_height = 0;

                    $slides.each(function (key, val) {
                        var $slide = $(val);
                        var slide_height = $slide.outerHeight();

                        if (slide_height > min_height) {
                            min_height = slide_height;
                        }
                    });

                    $slides.each(function (key, val) {
                        $(this).css('min-height', min_height + 'px');
                    });
                });

                clearInterval(handleEqualSlideHeight);
            }
        }, 300);

        // Load more content
        var loadMoreBtn = $('#more-content-btn');
        var loadMoreContainer = $('#more-content-container');
        function loadMoreContent() {
            var loadMoreContainerElements = loadMoreContainer.children();
            var invisibleElementsCount = loadMoreContainer.find("div:hidden").length;
            var loadStep = 4;

            if (invisibleElementsCount > 0) {
                loadMoreBtn.show();
            }

            $('body').on('click', loadMoreBtn, function () {
                var invisibleElements = loadMoreContainer.find("div:hidden");
                var nextStep = invisibleElements.slice(0,loadStep);
                nextStep.each(function () {
                    $(this).fadeIn(200);
                });

                var visibleElements = loadMoreContainer.find("div:visible");

                if (visibleElements.length >= loadMoreContainerElements.length) {
                    loadMoreBtn.hide();
                }
            })
        }

        if (loadMoreBtn.length > 0 && loadMoreContainer.length > 0) {
            loadMoreContent();
        }

        $(document).on('click', '.obfuscated-url', function() {
            var $this = $(this);
            var socials = {
                'facebook': 'https://www.facebook.com/',
                'twitter': 'https://twitter.com/',
                'youtube': 'https://www.youtube.com/user/',
                'instagram': 'https://www.instagram.com/',
                'faceshare': 'https://www.facebook.com/sharer/sharer.php?u=',
                'twitshare': 'http://twitter.com/share?url=',
            };

            var win = window.open(socials[$this.data('type')] + $this.data('target'), '_blank');
            win.focus();
        });

        var $blog_slider = $('#blog_slider');

        if ($blog_slider.length > 0) {
            $blog_slider.slick({
                dots: true,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear'
            });
        }
    });
})(jQuery);
