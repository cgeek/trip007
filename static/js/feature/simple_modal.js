(function(a) {
    a.fn.modal = function(d) {
        var c = a('<div id="simplemodal-overlay"></div>').appendTo(document.body);
        c.css({
            opacity: 0.5,
            position: "absolute",
            left: 0,
            top: 0,
            width: "100%",
            height: a(document).height(),
            "z-index": 10000
        });
        var b = a('<div id="simplemodal-container"></div>').append(this).appendTo(document.body).css("position", "absolute");
        b.css({
            top: a(window).scrollTop() + Math.ceil((a(window).height() - a(this).outerHeight()) / 3),
            left: Math.ceil((a(document).width() - a(this).outerWidth()) / 2),
            "z-index": 10001
        });
        b.bind("close", 
        function() {
            c.remove();
            b.remove()
        });
        if ("function" == typeof(d.onShow)) {
            d.onShow(b)
        }
    }
})(jQuery);
