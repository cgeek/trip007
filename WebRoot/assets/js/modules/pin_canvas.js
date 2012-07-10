/**
 *  masonry list
 *
 *  cgeek <cgeek.share@gmail.com>
 */
define(function(require, exports, module){
	var $ = require('jquery'),
		_ = require('underscore'),
		Mustache = require('mustache'),
		masonry = require('masonry');
	
	masonry($);
	
	var timer = null;
	this.Pin_Canvas = {
		config: {
			container:$('#waterfall'),
			LIST_TPL:$('#item_list_tpl').html().replace(/[\n\t\r]/g,''),
			width:222,
			count:0,
			gutterWidth:15,
			pageNum:2,
			loadTimes:30,
			minCount:4,
			ajaxUrl: $('#waterfall').attr('data-url'),
			isFinish:false
		}
	};
	
	_.extend(this.Pin_Canvas, {
		renderItem: function(data) {
			var _self = this,
				tpl = $('#item_tpl').html().replace(/[\n\t\r]/g,''),
				container = _self.config.container;
			var newItem  = $(Mustache.to_html(tpl,data));
			container.prepend(Mustache.to_html(tpl,data)).masonry( 'reload' );	
		},
		loadTime:function(){
			var _self = this,
				config = _self.config;
			if (config.pageNum > config.loadTimes) {
				_self.loadFinish('没有更多图片啦');
				return true;
			} else {
				return false;
			}
		},
		errorRetry: function(_self){
			var errorTime = $.data(document.body,'errorTime')?$.data(document.body,'errorTime') : 0;
			if(errorTime >= 0) {
				_self.loadFinish('没有更多信息啦！');
				return;
			}
			$.data(document.body,'errorTime',errorTime-0+1);
		},
		loadFinish:function(tip) {
			$('#more-loading').html('<img src=\"/images/end.png\" >').show();
			this.config.isFinish = true;
			$(window).unbind('scroll.loadData');
			return false;
		},
		
		_checkHeight:function(){
			var _self = this,
			container = _self.config.container,
			min = container.height(),
			$W = $(window);
			
			st = $W.scrollTop() + $W.height() - container.offset().top;
			if (_self._loading || (st+300) < min) {
				return true;
			}
		},
		
		reloadData:function() {
			var _self = this;
			_self.resetConfig();
			_self.loadData(true,true);
			
			$(window).resize(function(evt) {
				_self.allPins();
			});
			
			$(window).on('scroll.loadData',function(evt){
				if (timer !== null) return;
				timer = setTimeout(function() {
					timer = null;
					_self.loadData();
				}, 100);
			});
			
		},
		resetConfig:function() {
			var _self = this;
			_self.config.pageNum = 1;
			_self.config.isFinish = false;
		},
		// load data appendto container
		loadData: function(isFirst,isFirstReload) {
			var _self = this,
				tpl = _self.config.LIST_TPL,
				container = _self.config.container,
				pageNum = _self.config.pageNum,
				isLoading = _self.config.isloading;
			
			if ((_self._checkHeight() || isLoading || _self.loadTime()) && !isFirst) return;
			
			$('#more-loading').show();
			_self.config.isloading = true;
			
			$.ajax({
				'url' : _self.config.ajaxUrl + '&p=' + pageNum,
				'type': 'get',
				'datatype':'html',
				'cache': false,
				complete: function(){
					_self.config.isloading = false;
					if (timer !== null || _self.config.isFinish) return;
						timer = setTimeout(function() {
							timer = null;
							_self.loadData();
						},
						500);
				},
				error: function(jqXHR, textStatus, errorThrown){
					//alert('对不起，服务器泡妞去了');
					if('timeout' == textStatus) {
						_self.errorRetry(_self);
					} else {
						_self.errorRetry(_self);
					}
				},
				success: function(result){
					try {
						result = $.parseJSON(result);
					} catch(e){
						_self.errorRetry(_self);
					}
					var obj ='';
					for(var i in result['data'])
					{
						obj = result['data'][i];
					}
					if(result.success != true)
					{
						_self.errorRetry(_self);
						return;
					}
					if(result.data == '' || result.data.pin_list == '' || result.data.pin_list.length < 1) {
						_self.loadFinish('没有推荐了');
						return false;
					}
					
					var newItems  = $(Mustache.to_html(tpl,result.data));
					if(isFirstReload) {
						container.find('.pin').remove();
						container.append(newItems).masonry('appended', newItems);  
						container.masonry('reload');
						_self.config.pageNum++;
					} else {
						_self.showPic(_self,container,isFirst, newItems);
						//container.append(newItems).masonry('appended', newItems);
						_self.config.pageNum++;
					}
					
					$('#more-loading').hide();
					_self.config.isloading = false;
				}
			});
			
		},
		
		allPins:function(){
			var _self = this,
				config = _self.config,
				container = _self.config.container;
				
			container.masonry('reload');
		},
		showPic:function(_self,container,isFirst,newItems) {
			if(!newItems){
				newItems = $(container.children());
			}
			if(isFirst) {
				$('#header').css('visibility','visible');
				$('#waterfall').css('visibility','visible');
				newItems.css({
					'opacity': 0
				}).animate({
					'opacity': 1
				},100);
				return;
			}
			if ($.browser.msie && parseInt($.browser.version, 10)==6) {
				setTimeout(function() {
					container.append(newItems).masonry('appended', newItems);
				},250);
			}else if($.browser.msie){
				container.append(newItems).masonry('appended', newItems);  
			} else {
				container.append(newItems.css({
					'opacity': 0
				})).masonry('appended', newItems);
					newItems.animate({
						'opacity': 1
					},500);
				newItems.addClass('masonry-brick-resize');
			}
		},
		setup:function(){
			var _self = this;
			var container = _self.config.container;
			container.masonry({
				itemSelector: '.item',
				columnWidth:_self.config.width,
				gutterWidth:_self.config.gutterWidth
			}).on('mouseenter', 'div.pin', function(){
				$(this).addClass('phover');
			}).on('mouseleave', 'div.pin', function(){
				$(this).removeClass('phover');
			});
			
			container.children().addClass('masonry-brick-resize');
			
			$(window).resize(function(evt) {
				_self.allPins();
			});
			//_self.loadData(true);
			
			$(window).on('scroll.loadData',function(evt){
				if (timer !== null) return;
				timer = setTimeout(function() {
					timer = null;
					_self.loadData();
				}, 300);
			});
			
			_self.showPic(_self,container,true);
		}
		
	});

	return this.Pin_Canvas;
});
