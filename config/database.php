<?php
// used to get mysql database connection
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "cf_db";
    private $username = "my_cf_admin";
    private $password = "6Cq8epwBM8rSduFT";
    public $conn;
    
    //Basic Authentication credentials
    private $temp_user = "";
    private $temp_password = "";
    
    
    function __construct() {
    	$conn = $this->getConnection();
    	if(!empty($conn)) {
    		$this->conn = $conn;
    	}
    } 
    
    // get the database connection
    function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;     
    }
}
?>