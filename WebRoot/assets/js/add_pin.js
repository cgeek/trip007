define(function(require, exports, module){
	var $ = require('jquery'),
	AjaxUploader = require('ajaxupload');

	require('plugins')($);
	require('datepicker')($);
	require('datepicker-css');
	require('ueditor');
	require('ueditor-css');

	var editor = new UE.ui.Editor();

	return {
		upload: function() {
			var _self = this,
			input = $('#file-uploader');
			
			new AjaxUploader.FileUploader({
				element: input[0],
				action: '/image/upload',
				name: 'upfile',
				allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
				sizeLimit: 2097152,
				debug: false,
				onSubmit: function(id, fileName) {
				},
				onProgress: function(id, fileName, loaded, total) {
				},
				onComplete: function(id, fileName, responseJSON){
					if(responseJSON.success == true) {
						var image = responseJSON['data']['image'];
						$('input[name=cover_image_id]').val(image.image_id);
						$('input[name=cover_image_width]').val(image.width);
						$('input[name=cover_image_height]').val(image.height);
						$('.cover_image_show').html('<img src="'+ image.image_url_s +'" width=120 >');
					
						$('.cover_image_show_layout').html('<img src="'+ image.image_url_b +'" >');
						var html = editor.getContent() + '<p><img src="'+ image.image_url_b +'" ></p>';
						editor.setContent(html);
					} else {
						alert('上传失败，请重试.');
					}
				},
				onCancel: function(id, fileName){},
				messages: {
					typeError: "{file} 后缀名无效. 只允许 {extensions} .",
					sizeError: "{file} 大小不能超过 {sizeLimit}.",
					minSizeError: "{file} 大小不能小于 {minSizeLimit}.",
					emptyError: "{file} 为空，请选择一个图片.",
					onLeave: "图片上传中，请暂时不要离开此页面."
				},
				showMessage: function(message){ alert(message); },
				template: '<div class="qq-uploader">' +
							'<div class="qq-upload-drop-area"style="display: none;height:0;overflow: hidden;min-height: 0;"><span>把图片拖拽到这里</span></div>' +
							'<div class="btn pb-image-btn">上传图片</div><span class="text">支持上传png,jpg,jpeg,gif格式，小于2M</span>' +
							'<ul class="qq-upload-list" style="display: none;height:0;overflow: hidden;"></ul>' +
							'</div>',
				fileTemplate: '<li>' +
					'<span class="qq-upload-file"></span>' +
					'<span class="qq-upload-spinner"></span>' +
					'<span class="qq-upload-size"></span>' +
					'<a class="qq-upload-cancel" href="#">取消</a>' +
					'<span class="qq-upload-failed-text">上传失败</span>' +
					'</li>',
				classes: {
					button: 'btn',
					drop: 'qq-upload-drop-area',
					dropActive: 'qq-upload-drop-area-active',
					list: 'qq-upload-list',
					file: 'qq-upload-file',
					spinner: 'qq-upload-spinner',
					size: 'qq-upload-size',
					cancel: 'qq-upload-cancel',
					success: 'qq-upload-success',
					fail: 'qq-upload-fail'
				}
			});
		},
		save: function() {
			$('.pb-submit-btn').click(function(event){
				event.preventDefault();
				var btn = $(this),
					btn_txt = btn.html(),
					doing = btn.attr('doing'),
					$form = $(this).parents('form');
				if(doing && doing == true) {
					return ;
				}
				btn.html(' 保存中...');
				btn.attr('doing',true);

				if(!editor.hasContents()) {
					alert('内容不能为空');
					return false;
				}
				var content = editor.getContent(),
					cover_image_id = $form.find('input[name=cover_image_id]').val(),
					cover_image_height = $form.find('input[name=cover_image_height]').val(),
					cover_image_width = $form.find('input[name=cover_image_width]').val(),
					title = $form.find('input[name=title]').val(),
					desc = $form.find('textarea[name=desc]').val(),
					tags = $form.find('textarea[name=tags]').val(),
					pin_id = $form.find('input[name=pin_id]').val(),
					cron_pub = $form.find('input[name=cron_pub]').val(),
					type = $form.find('input[name=type]').val(),
					cron_time = $form.find('input[name=cron_time]').val(),
					is_sync_weibo = $form.find('input[name=is_sync_weibo]').attr('checked') ? 1:0;
				$.ajax({
					type: 'POST',
					url: '/Api/Pin.savePin',
					data: {'pin_id':pin_id,'type':type, 'content':content,'desc':desc,'title':title,'cover_image_id':cover_image_id,'cover_image_height':cover_image_height,'cover_image_width':cover_image_width, 'cron_pub':cron_pub, 'cron_time':cron_time,'is_sync_weibo':is_sync_weibo,'tags':tags},
					dataType:'json',
					cache:false
				}).success(function(result){
					if(result.success == true) {
						url = "/pin/" + result.data.pin_id;
						window.location.href= url;
					}
					btn.html(btn_txt);
					btn.attr('doing',false);
				}).error(function(){
					btn.html(btn_txt);
					btn.attr('doing',false);
				});

			});
		},
		datepick:function(){
			var _self = this;
			$('#datepicker').glDatePicker({
				startDate: new Date(),
				allowOld: false,
				onChange: function(target, newDate)
				{
					var new_date = newDate.getFullYear() + "-" +(newDate.getMonth() + 1) + "-" +newDate.getDate();
					target.val(new_date);
					var cron_time = new_date + ' ' + $('input[name=hour]').val() + ':'+ $('input[name=minute]').val();
					$('input[name=cron_time]').val(cron_time);
				}
			});
			
		},
		select_pub_type: function() {
			var _self = this;
			$('.pb-type-select').click(function(){
				$('.private-menu').show();
			});
			$('.private-menu li').click(function(){
				if($(this).attr('type') == 'now') {
					$('.cron-time-holder').hide();
					$('input[name=cron_pub]').val('0');
					$('.pb-submit-btn').html('立即发布');
				} else if($(this).attr('type') == 'cron') {
					$('.cron-time-holder').show();
					$('input[name=cron_pub]').val('1');
					$('.pb-submit-btn').html('定时发布');
				}
				$('.post-privacy-select span.selected').html($(this).html());
				$('.private-menu').hide();
			});

			_self.datepick();
			//定时时间
			$('input[name=hour], input[name=minute], input[name=date]').blur(function(){
				var cron_time = $('input[name=date]').val() + ' ' + $('input[name=hour]').val() + ':'+ $('input[name=minute]').val();
				$('input[name=cron_time]').val(cron_time);
			});
		},
		init:function(){
			var _self = this;
			
			_self.select_pub_type();
			
			$('.cover_image_show').hover(function(){
				$('.cover_image_show_layout').show();	
			},function(){
				$('.cover_image_show_layout').hide();	
			});
			$('textarea[name=desc]').textlimit('span.counter',120);
			
			_self.upload();
			
			editor.render('editor');
			
			_self.save();		
			
		}
	}
});
