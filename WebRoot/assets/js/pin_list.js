/**
 *  user page
 *
 *  cgeek <cgeek.share@gmail.com>
 */
define(function(require){
	var $ = require('jquery'),
		_ = require('underscore'),
		Mustache = require('mustache'),
		Canvas = require('./modules/pin_canvas');
		
		this.Pin_list = {};
		_.extend(this.Pin_list, {
			like: function(target, cancel) {
				var _self = this,
					$pin = $(target).parents('.pin'),
					pin_id = $pin.attr('pin_id'),
					url = "/user/do_like_ajax",
					like = $pin.find('.like_count_num');
					
				if(cancel) {
					url = "/user/do_unlike_ajax";
				}
				$.ajax({
					url:url,
					data:{"pin_id":pin_id},
					type: 'POST',
					success: function(result) {
						result = $.parseJSON(result);
						if(result['code'] == 200)
						{
							if(cancel) {
								$(target).removeClass('like_done');
								if(result['data']['like_count'] == 0) {
									$pin.find('.like_count_box').html('');
								} else {
									like.html(result['data']['like_count']);
								}
							} else {
								$(target).addClass('like_done');
								if(result['data']['like_count'] == 1 ) {
									$pin.find('.like_count_box').html('<span class="like_count"></span><span class="like_count_num">1</span>');
								} else {
									like.html(result['data']['like_count']);
								}
							}
						} else {
							alert(result.message);
						}
					}
				});
			},
			init:function() {
				var _self = this;
				_.extend(_self, Canvas);
				_self.setup();
			}
			
		});
		return this.Pin_list;
});
