$(document).ready(function() {
    /**
     * Toggle
     * -- Use to toggle any class on any element by clicking on it
     */

    $('[data-toggle]').on('click', function() {
        var $this = $(this),
            toggle = $this.attr('data-toggle'),
            _toggle = $this.hasClass(toggle);
        $this.toggleClass(toggle, !_toggle)
    });

    /**
     * Popover
     * -- Use to toggle popovers
     */

    var $popoverHolder = $('.popover--holder'),
        popoverActiveClass = 'is--active';

    $popoverHolder.on('click tap', 'a', function(e) {
        var $this = $(this);

        popover(e, $this)
    });

    $(window).on('click tap', function(e) {
        if ($('.popover--holder.is--active').length !== 0) {
            var _popover = $(e.target).hasClass('popover');
            if (!_popover) {
                $popoverHolder.removeClass(popoverActiveClass);
            }
        }
    });

    function popover(e, $el) {
        e.stopPropagation();

        var $parent = $el.parents('.popover--holder');

        if ($parent.hasClass(popoverActiveClass)) {
            $popoverHolder.removeClass(popoverActiveClass);
        } else {
            $popoverHolder.removeClass(popoverActiveClass);
            $parent.addClass(popoverActiveClass);
        }
    }

    /**
     * Animate
     * -- Use to animate and appearing element when scrolling
     */

    var $window = $(window),
        $animate = $('.animate');
    $animate.addClass('animate--soon');
    $window.on('scroll', function(){
        animate();
    });

    animate();

    function animate() {
        $animate.each(function() {
            var $el = $(this),
                top = $el.offset().top,
                topWindow = $window.scrollTop() + $window.height(),
                topWindowLate = $window.scrollTop() + ($window.height() / 1.2),

                // Options
                _late = $el.hasClass('animate--late'),
                _fill = $el.hasClass('fill-up'),
                _circle = $el.hasClass('circle-animation'),

                // Extend function for iframes
                $iframe = $el.find('iframe'),
                data = $iframe.attr('data-src');

            if (top < topWindow && !_late ||
                top < topWindowLate && _late) {
                $el.removeClass('animate--soon');

                if ($iframe.length !== 0 && !$iframe[0].hasAttribute('src')) {
                    $iframe.attr('src', data);
                }

                if (_fill) {
                    animateFill($el);
                }
                if (_circle) {
                    circleAnimation($el);
                }
            }
        });
    }

    function animateFill($el) {
        var $filled = $el.find('.filled'),
            fill = $filled.attr('data-fill');
        $filled.css('width', fill);
    }

    function circleAnimation($el) {
        var $path = $el.find('.fill path'),
            timing = $path.attr('data-timing');
        $path.css('animation', '');
        $path.css('animation', 'circleFill ' + timing);
    }

    /**
     * Slider
     * -- Use to initiate a slider on any desired element with the class ".is--slider-[viewport]"
     */

    $(window).on('resize', function() {
        updateSlider();
    });

    updateSlider();

    function createSlider($el, items, loop) {
        if ($($el).length) {
            $($el).addClass('owl-carousel');

            if (items >= 2) {
                $($el).owlCarousel({
                    loop: loop,
                    dots: false,
                    autoHeight: true,
                    nav: true,
                    navText: false,
                    margin: 20,
                    responsive: {
                        0: {
                            items: 1
                        },
                        599: {
                            items: items - 1
                        },
                        959: {
                            items: items
                        }
                    }
                });
            } else {
                $($el).owlCarousel({
                    loop: loop,
                    dots: false,
                    autoHeight: true,
                    nav: true,
                    navText: false,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    items: items,
                    margin: 20
                });
            }
        }
    }

    function deleteSlider($el) {
        $($el).trigger('destroy.owl.carousel').removeClass('owl-carousel');
    }

    function updateSlider() {
        var windowWidth = $(window).width();

        createSlider('.is--slider-four-items', 4, false);
        createSlider('.is--slider-three-items', 3, false);
        createSlider('.is--slider-two-items', 2, false);
        createSlider('.is--slider', 1, true);

        if (windowWidth <= 1259) {
            createSlider('.is--slider-xl', 1);
        } else {
            deleteSlider('.is--slider-xl');
        }

        if (windowWidth <= 1023) {
            createSlider('.is--slider-l', 1);
        } else {
            deleteSlider('.is--slider-l');
        }

        if (windowWidth <= 959) {
            createSlider('.is--slider-m', 1);
        } else {
            deleteSlider('.is--slider-m');
        }

        if (windowWidth <= 767) {
            createSlider('.is--slider-s', 1);
        } else {
            deleteSlider('.is--slider-s');
        }

        if (windowWidth <= 479) {
            createSlider('.is--slider-xs', 1);
        } else {
            deleteSlider('.is--slider-xs');
        }
    }

    /**
     * Input Placeholder
     * -- Use to get the placeholder as label inside an filled input field
     */

    var $input = $('input');

    $input.on('keyup', function() {
        checkInput($(this));
    });

    $input.each(function() {
        var $this = $(this);

        if ($this.prop('defaultValue') !== '') {
            $this.addClass('not-empty');
        }
    });

    function checkInput ($el) {
        var _notEmpty = $el.val() !== '';
        $el.toggleClass('not-empty', _notEmpty);
    }

    /**
     * Accordion
     * -- Use to make an accordion functional
     */

    $('.accordion--column').on('click', '.accordion--title', function() {
        var $this = $(this);

        if ($this.parents('.accordion').hasClass('is--active')) {
            closeAccordion($this);
        } else {
            closeAccordion($this);
            openAccordion($this);
        }
    });

    function closeAccordion($el) {
        var $contentAll = $el.parents('.accordion--column').find('.accordion--content');

        $contentAll.parents('.accordion').removeClass('is--active');
        $contentAll.slideUp(500);
    }

    function openAccordion($el) {
        var $content = $el.siblings('.accordion--content');

        $el.parents('.accordion').addClass('is--active');
        $content.slideDown(500);
    }

    /**
     * Tab-Accordion
     * -- Use to make and tab-accordion functional
     */

    var accordionRunning = false,
        $accordionTabs = $('.accordion--tabs');

    $accordionTabs.on('click', '.accordion--tab li', function() {
        var $this = $(this),
            data = $this.attr('data-accordion');

        if (!$this.hasClass('is--active') && accordionRunning !== true) {
            accordionRunning = true;
            $this.siblings('li').removeClass('is--active');
            $this.addClass('is--active');
            adjustAccordionTab($this, data);
            adjustFluidTab($this);
        }
    });

    $accordionTabs.each(function() {
        var $this = $(this),
            $tabRow = $this.find('.accordion--tab'),
            $activeTab = $this.find('li[data-accordion].is--active').first();

        initAccordionTab($this);

        if ($tabRow.hasClass('is--fluid')) {
            initFluidTab($tabRow);
            adjustFluidTab($activeTab, true);
        }
    });

    $(window).on('resize', function() {
        var $contentsHolder = $('.accordion--contents');

        $contentsHolder.each(function() {
            var $this = $(this),
                $tabs = $this.siblings('.accordion--tab'),
                contentHeight = $this.find('.is--active').height();

            $this.css('height', contentHeight);

            if ($tabs.hasClass('is--fluid')) {
                adjustFluidTab($tabs.find('li[data-accordion].is--active'), false, true);
            }
        });

    });

    function initAccordionTab($el) {
        var $contentsHolder = $el.find('.accordion--contents');

        $contentsHolder.each(function() {
            var $this = $(this),
                $content = $this.find('.is--active'),
                contentHeight = $content.height(),
                $prev = $content.prevAll(),
                $next = $content.nextAll();

            $this.css({
                height: contentHeight
            });

            $prev.addClass('is--prev').removeClass('is--next');
            $next.addClass('is--next').removeClass('is--prev');
        });
    }

    function adjustAccordionTab($el, data) {
        var $contentsHolder = $el.parents('.row').find('.accordion--contents'),
            $content        = $contentsHolder.find('div[data-accordion="' + data + '"]'),
            $contentAll     = $contentsHolder.find('div[data-accordion]'),
            $prev           = $content.prevAll(),
            $next           = $content.nextAll();

        $contentsHolder.each(function() {
            var $this = $(this),
                $active = $this.find('div[data-accordion="' + data + '"]'),
                contentHeight = $active.height();

            $this.animate({
                height: contentHeight
            }, 350);
        });

        $contentAll.animate({
            opacity: 0
        });

        $contentAll.removeClass('is--active');
        $content.addClass('is--active').removeClass('is--prev is--next');
        $prev.addClass('is--prev').removeClass('is--next');
        $next.addClass('is--next').removeClass('is--prev');

        $content.animate({
            opacity: 1
        }, 100, function() {
            accordionRunning = false;
        });
    }

    // Fluid Tab Accordion Navigation

    function initFluidTab($el) {
        $el.find('ul').append('<div class="fluid--navigation-tab"></div>');
    }

    function adjustFluidTab($el, init, animate) {
        var width           = $el.width(),
            height          = $el.height(),
            margin          = parseInt($el.css('marginLeft')),
            parentOffset    = $el.parents('ul').offset().left,
            parentOffsetTop = $el.parents('ul').offset().top,
            offset          = $el.offset().left - parentOffset - margin,
            offsetTop       = $el.offset().top - parentOffsetTop,
            background      = $el.attr('data-background'),
            $fluidTab       = $el.siblings('.fluid--navigation-tab');

        if (init !== true && animate !== true) {
            $fluidTab.animate({
                width: width,
                height: height,
                top: offsetTop,
                left: offset
            }, 125, 'linear');
        } else {
            $fluidTab.delay(100).css({
                width: width,
                height: height,
                top: offsetTop,
                left: offset
            });
        }

        if (background) {
            $fluidTab.css('background', background);
        }
    }

    /**
     * Scroll Parallax
     * -- Use to make an element scroll faster or slower than others
     */

    var $scrolling = $('[data-parallax]');

    $(window).on('scroll resize', function() {
        $scrolling.each(function() {
            scrollingTransform($(this));
        });
    });

    $scrolling.each(function() {
        scrollingTransform($(this));
    });

    function scrollingTransform($el) {
        var scrollM         = $el.attr('data-parallax'),
            scrollDisable   = $el.attr('data-disable-parallax'),

            multiplicator   = scrollM ? scrollM : 1, // if [data-parallax] has no value, use 1 as initial value
            disableParallax = scrollDisable !== undefined ? scrollDisable : 480; // if [data-disable-parallax] is not set, use 480 as initial value

        var windowHeight    = $window.height(),
            scrolled        = $window.scrollTop(),
            height          = $el.outerHeight(),
            offsetTop       = $el.offset().top,
            offsetBottom    = offsetTop + height,
            percent         = 100 / (offsetBottom + windowHeight - (offsetTop)) * ((scrolled + windowHeight) - offsetTop),
            movement        = (percent - 50) * multiplicator,

            _disable        = disableParallax && $window.width() < disableParallax,
            _visible        = offsetTop < windowHeight + scrolled && offsetBottom + windowHeight > windowHeight + scrolled;

        $el.toggleClass('now-scrolling', _visible);

        if (_disable) {
            $el.css('transform', 'translate3d(0, 0, 0)');
        } else if (_visible) {
            $el.css('transform', 'translate3d(0, ' + movement + 'px, 0)');
        }
    }

    /**
     * Mouse Parallax
     * -- Use to make an element move faster or slower than others while move the mouse
     */

    var $mouse = $('.mouse-parallax-holder .parallax');
    var isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0));

    $(window).on('mousemove', function(e) {
        $mouse.each(function() {
            mouseParallax($(this), e);
        });
    });

    function mouseParallax($el, e) {
        if (!isTouch) {
            var windowWidth     = $window.width(),
                windowHeight    = $window.height(),
                mouseM          = $el.attr('data-mouse-parallax'),

                multiplicator   = mouseM !== undefined ? 100 / mouseM : 10; // if [data-mouse-parallax] is not set, use 10 as initial value

            var mouseX          = e.clientX,
                mouseY          = e.clientY,
                centerX         = (100 / windowWidth * mouseX) - 50,
                centerY         = (100 / windowHeight * mouseY) - 50,
                posX            = centerX * -1 / multiplicator,
                posY            = centerY * -1 / multiplicator;

            $el.css('transform', 'translate3d(calc(-50% + ' + posX + '%), calc(-50% + ' + posY + '%), 0)');
        }
    }


    /**
     * Lightbox
     * -- Use to make images clickable and extendable in a seperate, large box
     */

    var lightboxContainer;

    var $body       = $('body'),
        $lbItems    = $('.lightbox--items');

    $lbItems.on('click', 'img', function() {
        var $this = $(this);

        removeLightbox();
        initLightbox($this);
        initLightboxItems($this);
        checkPrevNext();
    });

    $body.on('click', '.lightbox .backdrop, .lightbox .close', function() {
        removeLightbox();
    });

    $body.on('click', '.lightbox .prev, .lightbox .next', function() {
        updateLightbox($(this));
        checkPrevNext();
    });

    // Mouse Tracking

    var triggerDown = false,
        triggerStart,
        triggerMove;

    $body.on('mousedown touchstart', '.lightbox img', function(e) {

        if (e.originalEvent.type === 'mousedown') {
            triggerStart = e.pageX;
        } else {
            triggerStart = e.originalEvent.touches[0].pageX;
        }

        triggerDown = true;
    });

    $body.on('mouseup touchend', '.lightbox img', function() {
        resetStyles();
    });

    $body.on('mousemove touchmove', '.lightbox img', function(e) {
        dragLightboxItem(e);
    });

    function resetStyles() {
        var $lbItem     = $('.lightbox .item--container.is--active'),
            $prev       = $lbItem.prev(),
            $next       = $lbItem.next();

        $lbItem.css('transform', '');
        $prev.css('transform', '');
        $next.css('transform', '');
        triggerDown = false;
        return false;
    }

    function removeLightbox() {
        $('.lightbox').remove();
        $body.removeClass('noscroll');
    }

    function initLightbox($el) {
        if ($el.parents('.item').siblings().length > 0) {
            lightboxContainer =
                '<div class="lightbox">' +
                '<div class="items--holder"></div>' +
                '<div class="prev"></div>' +
                '<div class="next"></div>' +
                '<div class="close"></div>' +
                '<div class="backdrop"></div>' +
                '</div>';
        } else {
            lightboxContainer =
                '<div class="lightbox">' +
                '<div class="items--holder"></div>' +
                '<div class="close"></div>' +
                '<div class="backdrop"></div>' +
                '</div>';
        }

        $body.append(lightboxContainer);
        $body.addClass('noscroll');
    }

    function initLightboxItems($el) {
        var imgSource   = $el[0].src,
            $item       = $el.parents('.item'),
            $itemsAll   = $el.parents('.lightbox--items').find('.item'),
            $lbHolder   = $('.lightbox .items--holder'),
            $lbItem;

        $itemsAll.removeClass('is--prev is--next is--active');
        $item.addClass('is--active');
        $item.prevAll().addClass('is--prev');
        $item.nextAll().addClass('is--next');

        $itemsAll.each(function() {
            var $this = $(this),
                $source = $this.find('source'),
                _source = $source.length === 0,
                $img    = $this.find('img'),
                _img    = $img.length === 0;

            // Find optimal image
            if (!_source) {
                imgSource = $source[0].srcset;
            } else if (_img) {
                imgSource = $img[0].srcset;
            } else {
                imgSource = $img[0].src;
            }

            if ($this.hasClass('is--active')) {
                $lbHolder.append('<div class="item--container is--active"><img src="' + imgSource + '" draggable="false" /></div>');
            } else {
                $lbHolder.append('<div class="item--container"><img src="' + imgSource + '" draggable="false" /></div>');
            }
        });

        // Find active on init

        $lbItem = $lbHolder.find('.is--active');
        $lbItem.prevAll().addClass('is--prev');
        $lbItem.nextAll().addClass('is--next');
    }

    function updateLightbox($el, action) {
        var name        = $el.attr('class'),
            $lbItem     = $('.lightbox .item--container.is--active'),
            $prev       = $lbItem.prev(),
            $next       = $lbItem.next(),
            _prev       = $prev.length,
            _next       = $next.length;

        if (name === 'prev' && _prev || action === 'prev') {
            $lbItem.removeClass('is--active is--prev');
            $lbItem.addClass('is--next');
            $prev.removeClass('is--prev');
            $prev.addClass('is--active');
        }

        if (name === 'next' && _next || action === 'next') {
            $lbItem.removeClass('is--active is--next');
            $lbItem.addClass('is--prev');
            $next.removeClass('is--next');
            $next.addClass('is--active');
        }

        checkPrevNext();
        resetStyles();
    }

    function checkPrevNext() {
        var $prevButton = $('.lightbox .prev'),
            $nextButton = $('.lightbox .next'),
            $lbItem     = $('.lightbox .item--container.is--active'),
            $prev       = $lbItem.prev(),
            $next       = $lbItem.next(),
            _prev       = $prev.length === 0,
            _next       = $next.length === 0;

        $prevButton.toggleClass('disabled', _prev);
        $nextButton.toggleClass('disabled', _next);
    }

    function dragLightboxItem(e) {
        var $lbItem     = $('.lightbox .item--container.is--active'),
            $prev       = $lbItem.prev(),
            $next       = $lbItem.next(),
            _prev       = $prev.length,
            _next       = $next.length,
            threshold   = 100;

        if ($(window).width() < 769) {
            threshold = 50;
        }

        if (triggerDown === true) {

            if (e.originalEvent.type === 'mousemove') {
                triggerMove = e.pageX - triggerStart;
            } else {
                triggerMove = e.originalEvent.touches[0].pageX - triggerStart;
            }

            if (!_prev && triggerMove >= 0 ||
                !_next && triggerMove <= 0) {
                triggerMove = 0
            }

            $lbItem.css('transform', 'translateX(' + triggerMove + 'px)');
            $prev.css('transform', 'translateX(calc(-100% - ' + triggerMove + 'px))');
            $next.css('transform', 'translateX(calc(100% - ' + triggerMove + 'px))');

            if (triggerMove >= threshold && _prev) {
                updateLightbox($(this), 'prev');
                resetStyles();
            }
            if (triggerMove <= -threshold && _next) {
                updateLightbox($(this), 'next');
                resetStyles();
            }
        }
    }
});