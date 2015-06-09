$(function() {
    $('.btn--header-menu, .btn--offcanvas-menu').on('click', function() {
        $('.offcanvas--menu').toggleClass('js--is-active');
    });

    var responsiveLogo = function() {
        var $logo = $('.logo img');

        $.data($logo, 'data', {
            original: $logo.attr('src'),
            small: $logo.attr('data-small-src')
        });

        var onResize = function() {
            var width = window.innerWidth,
                data = $.data($logo, 'data');

            if(width <= 545) {
                $logo.attr('src', data.small);
            } else {
                $logo.attr('src', data.original);
            }
        };

        $(window).on('resize', onResize);
        onResize();
    };

    responsiveLogo();

    var mobileNav = function() {
        var links = [],
            $select = $('<select>', {
                'class': 'js--mobile-nav'
            }).on('change', function() {
                window.location.href = $select.val();
            });

        $(".navi--main .container").children().each(function() {
            var $this = $(this);

            links.push({
                'link': $this.attr('href'),
                'text': $this.children('span').html()
            });
        });

        var options = '<option disabled="disabled">-- Select a category --</option>';
        $.each(links, function(i, item) {
            options += '<option value="' + item.link + '">' + item.text + '</option>';
        });

        $select
            .html(options)
            .insertAfter($(".navi--main .container"));
    };

    mobileNav();
});
