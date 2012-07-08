define(function(require, exports, module){
	var $ = require('jquery');
	require('plugins')($);
	var cookie = require('cookie');

	var $form = $("form");
	
	return {
		showError:function(error) {
			$form.find('.page-account-alert-message').show('fast').fadeOut('fast', function() {$(this).html(error)}).fadeIn('fast');
		},
		register:function() {
			var _self = this;
			$.ajax({
				url:"/user/register",
				type:"POST",
				data:$form.serialize(),
				dataType:'json',
				success:function(data){
					if (data.success == true) {
						cookie.set('login_email', $form.find('input[name=email]').val(), {
							expires: 3650 
						});
						window.location.href = '/user/home';
					} else {
						_self.loadingElement.hideLoading();
						_self.showError(data.message);
					}
				}
			});
		},
		login:function(){
			var _self = this;
			$.ajax({
				url:"/user/login",
				type:"POST",
				data:$form.serialize(),
				dataType:'json',
				success:function(data){
					if (data.success == true) {
						cookie.set('login_email', $form.find('input[name=email]').val(), {
							expires: 3650 
						});
						window.location.href = '/user/home';
					} else {
						_self.loadingElement.hideLoading();
						_self.showError(data.message);
					}
				}
			});
		},
		validate:function(form) {
			var _self = this;
			$form.validate({
				debug:true,
				submitHandler: function(form) {
					_self.loadingElement = $('button', form).showLoading({img:false});
					if (form.id == 'register_form') {
						_self.register();
					} else if(form.id == 'login_form') {
						_self.login();
					}
				},
				validClass:'success',
				highlight: function(element, errorClass, validClass) {
					$(element).closest('.clearfix').addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).closest('.clearfix').addClass(validClass).removeClass(errorClass);
				},
				errorPlacement: function(error, element) {
					error.appendTo( element.next("span") );
				},
				errorElement: "span",
				rules:{
					email:{
						required:true,
						email: true
					},
					password: {
						required:true,
						minlength: 6,
						maxlength: 32
					}
				},
				messages:{
					email:{
						required:"请填写邮箱",
						email:"邮箱是不合法的邮箱"
					},
					password: {
						required:"请填写密码",
						minlength:$.format("密码最少 {0} 位")
					}
				}
			});
		},
		init:function(){
			var _self = this;
			_self.validate();
			
			var last_login_email = cookie.get('login_email');
			if (last_login_email) {      
				$('input[name=email]').val(last_login_email);
			}

			$form.find(':input[value=""]:first').select();
			var errorFromQuery = cookie.get('loginError');
			if (errorFromQuery) {
				cookie.remove('loginError');
				_self.showError(errorFromQuery);
			}
		}
	};
});
