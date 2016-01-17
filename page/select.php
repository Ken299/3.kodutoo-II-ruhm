<?php
require_once("functions.php");
$create = "";
$subject = "ee";
?>
<?php
	$page_title = "Ainete valimine";
	$file_name = "select.php";
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
	if(isset($_POST["create"]))
	{
		$subject = $_POST['subject'];
		addLecture($subject);
	}
?>

<html>
<head>
  <title>Ainete valimine</title>
</head>
<body>

  <h2>Vali aine</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<label for="subject">Aine</label><br>
  	<select name = "subject">
		<option value="[EKO6005.HT]">[EKO6005.HT] Suuline ja kirjalik kommunikatsioon </option> 
		<option value="[IFI6001.DT]">[IFI6001.DT] Arvuti töövahendina</option>
		<option value="[IFI6013.DT]">[IFI6013.DT] Andmebaaside projekteerimine</option>
		<option value="[IFI6045.DT]">[IFI6045.DT] MS Windows´i operatsioonisüsteemid</option>
		<option value="[IFI6056.DT]">[IFI6056.DT] Veebilehtede loomine</option>
		<option value="[IFI6063.DT]">[IFI6063.DT] ITSPEA</option>
		<option value="[IFI6070.DT]">[IFI6070.DT] Intelligentne arvutikasutus</option>
		<option value="[IFI6072.DT]">[IFI6072.DT] Arvutiriistvara</option>
		<option value="[IFI6074.DT]">[IFI6074.DT] Programmeerimise alused</option>
		<option value="[AIA7204.HT]">[AIA7204.HT] Erikursus arvutiarheoloogia</option>
		<option value="[IFI6017.DT]">[IFI6017.DT] Arvutite ja võrkude haldamine</option>
		<option value="[IFI6018.DT]">[IFI6018.DT] Arvutivõrgud ja andmeside</option>
		<option value="[IFI6023.DT]">[IFI6023.DT] Arvutigraafika</option>
		<option value="[IFI7112.DT]">[IFI7112.DT] Arvutimängud</option>
		<option value="[IFI6076.DT]">[IFI6076.DT] Veebiprogrammeerimine</option>
	</select>
	<input type="submit" name="create" value="Lisa">	
  </form>
<body>
<html>
<?php require_once("../footer.php") ?>