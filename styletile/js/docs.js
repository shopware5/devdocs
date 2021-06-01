;(function($) {
    'use strict';

    $.fn.stylesInliner = function() {
        var $template;
        var downloadStyles = function(link) {
            return $.ajax({
                url: link
            });
        };

        var createStyleCache = function(response) {
            var deferred = $.Deferred();

            $template = $('<template>', {
                'class': 'inline-styles',
                'html': response
            });

            $template.appendTo($('body'));
            return deferred.promise();
        };

        var inlineStylesIntoIframe = function() {
            $('iframe').each(function() {
                var $iframe = $(this.contentDocument),
                    $head = $iframe.find('style'),
                    $styles;

                $styles = $('<style>', {
                    'type': 'text/css',
                    'html': $template.html()
                });

                $styles.insertBefore($head.get(0));
                $(this).css('display', 'block');
                $(this).parent().addClass('done');
            });
        };

        return this.each(function() {
            var $item = $(this);
            downloadStyles($item.attr('data-href'))
                .done(createStyleCache)
                .done(inlineStylesIntoIframe);
        });
    };

    $('[data-iframe="true"]').stylesInliner();
})(jQuery);

$(document).ready(function() {

    var $id = $('section[id]'),
        $links = $('a[href^="#"]'),
        $folder = $('nav > ul > li > a.folder'),
        $code = $('section.code, .example.complete'),
        scrolling;

    /**
     * Active Navigation
     * -- Use to jump to the point you clicked on
     */

    $links.on('click', function(e) {
        var $this = $(this),
            href = $this.attr('href');
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(href).offset().top
        }, 500, function() {
            location.hash = href;
        });
        $this.addClass('active');
        $this.parent().siblings().children('a').removeClass('active');
        scrolling = true;
        setTimeout(function(){scrolling = false}, 1000);

        $('.open-menu').removeClass('opened');
    });

    /**
     * Passive Navigation
     * -- Use to update the sidebar navigation accordingly
     */

    $(window).on('scroll resize', function () {
        if (scrolling !== true) {
            var $this = $(this),
                scrolled = $this.scrollTop(),
                bodyHeight = $('body').height(),
                windowHeight = $this.height(),
                offset = 100,
                _scrolledBottom = (scrolled + windowHeight) > (bodyHeight - offset);

            if (_scrolledBottom) {
                $('aside > nav > ul > li > a.active + ul > li a').removeClass('active');
                $('aside > nav > ul > li > a.active + ul > li:last-child a').addClass('active');
            } else {
                $id.each(function () {
                    var $this = $(this),
                        id = $this.attr('id'),
                        idOffset = $this.offset().top,
                        $matching = $('a[href="#' + id + '"]');

                    if (scrolled + (windowHeight / 3) > idOffset) {
                        $matching.addClass('active');
                        $matching.parent().siblings().children('a').removeClass('active');
                        return true;
                    }
                });
            }
        }
    });

    /**
     * Folders
     * -- Use to open and close directories in the navigation
     */

    $folder.on('click', function() {
        var $this = $(this);

        if ($this.parent().hasClass('is--active')) {
            closeAccordion($this);
        } else if ($this.parent().hasClass('search--active')) {
            closeAccordion($this);
        } else {
            closeAccordion($folder);
            openAccordion($this);
        }
    });

    function closeAccordion($el) {
        var $contentAll = $el.siblings('ul');

        $el.parents('li').removeClass('is--active search--active');
        $contentAll.slideUp(500);
    }

    function openAccordion($el) {
        var $content = $el.siblings('ul');

        $el.parents('li').addClass('is--active');
        $content.slideDown(500);
    }

    /**
     * Mobile Navigation Symbol on Banner
     * -- Use to optimise the look of the Navigation Menu Button if on a Banner
     */

    $(window).on('scroll resize', function() {
        var scrolled        = $(window).scrollTop(),
            $banner         = $('section.banner'),
            bannerHeight    = $banner.height(),
            _scrolled       = scrolled >= bannerHeight;

        $('body.has--banner').toggleClass('beyond--banner', _scrolled)
    });

    /**
     * Expand Code Examples
     * -- Use to expand a specific code example
     */

    $code.on('click', '.expand', function() {
        var $this       = $(this),
            $parent     = $this.parents('section.code, .example.complete'),
            _expanded   = $parent.hasClass('expanded');
        $parent.toggleClass('expanded', !_expanded);

        if (_expanded) {
            $this.text('Expand Code');
            $parent.removeClass('expanded');
        } else {
            $this.text('Collapse Code');
            $parent.addClass('expanded');
        }
    });

    /**
     * Intro Banner
     * -- Use to change opacity for intro banner
     */

    $(window).on('scroll resize', function() {
        scrollBanner();
    });

    $(document).ready(function() {
        scrollBanner();
    });

    function scrollBanner() {
        var scrolled    = $(window).scrollTop(),
            $banner     = $('.banner.intro'),
            $this       = $banner.find('.section--inner'),
            $searchFixed = $('.search.fixed'),
            bannerHeight = $banner.height(),
            percent     = 100 / bannerHeight * scrolled,
            opacity     = 1 + (percent / 100) * -1.5;

        $this.css('opacity', opacity);
        $this.siblings('.bridge').css({
            '-webkit-clip-path': 'polygon(100% ' + percent + '%, 0% 100%, 100% 100%)',
            'clip-path': 'polygon(100% ' + percent + '%, 0% 100%, 100% 100%)'
        });

        if (opacity < 0.33) {
            $this.css('pointer-events', 'none');
        } else {
            $this.css('pointer-events', 'all');
        }

        $searchFixed.toggleClass('is--fixed', opacity < 0.1);
    }

    /**
     * File in Sidebar
     * -- Use to get the file in visible in the sidebar
     */

    var $page           = $('.page-wrap'),
        $viewFile       = $('a.view-file'),
        $closeFile      = $('aside.file .close'),
        $downloadFile   = $('aside.file .download');

    $viewFile.on('click', function() {
        var $this   = $(this),
            $file   = $('aside.file code'),
            _active = $this.hasClass('is--active'),
            file    = $this.attr('data-file');

        $.get(file, function(data) {
            $file.html(data);
            $file.each(function(i, e) {
                hljs.highlightBlock(e);
            });
        });

        if (_active) {
            closeFileSidebar();
        } else {
            openFileSidebar($this);
            $downloadFile.attr('href', file);
        }
    });

    $closeFile.on('click', function() {
        closeFileSidebar();
    });

    function openFileSidebar($el) {
        $viewFile.removeClass('is--active');
        $el.addClass('is--active');
        $page.addClass('file--active');
    }

    function closeFileSidebar() {
        $viewFile.removeClass('is--active');
        $page.removeClass('file--active');
    }

    /**
     * Live Search
     * -- Use to filter through every category, file and directory
     */

    var $search = $('input[type="search"].search'),
        $searchFor = $('aside nav .folder + ul a');

    jQuery.expr[':'].contains = function(a,i,m){
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
    };

    $search.on('change keyup', function() {
        var input = $(this).val().toLowerCase();

        // Regular Folders
        $searchFor.each(function() {
            var $this       = $(this),
                $folder     = $this.parent('li').parent('ul'),
                $directory  = $this.parent('li').parent('ul').parent('li'),
                _contains   = $this.is(':contains("' + input + '")'),
                _FolderContains = $folder.is(':contains("' + input + '")'),
                _DirContains    = $directory.is(':contains("' + input + '")');

            if (_contains === false) {
                $this.hide();
            } else {
                $this.show();
                $this.parents('ul').show();
                $directory.addClass('search--active');
            }

            if (_FolderContains === false) {
                $folder.hide();
            } else {
                $folder.show();
            }

            if (_DirContains === false) {
                $directory.addClass('hide');
            } else {
                $directory.removeClass('hide');
            }

            if (input === '') {
                $this.show();
                $directory.removeClass('hide search--active');

                if (!$directory.hasClass('is--active')) {
                    $folder.hide();
                }
            }
        });

        // No Folders
        var $preview = $('.preview'),
            _preview = $preview.is(':contains("' + input + '")');

        if (_preview === false) {
            $preview.parent('li').addClass('hide');
        } else {
            $preview.parent('li').removeClass('hide');
        }
    });

    $links.on('click', function() {
        $search.val('');
        $searchFor.each(function() {
            resetNavigation($(this));
        });
    });

    function resetNavigation($el) {
        var $folder     = $el.parent('li').parent('ul'),
            $directory  = $el.parent('li').parent('ul').parent('li');

        if (!$el.hasClass('folder')) {
            $el.show();
            $directory.removeClass('search--active');

            if (!$directory.hasClass('is--active')) {
                $folder.hide();
            }
        }
    }

    /**
     * Live Search (Classes for Cheatsheet)
     * -- Use to filter through every class within the cheatsheet
     */

    var $searchClass    = $('input[type="search"].class'),
        $searchForClass = $('.searchable li');

    $searchClass.on('change keyup', function() {
        var input = $(this).val().toLowerCase();

        $searchForClass.each(function() {
            var $this       = $(this),
                $col        = $this.parents('.col'),
                $colItems   = $col.find('li'),
                $row        = $this.parents('.row'),
                $rowItems   = $row.find('li'),
                // $sec        = $this.parents('.searchable > section'),
                // $secItems   = $sec.find('li'),
                _contains   = $this.is(':contains("' + input + '")'),
                _containsCol = $colItems.is(':contains("' + input + '")'),
                _containsRow = $rowItems.is(':contains("' + input + '")');
                // _containsSec = $secItems.is(':contains("' + input + '")');

            $this.toggleClass('hidden', !_contains);
            $col.toggleClass('hidden', !_containsCol);
            $row.toggleClass('hidden', !_containsRow);
            // $sec.toggleClass('hidden', !_containsSec);

            if (input === '') {
                $this.removeClass('hidden');
                $col.removeClass('hidden');
                $row.removeClass('hidden');
                // $sec.removeClass('hidden');
            }
        });
    });

    /**
     * Popover
     * -- Design Pattern Function
     * -- Use to toggle popovers
     */

    var $popoverHolder = $('.popover--holder'),
        popoverActiveClass = 'is--active';

    $('body').on('click tap', '.popover--holder a', function(e) {
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

});