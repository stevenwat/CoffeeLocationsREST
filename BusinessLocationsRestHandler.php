<?php
require_once("SimpleRest.php");
require_once("CoffeeLocations.php");
require_once("Reviews.php");
require_once("Users.php");

/*require_once("CategoryLocations.php");*/
require_once("config/database.php");

class BusinessRestHandler extends SimpleRest {
	
    function getLocationsWithinPostcode($postcode) {
        $coffeeSuppliers              = new CoffeeSuppliers();
        $coffeeSuppliers->business_postcode = $postcode;
        $rawData                  = $coffeeSuppliers->retrieveAllResultsWithinPostcode();
        $response                 = $this->encodeJson($rawData);
        echo $response;
    }
    
    function getLocationsWithinPostcodeCount($postcode) {
        $coffeeSuppliers              = new CoffeeSuppliers();
        $coffeeSuppliers->business_postcode = $postcode;
        $rawData                  = $coffeeSuppliers->retrieveAllResultsWithinPostcodeCount();
        $response                 = $this->encodeJson($rawData);
        echo $response;
    }
    
    function getLocationsWithinPostcodeWithPagination($postcode, $page_start, $page_end) {
        $coffeeSuppliers                = new CoffeeSuppliers();
        $coffeeSuppliers->business_postcode   = $postcode;
        $coffeeSuppliers->business_page_start = $page_start;
        $coffeeSuppliers->business_page_end   = $page_end;
        $rawData                    = $coffeeSuppliers->retrieveAllResultsWithinPostcodePaginated();
        $response                   = $this->encodeJson($rawData);
        echo $response;
    }
    
    function getPostCodeLatitudeAndLongitude($postcode) {
    	$coffeeSuppliers                = new CoffeeSuppliers();
    	$coffeeSuppliers->op_postcode_to_compare   = $postcode;
    	$rawData                    = $coffeeSuppliers->getPostcodeLatitudeAndLongitude();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;    	
    }
    
    function getPostCodeBusinessCount($postcode) {
    	$coffeeSuppliers                = new CoffeeSuppliers();
    	$coffeeSuppliers->business_postcode   = $postcode;
    	$rawData                    = $coffeeSuppliers->getCountWithPostcode();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;
    }    

/*-----------------------------------------------------------------Using Category Functions --------------------------------------------------------------*/
    function getLocationsWithinPostcodeUsingCategory($postcode, $category) {
    	$categoryLocations              = new CategoryLocations();
    	$categoryLocations->business_postcode = $postcode;
    	$categoryLocations->category 	= $category;
    	$rawData                  = $categoryLocations->retrieveAllResultsWithinPostcode();
    	$response                 = $this->encodeJson($rawData);
    	echo $response;
    }
    
    function getLocationsWithinPostcodeCountUsingCategory($postcode, $category) {
    	$categoryLocations              = new CategoryLocations();
    	$categoryLocations->business_postcode = $postcode;
    	$categoryLocations->category 	= $category;
    	$rawData                  = $categoryLocations->retrieveAllResultsWithinPostcodeCount();
    	$response                 = $this->encodeJson($rawData);
    	echo $response;
    }
    
    function getLocationsWithinPostcodeWithPaginationUsingCategory($postcode, $page_start, $page_end, $category) {
    	$categoryLocations              = new CategoryLocations();
    	$categoryLocations->business_postcode = $postcode;
    	$categoryLocations->category 	= $category;
    	$categoryLocations->business_page_start = $page_start;
    	$categoryLocations->business_page_end   = $page_end;
    	$rawData                    = $categoryLocations->retrieveAllResultsWithinPostcodePaginated();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;
    	//echo $rawData;
    }
    
    function getPostCodeLatitudeAndLongitudeUsingCategory($postcode, $category) {
    	$categoryLocations              = new CategoryLocations();
    	$categoryLocations->category 	= $category;
    	$categoryLocations->op_postcode_to_compare   = $postcode;
    	$rawData                    = $categoryLocations->getPostcodeLatitudeAndLongitude();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;
    }
    
    function getPostCodeBusinessCountUsingCategory($postcode, $category) {
    	$categoryLocations                = new CategoryLocations();
    	$categoryLocations->category 	= $category;
    	$categoryLocations->business_postcode   = $postcode;
    	$rawData                    = $categoryLocations->getCountWithPostcode();
    	echo $rawData;
    }    
	
 	/*---------------------------------------------------------------CHECK USER---------------------------------------------------------------------*/   
	
    function checkUserId($customer_id){
    	$users                			= new Users();
    	$users->customer_id   				= $customer_id;
    	$rawData                    	= $users->checkUserId();
    	echo $rawData;
		/*$response                   	= $this->encodeJson($rawData);
    	echo $response;*/
    }   
	
	/*---------------------------------------------------------------REVIEW SUBMISSION----------------------------------------------------------------------*/    
    
    /*    
    public $review_busilocation_id;				//busilocations.id
    public $review_user_id;
    public $review_quality;
    public $review_cost;
    public $review_comment;
    */
    
    function insertReview($user_id, $business_id, $quality, $cost, $comment, $ip_address){
    	$reviews                			= new Reviews();
    	$reviews->review_user_id	 		= $user_id;
    	$reviews->coffee_supplier_id   	= $business_id;
    	$reviews->review_quality					= $quality;
    	$reviews->review_cost						= $cost;
    	$reviews->review_comment					= $comment;
    	$reviews->ip_address						= $ip_address;
    	$rawData                    		= $reviews->submitCoffeeSupplierReview();
    	return $rawData;
    }
    
    function checkBusinessId($business_id){
    	$reviews                			= new Reviews();
    	$reviews->review_busilocation_id   	= $business_id;
    	$rawData                    		= $reviews->checkCoffeeSupplierId();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;
    }

	function getBusinessReviews($business_id){
    	$reviews                			= new Reviews();
    	$reviews->review_busilocation_id   	= $business_id;
    	$rawData                    		= $reviews->getAllCoffeeSupplierReviewsUsingId();
    	$response                   = $this->encodeJson($rawData);
    	echo $response;   
    }

   	public function encodeJson($responseData) {
        if (empty($responseData)) {
            $statusCode = 404;
            $responseData    = array(
                'error' => 'No locations found!'
            );
        } else {
            $statusCode = 200;
        }
        $this->setHttpHeaders('application/json', $statusCode);
        $jsonResponse = json_encode($responseData);
        return $jsonResponse;
    }
    
    public function addHeaderText($responseData) {
    	if (empty($responseData)) {
    		$statusCode = 404;
    		$responseData    = array(
    				'error' => 'No locations found!'
    		);
    	} else {
    		$statusCode = 200;
    	}
    	$this->setHttpHeaders('text/html', $statusCode);
    	return $responseData;
    }      	
}
?>