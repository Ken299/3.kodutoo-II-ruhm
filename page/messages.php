<?php
	$page_title = "Postkast";
	$file_name = "messages.php";
?>
<?php
	require_once("../header.php")

?>
<?php
	require_once("functions.php");
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
	}
	$messages = getMessages();
?>
<html>
<body>
<table border=1 >
	<tr>
		<th>Eesnimi</th>
		<th>Perekonnanimi</th>
		<th>Sõnum</th>
	</tr>
	
	<?php
	
		//iga massiivis oleva elemendi kohta
		//count($tasks) - massiivi pikkus
		for($i = 0; $i < count($messages); $i++)
		{
			echo "<tr>";
			
			//echo "<td>".$lectures[$i]->id."</td>";
			echo "<td align=center>".$messages[$i]->fname."</td>";
			echo "<td align=center>".$messages[$i]->lname."</td>";
			echo "<td align=center>".$messages[$i]->message."</td>";
			//echo "<td align=center>".$lectures[$i]->title."</td>";
			//echo "<td><a href='tasks.php?lecture_id=".$messages[$i]->lectureid."'>".$lectures[$i]->lname."</a></td>";
			//echo "<td align=center>".$lectures[$i]->teacher."</td>";
			//echo "<td><a href='letter.php?teacher_id=".$messages[$i]->teacher_id."'>".$lectures[$i]->teacher."</a></td>";
			echo "</tr>";
		}
	
	?>
</table	>
<br>
</body>
</html><br>
<?php require_once("../footer.php") ?>