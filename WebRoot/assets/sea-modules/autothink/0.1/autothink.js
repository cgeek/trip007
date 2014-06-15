define(function(require, exports, module) {
	var $ = require('jquery');
	var Mustache = require('mustache');
        
	var autoThink = function () {
		this.init.apply(this, arguments);
	}
	autoThink.prototype.init = function (conf) {
		this.conf = $.extend({
                        inputel: '#addcity',
                        listel: '#plan_search_drop',
                        itemel: '#plan_search_drop a',
                        entercb: false,
                        postdata: false,
                        clickcb: false,
                        autoClearInput: true,
                        posturl: "/plan/api/searchpoi?callback=?",
                                voidexec: false
					}, conf || {});
					
		this.searchresult = true;
		this.render();
	};

	autoThink.prototype.render = function () {
		var self = this;

		$(document).delegate(self.conf.inputel, 'keyup', function(e){
			self.keyupSearch(e);
		});
		$(document).delegate(self.conf.inputel, 'keydown', function(e){
            var tabKeyCode = 9;
            if (tabKeyCode == e.keyCode) {
                    self.clearSearched();
            }
		});
		
				
		$(self.conf.inputel).on('click', function (e) {
                        var kw = $(this).val();
                        self.searchCC(kw);
                        if (self.conf.voidexec) {
                                e.stopPropagation();
                        }
                }).on('keyup', function (e) {
                        self.keyupSearch(e);
                }).on('keydown', function (e) {
                        var tabKeyCode = 9;
                        if (tabKeyCode == e.keyCode) {
                                self.clearSearched();
                        }
                });
                $(self.conf.itemel).on('mouseover', function () {
                        $(self.conf.itemel).removeClass("current");
                        $(this).addClass("current");
                }).on('click', function (e) {
                        if (self.conf.clickcb) self.conf.clickcb(e);
                                self.clearSearched();
                });
                $(document).click(function () {
                        $(self.conf.listel).html('').hide();
                })
        }
        autoThink.prototype.searchCC = function (kw) {
                if (kw == '') {
                        if (this.conf.voidexec) {
                                this.conf.voidexec();
                        }
                        return;
                }
                var self = this;
                self.doSearch(kw, function (json) {
                        if (json.code == 200 && json.data.list) {
                                self.searchresult = true;
                        } else {
                                self.searchresult = false;
                        }
						var tpl = $('#tpl_searchlivecity').html();
						//var tpl = '<div class="citylist_box" >{{#list}}<a {{#current}}class="current"{{/current}} href="javascript:void(0)" data-title="{{name}}" data-type="{{type}}" data-dataid="{{id}}"><span>{{name}}</span></a>{{/list}}</div>';
                        //var tpl = '<ul>{{#list}}<li><a class="{{current}}" href="javascript:void(0)" data-title="{{name}}" data-type="{{type}}" data-dataid="{{id}}"><span>{{name}}（{{country_name}}）</span></a></li>{{/list}}</ul>';
                        var html = Mustache.to_html(tpl, json.data);
                        $(self.conf.listel).html(html).show();
                });
        }
        autoThink.prototype.doSearch = function (kw, cb) {
                var data = {
                        kw: kw,
                        format: "callback",
                        searchCountry: true
                };
                if (typeof this.conf.postdata == 'object') {
                        data = $.extend(data, this.conf.postdata);
                }
                $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: this.conf.posturl,
                        data: data,
                        success: function (json) {
                                cb(json);
                        }
                })
        }
        autoThink.prototype.keyupSearch = function (e) {
                var v = $(this.conf.inputel).val();
                if ("" == v) {
                        if (this.conf.voidexec) {
                                this.conf.voidexec()
                                return;
                        }
                        $(this.conf.listel).html('').hide();
                        return;
                }
                switch (e.keyCode) {
                        case 38:
                                this.auto_addcity_arrow_list("up");
                        break;
                        case 40:
                                this.auto_addcity_arrow_list("down");
                        break;
                        case 13:
                                this.auto_addcity_arrow_list("enter");
                        break;
                        default:
                                this.searchCC(v);
                        break;
                }
                return;
        }
        autoThink.prototype.clearSearched = function () {
                $(this.conf.listel).html('').hide();
                if (this.conf.autoClearInput) {
                        $(this.conf.inputel).val('');
                }
        }
        autoThink.prototype.auto_addcity_arrow_list = function (which) {
                var listclass = this.conf.itemel;
                var alist = $(listclass);
                var size = alist.size();
                var curAObj = null;
                $.each(alist, function (k, v) {
                	if ($(v).hasClass("current")) {
						curAObj = k;
						return false;
					}
                });
                if ("down" == which) {
                        if (curAObj >= (size - 1)) return;
                                $(listclass).eq(curAObj).removeClass("current");
                        $(listclass).eq(curAObj + 1).addClass("current");
                } else if ("up" == which) {
                        if (curAObj <= 0) return;
                                $(listclass).eq(curAObj).removeClass("current");
                        $(listclass).eq(curAObj - 1).addClass("current");
                } else if ("enter" == which) {
                        var obj = $(listclass).eq(curAObj);
                        var v = $(this.conf.inputel).val();
                        this.clearSearched();
                        if (this.conf.entercb) this.conf.entercb({
                                obj: obj,
                                searchresult: this.searchresult,
                                val: v
                        });
                }
        };

        exports.autoThink = autoThink;
});