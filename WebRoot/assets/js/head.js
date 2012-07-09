define(function(require, exports, module){
	var $ = require('jquery');
	require('plugins')($);
	require('bootstrap')($);
	//IE678 placeholder
	$('input[placeholder], textarea[placeholder]').placeholder();

	$('[rel=tooltip]').tooltip('hide');

	$("[rel=popover]").popover({
		offset: 10,
		html:true,
		live:true
	});

	$('body').delegate('.follow_btn, .unfollow_btn', 'click', function(){
		var self = this,
			user_id = $(this).attr('user_id'),
			followed = false,
			url = '/Api/User.follow';
		if($(this).hasClass('unfollow_btn')) {
			followed = true;
			url = '/Api/User.unfollow';
		}

		$.ajax({
			url: url,
			type: 'post',
			data: {'user_id':user_id},
			dataType: 'json'
		}).success(function(result){
			if(result.success == true) {
				if(followed) {
					$(self).removeClass('wbtn').addClass('rbtn').find('strong').html('关注');
					$(self).removeClass('unfollow_btn').addClass('follow_btn');
				} else {
					$(self).removeClass('rbtn').addClass('wbtn').find('strong').html('取消关注');
					$(self).removeClass('follow_btn').addClass('unfollow_btn');
				}
			} else {
				alert(result.message);
			}
		});
	});
});
