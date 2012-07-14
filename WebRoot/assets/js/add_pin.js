define(function(require, exports, module){
	var $ = require('jquery'),
	AjaxUploader = require('ajaxupload');

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
					console.log(responseJSON);
					if(responseJSON.success == true) {
						var image = responseJSON['data']['image'];
						$('input[name=cover_image_id]').val(image.image_id);
						$('input[name=cover_image_width]').val(image.width);
						$('input[name=cover_image_height]').val(image.height);
						$('.cover_image_show').html('<img src="'+ image.image_url_s +'" width=120 >');
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
				var $form = $(this).parents('form');
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
					pin_id = $form.find('input[name=pin_id]').val(),
					cron_pub = $form.find('input[name=cron_pub]').val(),
					cron_time = $form.find('input[name=cron_time]').val();

				$.ajax({
					type: 'POST',
					url: '/Api/Pin.savePin',
					data: {'pin_id':pin_id, 'content':content,'desc':desc,'title':title,'cover_image_id':cover_image_id,'cover_image_height':cover_image_height,'cover_image_width':cover_image_width, 'cron_pub':cron_pub, 'cron_time':cron_time},
					dataType:'json',
					cache:false
				}).success(function(result){
					console.log(result);
					if(result.success == true) {
						url = "/pin/" + result.data.pin_id;
						window.location.href= url;
					}
				});

			});
		},
		init_datepick:function(){
			var _self = this;
			
			$('#datepicker').glDatePicker({
				endDate: 30,
				startDate: new Date(),
				selectedDate: 30,
				allowOld: false
			});
		},
		init:function(){
			var _self = this;
			_self.upload();

			editor.render('editor');
			_self.init_datepick();
			_self.save();		

		}
	}
});
