<?php
/* 
*/
class Reviews {
	
    private $review_table = "coffee_review";
    public $coffee_supplier_id;				//busilocations.id
    public $review_user_id;
    public $review_quality;
    public $review_cost;
    public $review_comment;
    public $category;
    public $ip_address;
    
    function submitCoffeeSupplierReview() {
    	$query  = "Insert into ".
      			$this-> review_table.
    			"(user_id, coffee_supplier_id, quality, cost, comment, registered_ip) ".
    			" values(?,?,?,?,?,?);";
    	$database            = new Database();
    	$db                  = $database->getConnection();
    	$stmt                = $db->prepare($query);
        
        $this->review_user_id = htmlspecialchars(strip_tags($this->review_user_id));
		$this->coffee_supplier_id   = htmlspecialchars(strip_tags($this->coffee_supplier_id));
        $this->review_quality   = htmlspecialchars(strip_tags($this->review_quality));
        $this->review_cost   = htmlspecialchars(strip_tags($this->review_cost));
        $this->review_comment   = htmlspecialchars(strip_tags($this->review_comment));
        $this->ip_address   = htmlspecialchars(strip_tags($this->ip_address));
        
		$stmt->bindParam(1, $this->review_user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $this->coffee_supplier_id, PDO::PARAM_INT);        
        $stmt->bindParam(3, intval($this->review_quality), PDO::PARAM_INT);
        $stmt->bindParam(4, intval($this->review_cost), PDO::PARAM_INT);
        $stmt->bindParam(5, $this->review_comment);
        $stmt->bindParam(6, $this->ip_address);
        
        try {
			if ($stmt->execute()){
				return true;
			}
			else{
				return false;
			}
        }
        catch (Exception $exception) {
        	return false;
        }    	
    }

	/* New Code */
    function getAllCoffeeSupplierReviewsUsingId() {
    	//(user_id, business_id, quality, cost, comment, registered_ip) ".
    	$query  = "SELECT user_id, cofee_supplier_id, quality, cost, comment, registered_ip FROM ".
      			$this-> review_table.
        		" WHERE business_id = ?";
    			
    	$database            = new Database();
    	$db                  = $database->getConnection();
    	$stmt                = $db->prepare($query);
        
    	$this->coffee_supplier_id   = htmlspecialchars(strip_tags($this->coffee_supplier_id));
    	$stmt->bindParam(1, $this->coffee_supplier_id, PDO::PARAM_INT);
    
        try {
        	$stmt->execute();
        	$rows       = array();
        	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        	    $rows[] = $row;
            }
        	return $rows;        
        }
        catch (Exception $exception) {
        	return false;
        }    	
    }
    
    function checkCoffeeSupplierId() {
    	$query  = "Select * from coffee_suppliers".
    			" where coffee_supplier_id=?;";
    	$database            = new Database();
    	$db                  = $database->getConnection();
    	$stmt                = $db->prepare($query);
    	$this->coffee_supplier_id   = htmlspecialchars(strip_tags($this->coffee_supplier_id));
    	$stmt->bindParam(1, $this->coffee_supplier_id, PDO::PARAM_INT);
    	try {
    		return $stmt->execute();
    	}
    	catch (Exception $exception) {
    		return $exception;
    	}    	
    }
}
?>