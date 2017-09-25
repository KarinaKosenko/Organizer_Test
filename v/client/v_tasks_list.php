<article class="col-sm-8 maincontent">
	<header class="page-header">
		<h1 class="page-title"><?=$title;?></h1>
	</header>
	
	<form method="post">
        <?php
			$columns = ["По имени пользователя" => 'username', "По e-mail" => 'email', "По статусу" => 'status'];
        ?>
            
		<p><strong>Сортировать:</strong></p>
		<p><select name="sort_column">
			<?php
				foreach($columns as $name => $value){
					echo "<option value=\"$value\">$name</option>";
				}
			?>
	   </select><br>
	  
		<p><input name="sort_order" type="radio" value='ASC' checked> По возрастанию</p>
		<p><input name="sort_order" type="radio" value='DESC'> По убыванию</p>
		
		<input type="submit" value="Вперед!"><br>
    </form>
	
	<?php foreach($data as $one): ?>
		<h3><a href="/tasks/one/<?=$one['id_task'];?>"><?=$one['username'];?></a></h3>
		<img src="<?=$one['image_link'];?>" alt="image">
		<p><?=$one['email'];?></p>
		<p><?=$one['text'];?></p>
		
		<?php if($one['status'] == 'Выполнена'):
				echo "<strong><p>" . $one['status'] . "</p></strong>";
			  endif;
		?>
	
	<?php endforeach; ?>
	
	<br>
	<p>Найдено задач: <b><?=$rows?></b></p>
	
	<p>Страницы:</p>
	<?php for($page = 1; $page <= $num_pages; $page++):?>
		<?php 
			if($page == $cur_page):
				echo "<b>$page</b> ";
			else:
				echo "<a href=/tasks/page/$page>$page</a>";
			endif;
		?>
	<?php endfor;?>
		   
</article>