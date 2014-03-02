<?php
	class WebServiceFuncs {
		public $dbobj;
		
		/**
		 * Class Constructor, Calls The DBMS Interface, And Instantiates It
		 */
		function __construct(){
			require_once 'DBMS.php';
			$this -> dbobj = new DBMS();			
		}
		
		/**
		 * Authenticate User
		 * @param email, password
		 * @return boolean - Indicates If User Exists
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 */
		public function authenticateUser($useremail, $password){
			if($this->dbobj->checkIfUserExists($useremail, $password)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		
		/**
		 * Get User Details 
		 * @param email, password
		 * @return JSON Object Of User Details - Empty Object If User Details Not Available
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 */
		public function getUser($email, $password){
			$response = array();
			$response["users"] = array();
			$response["tag"] = 'user';
			$response["success"] = 1;			
			$response["error"] = 0;
			
			$result = $this->dbobj->getUser($email, $password);
			if(!empty($result)){			
				while($row = $result->fetchArray(SQLITE3_ASSOC)){
					$user = array();
					$user["name"] = $row["USERNAME"];
					$user["email"] = $row["USEREMAIL"];
					$user["password"] = $row["USERPASSWORD"];
					array_push($response["users"], $user);
				}
				return json_encode($response);
			}
			else{
				return FALSE;
			}
		}
		
		/**
		 * Get Advertisements
		 * @param null
		 * @return JSON Object - Advertisements
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function getAdvertisements(){
			$response = array();
			$response["advertisements"] = array();
			$response["tag"] = 'advertisement';
			$response["success"] = 1;			
			$response["error"] = 0;
			
			$result = $this->dbobj->getAdvertisements();
			if(!empty($result)){			
				while($row = $result->fetchArray(SQLITE3_ASSOC)){
					$advertisement = array();
					$advertisement["advertid"] = $row["ADVERTID"];
					$advertisement["advertname"] = $row["ADVERTNAME"];
					$advertisement["advertcustomer"] = $row["ADVERTCUSTOMER"];
					$advertisement["advertimg"] = $row["ADVERTIMG"];
					$advertisement["advertimpressions"] = $row["ADVERTIMPRESSIONS"];
					array_push($response["advertisement"], $advertisement);
				}
				return json_encode($response);
			}
			else{
				return FALSE;
			}
		}
		
		public function main(){
			$this->dbobj->createTables();
		}
	}	
	
	$obj = new WebServiceFuncs();
	//$obj->main();
	echo($obj->getUser('simeon.obwogo79@gmail.com','obsiha2013'));
	//echo($obj->getAdvertisements());
?>