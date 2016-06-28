
(function($) {
    jQuery.expr[':'].Contains = function(a, i, m) {
        return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };
    var wHeight = $(window).height();
    var tHeight = $('#top').height();
    var mHeight = $('.head').height();
    var height = wHeight - (tHeight + mHeight);

    $('#menu').css('height', height);
    $('#menu').css('overflow-y', 'scroll');

    $('#socialuser').change(function() {
alert('fdd');
        var list = $("#menu");
        var filter = $(this).val();
        if (filter) {

            $matches = $(list).find('a:Contains(' + filter + ')').parent();
            $('li', list).not($matches).slideUp();
            $matches.slideDown();

        } else {
            $(list).find("li").slideDown();
        }
        return false;
    })
            .keyup(function() {
        $(this).change();
    });
    
}(jQuery));
 