<div class="main">
	<div class="box page-account">
		<div class="w520">
			<form id="login_form" action="/user/login" method="post">
				<p class="page-account-alert-message error"></p>
				<input type="hidden" name="type" value="personal">
				<fieldset>
					<div class="clearfix">
						<label for="email">E-Mail</label>
						<input type="text" size=30 id="email" name="email" value="">
						<span class="help-inline"></span>
					</div>
					<div class="clearfix">
						<label for="password">密码</label>
						<input type="password" size=30 id="password" name="password" value="">
						<span class="help-inline"></span>
					</div>
					<div class="clearfix">
						<div class="input">
							<label>
								<input type="checkbox" name="remember" value="1">
								<span>一个月内自动登录</span>
							</label>
						</div>
					</div>
				</fieldset>
				<div class="submit_btn">
					<button type="submit" class="login_btn"></button>
				</div>
			</form>
		</div>
		<div class="aside w300">
			<p>还没有账号? <a href="/user/register">立即注册</a></p>
		</div>
	</div>
</div>

<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('account');
});
</script>
