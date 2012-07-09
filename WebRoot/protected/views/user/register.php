<div class="main home">
	<div class="box page-account">
		<div class="w520">
			<form id="register_form" action="/user/register" method="post">
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
				</fieldset>

				<div class="submit_btn">
					<button type="submit" class="register_btn"></button>
				</div>
			</form>
		</div>
		<div class="aside w300">
			<p>已经有账号? <a href="/user/login">马上登录</a></p>
		</div>
	</div>
</div>
<script type="text/javascript">
	seajs.use('/assets/js/router.js',function(router){
		router.load('account');
	});

</script>
