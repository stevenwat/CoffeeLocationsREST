<?php

/*controls the RESTful services
URL mapping*/

require_once("BusinessLocationsRestHandler.php");
require_once('lib/defuse-crypto.phar');
require_once("AuthenticateUser.php");

$http_origin = "";
$http_origin = $_SERVER['HTTP_ORIGIN'];

if ($http_origin == "http://localhost" || $http_origin == "https://findyourhairdresser.com.au" || $http_origin == "https://coffeehunter.com.au" || $http_origin == "https://watterson-tech.com" || $http_origin == "https://localhost"){  
    header("Access-Control-Allow-Origin: $http_origin");
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
	header('Access-Control-Allow-Credentials: true');
	exit(0);
}else{
	header('Access-Control-Allow-Credentials: true');
}

$action = "";
if (isset($_GET["action"]))
    $action = $_GET["action"];
$postcode = "";
if (isset($_GET["postcode"]))
    $postcode = $_GET["postcode"];
$page_start = "";
if (isset($_GET["page_start"]))
    $page_start = $_GET["page_start"];
$page_end = "";
if (isset($_GET["page_end"]))
    $page_end = $_GET["page_end"];
$category = "";
if (isset($_GET["category"]))
	$category = $_GET["category"];
$ip_address = "";
if (isset($_GET["ip_address"]))
	$ip_address = $_GET["ip_address"];

$authenticateUser = new AuthenticateUser();
$secret = "";
if(isset($_SERVER['PHP_AUTH_USER'])){
	$authenticateUser->temp_email = $_SERVER['PHP_AUTH_USER'];
}
if(isset($_SERVER['PHP_AUTH_PW'])){
	$authenticateUser->temp_password = $_SERVER['PHP_AUTH_PW'];
}

if (isset($authenticateUser->temp_password) && isset($authenticateUser->temp_email )){
	if ($authenticateUser->emailExists() && password_verify($_SERVER['PHP_AUTH_PW'], $authenticateUser->temp_password)){
		switch ($action) {
			case "non-optimised-count":
				$businessLocationsRestHandler = new BusinessRestHandler();
				if (strcmp($category,"")!=0){
					$businessLocationsRestHandler->getLocationsWithinPostcodeCountUsingCategory($postcode, $category);		
				}else {
					$businessLocationsRestHandler->getLocationsWithinPostcodeCount($postcode);					
				}				
				break;
        	case "get-business-reviews-by-id":
				$business_id= "";
				if(isset($_GET["business_id"])){
					$business_id = $_GET["business_id"];
                	if (strcmp($business_id,"") != 0){
						$businessLocationsRestHandler = new BusinessRestHandler();
						$businessLocationsRestHandler->getBusinessReviews($business_id);
					} else {
						echo "No reviews found";
					}
				}
        		break;
			case "non-optimised-get-postcode":
				$businessLocationsRestHandler = new BusinessRestHandler();
				if (strcmp($category,"")!=0){
					$businessLocationsRestHandler->getLocationsWithinPostcodeUsingCategory($postcode, $category);					
				}else {
					$businessLocationsRestHandler->getLocationsWithinPostcode($postcode);		
				}
				break;
			case "non-optimised-get-postcode-with-pagination":
				$businessLocationsRestHandler = new BusinessRestHandler();
				if (strcmp($category,"")!=0){
					$businessLocationsRestHandler->getLocationsWithinPostcodeWithPaginationUsingCategory($postcode, $page_start, $page_end, $category);
				} else{
					$businessLocationsRestHandler->getLocationsWithinPostcodeWithPagination($postcode, $page_start, $page_end);
				}
				break;
			case "get-postcode-latitude-and-longitude":
				$businessLocationsRestHandler = new BusinessRestHandler();
				if (strcmp($category,"")!=0){
					$businessLocationsRestHandler->getPostCodeLatitudeAndLongitudeUsingCategory($postcode, $category);	
				} else{
					$businessLocationsRestHandler->getPostCodeLatitudeAndLongitude($postcode);					
				}				
				break;
			case "get-postcode-business-count":
				$businessLocationsRestHandler = new BusinessRestHandler();
				if (strcmp($category,"")!=0){
					$businessLocationsRestHandler->getPostCodeBusinessCountUsingCategory($postcode, $category);
				} else{
					$businessLocationsRestHandler->getPostCodeBusinessCount($postcode);
				}
				break;
			case "submit-review":
				$user_id= ""; 
				if(isset($_GET["user_id"])){
					$user_id = $_GET["user_id"];
				}	
				$business_id= "";
				if(isset($_GET["business_id"])){
					$business_id = $_GET["business_id"];
				}	
				$quality= "";
				if(isset($_GET["quality"])){
					$quality = $_GET["quality"];
				}									
				$cost= "";
				if(isset($_GET["cost"])){
					$cost = $_GET["cost"];
				}					
				$comment= "";
				if(isset($_GET["comment"])){
					$comment = $_GET["comment"];
				}			
				if (strcmp($comment,"") != 0 && strcmp($user_id,"")!=0 && strcmp($quality,"")!=0 && strcmp($cost,"")!=0 && strcmp($business_id,"")!=0){
					$businessLocationsRestHandler = new BusinessRestHandler();
					if($businessLocationsRestHandler->insertReview($user_id,$business_id,$quality,$cost,$comment, $ip_address)){
						echo "Review Submit Completed";
					} else {
						echo "Review Submit Failed";
					}
				} else{
					echo "Review Submit Failed";
				}
				break;
			case "check-user-id":
				$customer_id= "";
				if(isset($_GET["customer_id"])){
					$customer_id = $_GET["customer_id"];
					if (strcmp($customer_id,"") != 0){
						$businessLocationsRestHandler = new BusinessRestHandler();
						$businessLocationsRestHandler->checkUserId($customer_id);
					} else {
						echo "User Id Check Failed";
					}
				}					
				break;
			case "check-business-id":
				$business_id= "";
				if(isset($_GET["business_id"])){
					$business_id = $_GET["business_id"];
					if (strcmp($business_id,"") != 0){
						$businessLocationsRestHandler = new BusinessRestHandler();
						$businessLocationsRestHandler->checkBusinessId($business_id);
					} else {
						echo "Business Check Submit Failed";
					}
				}		
				break;
			case "":
				//404 - not found;
				break;
			
		}
	} else {
		header("HTTP/1.1 404 Not Found");
		echo "<h1>404 Access denied.</h1>";
		echo "You have not provided the required credentials to access this page.";
		die();
	}
} else {
		header("HTTP/1.1 404 Not Found");
		echo "<h1>404 Not Found</h1>";
		echo "You have not provided the required credentials to access this page.";
		die();
}

?>


