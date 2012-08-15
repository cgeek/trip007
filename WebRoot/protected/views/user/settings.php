<div class="main">
	<?php if(empty($user_db['password']) || empty($user_db['email'])): ?>
	<div class="box" style="background:#fff;padding:40px;border-radius:5px;">
	<h2 class="">Hi,<?=$user_db['user_name'];?> ，完善下你的信息，以后你也可以使用该邮箱和密码来登录了。</h2>
	<div class="avatar">
		<img src="<?=$user_db['avatar_large'];?>">
	</div>
	<input type="text" name="user_name" value="<?=$user_db['user_name'];?>">
		<input type="text" name="email" value="" placeholder="登录邮箱地址">
		<input name="password" type="password" placeholder="密码">
		<button type="submit">提交</button>
	</div>
	<?php endif;?>
</div>

<script type="text/javascript">
seajs.use('/assets/js/router.js',function(router){
	//router.load('home');
});
</script>
