<?php
$message = "";
?>
<?php
	$page_title = "Saada kiri";
	$file_name = "letter.php";
?>
<?php

	require_once("../header.php")
	
?>
<?php
	require_once("functions.php");
	if(!isset($_SESSION["logged_in_user_id"]))
	{
		header("Location: login.php");
	}
?>
<?php
if(isset($_GET["teacher_id"]))
	{
		
		$teacher = getTeacherData($_GET["teacher_id"]);
	}
	else
	{
		
		echo "VIGA";
		header("Location: home.php");
	
	}
?>
<?php
	if(isset($_POST["send"]))
	{
		$message = $_POST['message'];
		sendMessage($message, $_POST["id"]);
	}
?>
<html>

<h2>Kirjuta õpetajale</h2>
<?=$_SESSION["teacher_name"];?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
		<input type="hidden" name="id" value="<?=$_GET["teacher_id"];?>">
		<TEXTAREA Name="message" ROWS=10 COLS=50></TEXTAREA>
		<input name="send" type="submit" value="Saada"> <br><br>
		</form>
</html>