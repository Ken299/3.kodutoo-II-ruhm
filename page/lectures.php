<?php
	$page_title = "Ained";
	$file_name = "lectures.php";
?>
<?php
	//kopeerime header.php sisu
	// ../ -tähistab, et fail asub ühe võrra kõrgemal kaustas
	require_once("../header.php")

?>
<?php
	require_once("functions.php");
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
	}
	$lectures = lectures();
?>
<html>
<body>
<table border=1 >
	<tr>
		<th>Aine kood</th>
		<th>Aine nimetus</th>
		<th>Õpetaja eesnimi</th>
		<th>Õpetaja perekonnanimi</th>
	</tr>
	
	<?php
	
		//iga massiivis oleva elemendi kohta
		//count($tasks) - massiivi pikkus
		for($i = 0; $i < count($lectures); $i++)
		{
			echo "<tr>";
			
			//echo "<td>".$lectures[$i]->id."</td>";
			echo "<td align=center>".$lectures[$i]->lectureid."</td>";
			//echo "<td align=center>".$lectures[$i]->title."</td>";
			echo "<td><a href='tasks.php?lecture_id=".$lectures[$i]->lectureid."'>".$lectures[$i]->title."</a></td>";
			//echo "<td align=center>".$lectures[$i]->teacher."</td>";
			echo "<td align=center>".$lectures[$i]->teacher_fname."</td>";
			echo "<td><a href='letter.php?teacher_id=".$lectures[$i]->teacher_id."'>".$lectures[$i]->teacher_lname."</a></td>";
			echo "</tr>";
		}
	
	?>
</table	>
<br>
</body>
</html><br>
<?php require_once("../footer.php") ?>