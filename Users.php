<?php

class Users {
    private $users_table = "users";
	private $customers_table = "customers";
    public $user_id="";				
    public $ip_address;
	public $customer_id=""; 
    function checkUserId() {
    	$query  = "Select users.user_id 
					from users inner join customers
					on users.customer_id = customers.customer_id 
					where ".$this-> users_table.".customer_id=? and ".$this-> customers_table.".customer_id<>'';";
    	$database            = new Database();
    	$db                  = $database->getConnection();
    	$stmt                = $db->prepare($query);
    	$this->customer_id   = htmlspecialchars(strip_tags($this->customer_id));
    	$stmt->bindParam(1, $this->customer_id, PDO::PARAM_INT);
    	try {
			$stmt->execute();
			$id_to_return = "";
			$id_to_return = $stmt->fetchColumn();
			if (isset($id_to_return) && !empty($id_to_return)){
				return $id_to_return;
			}else {
				return "Id not linked.";
			}
    	}
    	catch (Exception $exception) {
    		return $exception;
    	}    	
    }	
}
?>

