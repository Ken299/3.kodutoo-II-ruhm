<?php
	 //ühenduse loomiseks kasuta
	require_once("../../config.php");
	$database = "if15_kenaon";
	$mysqli = new mysqli($servername, $server_username, $server_password, $database);
	
	// funktsioon, mis eemaldab kõikvõimaliku üleliigse tekstist
	function cleanInput($data) { 
  	$data = trim($data); //eemaldab tühikud, tabid ja enterid
  	$data = stripslashes($data); // eemaldab tagurpidi kaldkriipsud "\"
  	$data = htmlspecialchars($data);
  	return $data;
	}

	//Defineerime muutujad
	$email_error = "";
	$password_error = "";
	$password_error2 = "";
	$password_error3 = "";
	$password_error_length = "";
	
	// muutujad väärtuste jaoks
	$fname ="";
	$lname ="";
	$age ="";
	$email = "";
	$password = "";
	$password2 = "";
	//kontrollin kas keegi vajutas nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		 if(isset($_POST["register"])){
		//echo"jah";
		
			//kas e-post on tühi
			if( empty($_POST["email"]) ) {
				
				//jah oli tühi
				$email_error = "See väli on kohustuslik";
				
			}else
			{
				$email = cleanInput($_POST["email"]);
			}
			if( empty($_POST["password"]) ) {
				
				//jah oli tühi
				$password_error = "See väli on kohustuslik";
			}
			elseif (strlen($_POST["password"]) <= 7){
				$password_error_length = "Parool peab olema vähemalt 8 tähemärki.";
			}
			else
			{
				$password = cleanInput($_POST["password"]);
			}
			if( empty($_POST["password2"]) ) {
				
				//jah oli tühi
				$password_error2 = "See väli on kohustuslik";
			} elseif( empty($_POST["password2"]) == false && $_POST["password"] != $_POST["password2"]) {
				
				$password_error3 = "Paroolid ei kattu omavahel";
				//echo"Paroolid ei kattu omavahel!";			
			}
			if(	$email_error == "" && $password_error == "" && $password_error2 == "" && $password_error3 == "" && $password_error_length == "")
			{
				if(isset($_POST['access']))
				{
					echo "Võib kasutajat luua! Kasutajanimi on ".$email." ja parool on ".$password;
					
					$fname = cleanInput($_POST["fname"]);
					$lname = cleanInput($_POST["lname"]);
					$password_hash = hash("sha512", $password);
					echo "<br>";
					echo $password_hash;
					
					$stmt = $mysqli->prepare("INSERT INTO user_accounts (fname, lname, email, password, access) VALUES (?, ?, ?, ?, 1)");
					
					//alumine funktsioon näitab mis error oleks kui on midagi
					echo $mysqli->error;
					echo $stmt->error;
					//asendame ? märgid muutujate väärtustega
					//ss - s tähendab string iga muutuja kohta
					$stmt->bind_param("ssss", $fname, $lname, $email, $password_hash);
					$stmt->execute();
					$stmt->close();
					header("Location: login.php");
				}
				else
				{
					echo "Võib kasutajat luua! Kasutajanimi on ".$email." ja parool on ".$password;
				
					$fname = cleanInput($_POST["fname"]);
					$lname = cleanInput($_POST["lname"]);
					$password_hash = hash("sha512", $password);
					echo "<br>";
					echo $password_hash;
					
					$stmt = $mysqli->prepare("INSERT INTO user_accounts (fname, lname, email, password) VALUES (?, ?, ?, ?)");
					
					//alumine funktsioon näitab mis error oleks kui on midagi
					echo $mysqli->error;
					echo $stmt->error;
					//asendame ? märgid muutujate väärtustega
					//ss - s tähendab string iga muutuja kohta
					$stmt->bind_param("ssss", $fname, $lname, $email, $password_hash);
					$stmt->execute();
					$stmt->close();
					header("Location: login.php");
				}
			}
		}
	}
	
	//Paneme ühenduse kinni
	$mysqli->close();
?>
<?php
	$page_title = "Registreerimine";
	$file_name = "register.php";
?>
<?php require_once("../header.php") ?>
		<h2>Registreerimine</h2>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
		<input name="fname" type="text" placeholder="Eesnimi"> <br><br>
		<input name="lname" type="text" placeholder="Perekonnanimi"> <br><br>
		<input name="email" type="email" placeholder="E-post"> <?php echo $email_error; ?> <br><br>
		<input name="password" type="password" placeholder="Parool" > <?php echo $password_error; echo $password_error_length; ?> <br><br>
		<input name="password2" type="password" placeholder="Parool" > <?php echo $password_error2; echo $password_error3;?> <br><br>
		<input name="access" type="checkbox" value="Õpetaja">Olen õpetaja<br><br>
		<input name="register" type="submit" value="Registreeri"> <br><br>
		</form>
		
<?php require_once("../footer.php") ?>