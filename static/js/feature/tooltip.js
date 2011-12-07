(function(a) {
    a.fn.tooltip = function(c, b) {
        return this.each(function() {
			var d = a(this).prop("tagName").toLowerCase(),
            e = "input" != d && "textarea" != d && "select" != d,
            f = a('<div class="tooltip"><div class="tooltip-inner"/></div>'),
            g = a(this).parent();
            if (this.tip) {
                this.tip.remove()
            }
            this.tip = f;
            a(this).css("position", "relative");
            a(".tooltip-inner", f).html(c);
            f.css({
                position: "absolute",
                "z-index": 100
            }).appendTo(document.body);
            f.css("width", f.width());
            g.css("position", "relative").append(f);
            var h = {
                top: a(this).offset().top - a(this).parent().offset().top,
                left: a(this).offset().left - a(this).parent().offset().left
            };
            if ("top" == b) {
                f.css({
                    top: h.top - f.outerHeight(),
                    left: h.left + ((a(this).outerWidth() - f.outerWidth()) / 2)
                }).addClass("tooltip-top")
            } else if("bottom" == b) {
                f.css({
                    top: h.top + ((a(this).outerHeight() - f.outerHeight()) / 2),
                    left: h.left + a(this).outerWidth()
                }).addClass("tooltip-bottom")
            } else {
                f.css({
                    top: h.top + ((a(this).outerHeight() - f.outerHeight()) / 2),
                    left: h.left + a(this).outerWidth()
                }).addClass("tooltip-right")
            }
            if (e) {
                setTimeout(function() {
                    f.remove()
                },
                2000)
            } else {
                a(this).keyup(function() {
                    if (this.tip) {
                        this.tip.remove()
                    }
                })
            }
        })
    }
})(jQuery);
