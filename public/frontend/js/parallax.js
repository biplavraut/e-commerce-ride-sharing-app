$(function() {
    var $el = $('.inner__screen');
    $(window).on('scroll', function () {
        var scroll = $(document).scrollTop();
        $el.css({
            'background-position':'0 '+(-.4*scroll)+'px'
        });
    });
});