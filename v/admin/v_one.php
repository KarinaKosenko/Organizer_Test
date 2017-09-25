<article class="col-sm-8 maincontent">
	
		<h3><?=$task['username'];?></h3>
		<img src="<?=$task['image_link'];?>" alt="image">
		<p><?=$task['email'];?></p>
		<p><?=$task['text'];?></p>
		
		<?php if($task['status'] == 'Выполнена'):
				echo "<strong><p>" . $task['status'] . "</p></strong>";
			  endif;
	    ?>
		<a href="/admin/tasks/edit/<?=$task['id_task'];?>">Редактировать задачу</a>
	
</article>