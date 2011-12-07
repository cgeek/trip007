/**
 *
 *
 */

function swfuploadModal() {
    var c='<iframe id="upload-iframe" style="display:none;" src="about:blank" name="upload-iframe"></iframe><form id="upload-form" action="/upload/img" method="post" enctype="multipart/form-data"><h4>插入一张图片</h4><p class="nav" for="nav"><a href="#" id="upload-local" class="radius-3 selected">从本地上传</a><a href="#" id="upload-remote" class="radius-3">从远程地址获取</a></p><p class="file-upload" for="upload-local"><input type="text" id="filename-text" disabled class="text" /><button id="swfupload-button" disabled>选择文件</button><br/><cite class="desc">图片文件体积最大不超过4M</cite></p><p class="file-upload-remote" for="upload-remote"><input type="text" id="fileurl-text" name="url" class="text" /><br /><cite class="desc">输入一个图片地址上传</cite></p><p class="submit" for="submit"><button class="submit-button" style="display:none">上传</button><button class="save-button" style="display:none;">保存</button><button class="cancel">取消</button></p></form>';
    $(c).modal({
        onShow:function(g) {
            var f=false,h=false;
            if (!h) {
                $photo.init();
            }
            $("#upload-local").click(function() {
                $(this).addClass("selected");
                $("#upload-remote").removeClass("selected");
                $('p[for="upload-local"]').show();
                $('.submit-button').hide();
                $('p[for="upload-remote"]').hide();
                h = false;
                return false;
            });
                
            $("#upload-remote").click(function() {
                $(this).addClass("selected");
                $("#upload-local").removeClass("selected");
                $('p[for="upload-remote"]').show();
                $('.submit-button').show();
                $('p[for="upload-local"]').hide();
                h = true;

                return false;
            });

            $(".cancel", g).click(function() {
                g.trigger("close");
                return false
            });

            $('.save-button').click(function() {
                var desc = $("#upload-photo-desc-modal").val();
                var imageId = $("#upload-photo-desc-modal").attr('img-id');
                $.post();
            });

        }
    });
    
};

(function($){
	var window = this;
	
	$photo = {
		init:function(o) {
			var self = this;
			self.initSWFUpload();
		},
		initSWFUpload:function() {
            var swfu = new SWFUpload({
				// Backend Settings
                upload_url: "./upload.php",
				flash_url : "./static/js/swfupload/swfupload.swf",
				
                file_post_name: "Filedata",
				file_size_limit : "20 MB",                               //允许上传的文件大小
				file_types : "*.jpg;*.jpeg;*.gif;*.png;*.bmp;*.jpe",
				file_types_description : "All Images",
				file_upload_limit : "0",
				
	            prevent_swf_caching : false,
                preserve_relative_urls : false,
                button_placeholder_id : "swfupload-button",
				//button_image_url	: './static/images/upload-modal-button.png',
				button_width:  80,
                button_height: 30,
                button_text:    "<button class=\"button\" disabled=\"\" id=\"swfupload-button\">选择文件</button>",
                button_text_style: "font-size:14px;line-height:22px;height:22px;",
                button_text_top_padding:5,
                button_text_left_padding: 5,
				button_action: SWFUpload.BUTTON_ACTION.SELECT_FILE,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				file_dialog_complete_handler : $photo.fileDialogComplete,
				upload_start_handler	:$photo.uploadStartHandler,
				upload_progress_handler : $photo.uploadProgress,
				upload_success_handler : $photo.uploadSuccess,
				upload_error_handler : $photo.uploadError,
				upload_complete_handler : $photo.uploadComplete,	
				file_queued_handler: $photo.fileQueuedHandler,
				file_queue_error_handler : $photo.fileQueueError,
				
				// Debug Settings
				debug: false
			});
		},
		
		fileDialogComplete:function(numFilesSelected, numFilesQueued) {
			try {
				numFilesQueued != 0 && this.startUpload();
			} catch (ex) {
				this.debug(ex);
			}
		},
		
		uploadStartHandler: function(a) {
			
        },
		
		uploadProgress:function(file, complete, total) {
            $("#fileurl-text").addClass("upload-loading").attr("disabled", "disabled");
			//var a = 240, b= -400, c = complete / total, d = 240 * c, e = -400 + d;
			//$("#photo-" + file.id + " .pb-photo-li-progress").css('background-position', e + "px center");
		},

		fileQueuedHandler:function(a) {
			var html = '<li id=\"photo-' + a.id + '\" class=\"clearfix\">'+
				'<a title=\"拖动调整图片顺序\" class=\"pb-photo-li-move\">移动</a> ' +
				'<a class=\"pb-photo-li-rm\">删除</a>'+
				'<span class=\"pb-photo-li-name\">' + a.name + '</span>'+
				'<span style=\"background-position: -150px center;\" class=\"pb-photo-li-progress\"></span>'+
				'</li>';

            $("#fileurl-text").addClass("upload-loading").attr("disabled", "disabled");
                          
            console.log(html);
			//$("#pb-photo-list").append(html);
        },

		fileQueueError:function(file, errorCode, message) {
			alert('fileQueueError');
		},
		uploadSuccess:function(file, serverData) {
			try {
                console.log(serverData);
				var img_url = "http://qiugonglue.com/file/upload/" + serverData + "_100_100.jpg";
				var html = '<p>' +
							'<img height=100px; width=100px; src="' + img_url + '">' +
							'<textarea id="upload-photo-desc-modal" img-id="'+ serverData+'" class="upload-photo-desc-modal"></textarea></p>';
                $('form h4').html('上传成功，给图片添加一个标题吧');
                $('p[for=nav]').hide();
                $('p[for=upload-local]').hide();
                $('p[for=submit]').before(html);
                $('p[for=submit]').show();
                $('.save-button').show();
				console.log(html);
			} catch (ex) {
				this.debug(ex);
			}
		},

		uploadComplete:function(file) {
			try {
				/*  I want the next upload to continue automatically so I'll call startUpload here */
				this.startUpload();
			} catch (ex) {
				this.debug(ex);
			}
		},

		uploadError:function(file, errorCode, message) {
			alert('uploadError');
		}
		
	};
})(jQuery);
