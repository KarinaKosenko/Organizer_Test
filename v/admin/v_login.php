<article class="col-sm-8 maincontent"
	<header class="page-header">
		<h1 class="page-title"><?=$title;?></h1>
	</header>	
	
	<div class="post_section">
		<form method="post">
			Логин:<br>
			<input type="text" name="login"><br>
			Пароль:<br>
			<input type="password" name="password"><br><br>
			<input type="checkbox" name="remember">Запомнить<br><br>
			<input type="submit" value="Войти">
		</form>
		<br>
		<div>
			<?=$msg;?>
		</div>
	</div>
</article>