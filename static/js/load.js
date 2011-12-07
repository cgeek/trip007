(function(){
    $(document).ready(function(){
        var _features = [],
        featureCallback = [],
        featureJs =
        "|showAddress:googlemap_modal|markitup:markitup,simple_modal,upload_modal|photo|publish|inlineMessage:tooltip|",
        featureJsToLoad = [],
        featureJsLoaded ={},
        featureJsLoadCallback = [];
        
        $("script[type=feature]").each(function(){
            _features = $.trim($(this).html()).split(";");
            for (var i=0; i < _features.length; i++) {
                var f = $.trim(_features[i]),
                key = f,
                val = true,
                pos = f.indexOf(":");
                if (!f) {
                    continue;
                }
                if (pos > 0) {
                    key = f.substring(0,pos);
                    val = f.substring(pos+1);
                }
                if (featureJs.indexOf("|" + key + "|") >=0) {
                    if (!featureJsLoaded[key]) {
                        featureJsToLoad.push("js/feature/" + key + ".js");
                        featureJsLoaded[key] = true
                    }
                    featureJsLoadCallback.push([key, val]);
                } else {
                    if ((pos = featureJs.indexOf("|" + key + ":")) >= 0) {
                        var cut = featureJs.substring(pos + key.length + 2);
                        var js = cut.substring(0, cut.indexOf("|")).split(",");
                        for (var j = 0; j < js.length; j++) {
                            if (!featureJsLoaded[js[j]]) {
                                featureJsToLoad.push("js/feature/" + js[j] + ".js");
                                featureJsLoaded[js[j]] = true
                            }
                        }
                        featureJsLoadCallback.push([key, val])
                    } else {
                        featureCallback.push([key,val]);
                    }
                }
            }
        });

		function runFeature(f) {
            console.log(f);
            for(var i=0;i<f.length;i++) {
                var key = f[i][0],
                    val = f[i][1];
                if ("boolean" == typeof(val)) {
                    eval(key + "()");
                } else {
                    eval(key +"('" + val + "')");
                }
            }
        }
/*
		function publish() {
			console.log('publish')
		}
*/		
        function placeholder(match) {
            $(match).each(function() {
                var t = $(this),
                p = t.parents("form");
                this.entered = t.val() ? true: false;
                this._name = this.name;
                if (!this.entered) {
                    t.val(t.attr("holder"));
                    t.addClass("placeholder");
                    t.attr("name", "")
                }
                t.focus(function() {
                    var t = $(this);
                    if (!this.entered) {
                        t.val("");
                        t.removeClass("placeholder");
                        p.addClass("focus")
                    }
                });
                t.blur(function() {
                    var t = $(this);
                    if (!this.entered) {
                        t.val(t.attr("holder"));
                        t.addClass("placeholder");
                        t.attr("name", "");
                        p.removeClass("focus")
                    }
                });
                t.keyup(function() {
                    var t = $(this);
                    this.entered = t.val() ? true: false;
                    if (this.entered) {
                        this.name = this._name
                    }
                });
                t.change(function() {
                    var t = $(this);
                    this.entered = t.val() ? true: false;
                    if (this.entered) {
                        this.name = this._name
                    }
                })
            })
        }
		function inlineMessage(match) {
            $(".inline-message").each(function() {
                var t = $(this),
                id = t.attr("rel");
                console.log(id);
                $("#" + id).tooltip(t.html(), match)
            })
        }

/*
        function autocomplete(match) {
            var parts = match.split(","),
            sel = parts[0],
            url = parts[1],
            multi = parts.length > 2 && "multi" == parts[2];
            function parseAutocomplete(rows) {
                result = [];
                for (var i = 0; i < rows.length; i++) {
                    row = rows[i];
                    row = row.split("|");
                    result.push({
                        data: row,
                        value: row[0],
                        result: row[0]
                    })
                }
                return result
            }
            function formatAutocomplete(row) {
                return row[0] + (row.length > 1 ? "<cite>" + row[1] + "</cite>": "")
            }
            $(sel).each(function() {
                var t = $(this);
                t.autocomplete(url, {
                    max: 5,
                    selectFirst: false,
                    parse: parseAutocomplete,
                    formatItem: formatAutocomplete,
                    dataType: "json",
                    width: t.outerWidth() - 2,
                    multiple: multi,
                    delay: 0
                })
            })
        }
*/
        function markitup(match) {
            var parts = match.split(","),
                sel = parts[0],
                img = parts.length > 1 ? "img" ==parts[1] :false,
                opt = [{name:"粗体",className:"btnBold",key:"B",openWith:"**",closeWith:"**",placeHolder:"粗体文本"},
                       {name:"链接",className:"btnLink",key:"L",openWith:"[[",closeWith:"]]",placeHolder:"http://..."},
                       {name:"图片",className:"btnImg",key:"M",openWith:"{{",closeWith:"}}",placeHolder:"上传图片",call:uploadModal},
                      ];
                $(sel).markItUp({
                    onShiftEnter:{keepDefault:false, replaceWith:'<br />\n'},
                    onCtrlEnter:{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
                    onTab:{
                        keepDefault:false,
                        replaceWith:"   "      
                    },
                    markupSet: opt
                });
        }

        function showAddress(match) {
            $(match).click(function(){
                var google_api_url = 'http://maps.googleapis.com/maps/api/js?sensor=false';
                $LAB.script(google_api_url).wait();
                get_address_info();
            });
        }

        function loadCdn(match) {
            var parts = match.split(","),
                url = parts[0],
                v   = parts[1];
            var jsUrl = url + featureJsToLoad.join(",") + "?" + v;
            $LAB.script(jsUrl).wait();
        }

        function loadLocal(url) {
            var jsUrl = [];
            for (var i=0;i< featureJsToLoad.length; i++ ) {
                jsUrl.push(url + featureJsToLoad[i]);
            }
            console.log(jsUrl);
            $LAB.script(jsUrl).wait(function(){
                runFeature(featureJsLoadCallback);
            });

        }
        runFeature(featureCallback);
    })
})();
