<article class="col-sm-8 maincontent">	
	<br>
	<strong><?=$msg;?></strong><br>
    <br>
	<h3><?=$task['username'];?></h3>
	<img src="<?=$task['image_link'];?>" alt="image">
	<p><?=$task['email'];?></p>
	
	<form method="post">
		Текст задачи:<br>
		<textarea name="text" style="height: 150px; width: 50%;"><?=$task['text'];?></textarea><br>
		<input type="checkbox" name="done">Отметить задачу как выполненную<br><br>
		<input type="submit" name="button" value="Отправить"><br>
	</form>
	
</article>