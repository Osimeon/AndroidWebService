<?php
	class DBMS extends SQLite3{
		function __construct(){
			$this->open('adverts_db.db');		
		}
		
		/**
		 * Initialize Database Tables
		 * All Tables Will Be Created From This Function
		 * Comment Out Tables That Have Alread Been Created, To Avoid Exceptions
		 * Add Strings For New Tables And Call Method
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function createTables(){
			try{
				$this->exec('CREATE TABLE USERS(USERID INTEGER PRIMARY KEY AUTOINCREMENT, 
							 USEREMAIL TEXT UNIQUE NOT NULL, USERNAME TEXT NOT NULL ,USERPASSWORD TEXT NOT NULL, 
							 USEREARNINGS INTEGER NOT NULL)');
				$this->exec('CREATE TABLE ADVERTISEMENTS(ADVERTIT INTEGER PRIMARY KEY AUTOINCREMENT, 
							 ADVERTNAME TEXT NOT NULL, ADVERTCUSTOMER TEXT NOT NULL, ADVERTIMG TEXT NOT NULL, 
							 ADVERTIMPRESSIONS TEXT NOT NULL)');
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
		}
		
		/**
		 * Resets DB By Deleting All Tables
		 * @params null
		 * @return void
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 */
		public function resetTables(){
			try{
				$this->exec("DROP TABLE USERS");
				$this->exec("DROP TABLE ADVERTISEMENTS");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
		}
		
		#==============================================================================================================#
		#																											   #
		# CRUD Operations - Create, Read, Update, Delete (Though Implementation Of Delete Is Not Always Best Practice) #
		# Order - Create, Read, Update, Delete - Users, Adverts, VIE, Balance										   #
		#																											   #
		#==============================================================================================================#
		
		/**
		 * Create New User
		 * @param email, password, name
		 * @return boolean - Indicates Success
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function createUser($email, $password, $name){
			$password = md5($password);
			try{
				$this->exec("INSERT INTO USERS(USEREMAIL, USERPASSWORD, USERNAME, USEREARNINGS) VALUES ('$email', '$password', '$name', 0)");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		/**
		 * Create Get User
		 * @param email, password
		 * @return ResultSet Object
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function getUser($email, $password){
			$password = md5($password);
			$result;
			try{
				$result = $this->query("SELECT USEREMAIL, USERPASSWORD, USERNAME FROM USERS WHERE USEREMAIL = '$email' 
										AND USERPASSWORD = '$password' LIMIT 1");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return $result;
		}
		
		/**
		 * Update User
		 * @param userid, email, password, name, earning
		 * @return boolean
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function updateUser($userid, $newemail, $newpassword, $newname, $newearning){
			$newpassword = md5($newpassword);
			$today = date('Y-m-d');
			try{
				$this->exec("UPDATE USERS SET USEREMAIL = '$newemail', USERPASSWORD = '$newpassword',
							 USERNAME = '$newname', USEREARNING = '$newearning' WHERE USERID = '$userid'");
				$this->exec("INSERT INTO USERHISTORY (USERID, DATEOFUPDATE, USEREARNINGS) VALUES ('$userid','$today', '$newearning')");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		/**
		 * Delete User
		 * @param userid
		 * @return boolean - Indicates Success Or Failure
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function deleteUser($userid){
			$password = md5($password);
			try{
				$this->exec("DELETE FROM USERS WHERE USERID = '$userid'");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		/**
		 * Create Advert Record
		 * @param advertname, customer, image, impressions
		 * @return boolean - Indicates Success Or Failure
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function createAdvertisement($advertname, $advertcustomer, $advertimg, $advertimpression){
			try{
				$this->exec("INSERT INTO ADVERTISEMENTS (ADVERTNAME, ADVERTCUSTOMER, ADVERTIMG, ADVERTIMPRESSIONS) VALUES(
							 '$advertname', '$advertcustomer', '$advertimg', '$advertimpression')");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		/**
		 * Get Advertisements
		 * @param null
		 * @retun ResultSet Object
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function getAdvertisements(){
			$result;
			try{
				$result = $this->query("SELECT * FROM ADVERTISEMENTS ORDER BY ADVERTNAME");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return $result;
		}
		
		/**
		 * Update Advertisement Record
		 * @param id, name, customer, image, impressions
		 * @return boolean - Indicates Success Or Failure
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function updateAdvertisement($advertid, $advertname, $advertcustomer, $advertimg, $advertimpressions){
			try{
				$this->exec("UPDATE ADVERTISEMENTS SET ADVERTNAME = '$advertname', ADVERTCUSTOMER = '$advertcustomer',
							 ADVERTIMG = '$advertimg', ADVERTIMPRESSIONS = '$advertimpressions' WHERE ADVERTID = '$advertid'");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		/**
		 * Delete Advertisement
		 * @param id
		 * @return boolean - Indicates Success Or Failure
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function deleteAdvertisement($advertid){
			try{
				$this->exec("DELETE FROM ADVERTISEMENTS WHERE ADVERTID = '$advertid'");
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return TRUE;
		}
		
		#==================================================================================================================
		# Utility Functions
		# These are functions that can be coined out of class core functions
		#==================================================================================================================
		
		
		
		/**
		 * Check For User Records
		 * @param email, password
		 * @return boolean - Indicates Whether User Exists Or Not
		 * @author Simeon Harvey Obwogo
		 * @email simeon.obwogo79@gmail.com
		 **/
		public function checkIfUserExists($email, $password){
			$count;
			$password = md5($password);
			try{
				$string = "SELECT COUNT(USERID) AS countofuser FROM USERS WHERE USEREMAIL = '$email' AND USERPASSWORD = '$password'";
				$result = $this->query("SELECT COUNT(USERID) AS countofuser FROM USERS WHERE USEREMAIL = '$email' AND USERPASSWORD = '$password'");
				while($row = $result->fetchArray(SQLITE3_ASSOC) ){
				 	$count = $row['countofuser'];
				}
			}
			catch(Exception $e){
				echo $this->lastErrorMsg();
			}
			return $count > 0 ? TRUE : FALSE;
		}
		
		
		#=========================================================================================================================================
		# Class Ends Here
		#=========================================================================================================================================
	}
?>