<?php
/* 
A domain Class to demonstrate RESTful web services
*/
class CoffeeSuppliers {
	
    private $coffee_suppliers = "coffee_suppliers";
	private $coffee_review = "coffee_review";
    private $postcode_optimiser = "postcodeoptimiser";
    public $coffee_suppliers_id;
    public $business_name;
    public $op_postcode_to_compare;
	public $business_postcode;
    public $business_page_start;
    public $business_page_end;
    
    function retrieveAllResultsWithinPostcode() {
        $query             = "SELECT coffee_supplier_id,
                business_name,
                contact_fullname,
                email,
				state,
				suburb,
                address,
                phone,
                postcode,
                granularity,
                latitude,
                longitude,
				site,
				business_type,
				date_created,
				active,
				status FROM " . $this->coffee_suppliers . "
                WHERE Postcode = ?;";
        // prepare query statement
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        // sanitize
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        // bind given email value
        $stmt->bindParam(1, $this->business_postcode);
        try {
            $stmt->execute();
            $rows = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
            return $rows;
        }
        catch (Exception $exception) {
            return $exception;
        }
    }
    
    function retrieveAllResultsWithinPostcodeCount() {
        $query             = "SELECT coffee_supplier_id FROM " . $this->coffee_suppliers . "
                WHERE Postcode = ?;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->execute();
        $num = $stmt->rowCount();
        return $num;
    }
    
    function retrieveAllResultsWithinPostcodePaginated() {
        /*$query  = "SELECT ".
        		$this->coffee_suppliers .".coffee_supplier_id,".
                $this->coffee_suppliers .".business_name,".
                $this->coffee_suppliers .".contact_fullname,".
                $this->coffee_suppliers .".email,".
                $this->coffee_suppliers .".state,".
                $this->coffee_suppliers .".suburb,".
                $this->coffee_suppliers .".phone,".
                $this->coffee_suppliers .".postcode,".
                $this->coffee_suppliers .".granularity,".
                $this->coffee_suppliers .".latitude,".
                $this->coffee_suppliers .".longitude,".
                $this->coffee_suppliers .".site,".
				$this->coffee_suppliers .".business_type,".
				$this->coffee_suppliers .".date_created,".
				$this->coffee_suppliers .".active,".
				$this->coffee_suppliers .".status ".
				"FROM " . $this->coffee_suppliers . "
                WHERE postcode = ?
                LIMIT ?, ?";*/
        $query  = "SELECT ".
        		$this->coffee_suppliers .".coffee_supplier_id,".
                $this->coffee_suppliers .".business_name,".
                $this->coffee_suppliers .".contact_fullname,".
                $this->coffee_suppliers .".email,".
                $this->coffee_suppliers .".state,".
                $this->coffee_suppliers .".suburb,".
                $this->coffee_suppliers .".phone,".
                $this->coffee_suppliers .".postcode,".
                $this->coffee_suppliers .".granularity,".
                $this->coffee_suppliers .".latitude,".
                $this->coffee_suppliers .".longitude,".
                $this->coffee_suppliers .".site,".
				$this->coffee_suppliers .".business_type,".
				$this->coffee_suppliers .".date_created,".
				$this->coffee_suppliers .".active,".
				$this->coffee_suppliers .".status,".
				"avg(".$this->coffee_review.".cost) as cost, ".
				"avg(".$this->coffee_review.".quality) as quality".
				" FROM " . $this->coffee_suppliers . " left join ".$this->coffee_review.
				" ON ". $this->coffee_suppliers.".coffee_supplier_id = ".$this->coffee_review.".coffee_supplier_id". 
                " WHERE postcode = ? ".
				" GROUP BY ".
				$this->coffee_suppliers.".coffee_supplier_id,".
				$this->coffee_suppliers.".business_name,".
				$this->coffee_suppliers.".contact_fullname,".
				$this->coffee_suppliers.".email,".
				$this->coffee_suppliers.".state,".
				$this->coffee_suppliers.".suburb,".
				$this->coffee_suppliers.".phone,".
				$this->coffee_suppliers.".postcode,".
				$this->coffee_suppliers.".granularity,".
				$this->coffee_suppliers.".latitude,".
				$this->coffee_suppliers.".longitude,".
				$this->coffee_suppliers.".site,".
				$this->coffee_suppliers.".business_type,".
				$this->coffee_suppliers.".date_created,".
				$this->coffee_suppliers.".active,".
				$this->coffee_suppliers.".status LIMIT ?, ?";				
        $database            = new Database();
        $db                  = $database->getConnection();
        $stmt                = $db->prepare($query);
        $this->business_postcode   = htmlspecialchars(strip_tags($this->business_postcode));
        $this->business_page_start = htmlspecialchars(strip_tags($this->business_page_start));
        $this->business_page_end   = htmlspecialchars(strip_tags($this->business_page_end));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(2, intval($this->business_page_start), PDO::PARAM_INT);
        $stmt->bindParam(3, intval($this->business_page_end), PDO::PARAM_INT);
        try {
            $stmt->execute();
            $rows = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
            return $rows;
        }
        catch (Exception $exception) {
            return $exception;
        }
    }
    
    function retrievePaginatedResultsFromPostcode($from_record_num, $records_per_page) { 	
        $query             = "SELECT coffee_supplier_id,
                business_name,
                contact_fullname,
                email,
                state,
                suburb,
                address,
                phone,
                postcode,
                granularity,
                latitude,
				longitude,
				site,
				business_type,
				date_created,
				active,
				status FROM " . $this->coffee_suppliers . "
                WHERE Postcode = ?
                LIMIT ?,?;";
        $stmt              = $this->conn->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(2, intval($from_record_num), PDO::PARAM_INT);
        $stmt->bindParam(3, intval($records_per_page), PDO::PARAM_INT);
        $stmt->execute();
        $jsonString = "";
        $rows       = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    function getPostCodeLatitudeAndLongitude() {
        $query                        = "SELECT LatToCompare,
                LonToCompare FROM " . $this->postcode_optimiser . "
                WHERE PostcodeToCompare = ?
                LIMIT 2;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);        
        $this->op_postcode_to_compare = htmlspecialchars(strip_tags($this->op_postcode_to_compare));
        $stmt->bindParam(1, $this->op_postcode_to_compare);
        $stmt->execute();
        $lat = $stmt->fetchColumn();
        $lng = $stmt->fetchColumn(1);
        return $lat . '+' . $lng;
    }
    
    function getCountWithPostcode(){
        $query             = "SELECT COUNT(coffee_supplier_id) FROM " . $this->coffee_suppliers . "
                WHERE Postcode = ?;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->execute();
        $num = $stmt->fetchColumn();
        return $num;
    }    
}
?>