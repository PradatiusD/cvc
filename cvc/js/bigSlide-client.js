(function ($) {

    $(document).ready(function() {

        var options = {
            menu: '#mobile-menu',
            side: 'left',
            push: '.mobile-nav-push'
        };

        $('.mobile-menu-nav-link').bigSlide(options);

        var mobileMenu = $('#mobile-menu');

        mobileMenu.find('li').each(function () {

            var item = $(this);
            var $a = item.find('a');
            var href = $a.attr('href');

            if (href.indexOf('facebook') > -1) {
                $a.html($a.html() + ' Follow us on Facebook');
            } else if (href.indexOf('instagram') > -1) {
                $a.html($a.html() + ' Follow us on Instagram');
            }

        });

        mobileMenu.show();
    });

})(jQuery);