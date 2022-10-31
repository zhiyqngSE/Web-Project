<?php 

require_once 'SQL_Login.php';

$validation = "";

if(isset($_POST['email'])){

	try{
		$pdo = new PDO($attr, $user, $pass, $opts);
	} catch (\PDOException $e){
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
	
	// username and password sent from form and sanitise
	/**$myusername = sanitise($pdo,$_POST['username']);*/
	$mypassword =  sanitise($pdo,$_POST['pwd']);
	$mypassword = password_hash($mypassword, PASSWORD_DEFAULT);
	$cfmpassword = sanitise($pdo,$_POST['cfmpwd']);
	$cfmpassword = password_hash($cfmpassword,PASSWORD_DEFAULT);
	/**$dob =  sanitise($pdo,$_POST['dob']);*/
    $email =  sanitise($pdo,$_POST['email']);
    /**$fullName =  sanitise($pdo,$_POST['full_name']);*/
        
	//data validation
	
	/**$validation = data_validation($_POST['username'], "/^[a-z\d_]{5,20}$/" , "username");*/
	$validation .= data_validation($_POST['email'],  "/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/" , "email");
	/**$validation .= data_validation($_POST['dob'], "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/" , "date time");*/
	/**$validation .= data_validation($_POST['full_name'], "/.+/", "full Name");*/
	$validation .= data_validation($_POST['pwd'], '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,12}$/', "password- at least one letter, at least one number, and there have to be 6-12 characters");

	
	if ( $validation== ""){
		$query = "INSERT INTO $table (userid, email, pass, cfmpass) 
		VALUES(NULL,$email, '$mypassword', '$cfmpassword')";
		//dob datetime format = 2013-02-02 10:13:20
	
		
		$result = $pdo->query($query);
		
		if (! $result){
			die('Error: ' . mysqli_error());
		}
		header("location:registered.php");
	}
	else{
		echo $validation;}
}

function sanitise($pdo, $str) {
    $str = htmlentities($str);
    return $pdo->quote($str);
}

function data_validation($data, $data_pattern, $data_type){
	if (preg_match($data_pattern, $data)) {
		return "";
	} else { 
		return " Invalid data for " .  $data_type . ";";
	}   
}    	
?>


