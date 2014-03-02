<?php
	if(isset($_POST['tag']) && $_POST['tag'] != ''){
		$tag = $_POST['tag'];
		require_once 'WebServiceFuncs.php';
		$db = new WebServiceFuncs();
		$response = array("tag" => $tag, "success" => 0, "error" => 0);
		
		if($tag == 'login'){
			$email = $_POST['email'];
			$password = $_POST['password'];
			$user = $db->authenticateUser($staffId, $password);
			if($user != FALSE){
				$response["success"] = 1;
				$response["user"]["email"] = $email;
				$response["user"]["password"] = $password;
				echo json_encode($response);
			}
			else{
				$response["error"] = 1;
				$response["error_msg"] = "Authentication Failed!";
				echo json_encode($response);
    		}
		}
		else if($tag == 'getuserdetails'){
			$email = $_POST['email'];
			$password = $_POST['password'];
			$details = $db->getUser($staffId, $password);
			
			if($details != FALSE){
				echo($details);
			}
			else{
				$response["error"] = 1;
				$response["error_msg"] = "Could Not Get User Details!";
				echo json_encode($response);
			}
		}
	}
?>