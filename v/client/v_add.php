<article class="col-sm-8 maincontent">	
	<br>
	<strong><?=$msg;?></strong><br>
    <br>
	Шаг 1: Загрузите изображение:
	 <form method="post" action="" enctype="multipart/form-data">
	  <input id="img" name="imgfile" type="file">
	  <input class="button" value="Загрузить" type="submit">
	</form>
	<br>
	Шаг 2: Заполните форму:
	<form method="post">
		Ваше имя:<br>
		<input type="text" name="username" style="width: 50%;" value="<?=$username;?>"><br>
		Ваш e-mail:<br>
		<input type="text" name="email" style="width: 50%;" value="<?=$email;?>"><br>
		Текст задачи:<br>
		<textarea name="text" style="height: 150px; width: 50%"><?=$text;?></textarea><br>
		<input type="submit" name="button" value="Отправить"><br>
	</form>
	
</article>