<?php
class AuthenticateUser{
	//Basic Authentication credentials
	private $table_name = "users"; 
	public $temp_email = "";
	public $temp_password = "";
	public $temp_secret = "";
	
	//Authorize the php header
	function authorizeUser($secret){
		$temp_password = $_SERVER['PHP_AUTH_PW'];
		$this->temp_secret = $secret;
	}
	
	//Check if email exists in the database
	function emailExists(){
		$query = "SELECT email, password, secret
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";		
		
		$database          = new Database();
		$db                = $database->getConnection();
		$stmt              = $db->prepare($query);
		
		$this->temp_email=htmlspecialchars(strip_tags($this->temp_email));
		$stmt->bindParam(1, $this->temp_email);
		$stmt->execute();
		$num = $stmt->rowCount();
	
		//If the email exists, assign values to object properties for easy access and use for php sessions
		if($num>0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->temp_password = $row['password'];
			$this->temp_secret = $row['secret'];
			$this->email = $row['email'];
			return true;
		}
		return false;
	}	
}
?>