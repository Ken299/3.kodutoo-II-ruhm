<?php 
	
	// Loon AB'i ühenduse
	require_once("../../config.php");
	$database = "if15_kenaon";
	//tekitatakse sessioon, mida hoitakse serveris,
	// kõik session muutujad on kättesaadavad kuni viimase brauseriakna sulgemiseni
	session_start();
	
	function loginUser($email, $password_hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);		
		
		$stmt = $mysqli->prepare("SELECT userid, email, fname, lname FROM user_accounts WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email, $password_hash);
		$stmt->bind_result($id_from_db, $email_from_db, $fname_from_db, $lname_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			// ab'i oli midagi
			echo "Email ja parool õiged, kasutaja id=".$id_from_db;
			
			// tekitan sessiooni muutujad
			$_SESSION["logged_in_user_id"] = $id_from_db;
			$_SESSION["logged_in_user_email"] = $email_from_db;
			$_SESSION["logged_in_fname"] = $fname_from_db;
			$_SESSION["logged_in_lname"] = $lname_from_db;
			
			//suunan data.php lehele
			header("Location: tasks.php");
			
		}else{
			// ei leidnud
			echo "Wrong credentials!";
		}
		$stmt->close();
		
		$mysqli->close();
	}
	function addTask($subject, $lecturer, $task, $date, $difficulty, $importance){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO tasks (user_id, subject, lecturer, task, date, difficulty, importance) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("issssii", $_SESSION["logged_in_user_id"], $subject, $lecturer, $task, $date, $difficulty, $importance);
		
		$message ="";
		
		if($stmt->execute()){
			// see on tõene siis kui sisestus ab õnnestus
			$message = "Edukalt sisestatud andmebaasi";
		}else{
			//execute on false, miski on katki
			echo $stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		
		return $message;
	}
	function tasks(){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, subject, lecturer, task, date, difficulty, importance FROM tasks WHERE deleted IS NULL AND done IS NULL AND user_id =?");
		$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
		$stmt->bind_result($id, $subject, $lecturer, $task, $date, $difficulty, $importance);
		$stmt->execute();
		//tühi massiiv kus hoiame objekte( 1 rida andmeid)
		$array = array();
		
		//tee tsüklit nii mitu korda, kui saad ab'st ühe rea andmeid
		while($stmt->fetch())
		{
		
			//loon objekti
			$tasks = new stdClass();
			$tasks->id = $id;
			$tasks->subject= $subject;
			$tasks->lecturer = $lecturer;
			$tasks->task = $task;
			$tasks->datee = $date;
			$tasks->difficulty = $difficulty;
			$tasks->importance = $importance;
			//lisame selle massiivi
			array_push($array, $tasks);
			//echo "<pre>";
			//var_dump($array);
			//echo "</pre>";
			
		}
		$stmt->close();
		$mysqli->close();
		
		return $array;
	}
	function deleteTask($id_to_be_deleted)
	{
	$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
	
	$stmt = $mysqli->prepare("UPDATE tasks SET deleted=NOW() WHERE id=? AND user_id=?");
	$stmt->bind_param("ii", $id_to_be_deleted, $_SESSION["logged_in_user_id"]);
	
	if($stmt->execute())
	{
		//sai edukalt kustutatud
		header("Location: tasks.php");
		
	}
	$stmt->close();
	$mysqli->close();
	}
	function doneTask($id_to_mark_as_done)
	{
	$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
	
	$stmt = $mysqli->prepare("UPDATE tasks SET done=NOW() WHERE id=? AND user_id=?");
	$stmt->bind_param("ii", $id_to_mark_as_done, $_SESSION["logged_in_user_id"]);
	
	if($stmt->execute())
	{
		//sai edukalt kustutatud
		header("Location: tasks.php");
		
	}
	$stmt->close();
	$mysqli->close();
	}
	function deletedTasks(){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, subject, lecturer, task, date, difficulty, importance FROM tasks WHERE deleted IS NOT NULL AND user_id=?");
		$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
		$stmt->bind_result($id, $subject, $lecturer, $task, $date, $difficulty, $importance);
		$stmt->execute();
		//tühi massiiv kus hoiame objekte( 1 rida andmeid)
		$array = array();
		
		//tee tsüklit nii mitu korda, kui saad ab'st ühe rea andmeid
		while($stmt->fetch())
		{
		
			//loon objekti
			$tasks = new stdClass();
			$tasks->id = $id;
			$tasks->subject= $subject;
			$tasks->lecturer = $lecturer;
			$tasks->task = $task;
			$tasks->datee = $date;
			$tasks->difficulty = $difficulty;
			$tasks->importance = $importance;
			//lisame selle massiivi
			array_push($array, $tasks);
			//echo "<pre>";
			//var_dump($array);
			//echo "</pre>";
			
		}
		$stmt->close();
		$mysqli->close();
		
		return $array;
	}
	function doneTasks(){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, subject, lecturer, task, date, difficulty, importance FROM tasks WHERE done IS NOT NULL AND user_id=?");
		$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
		$stmt->bind_result($id, $subject, $lecturer, $task, $date, $difficulty, $importance);
		$stmt->execute();
		//tühi massiiv kus hoiame objekte( 1 rida andmeid)
		$array = array();
		
		//tee tsüklit nii mitu korda, kui saad ab'st ühe rea andmeid
		while($stmt->fetch())
		{
		
			//loon objekti
			$tasks = new stdClass();
			$tasks->id = $id;
			$tasks->subject= $subject;
			$tasks->lecturer = $lecturer;
			$tasks->task = $task;
			$tasks->datee = $date;
			$tasks->difficulty = $difficulty;
			$tasks->importance = $importance;
			//lisame selle massiivi
			array_push($array, $tasks);
			//echo "<pre>";
			//var_dump($array);
			//echo "</pre>";
			
		}
		$stmt->close();
		$mysqli->close();
		
		return $array;
	}
	function addLecture($subject){
		
		/*$lectureid = "";
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT lectureid FROM lecture_ids WHERE userid =?");
		$stmt = $mysqli->prepare("SELECT id, lectureid FROM  lecture_ids WHERE userid =? limit 1");
		$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
		$stmt->bind_result($iddd, $lectureid);
		$stmt->execute();
		echo $lectureid;
		echo $iddd;
		if($subject == $lectureid)
		{*/
			$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
			$stmt = $mysqli->prepare("INSERT INTO lecture_ids (lectureid, userid) VALUES (?, ?)");
			$stmt->bind_param("si", $subject, $_SESSION["logged_in_user_id"]);
			
			$message ="";
			
			if($stmt->execute()){
				// see on tõene siis kui sisestus ab õnnestus
				$message = "Edukalt sisestatud andmebaasi";
				echo $message;
			}else{
				//execute on false, miski on katki
				echo $stmt->error;
			}
			$stmt->close();
			$mysqli->close();
			
			return $message;
		}
		/*else
		{
			echo $lectureid;
			echo "Olemas juba";
		}
	}*/
	function lectures(){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT access FROM user_accounts WHERE userid =?");
		$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
		$stmt->bind_result($access);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
		/*if($access == 1)
		{
			$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$stmt = $mysqli->prepare("SELECT lectureid, lectures.title FROM lecture_ids JOIN lectures ON lectures.code=lecture_ids.lectureid WHERE userid =?");
			$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
			$stmt->bind_result($lectureid, $title);
			$stmt->execute();
			//tühi massiiv kus hoiame objekte( 1 rida andmeid)
			$array = array();
			
			//tee tsüklit nii mitu korda, kui saad ab'st ühe rea andmeid
			while($stmt->fetch())
			{
			
				//loon objekti
				$lectures = new stdClass();
				$lectures->lectureid = $lectureid;
				$lectures->title= $title;
				//lisame selle massiivi
				array_push($array, $lectures);
				//echo "<pre>";
				//var_dump($array);
				//echo "</pre>";
				
			}
			$stmt->close();
			$mysqli->close();
			
			return $array;
		}
		else
		{*/
			$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$stmt = $mysqli->prepare("SELECT lectureid, lectures.title, teachers.fname, teachers.lname, teachers.id FROM lecture_ids JOIN lectures ON lectures.code=lecture_ids.lectureid JOIN teachers ON teachers.id=lectures.teacher  WHERE userid =?");
			$stmt->bind_param("i", $_SESSION["logged_in_user_id"]);
			$stmt->bind_result($lectureid, $title, $teacher_fname, $teacher_lname, $teacher_id);
			$stmt->execute();
			//tühi massiiv kus hoiame objekte( 1 rida andmeid)
			$array = array();
			
			//tee tsüklit nii mitu korda, kui saad ab'st ühe rea andmeid
			while($stmt->fetch())
			{
			
				//loon objekti
				$lectures = new stdClass();
				$lectures->lectureid = $lectureid;
				$lectures->title= $title;
				$lectures->teacher_fname= $teacher_fname;
				$lectures->teacher_lname= $teacher_lname;
				$lectures->teacher_id= $teacher_id;
				//lisame selle massiivi
				array_push($array, $lectures);
				//echo "<pre>";
				//var_dump($array);
				//echo "</pre>";
				
			}
			$stmt->close();
			$mysqli->close();
			
			return $array;
		}
	function sendMessage($message, $teacher_name)
	{
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO messages (teacher, student_fname, student_lname, message) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("isss", $teacher_name, $_SESSION["logged_in_fname"], $_SESSION["logged_in_lname"], $message);
		
		$messager = "";
		
		if($stmt->execute()){
			// see on tõene siis kui sisestus ab õnnestus
			$messager = "Edukalt sisestatud andmebaasi";
		}else{
			//execute on false, miski on katki
			echo $stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		
		return $messager;	
	}
	function getTeacherData($teacher_id){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, fname, lname FROM teachers WHERE id=? ");
		$stmt->bind_param("i",$teacher_id);
		$stmt->bind_result($id, $fname, $lname);
		$stmt->execute();
		//object
		if($stmt->fetch())
		{
			$_SESSION["teacher_name"] = $lname;
		}
		else
		{
			echo "katki";
		}
		
		$stmt->close();
		$mysqli->close();

		
	}
	function getMessages()
	{
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT messages.fname, messages.lname, messages.message FROM user_accounts JOIN messages ON messages.teacher WHERE lname =?");
		$stmt->bind_param("i", $_SESSION["teacher_name"]);
		$stmt->bind_result($fname, $lname, $message);
		$stmt->execute();
		
		$array = array();
		
		while($stmt->fetch())
		{
		
			//loon objekti
			$messages = new stdClass();
			$messages->fname = $fname;
			$messages->lname= $lname;
			$messages->message= $message;

			array_push($array, $messages);
			
		}
		$stmt->close();
		$mysqli->close();
		
		return $array;
	}
		
	/*function editTask($id_to_be_edited)
	{
	header("Location: task.php");
	$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT subject, lecturer, task, date, difficulty, importance FROM tasks WHERE id=?");
	$stmt->bind_param("i", $id_to_be_edited);
	$stmt->bind_result($esubject, $electurer, $etask, $edate, $edifficulty, $eimportance);
	$stmt->execute();
	
	$subject = $esubject;
	$lecturer = $electurer;
	$task = $etask;
	$date = $edate;
	$edifficulty = $difficulty;
	$eimportance = $importance;
	
	$stmt->close();
	$mysqli->close();
	}
	*/
?>