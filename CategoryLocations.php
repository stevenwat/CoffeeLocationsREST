<?php
/* 
A domain Class to demonstrate RESTful web services
*/
class CategoryLocations {
	
    private $table = "busilocations";
	private $reviewtable = "businessreview";
    public $business_locations_id;
    public $business_name;
    public $business_page_start;
    public $business_page_end;
    public $op_postcode_to_compare;
    public $category;
    
    function retrieveAllResultsWithinPostcode() {
        $query             = "SELECT Id,
                BusinessName,
                Email,
                State,
                Suburb,
                Address,
                Phone,
                Postcode,
                Granularity,
                Latitude,
                Longitude,
                WebsiteUrl,
                BusinessType,
        		Category FROM " . $this->table. "
                WHERE Postcode = ? and Category = ?;";
        // prepare query statement
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        // sanitize
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        // bind given email value
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(2, $this->category);
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
        $query             = "SELECT id FROM " . $this->table. "
                WHERE Postcode = ? and Category = ?;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(2, $this->category);
        $stmt->execute();
        $num = $stmt->rowCount();
        return $num;
    }

function retrieveAllResultsWithinPostcodePaginated() {
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		$query  = "SELECT ".$this->table .".Id,".
                $this->table.".BusinessName,".
                $this->table.".Email,".
                $this->table.".State,".
                $this->table.".Suburb,".
                $this->table.".Address,".
                $this->table.".Phone,".
                $this->table.".Postcode,".
                $this->table.".Latitude,".
                $this->table.".Longitude,".
                $this->table.".WebsiteUrl,".
        		$this->table.".Category,".
        		"avg(".$this->reviewtable.".Cost) as Cost,".
        		"avg(".$this->reviewtable.".Quality) as Quality ".
        		"FROM ".$this->table." left join ".$this->reviewtable." on ".$this->table.".id =".$this->reviewtable.".business_id ".
                "WHERE ".$this->table.".Postcode = ? and ".$this->table.".Category = ? ".
        		"GROUP BY ".
        		$this->table .".Id,".
                $this->table.".BusinessName,".
                $this->table.".Email,".
        		$this->table.".State,".
                $this->table.".Suburb,".
                $this->table.".Address,".
                $this->table.".Phone,".
                $this->table.".Postcode,".
                $this->table.".Latitude,".
                $this->table.".Longitude,".
                $this->table.".WebsiteUrl,".
        		$this->table.".Category ".
                "LIMIT ?, ?";
        $database            = new Database();
        $db                  = $database->getConnection();
        $stmt                = $db->prepare($query);
        $this->business_postcode   = htmlspecialchars(strip_tags($this->business_postcode));
        $this->business_page_start = htmlspecialchars(strip_tags($this->business_page_start));
        $this->business_page_end   = htmlspecialchars(strip_tags($this->business_page_end));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(3, intval($this->business_page_start), PDO::PARAM_INT);
        $stmt->bindParam(4, intval($this->business_page_end), PDO::PARAM_INT);
        $stmt->bindParam(2, $this->category);
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

    /*
     * Backup
     * 
     *//* function retrieveAllResultsWithinPostcodePaginated() {
        $query  = "SELECT ".$this->table .".Id,
                BusinessName,
                Email,
                State,
                Suburb,
                Address,
                Phone,
                Postcode,
                Granularity,
                Latitude,
                Longitude,
                WebsiteUrl,
        		Category,
                BusinessType FROM " . $this->table. "
                WHERE Postcode = ? and Category = ?
                LIMIT ?, ?";
        $database            = new Database();
        $db                  = $database->getConnection();
        $stmt                = $db->prepare($query);
        $this->business_postcode   = htmlspecialchars(strip_tags($this->business_postcode));
        $this->business_page_start = htmlspecialchars(strip_tags($this->business_page_start));
        $this->business_page_end   = htmlspecialchars(strip_tags($this->business_page_end));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(3, intval($this->business_page_start), PDO::PARAM_INT);
        $stmt->bindParam(4, intval($this->business_page_end), PDO::PARAM_INT);
        $stmt->bindParam(2, $this->category);
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
    }*/
    
    function retrievePaginatedResultsFromPostcode($from_record_num, $records_per_page) { 	
        $query             = "SELECT Id,
                BusinessName,
                Email,
                State,
                Suburb,
                Address,
                Phone,
                Postcode,
                Granularity,
                Latitude,
                Longitude,
                WebsiteUrl,
                BusinessType FROM " . $this->table. "
                WHERE Postcode = ? and Category = ?
                LIMIT ?,?;";
        $database            = new Database();
        $db                  = $database->getConnection();        
        $stmt              = $this->conn->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(3, intval($from_record_num), PDO::PARAM_INT);
        $stmt->bindParam(4, intval($records_per_page), PDO::PARAM_INT);
        $stmt->bindParam(2, $this->category);
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
                WHERE PostcodeToCompare = ? and Category = ?
                LIMIT 2;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);        
        $this->op_postcode_to_compare = htmlspecialchars(strip_tags($this->op_postcode_to_compare));
        $stmt->bindParam(1, $this->op_postcode_to_compare);
        $stmt->bindParam(2, $this->category);
        $stmt->execute();
        $lat = $stmt->fetchColumn();
        $lng = $stmt->fetchColumn(1);
        return $lat . '+' . $lng;
    }
    
    function getCountWithPostcode(){
        $query             = "SELECT COUNT(id) FROM " . $this->table. "
                WHERE Postcode = ? and Category = ?;";
        $database          = new Database();
        $db                = $database->getConnection();
        $stmt              = $db->prepare($query);
        $this->business_postcode = htmlspecialchars(strip_tags($this->business_postcode));
        $stmt->bindParam(1, $this->business_postcode);
        $stmt->bindParam(2, $this->category);
        $stmt->execute();
        $num = $stmt->fetchColumn();
        return $num;
    }    
}
?>