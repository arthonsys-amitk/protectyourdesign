<?php
	if($_GET['function'] == 'login'){
		login();
	}elseif($_GET['function'] == 'registration'){
		registration();
	}elseif($_GET['function'] == 'update_profile_info'){
		update_profile_info();
	}elseif($_GET['function'] == 'delete_designs'){
		delete_designs();
	}elseif($_GET['function'] == 'sign_out'){
		sign_out();
	}elseif($_GET['function'] == 'forgot_password'){
		forgot_password();
	}elseif($_GET['function'] == 'change_password'){
		change_password();
	}elseif($_GET['function'] == 'get_profile_info'){
		get_profile_info();
	}elseif($_GET['function'] == 'get_design_info'){
		get_design_info();
	}elseif($_GET['function'] == 'get_category_info'){
		get_category_info();
	}elseif($_GET['function'] == 'shared_users'){
		shared_users();
	}elseif($_GET['function'] == 'grant_access_design'){
		grant_access_design();
	}elseif($_GET['function'] == 'upload_design'){
		upload_design();
	}elseif($_GET['function'] == 'get_all_designs'){
		get_all_designs();
	}elseif($_GET['function'] == 'get_all_users_info'){
		get_all_users_info();
	}elseif($_GET['function'] == 'send_access_request'){
		send_access_request();
	}elseif($_GET['function'] == 'all_request_list'){
		all_request_list();
	}elseif($_GET['function'] == 'accept_access_request'){
		accept_access_request();
	}elseif($_GET['function'] == 'resend_designs'){
		resend_designs();
	}elseif($_GET['function'] == 'about_us'){
		about_us();
	}elseif($_GET['function'] == 'delete_shared_user'){
		delete_shared_user();
	}elseif($_GET['function'] == 'get_country_info'){
		get_country_info();
	}elseif($_GET['function'] == 'user_validate'){
		user_validate();
	}else{
		//print_r("hg");
	}

	
	
	
	/**
	 * @api {post} /registration  registration
	 * @apiName registration
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {String}  fname  User's First Name.
	 * @apiParam {String}  lname User's Last Name.
	 * @apiParam {String}  email User's Email Address.
	 * @apiParam {String}  password User's Password.
	 * @apiParam {String}  design_access User's Design Access (Public/Private).
	 * @apiParam {Numeric}  contact User's Contact.
	 * @apiParam {String}  address User's Address.
	 * @apiParam {Numeric}  account_type_id Account Type Id.
	 * @apiParam {String}  devicetype User device type (Iphone/Android).
	 * @apiParam {String}  region User's Region.
	 * @apiParam {String}  post_code User's Post Code.
	 * @apiParam {String}  country_name User's Country Name.
	 * @apiParam {String}  image User's Image.
	 * @apiParam {String}  company_name company_name (if company_name is blank send blank or send company_name).
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName registration.
	 * @apiSuccess {Array}  data User Data.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"registration",
	 *  		 "data":USERDATA.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "registration",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function registration(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"fname":"shivani","lname":"suman","email":"shinbvgifsf6@gmail.com","password":"123456","design_access":"Public","contact":"6757657","address":"jwr","account_type_id":"2","devicetype":"Android","region":"yty","post_code":"yugyhj","country_name":"United Kingdom","image":"","company_name":""}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
		
			$fname = $decoded['fname'];
			$lname = $decoded['lname'];
			$email = $decoded['email'];
			$password = encryptIt($decoded['password']);
			$design_access = $decoded['design_access'];
			$contact = $decoded['contact'];
			$address = $decoded['address'];
			$account_type_id = $decoded['account_type_id'];
			$devicetype = $decoded['devicetype'];
			$region = $decoded['region'];
			$post_code = $decoded['post_code'];
			$country_name = $decoded['country_name'];
			$UseKey = getGUID();
			$cdate =  date('Y-m-d H:i:s');
			//print_r($cdate);die;
			$email_verify =  $database->select('users',"*", [
			"AND"=>[
					'userRole[!]'=> 3,
					"EmailAddress" => $email
					]
			  ]);
			  
			if(!empty($email_verify)){
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Email already exist';				
				$responseData['functionName'] = 'registration';				
				$responseData['data'] = array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;	
			}else{
				$country_detail = $database->select('countries',"*", [
							
									"country_name" => $country_name
									
							  ]);
						
				if(!empty($country_detail)){
					$data = $database->insert("users",
								[
									"UseKey"=>$UseKey,
									"EmailAddress" => $email,
									"Password" => $password,
									'created_on'=> $cdate,
									'payment_on'=> $cdate
									
								]);
					
					$user_data =  $database->select('users',"*", [
					"AND" =>
						[	
							'userRole[!]'		=> 3,
							"EmailAddress" => $email,
							"Password" => $password
						]
					  ]);
					 
					if(!empty($user_data)){
						$user_id = $user_data[0]['UserID'];
						
						
						$country_code = $country_detail[0]['country_code'];
						if($decoded['image'] != ''){
							$imagedecode = base64_decode($decoded['image']);
							$image_name = time().'.jpg';
							$path = 'uploads/'.$image_name;
							$file= fopen($path,'wb');
							$is_written = fwrite($file, $imagedecode);
							fclose($file);
						
							if($decoded['company_name'] != ''){
								$detail = $database->insert("user_meta",
									[
										"user_id" => $user_id,
										"first_name" => $fname,
										"surname" => $lname,
										"contact" => $contact,
										"address1" => $address,
										"design_access" => $design_access,
										"device_type"=>$devicetype,
										"image_name"=>$image_name,
										"account_type_id"=>$account_type_id,
										"region"=>$region,
										"postcode"=>$post_code,
										"country_code"=>$country_code,
										"agree"=>1,
										//"upload_image_count"=>0,
										"company_name"=>$decoded['company_name']
									]);
							}else{
								$detail = $database->insert("user_meta",
									[
										"user_id" => $user_id,
										"first_name" => $fname,
										"surname" => $lname,
										"contact" => $contact,
										"address1" => $address,
										"design_access" => $design_access,
										"device_type"=>$devicetype,
										"image_name"=>$image_name,
										"account_type_id"=>$account_type_id,
										"region"=>$region,
										"postcode"=>$post_code,
										"country_code"=>$country_code,
										"agree"=>1,
										//"upload_image_count"=>0,
										"company_name"=>NULL
									]);
							}
						}else{
							if($decoded['company_name'] != ''){
								$detail = $database->insert("user_meta",
									[
										"user_id" => $user_id,
										"first_name" => $fname,
										"surname" => $lname,
										"contact" => $contact,
										"address1" => $address,
										"design_access" => $design_access,
										"device_type"=>$devicetype,
										"image_name"=>NULL,
										"account_type_id"=>$account_type_id,
										"region"=>$region,
										"postcode"=>$post_code,
										"country_code"=>$country_code,
										"agree"=>1,
										//"upload_image_count"=>0,
										"company_name"=>$decoded['company_name']
									]);
							}else{
								$detail = $database->insert("user_meta",
									[
										"user_id" => $user_id,
										"first_name" => $fname,
										"surname" => $lname,
										"contact" => $contact,
										"address1" => $address,
										"design_access" => $design_access,
										"device_type"=>$devicetype,
										"image_name"=>NULL,
										"account_type_id"=>$account_type_id,
										"region"=>$region,
										"postcode"=>$post_code,
										"country_code"=>$country_code,
										"agree"=>1,
										//"upload_image_count"=>0,
										"company_name"=>NULL
									]);
							}
						}
							$user_detail =  $database->select('user_meta',
										[
											"[>]users" => ["user_id" => "UserID"],
											"[>]account_type" => ["account_type_id" => "account_type_id"],
											"[>]countries" => ["country_code" => "country_code"]

										],
											"*",
										[
											"user_id[=]" => $user_id
										]);
							
						if(!empty($user_detail)){
								
							
							$to = $email;
							$subject = "Protect Your Design Account Activation";
							$message ="Dear ".$fname. " " . $lname . "\r\n\r\n" .
									"Thank you for registering with Protect Your Design."  . "\r\n\r\n" .
									"You are now just one step away from enjoying the full privileges of a registered user at protectyourdesign.com" . "\r\n\r\n" .
									"======================================" . "\r\n\r\n" .
									"Final Step: Activate Your Account Here" . "\r\n\r\n" .
									"======================================" . "\r\n\r\n" .
									"Please click the link below to activate your account:" . "\r\n\r\n" .
									SITEURL."/activate.php?email=".$email."&code=" . $password . "\r\n\r\n\r\n\r\n" .
									"*** If the link above is not clickable, you may need to copy and paste the link into your browser. ***" . "\r\n\r\n" .
									"Best Regards," . "\r\n" .
									"Protect Your Design" . "\r\n" .
									"Email: support@protectyourdesign.com" . "\r\n" .
									"URL  : http://www.staging.protectyourdesign.com";

							$headers = 'From: support@protectyourdesign.com' . "\r\n" .
												 'Reply-To: support@protectyourdesign.com';

							@mail($to, $subject, $message, $headers,'-fsupport@protectyourdesign.com');
						
								
							$responseData['status'] = 1;
							$responseData['statusCode'] = 200;
							$responseData['message'] = 'Verification email has been send to you email id';				
							$responseData['functionName'] = 'registration';				
							$responseData['data'] = $user_detail[0];
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'registration unsucceccfully';				
							$responseData['functionName'] = 'registration';				
							$responseData['data'] = array();	
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						}
								
							
						
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'registration unsucceccfully';				
						$responseData['functionName'] = 'registration';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;	
					}
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Please select country';				
					$responseData['functionName'] = 'registration';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;	
				}
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'registration';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
	}
	
	
	
	/**
	 * @api {post} /login  Login
	 * @apiName login
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {String}  email User Email Address.
	 * @apiParam {String}  password User Valid Password.
	 * @apiParam {String}  device_type User Device Type (Iphone/Android).
	 *
	 * @apiSuccess {Numeric} response_status 1.
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName login.
	 * @apiSuccess {Array}  data All data of user.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"login",
	 *  		 "data":All data of user.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "login",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function login(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"email":"shivani.chourasiya@arthonsys.com","password":"123456","device_type":"Android"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){  
			$email = $decoded['email'];
			$password = $decoded['password'];
			$hashAndSalt = encryptIt($password);
			$email_verify = $database->select('users',"*",[
			"AND" =>
					[
						'userRole[!]'=> 3,
						"userActive"=>1,
						"EmailAddress" => $email
					]
				]);
				//print_r($email_verify);die;
			if(!empty($email_verify)){
				$payment_date = $email_verify[0]['payment_on'];
				$user_payment_date = substr($payment_date,0,10);
				$year_month = date("Y-m",strtotime($user_payment_date));
				$timestam = strtotime(date($user_payment_date)." +11 month");
				$after_eleven_date = date('Y-m-d', $timestam);
				$current_date = date('Y-m-d');
				if($after_eleven_date > $current_date){
					$user_id = $email_verify[0]['UserID'];
					//$total_uploads = $database->count("designs", [ "user_id" => $user_id ]);
					$total_upload_image = $database->count("designs", [ "user_id" => $user_id ]);
					$total_uploads = (string)$total_upload_image;
					//print_r($total_uploads);die;
					//print_r($total_uploads);die;
					/* $detail = $database->update("user_meta",
											[
												"upload_image_count"=>$total_uploads
											],
											["user_id" => $user_id]);
					 */
					$password_verify = $email_verify[0]['Password'];
					if($password_verify == $hashAndSalt){
						$data =  $database->select('users',
						[
							"[>]user_meta"	=> ["UserID" => "user_id"],
							"[>]account_type" => ["account_type_id" ],
							"[>]countries" => ["country_code"]
						],
						"*", [
			
							"AND" =>
								[
									'userRole[!]'		=> 3,
									"EmailAddress" => $email,
									"Password" => $hashAndSalt
									
								],"LIMIT" => 1
							
							
							]);
						//print_r($);die;
						if(!empty($data)){
								$responseData['status'] = 1;
								$responseData['statusCode'] = 200;
								$responseData['message'] = 'login successfully';				
								$responseData['functionName'] = 'login';				
								$responseData['data'] = $data[0];
								$responseData['upload_image_count'] = $total_uploads;
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
						}else{
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = 'login unsucceccfully';				
								$responseData['functionName'] = 'login';				
								$responseData['data'] = array();	
								$responseData['upload_image_count'] = "";
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;			
						}
											
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Password does not match';				
						$responseData['functionName'] = 'login';				
						$responseData['data'] = array();	
						$responseData['upload_image_count'] = "";
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;	
					}
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'You account is Inactive. Please renew your account by paying through the website.';				
					$responseData['functionName'] = 'login';				
					$responseData['data'] = array();	
					$responseData['upload_image_count'] = "";
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;		
				}
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Email is not register/verified';				
				$responseData['functionName'] = 'login';				
				$responseData['data'] = array();	
				$responseData['upload_image_count'] = "";
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;	
			}
				
		}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid Request Type';				
				$responseData['functionName'] = 'login';				
				$responseData['data'] = array();
				$responseData['upload_image_count'] = "";
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
				
		}
		
	}
	
	
	/**
	 * @api {post} /sign_out User Sign Out
	 * @apiName sign_out
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric} UserID User Id.
	 *
	 * @apiSuccess {Numeric} response_status 1.
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName sign_out.
	 * @apiSuccess {Array}  data .
	 *
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"sign_out",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "sign_out",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function sign_out(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"UserID":"52"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$_SESSION = array();
			session_destroy();
			$responseData['status'] = 1;
			$responseData['statusCode'] = 200;
			$responseData['message'] = 'sign out successfully';				
			$responseData['functionName'] = 'sign_out';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
				echo $jsonEncode;	
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'unsucceccfully';				
			$responseData['functionName'] = 'sign_out';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;	
		}
	}
	
	
	
	 /**
	 * @api {post} /forgot_password reset Password For User info
	 * @apiName forgot_password
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {String} email User's Email Id.
	 *
	 *
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {Numeric} statusCode 200.
	 * @apiSuccess {String}  message text message.
	 * @apiSuccess {Boolean} functionName forgot_password.
	 * @apiSuccess {String}  data.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "status": 1,
	 *           "statusCode": 200,
	 *           "message": "Success",
	 *			 "functionName": "forgot_password",
	 *			 "data": ""
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "status": 0
	 *           "statusCode": 400
	 *           "message": "appropriate error message."
	 *			 "functionName": "forgot_password",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */	
	
	function forgot_password(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"email":"madan.choudhary@arthonsys.com"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$email = $decoded['email'];
			$data = $database->select(
					'users',
					[
						"[>]user_meta"	=> ["UserID" => "user_id"]
					],
					'*',
					[ 
						"AND" =>
						[
							'userRole[!]'		=> 3,
							"EmailAddress" => $email
						]
					]
				);
				
			if(!empty($data)){
				$user_id = $data[0]['UserID'];
				$fname = $data[0]['first_name'];
				$surname = $data[0]['surname'];
				$password = $data[0]['Password'];
			/* 	$newPassword	=	rand(0, 999999);
				$resetpass = $database->update("users",[
						"Password" => encryptIt($newPassword)],
						["UserID" => $user_id ]); */
				//if(isset($resetpass)){
					/* $to = $email;
					$subject = 'Password reset to Protect Your Design was successful';
					$message = "Hello" . "\r\n" .$fname  . "\r\n\r\n" .
					"Here the new password is " . "\r\n\r\n" . $newPassword . "\r\n\r\n";
					
					
					$headers = 'From: nobody@protectyourdesign.com' . "\r\n" .
											 'Reply-To: nobody@protectyourdesign.com';

						mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com'); */
						
						
					$to = $email;
					$subject = "Password Reset Confirmation - Protect Your Design";
					$message = $fname. " " . $surname . "\r\n\r\n" .
										 #"Your Password is ". decryptIt( $result[0]['Password'] ) . "\r\n\r\n" .
										 "There was recently a request to change the password for your account." . "\r\n\r\n" .

										 "If you did not make this request, you can ignore this message and your password will remain the same." . "\r\n\r\n" .

										 "If you requested this password change, please click the link below to reset your password:"  . "\r\n\r\n" .

										 SITEURL."/resetpassword.php?email=".urlencode($email)."&resetcode=" . urlencode($password) . "\r\n\r\n" .

										 "*** If the link above is not clickable, you may need to copy and paste the link into your browser. ***" . "\r\n\r\n\r\n" .

										 "Best Regards," . "\r\n" .
										 "Protect Your Design" . "\r\n" .
										 "Email: support@protectyourdesign.com" . "\r\n" .
										 "URL  : http://www.staging.protectyourdesign.com";

					$headers = 'From: nobody@protectyourdesign.com' . "\r\n" .
										 'Reply-To: nobody@protectyourdesign.com';

					@mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com');
						
						
						
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'Your new password has been sent to your email';				
						$responseData['functionName'] = 'forgot_password';				
						$responseData['data'] = array();
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					
				/* }else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Your password reset email has not been sent';				
					$responseData['functionName'] = 'forgot_password';				
					$responseData['data'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;
				} */
			}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'username/e-mail does not exist';				
					$responseData['functionName'] = 'forgot_password';				
					$responseData['data'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;
			}
					
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'forgot_password';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
	}

	
	
	 /**
	 * @api {post} /change_password User Change Password
	 * @apiName change_password
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric} user_id User's ID.
	 * @apiParam {String} old_password old password  .
	 * @apiParam {String} new_password new password  .
	 *
	 *
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {Numeric} statusCode 200.
	 * @apiSuccess {String}  message text message.
	 * @apiSuccess {Boolean} functionName change_password.
	 * @apiSuccess {Array}  data.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "status": 1,
	 *           "statusCode": 200,
	 *           "message": "Success",
	 *			 "functionName": "change_password",
	 *			 "data": ""
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "status": 0
	 *           "statusCode": 400
	 *           "message": "appropriate error message."
	 *			 "functionName": "change_password",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */	
	
	function change_password(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"25","old_password":"556666hu75","new_password":"556666hu75"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			$password = $decoded['old_password'];
			$hashAndSalt = encryptIt($password);
			$new_password = encryptIt($decoded['new_password']);
		
			$data = $database->select('users',"*",[
			/* "AND" =>
					[ */ 
						"UserID" => $user_id/* ,
						"Password" => $hashAndSalt
					] */
				]);
				
			if(!empty($data)){
				$password_match = $database->select('users',"*",[
				"AND" =>
					[ 
						"UserID" => $user_id ,
						"Password" => $hashAndSalt
					]
				]);
				
				if(!empty($password_match)){
					$userId = $password_match[0]['UserID'];
					$savedata = $database->update("users", [ "Password" => $new_password  ], [ "UserID[=]" => $user_id] );
				
					if(isset($savedata)){
						
						$to = $password_match[0]['EmailAddress'];

						$subject = 'Password reset to Protect Your Design was successful';
						//$message = "Dear ".$validation[0]['first_name']. " " . $validation[0]['surname'] . "\r\n" .
						$message = "Hello, \r\n\r\n" .
												#"Thank you for registering on Protect Your Design. You can log in using the link below to upload your designs." . 	"\r\n\r\n" .
												#"http://www.protectyourdesign.com" . "\r\n\r\n" .
												#
												"Your account details are below" . "\r\n\r\n" .

												"===================" . "\r\n\r\n" .
												"Username " . $password_match[0]['EmailAddress'] . "\r\n\r\n" .
												"Password: " . $decoded['new_password'] . "\r\n\r\n" .
												"Share Key: " . $password_match[0]['UseKey'] . "\r\n\r\n" .
												"===================" . "\r\n\r\n" .

												"PLEASE KEEP THIS E-MAIL FOR YOUR RECORDS" . "\r\n" .
												"------------------------------------------------------------------------------" . "\r\n\r\n" .
												"Your ID Key can be distributed to designers, allowing them to create Protect Your Design accounts and submit designs to 	your company." . "\r\n\r\n" .
												"If you lose this key, or wish to update your primary contact e-mail address, please send an e-mail to 	info@protectyourdesign.com" . "\r\n\r\n" .
												"Protect Your Design" . "\r\n" .
												"Lawdit Solicitors" . "\r\n";

						$headers = 'From: nobody@protectyourdesign.com' . "\r\n" .
											 'Reply-To: nobody@protectyourdesign.com';

						mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com');
						
						
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'Change password successfully';				
						$responseData['functionName'] = 'change_password';				
						$responseData['data'] =array();
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Change password unsucceccfully';				
						$responseData['functionName'] = 'change_password';				
						$responseData['data'] = array();
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					}
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Old password does not match';				
					$responseData['functionName'] = 'change_password';				
					$responseData['data'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;
				}		
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid User Id';				
				$responseData['functionName'] = 'change_password';				
				$responseData['data'] = array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'change_password';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
	}
	
	
	/**
	 * @api {post} /get_profile_info  Get Profile Info
	 * @apiName get_profile_info
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id User Id.
	 *
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName get_profile_info.
	 * @apiSuccess {Array}  List Of All Data Of User.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"get_profile_info",
	 *  		 "data":List Of All Data Of User.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "get_profile_info",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function get_profile_info(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"16"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
			$user_id = $decoded['user_id'];
			$total_upload_image = $database->count("designs", [ "user_id" => $user_id ]);
			$total_uploads = (string)$total_upload_image;
			$data = $database->select('users',
				[
					"[>]user_meta" => ["UserID" => "user_id"],
					"[>]account_type" => ["account_type_id"],
					"[>]countries" => ["country_code"],
					"[>]designs" => ["user_id"]


				],
					"*",
				[
					"UserID" => $user_id
				]);
				
				
			if(!empty($data))
			{
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'successfully';				
				$responseData['functionName'] = 'get_profile_info';				
				$responseData['data'] =$data;
				$responseData['upload_image_count'] = $total_uploads;
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'no data available';				
				$responseData['functionName'] = 'get_profile_info';				
				$responseData['data'] = array();	
				$responseData['upload_image_count'] = "";
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			} 
					
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_profile_info';				
			$responseData['data'] = array();
			$responseData['upload_image_count'] = "";
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
				
		}
				
	}
	
	
	/**
	 * @api {post} /get_design_info  Get Design Info
	 * @apiName get_design_info
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  design_id Design Id.
	 *
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName get_design_info.
	 * @apiSuccess {Array}  data List Of All Data Of Design of user.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"get_design_info",
	 *  		 "data": List Of All Data Of Design of user.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "get_design_info",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function get_design_info(){
		include("base.php");
		$data = file_get_contents("php://input");
	//	$data = '{"design_id":"2"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
			$design_id = $decoded['design_id'];
			 $data = $database->select('designs',
				[
					"[>]users" => ["user_id" => "UserID"],
					"[>]user_meta" => ["user_id"],
					"[>]account_type" => ["account_type_id"],
					"[>]countries" => ["country_code"]


				],
					"*",
				[
					"design_id" => $design_id
				]); 
			
			if(!empty($data)){
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'successfully';				
				$responseData['functionName'] = 'get_design_info';				
				$responseData['data'] = $data[0];
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'no data available';				
				$responseData['functionName'] = 'get_design_info';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			}
					
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_design_info';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
				
		}
				
	}
	
	
	/**
	* @api {get} get_category_info  Get Category Info
	* @apiName get_category_info
	* @apiGroup User
	* @apiHeaderExample {json} Header-Example
	*	{
	*        "Content-Type":"application/json"
	*	}
	*
	* @apiParam No params. 
	*
	*	 
	* @apiSuccess {Numeric} status 1.
	* @apiSuccess {Numeric} response_status 1.
	* @apiSuccess {Numeric} response_code 200.
	* @apiSuccess {String}  message success.
	* @apiSuccess {Boolean}  functionName get_category_info.
	* @apiSuccess {String}  data List of all data of Category.
	*
	* @apiSuccessExample Success-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 1,
	*           "response_code": 200,
	*           "message": "Success",
	*           "functionName":"get_category_info",
	*           "data": List Of All Data Of Category
	*           
	*       }
	*     }
	*
	* @apiErrorExample Error-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 0,
	*           "response_code": 400,
	*           "message": "No data found",
	*           "functionName":"get_category_info",
	*           "data":""
	*           
	*       }
	*     }
	*
	*/
	
	function get_category_info(){
		include("base.php");
		$responseData = array();
		if(!empty($_GET['function'])){
			$data = $database->select('category',
				[
						"[>]subcategory" => ["catid"]
				],
					"*"
				);
				
			if(!empty($data)){
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'successfully';				
				$responseData['functionName'] = 'get_category_info';				
				$responseData['data'] = $data;
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode; 
			}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'no data available';				
					$responseData['functionName'] = 'get_category_info';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_category_info';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
				
	}
	
	
	/**
	 * @api {post} /shared_users  Shared Users
	 * @apiName shared_users
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id User Id.
	 *
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName shared_users.
	 * @apiSuccess {Array}  data List Of all data of users shared design from user.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"shared_users",
	 *  		 "data": List Of all data of users shared design from user.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "shared_users",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function shared_users(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"17"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
			$user_id = $decoded['user_id'];
			/* $data =	 $database->select('designs',
				[
					"[>]users" => ["user_id" => "UserID"],
					"[>]user_meta" => ["user_id"],
				],
				"*",
				[
					"AND" =>
						[
							"user_id" => $database->select("design_share",

							"shared_with_id",
								[
									"userID" => $user_id,
								]
							)
						]
				]
			); */
			
			$data =	 $database->select('design_share',
				"*",
				[
					"userID"=>$user_id
				]
			);
			
			if(!empty($data)){
				$record = array();
				foreach($data as $value){
					$record[] = $value['shared_with_id'];
				}
				$user_detail = $database->select('users',
				[
					"[>]user_meta" => ["UserID" =>"user_id"],
					"[>]account_type" => ["account_type_id"],
					"[>]countries" => ["country_code"]
				],
				"*",
				[
					"UserID"=>$record
				]
				);
				
				if(!empty($user_detail)){
					$responseData['status'] = 1;
					$responseData['statusCode'] = 200;
					$responseData['message'] = 'shared successfully';				
					$responseData['functionName'] = 'shared_users';				
					$responseData['data'] = $user_detail;
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode; 
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'shared unsuccessful';				
					$responseData['functionName'] = 'shared_users';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
				}	
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'No user available';				
				$responseData['functionName'] = 'shared_users';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;	
			}	
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'shared_users';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;	
		}
	}
	
	
	/**
	 * @api {post} /grant_access_design  Grant Access Design
	 * @apiName grant_access_design
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id User ID.
	 * @apiParam {String}  userkey UseKey.
	 *
	 * @apiSuccess {Numeric} response_status 1.
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName grant_access_design.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"grant_access_design",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "grant_access_design",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function grant_access_design(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"115","userkey":"5407CFA5"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
			$given_user_id = $decoded['user_id'];
			
			$userkey = $decoded['userkey'];
			$data = $database->select('users',
					"*",
					[
						"UseKey"=>$userkey
					]
				);
				
			if(!empty($data)){
				$user_id = $data[0]['UserID'];
				$already_accept = $database->select('design_share',
						"*",[
					"AND"	=>
						[
							"userID"=>$given_user_id,
							"shared_with_id" => $user_id
						],
					]
						);	
				if(empty($already_accept)){
						
					$savedata = $database->insert("design_share",
								[
									"userID"=>$given_user_id,
									"shared_with_id" => $user_id
									
								]);
					if(!empty($savedata)){
						$design_detail = $database->select('design_share',
							"*",[
						"AND"	=>
							[
								"userID"=>$given_user_id,
								"shared_with_id" => $user_id
							],
						]
							);	
								
						if(!empty($design_detail)){
							$request_data = $database->insert("send_request",
								[
									"key" =>1,
									"sender_id"=>$user_id,
									"receiver_id" => $decoded['user_id']
									
								]);
							$responseData['status'] = 1;
							$responseData['statusCode'] = 200;
							$responseData['message'] = 'shared successfully';				
							$responseData['functionName'] = 'grant_access_design';				
							$responseData['data'] = array();
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'shared unsucceccful';				
							$responseData['functionName'] = 'grant_access_design';		
							$responseData['data'] = array();				
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						}
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'user does not shared';				
						$responseData['functionName'] = 'grant_access_design';		
						$responseData['data'] = array();				
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;	
					}
				}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Already access given';				
						$responseData['functionName'] = 'grant_access_design';		
						$responseData['data'] = array();				
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					}
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'share key is not valid';				
				$responseData['functionName'] = 'grant_access_design';		
				$responseData['data'] = array();				
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;	
			}
				
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'grant_access_design';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
	}
	
	
	/**
	 * @api {post} /upload_design  Upload Design
	 * @apiName upload_design
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id User ID.
	 * @apiParam {String}  design_title Design Title.
	 * @apiParam {String}  description Description.
	 * @apiParam {Numeric}  category Category Id.
	 * @apiParam {Numeric}  subcategory Subcategory Id.
	 * @apiParam {String}  visibility Visibility(Public/Private).
	 * @apiParam {String}  image Image.
	 * @apiParam {Numeric}  width Image width.
	 * @apiParam {Numeric}  height Image height.
	 *
	 * @apiSuccess {Numeric} response_status 1.
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName upload_design.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"upload_design",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "upload_design",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function upload_design(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"137","design_title":"shivani","description":"image","category":"1","subcategory":"1","visibility":"Public","image":"","width":"100","height":"100"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
	
			$user_id = $decoded['user_id'];
                   
			$category = $decoded['category'];
			$subcategory = $decoded['subcategory'];
			
			require_once __DIR__ . '/../imagehash/src/Implementation.php';
			require_once __DIR__ . '/../imagehash/src/Implementations/DifferenceHash.php';
			require_once __DIR__ . '/../imagehash/src/ImageHash.php';

			//use Jenssegers\ImageHash\ImageHash;	
							
			if(!empty($decoded['image'])){
				$user_detail = $database->select('users',
										[
											"[>]user_meta" => ["UserID" => "user_id"],
											"[>]account_type" => ["account_type_id"],
											"[>]countries" => ["country_code"],
										],"*",
										["user_id" => $user_id, "LIMIT" => 1]);
				$design_data = $database->select('designs',"*",
										["user_id" => $user_id]);
									//print_r(count($design_data));die;
			if($user_detail[0]['upload_allowed'] != 0){
				if((count($design_data)) < $user_detail[0]['upload_allowed']){
					
				
						$imagedecode = base64_decode($decoded['image']);
						$image_name= time().'.jpg';
						$path = 'uploads/'.$image_name;
						$file = fopen($path,'wb');
						$is_written=fwrite($file, $imagedecode);
						fclose($file);
						$percent = 0.5;
						$im = imagecreatefromstring($imagedecode);

						/////////
						$hasher = new Jenssegers\ImageHash\ImageHash;
						$hash1 = $hasher->hash('uploads/'.$image_name);
						
						$db_collection = $database->select('designs', "*",[]);
						$similar_img = 0;
						foreach($db_collection as $row) {
							$db_imagehash = $row['imagehash'];
							if(!is_null($db_imagehash) && $db_imagehash) {
								$distance = $hasher->distance($hash1, $db_imagehash);
								if($distance < 5) {
									$similar_img = 1;
									$responseData['status'] = 0;
									$responseData['statusCode'] = 400;
									$responseData['message'] = 'This image already exists on the server';
									$responseData['functionName'] = 'upload_design';
									$responseData['data'] = array();				
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
									exit(0);
								}
							}
						}
						
						//store on storj server
						if(!$similar_img) {
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_POST, true);
							//$imgdata['bucketid'] = '592f7d178324eb8a7f54647e';
							$imgdata['bucketid'] = '92ca4dfb52d63b7e6b7454d8';
							//$imgdata['filetoupload'] = new CurlFile($fTmpName, 'image/png', basename($fTmpName));
							$imgdata['filetoupload'] = new CurlFile(__DIR__ .'/uploads/'.$image_name, 'image/jpg', basename(__DIR__ .'/uploads/'.$image_name));
							curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							//curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3000/postimage');
							curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3030/postimage');
							$cresponse = curl_exec($ch);
							$error = "";
							if($cresponse === false) {
								$error = "Error occurred." . curl_error($ch);
							} elseif(strpos($cresponse, "File uploaded with fileid") === false) {
								$error = $cresponse;
							}
							curl_close($ch);
							if($error) {
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = $error;
								$responseData['functionName'] = 'upload_design';
								$responseData['data'] = array();				
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
								exit(0);
							}
						}

						
						// Get width and height of original image resource
						$origWidth = imagesx($im);
						$origHeight = imagesy($im);
						$newwidth = $origWidth * $percent;
						$newheight = $origHeight * $percent;
						// Create new destination image resource for new 24 x 24 image
						$imNew = imagecreatetruecolor($newwidth, $newheight);

						// Re-sample image to smaller size and display
						imagecopyresampled($imNew, $im, 0, 0, 0, 0, $newwidth, $newheight, $origWidth, $origHeight);
						imagedestroy($im);

						$fileName  = 'thumb'.time().'.jpg';
						$filepath =  'uploads/'.$fileName  ;
						imagejpeg($imNew,$filepath);

						imagedestroy($imNew);
				   
							//if($is_written > 0 ){
						$data =	$database->insert("designs",[
													"user_id" 	=> $user_id,
													"design_title" 	=> $decoded['design_title'],
													"description" 	=> $decoded['description'],
													"category" 	=> $category,
													"subcategory" => $subcategory,
													"visibility" => $decoded['visibility'],
													"width" => $decoded['width'],
													"height" => $decoded['height'],
													"size_thumbnail" 	=> $fileName,
													"size_medium" 	=> $fileName,
													"size_full" 		=> $image_name,
													"imagehash" 		=> $hash1
												]);
									
						if(!empty($data)){
							$user_data = $database->select('users',
										[
											"[>]user_meta" => ["UserID" => "user_id"],
											"[>]account_type" => ["account_type_id"],
											"[>]countries" => ["country_code"],
										],"*",
										["user_id" => $user_id, "LIMIT" => 1]);
										
							if(!empty($user_data)){
									
								$upload_allowed = $user_data[0]['upload_allowed'];
								$to = $user_data[0]['EmailAddress'];
								$subject = "Design Number #" . $data . " uploaded to Protect Your Design";
								$upload_count = $database->count("designs", [ "user_id" => $user_id ]);
								if( $upload_allowed != '0' ) {
									$count = $upload_allowed - $upload_count;
								} else {
									$count = 'unlimited';
								}

								$message = "Your design was registed on Protect Your Design. \r\n\r\n" .
													 "Your design's registration number is: " . $data . "\r\n\r\n" .
													 "Please keep this number for your records." ."\r\n\r\n".
													 "You currently have ".$count." upload(s) remaining in your current quota." . "\r\n\r\n\r\n" .
													 "Protect Your Design" . "\r\n" .
													 "Lawdit Solicitors" . "\r\n\r\n";
								$headers = 'From: nobody@protectyourdesign.com' . "\r\n" .
													 'Reply-To: nobody@protectyourdesign.com';

								@mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com');
									$responseData['status'] = 1;
									$responseData['statusCode'] = 200;
									$responseData['message'] = 'design uploaded successfully';				
									$responseData['functionName'] = 'upload_design';				
									$responseData['data'] = array();
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
													
							}else{
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = 'design upload unsucceccful';				
								$responseData['functionName'] = 'upload_design';		
								$responseData['data'] = array();				
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
							}
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'design upload unsucceccful';				
							$responseData['functionName'] = 'upload_design';		
							$responseData['data'] = array();				
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						}
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Update Your Account';				
					$responseData['functionName'] = 'upload_design';		
					$responseData['data'] = array();				
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;	
				}
			}else{
				$imagedecode = base64_decode($decoded['image']);
						$image_name= time().'.jpg';
						$path = 'uploads/'.$image_name;
						$file = fopen($path,'wb');
						$is_written=fwrite($file, $imagedecode);
						fclose($file);
						$percent = 0.5;
						$im = imagecreatefromstring($imagedecode);

						/////////
						$hasher = new Jenssegers\ImageHash\ImageHash;
						$hash1 = $hasher->hash('uploads/'.$image_name);
						$db_collection = $database->select('designs', "*",[]);
						$similar_img = 0;
						foreach($db_collection as $row) {
							$db_imagehash = $row['imagehash'];
							if(!is_null($db_imagehash) && $db_imagehash) {
								$distance = $hasher->distance($hash1, $db_imagehash);
								if($distance < 5) {
									$similar_img = 1;
									$responseData['status'] = 0;
									$responseData['statusCode'] = 400;
									$responseData['message'] = 'This image already exists on the server';
									$responseData['functionName'] = 'upload_design';
									$responseData['data'] = array();				
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
									exit(0);
								}
							}
						}
						
						//store on storj server
						if(!$similar_img) {
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_POST, true);
							//$imgdata['bucketid'] = '592f7d178324eb8a7f54647e';
							$imgdata['bucketid'] = '92ca4dfb52d63b7e6b7454d8';
							//$imgdata['filetoupload'] = new CurlFile($fTmpName, 'image/png', basename($fTmpName));
							$imgdata['filetoupload'] = new CurlFile('uploads/'.$image_name, 'image/jpg', basename('uploads/'.$image_name));
							curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							//curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3000/postimage');
							curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3030/postimage');
							$cresponse = curl_exec($ch);
							$error = "";
							if($cresponse === false) {
								$error = "Error occurred." . curl_error($ch);
							} elseif(strpos($cresponse, "File uploaded with fileid") === false) {
								$error = $cresponse;
							}
							curl_close($ch);
							if($error) {
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = $error;
								$responseData['functionName'] = 'upload_design';
								$responseData['data'] = array();				
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
								exit(0);
							}
						}
						
						
						// Get width and height of original image resource
						$origWidth = imagesx($im);
						$origHeight = imagesy($im);
						$newwidth = $origWidth * $percent;
						$newheight = $origHeight * $percent;
						// Create new destination image resource for new 24 x 24 image
						$imNew = imagecreatetruecolor($newwidth, $newheight);

						// Re-sample image to smaller size and display
						imagecopyresampled($imNew, $im, 0, 0, 0, 0, $newwidth, $newheight, $origWidth, $origHeight);
						imagedestroy($im);

						$fileName  = 'thumb'.time().'.jpg';
						$filepath =  'uploads/'.$fileName  ;
						imagejpeg($imNew,$filepath);

						imagedestroy($imNew);
				   
							//if($is_written > 0 ){
						$data =	$database->insert("designs",[
													"user_id" 	=> $user_id,
													"design_title" 	=> $decoded['design_title'],
													"description" 	=> $decoded['description'],
													"category" 	=> $category,
													"subcategory" => $subcategory,
													"visibility" => $decoded['visibility'],
													"width" => $decoded['width'],
													"height" => $decoded['height'],
													"size_thumbnail" 	=> $fileName,
													"size_medium" 	=> $fileName,
													"size_full" 		=> $image_name,
													"imagehash" 		=> $hash1
												]);
									
						if(!empty($data)){
							$user_data = $database->select('users',
										[
											"[>]user_meta" => ["UserID" => "user_id"],
											"[>]account_type" => ["account_type_id"],
											"[>]countries" => ["country_code"],
										],"*",
										["user_id" => $user_id, "LIMIT" => 1]);
										
							if(!empty($user_data)){
									
								$upload_allowed = $user_data[0]['upload_allowed'];
								$to = $user_data[0]['EmailAddress'];
								$subject = "Design Number #" . $data . " uploaded to Protect Your Design";
								$upload_count = $database->count("designs", [ "user_id" => $user_id ]);
								if( $upload_allowed != '0' ) {
									$count = $upload_allowed - $upload_count;
								} else {
									$count = 'unlimited';
								}

								$message = "Your design was registed on Protect Your Design. \r\n\r\n" .
													 "Your design's registration number is: " . $data . "\r\n\r\n" .
													 "Please keep this number for your records." ."\r\n\r\n".
													 "You currently have ".$count." upload(s) remaining in your current quota." . "\r\n\r\n\r\n" .
													 "Protect Your Design" . "\r\n" .
													 "Lawdit Solicitors" . "\r\n\r\n";
								$headers = 'From: nobody@protectyourdesign.com' . "\r\n" .
													 'Reply-To: nobody@protectyourdesign.com';

								@mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com');
									$responseData['status'] = 1;
									$responseData['statusCode'] = 200;
									$responseData['message'] = 'design uploaded successfully';				
									$responseData['functionName'] = 'upload_design';				
									$responseData['data'] = array();
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
													
							}else{
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = 'design upload unsucceccful';				
								$responseData['functionName'] = 'upload_design';		
								$responseData['data'] = array();				
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
							}
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'design upload unsucceccful';				
							$responseData['functionName'] = 'upload_design';		
							$responseData['data'] = array();				
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						}
			}
					/* }else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Image unsucceccfully save';				
						$responseData['functionName'] = 'upload_design';		
						$responseData['data'] = array();				
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;	
					} */
				
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Image is required';				
				$responseData['functionName'] = 'upload_design';		
				$responseData['data'] = array();				
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;	
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'upload_design';				
			$responseData['data'] = array();
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
	}
	
	
	/**
	 * @api {post} /update_profile_info  Update Profile Info
	 * @apiName update_profile_info
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {String}  mode  Mode.
	 * @apiParam {Numeric}  user_id  User Id.
	 * @apiParam {String}  fname  User's First Name.
	 * @apiParam {String}  lname User's Last Name.
	 * @apiParam {String}  email User's Email Address.
	 * @apiParam {String}  design_access User's Design Access (Public/Private).
	 * @apiParam {Numeric}  contact User's Contact.
	 * @apiParam {String}  address User's Address.
	 * @apiParam {Numeric}  account_type_id Account Type Id.
	 * @apiParam {String}  devicetype User device type (Iphone/Android).
	 * @apiParam {String}  region User's Region.
	 * @apiParam {String}  post_code User's Post Code.
	 * @apiParam {String}  country_name User's Country Name.
	 * @apiParam {String}  image User's Image(if image is blank send blank or send image code).
	  * @apiParam {String}  company_name company_name (if company_name is blank send blank or send company_name).
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName update_profile_info.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"update_profile_info",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "update_profile_info",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function update_profile_info(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"mode":"edit","user_id":"147","fname":"dhgj","lname":"jkjgkjk","email":"jhhhjg@gmail.com","design_access":"Private","contact":"67","address":"jwr","account_type_id":"2","devicetype":"Iphone","region":"yty","post_code":"yugyhj","country_name":"United Kingdom","image":"","company_name":""}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded))
		{
			$country_name = $decoded['country_name'];
			
			$email = $decoded['email'];
			//$password = encryptIt($decoded['password']);
			$country_detail = $database->select('countries',"*", [
						
								"country_name" => $country_name
								
						  ]);
			if(!empty($country_detail)){
					$country_code = $country_detail[0]['country_code'];
				if(!empty($decoded['mode'])){
					if($decoded['image'] != ''){
						$user_id = $decoded['user_id'];
						$imagedecode = base64_decode($decoded['image']);
						$image_name = time().'.jpg';
						$path = 'uploads/'.$image_name;
						$file= fopen($path,'wb');
						$is_written = fwrite($file, $imagedecode);
						fclose($file);
						$update_user = $database->update("users", [ "EmailAddress" => $email/* , "Password"=>$password */  ], [ "UserID[=]" => $user_id] );
						if($decoded['company_name'] != ''){
							$data =  $database->update("user_meta", [ "first_name" => $decoded['fname'], "surname"=>$decoded['lname'],"contact"=> $decoded['contact'],"design_access"=>$decoded['design_access'],"address1"=> $decoded['address'],"region"=>$decoded['region'],"postcode" =>$decoded['post_code'],"country_code" =>$country_code,"account_type_id" =>$decoded['account_type_id'],"device_type" =>$decoded['devicetype'],"image_name"=>$image_name,"company_name"=>$decoded['company_name'] ], [ "user_id[=]" => $user_id] );
						}else{
							$data =  $database->update("user_meta", [ "first_name" => $decoded['fname'], "surname"=>$decoded['lname'],"contact"=> $decoded['contact'],"design_access"=>$decoded['design_access'],"address1"=> $decoded['address'],"region"=>$decoded['region'],"postcode" =>$decoded['post_code'],"country_code" =>$country_code,"account_type_id" =>$decoded['account_type_id'],"device_type" =>$decoded['devicetype'],"image_name"=>$image_name,"company_name"=>NULL ], [ "user_id[=]" => $user_id] );
						}
							
					}else{
						
						$user_id = $decoded['user_id'];
						$update_user = $database->update("users", [ "EmailAddress" => $email/* , "Password"=>$password */  ], [ "UserID[=]" => $user_id] );
						if($decoded['company_name'] != ''){
							
							$data =  $database->update("user_meta", [ "first_name" => $decoded['fname'], "surname"=>$decoded['lname'],"contact"=> $decoded['contact'],"design_access"=>$decoded['design_access'],"address1"=> $decoded['address'],"region"=>$decoded['region'],"postcode" =>$decoded['post_code'],"country_code" =>$country_code,"account_type_id" =>$decoded['account_type_id'],"device_type" =>$decoded['devicetype'],"company_name"=>$decoded['company_name'] ], [ "user_id[=]" => $user_id] );
						}else{
							
							$data =  $database->update("user_meta", [ "first_name" => $decoded['fname'], "surname"=>$decoded['lname'],"contact"=> $decoded['contact'],"design_access"=>$decoded['design_access'],"address1"=> $decoded['address'],"region"=>$decoded['region'],"postcode" =>$decoded['post_code'],"country_code" =>$country_code,"account_type_id" =>$decoded['account_type_id'],"device_type" =>$decoded['devicetype'],"company_name"=>NULL ], [ "user_id[=]" => $user_id] );
						}
					}
						if(isset($data)){
							$total_upload_image = $database->count("designs", [ "user_id" => $user_id ]);
							$total_uploads = (string)$total_upload_image;
							$user_data = $database->select('users',
									[
										"[>]user_meta" => ["UserID" => "user_id"],
										"[>]account_type" => ["account_type_id"],
										"[>]countries" => ["country_code"],

									],
										"*",
									[
										"UserID[=]" => $user_id
									]);
							if(!empty($user_data)){
								$responseData['status'] = 1;
								$responseData['statusCode'] = 200;
								$responseData['message'] = 'profile updated successfully';				
								$responseData['functionName'] = 'update_profile_info';				
								$responseData['data'] = $user_data[0];
								$responseData['upload_image_count'] = $total_uploads;
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
							}else{
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = 'profile update unsucceccful';				
								$responseData['functionName'] = 'update_profile_info';		
								$responseData['data'] = array();				
								$responseData['upload_image_count'] = "";			
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;	
							}
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'update profile unsucceccful';				
							$responseData['functionName'] = 'update_profile_info';		
							$responseData['data'] = array();	
							$responseData['upload_image_count'] = "";							
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;	
						} 
					
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Mode parameter is required';				
					$responseData['functionName'] = 'update_profile_info';		
					$responseData['data'] = array();	
					$responseData['upload_image_count'] = "";					
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;	
				} 
						/* else{
					
					$UseKey = getGUID();
					$data = $database->insert("users",
							[
								"UseKey"=>$UseKey,
								"EmailAddress" => $email,
								"Password" => $password,
								
							]);
					
					
					if(!empty($data)){				  
						$imagedecode = base64_decode($decoded['image']);
						$image_name = time().'.jpg';
						$path = 'uploads/'.$image_name;
						$file= fopen($path,'wb');
						$is_written = fwrite($file, $imagedecode);
						fclose($file);
						if($decoded['company_name'] != ''){
							$detail = $database->insert("user_meta",
								[
									"user_id" => $data,
									"first_name" => $decoded['fname'],
									"surname" => $decoded['lname'],
									"contact" => $decoded['contact'],
									"address1" => $decoded['address'],
									"design_access" => $decoded['design_access'],
									"device_type"=>$decoded['devicetype'],
									"account_type_id"=>$decoded['account_type_id'],
									"region"=>$decoded['region'],
									"postcode"=>$decoded['post_code'],
									"country_code"=>$country_code,
									"image_name"=>$image_name,
									"agree"=>1
								]);
						}else{
							$detail = $database->insert("user_meta",
								[
									"user_id" => $data,
									"first_name" => $decoded['fname'],
									"surname" => $decoded['lname'],
									"contact" => $decoded['contact'],
									"address1" => $decoded['address'],
									"design_access" => $decoded['design_access'],
									"device_type"=>$decoded['devicetype'],
									"account_type_id"=>$decoded['account_type_id'],
									"region"=>$decoded['region'],
									"postcode"=>$decoded['post_code'],
									"country_code"=>$country_code,
									"image_name"=>$image_name,
									"agree"=>1
								]);
						}
							if(!empty($detail)){
								
								$user_data = $database->select('users',
									[
										"[>]user_meta" => ["UserID" => "user_id"],
										"[>]account_type" => ["account_type_id"],
										"[>]countries" => ["country_code"],

									],
										"*",
									[
										"UserID[=]" => $data
									]);
								if(!empty($user_data)){
									
									$responseData['status'] = 1;
									$responseData['statusCode'] = 200;
									$responseData['message'] = 'registration successfully';				
									$responseData['functionName'] = 'update_profile_info';				
									$responseData['data'] = $user_data[0];
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
								}else{
									$responseData['status'] = 0;
									$responseData['statusCode'] = 400;
									$responseData['message'] = 'registration unsucceccfully';				
									$responseData['functionName'] = 'update_profile_info';				
									$responseData['data'] = array();
									$result = array('response' => $responseData);
									$jsonEncode = json_encode($result);
									echo $jsonEncode;
								}
							}else{
								$responseData['status'] = 0;
								$responseData['statusCode'] = 400;
								$responseData['message'] = 'registration unsucceccfully';				
								$responseData['functionName'] = 'update_profile_info';				
								$responseData['data'] = array();
								$result = array('response' => $responseData);
								$jsonEncode = json_encode($result);
								echo $jsonEncode;
							}
						
					}
				} */
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid Country Code';				
				$responseData['functionName'] = 'update_profile_info';				
				$responseData['data'] = array();
				$responseData['upload_image_count'] = "";
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'update_profile_info';				
			$responseData['data'] = array();
			$responseData['upload_image_count'] = "";
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;
		}
		
	}
	
	
	/**
	 * @api {post} /get_all_designs  Get All Designs
	 * @apiName get_all_designs
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName get_all_designs.
	 * @apiSuccess {Array}  data list of all data of users personal and shared image.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"get_all_designs",
	 *  		 "data":list of all data of users personal and shared image.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "get_all_designs",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function get_all_designs(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"116"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			
			$personaldata = $database->select('designs',
						[
							"[>]users" => ["user_id" => "UserID"],
							"[>]user_meta" => ["user_id"],
						]
							,"*",
						[
							"user_id[=]" => $user_id
						]);
		//print_r($personaldata);die;
			//if(!empty($personaldata)){
				$public_data = $database->select('designs',
										[
											"[>]users" => ["user_id" => "UserID"],
											"[>]user_meta" => ["user_id"],
										],
									"*",
											[	
												"visibility[=]" =>'Public'
											]
									);
							
				$sharedata = $database->select('design_share',
							"*",
						[
							"shared_with_id[=]" => $user_id
						]);
					//print_r($sharedata);die;
				if(!empty($sharedata)){
					$record = array();
					foreach($sharedata as $value){
						$record[] = $value['userID'];
					}
					//print_r($record);die;
					$design_data =  $database->select('designs',
										[
											"[>]users" => ["user_id" => "UserID"],
											"[>]user_meta" => ["user_id"],
										],
									"*",[
								 "AND" =>	[	
										"visibility[=]" =>'Private', 
										"user_id[=]" => $record
									]
									]);
								
						//$arr = array_merge($personaldata, $design_data);
				
					if(isset($design_data)){
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'successfully';				
						$responseData['functionName'] = 'get_all_designs';				
						$responseData['user_design'] = $personaldata;
						$responseData['shared_design'] = $design_data;
						$responseData['public_design'] = $public_data;
						//$responseData['shared_data'] = $design_data;
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'No Data available';				
						$responseData['functionName'] = 'get_all_designs';				
						$responseData['user_design'] = array();
						$responseData['shared_design'] = array();
						$responseData['public_design'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;				
					}
				}else{
					
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'successfully';				
						$responseData['functionName'] = 'get_all_designs';				
						$responseData['user_design'] = $personaldata;
						$responseData['shared_design'] = array();
						$responseData['public_design'] = $public_data;
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					
				}
			/* }else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'No image available';				
				$responseData['functionName'] = 'get_all_designs';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;				
			} */
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_all_designs';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;				
		}
	}
	
	
	/**
	 * @api {post} /delete_designs  Delete Designs
	 * @apiName delete_designs
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  design_id  Design Id.
	 * @apiParam {Numeric}  user_id  Login User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName delete_designs.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"delete_designs",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "delete_designs",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function delete_designs(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"design_id":"127","user_id":119}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$design_data = $database->select('designs',
							"*",
							["design_id" => $decoded['design_id'], "LIMIT" => 1]);
								
			if(!empty($design_data[0]['user_id'])){
				$user_id = $design_data[0]['user_id'];
				 
				if($user_id == $decoded['user_id']){
					
					$data = $database->delete("designs", 
							/* [	"AND" => */
								[ "design_id" => $decoded['design_id']/* , "user_id" => $decoded['user_id'] */ ] 
							//]
							);			
						
					if(!empty($data)){
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'deleted successfully';				
						$responseData['functionName'] = 'delete_designs';				
						$responseData['data'] =array();
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'delete unsucceccful';				
						$responseData['functionName'] = 'delete_designs';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;				
					}
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'You are not authorized';				
					$responseData['functionName'] = 'delete_designs';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;				
				} 
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'User does not upload image';				
				$responseData['functionName'] = 'delete_designs';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;				
			} 
		}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid Request Type';				
				$responseData['functionName'] = 'delete_designs';				
				$responseData['data'] = array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
				
		}
		
	}
	
	
	/**
	* @api {post} get_all_users_info  Get All Users Info
	* @apiName get_all_users_info
	* @apiGroup User
	* @apiHeaderExample {json} Header-Example
	*	{
	*        "Content-Type":"application/json"
	*	}
	*
	* @apiParam {Numeric}  user_id login User Id.
	*
	*	 
	* @apiSuccess {Numeric} status 1.
	* @apiSuccess {Numeric} response_status 1.
	* @apiSuccess {Numeric} response_code 200.
	* @apiSuccess {String}  message success.
	* @apiSuccess {Boolean}  functionName get_all_users_info.
	* @apiSuccess {String}  data List of all data of Users.
	*
	* @apiSuccessExample Success-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 1,
	*           "response_code": 200,
	*           "message": "Success",
	*           "functionName":"get_all_users_info",
	*           "data": List Of All Data Of Users
	*           
	*       }
	*     }
	*
	* @apiErrorExample Error-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 0,
	*           "response_code": 400,
	*           "message": "No data found",
	*           "functionName":"get_all_users_info",
	*           "data":""
	*           
	*       }
	*     }
	*
	*/
	
	function get_all_users_info(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"137"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$data = $database->select('users',
				[
					"[>]user_meta"	=> ["UserID" => "user_id"],
					"[>]account_type" => ["account_type_id" ],
					"[>]countries" => ["country_code"]
				],
					"*",
					[
						"UserID[!]" =>$decoded['user_id']
					]
				);
			//print_r($data);die;
			if(!empty($data)){
				 $request_detail = $database->select('send_request',
										/* [
											"[>]user_meta"	=> ["UserID" => "user_id"]
											
										], */
											"*",
											[
												"sender_id" =>$decoded['user_id']
											]
										); 
									
				if(!empty($request_detail)){
					
					$responseData['status'] = 1;
					$responseData['statusCode'] = 200;
					$responseData['message'] = 'successfully';				
					$responseData['functionName'] = 'get_all_users_info';				
					$responseData['data'] = $data;
					$responseData['sender_detail'] = $request_detail;
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode; 
				}else{
						
					$responseData['status'] = 1;
					$responseData['statusCode'] = 200;
					$responseData['message'] = 'successfully';				
					$responseData['functionName'] = 'get_all_users_info';				
					$responseData['data'] = $data;
					$responseData['sender_detail'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
						echo $jsonEncode; 
				}
			}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'unsucceccfully';				
					$responseData['functionName'] = 'get_all_users_info';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_all_users_info';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
				
	}
	
	
	/**
	 * @api {post} /send_access_request  Request Send 
	 * @apiName send_access_request
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  sender_id  Sender Id.
	 * @apiParam {Numeric}  receiver_id  Receiver Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName send_access_request.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"send_access_request",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "send_access_request",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function send_access_request(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"sender_id":"116","receiver_id":"115"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
		/* 	$user_data = $database->select("send_request","*",[
								"AND" =>[
									
									"sender_id"=>$decoded['sender_id'],
									"receiver_id"=>$decoded['receiver_id']
									]
								]);
			if(empty($user_data)){ */				
				$data = $database->insert("send_request",
								[
									"sender_id"=>$decoded['sender_id'],
									"receiver_id"=>$decoded['receiver_id']
									
								]);
				if(!empty($data)){
					$responseData['status'] = 1;
					$responseData['statusCode'] = 200;
					$responseData['message'] = 'Request Send Successfully';				
					$responseData['functionName'] = 'send_access_request';				
					$responseData['data'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode; 
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'Request fail';				
					$responseData['functionName'] = 'send_access_request';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
				}
			/* }else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'You have already send request';				
					$responseData['functionName'] = 'send_access_request';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
				}	 */
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'send_access_request';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
	}

	
	
	/**
	 * @api {post} /all_request_list  list of Request Receive
	 * @apiName all_request_list
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName all_request_list.
	 * @apiSuccess {Array}  data List of all Sender request.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"all_request_list",
	 *  		 "data":List of all Sender request.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "all_request_list",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function all_request_list(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"115"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			$data = $database->select('send_request',
					[
						"[>]user_meta" => ["sender_id" => "user_id"],
						"[>]users" => ["sender_id" => "UserID"],
						"[>]account_type" => ["account_type_id"],
						"[>]countries" => ["country_code"]

					],
						"*" ,[
				"AND"=>[
						
						"key[=]" =>0,
						"receiver_id[=]" => $user_id
					]
					]);
					
			if(!empty($data)){
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'Successfully';				
				$responseData['functionName'] = 'all_request_list';				
				$responseData['data'] = $data;
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode; 
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'You do not have any access request';				
				$responseData['functionName'] = 'all_request_list';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'all_request_list';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
	}

	
	
	/**
	 * @api {post} /accept_access_request  Request Accept 
	 * @apiName accept_access_request
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id(Self).
	 * @apiParam {Numeric}  request_sender_id  Request Sender Id(whome you received request).
	 * @apiParam {Numeric}  key  key(1 if request accept and 0 if request reject).
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName accept_access_request.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"accept_access_request",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "accept_access_request",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	function accept_access_request(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"136","request_sender_id":"143","key":"0"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			
			//if(isset($data)){
				if($decoded['key'] == 1){
					$data = $database->update("send_request",["key" => $decoded['key'] ],[
					"AND" =>["receiver_id" => $user_id,"sender_id"=> $decoded['request_sender_id']]]);
		
					/* $user_data = $database->select("design_share","*",[
								"AND" =>[
									"userID"=>$decoded['user_id'],
									"shared_with_id" => $decoded['request_sender_id']
									]
								]); */
					//if(empty($user_data)){		
						$savedata = $database->insert("design_share",
									[
										"userID"=>$decoded['user_id'],
										"shared_with_id" => $decoded['request_sender_id']
										
									]);
									
						if(!empty($savedata)){	
							$responseData['status'] = 1;
							$responseData['statusCode'] = 200;
							$responseData['message'] = 'Request Accepted';				
							$responseData['functionName'] = 'accept_access_request';				
							$responseData['data'] = array();
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode; 
						}else{
							$responseData['status'] = 0;
							$responseData['statusCode'] = 400;
							$responseData['message'] = 'Request fail';				
							$responseData['functionName'] = 'accept_access_request';				
							$responseData['data'] = array();	
							$result = array('response' => $responseData);
							$jsonEncode = json_encode($result);
							echo $jsonEncode;					
						}
					/* }else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Request already accept';				
						$responseData['functionName'] = 'accept_access_request';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;					
					} */
				}else{
					
					 $delete = $database->delete("send_request", 
									[
									"AND" =>[ 
									"sender_id[=]" => $decoded['request_sender_id'],
									"receiver_id[=]" => $user_id 
									]
									]);			
					if(isset($delete)){
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'Request Rejected';				
						$responseData['functionName'] = 'accept_access_request';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;	
					}					
				}
			/* }else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Request fail';				
				$responseData['functionName'] = 'accept_access_request';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			} */
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'accept_access_request';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
	}
	
	
  
	/**
	 * @api {post} /resend_designs  Resend Designs
	 * @apiName resend_designs
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName resend_designs.
	 * @apiSuccess {Array}  data Eight Images of User .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"resend_designs",
	 *  		 "data":Eight Images of User.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "resend_designs",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
  
	function resend_designs(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"108"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			
			
			$user_data =	$database->select('user_meta',
									[
										
										"[>]users" => ["user_id" => "UserID"],
										"[>]account_type" => ["account_type_id" => "account_type_id"],
										
									],
										"*",
									[
										"user_id[=]" => $user_id
									]);
					
			if(!empty($user_data)){
				$interval = strtotime("+11 months", strtotime($user_data[0]['created_on'])) - time();
				$trial_days = floor($interval / 86400);
			
				$total_uploads = $database->count("designs", [ "user_id" => $user_id ]);
				if(!empty($total_uploads)){
					
					$data =$database->select('designs',['design_id','size_thumbnail'], [ "user_id"=>$user_id,"LIMIT"=>8, "ORDER" => [ "design_id" => "DESC" ] ]);
					
					if(!empty($data)){
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'Successfully';				
						$responseData['functionName'] = 'resend_designs';				
						$responseData['data'] = $data;
						$responseData['Total_design_upload'] = $total_uploads;
						$responseData['total_allowed'] = $user_data;
						$responseData['trial_days'] = $trial_days;
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode; 
						
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'Design does not upload';				
						$responseData['functionName'] = 'resend_designs';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;					
					}
				}else{
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'Successfully';				
						$responseData['functionName'] = array();				
						$responseData['data'] = array();
						$responseData['Total_design_upload'] = 0;
						$responseData['total_allowed'] = $user_data;
						$responseData['trial_days'] = $trial_days;
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode; 				
				}
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'User does not register';				
				$responseData['functionName'] = 'resend_designs';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			}
				
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'resend_designs';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
	}
	
	
	/**
	 * @api {post} /about_us  About Us
	 * @apiName about_us
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName about_us.
	 * @apiSuccess {Array}  data about us data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"about_us",
	 *  		 "data":about us data.
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "about_us",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	function about_us(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"1"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$user_id = $decoded['user_id'];
			/* $check_user = $database->select('users',"*", [
									"UserID" => $user_id	
								]); */
			 
			 // if($check_user[0]['userRole'] == 9999){
				/* $data = $database->update("users",
											[
												"about_us"=>$decoded['content']
											],
											[	"AND" =>
												["UserID" => $user_id,"userRole"=>9999]
											]);
					
				if(!empty($data)){ */
					$user_detail = $database->select('user_meta',"*", [
										/* "AND"=>[ */
										//	"userRole"=>9999,
											"user_id" => $user_id
											//]
									  ]);
									
					if(!empty($user_detail)){		
						$responseData['status'] = 1;
						$responseData['statusCode'] = 200;
						$responseData['message'] = 'success';				
						$responseData['functionName'] = 'about_us';				
						$responseData['data'] = $user_detail[0];
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode; 
					}else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'unsucceccfully';				
						$responseData['functionName'] = 'about_us';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;					
					}
				/* }else{
						$responseData['status'] = 0;
						$responseData['statusCode'] = 400;
						$responseData['message'] = 'This content already exist';				
						$responseData['functionName'] = 'about_us';				
						$responseData['data'] = array();	
						$result = array('response' => $responseData);
						$jsonEncode = json_encode($result);
						echo $jsonEncode;					
				} */
			 /* }else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid User id';				
				$responseData['functionName'] = 'about_us';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;					
			} */
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'about_us';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
				
	}
	
	
	
	/**
	 * @api {post} /delete_shared_user Delete shared user
	 * @apiName delete_shared_user
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 * @apiParam {Numeric}  shared_user_id  Shared User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName delete_shared_user.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"delete_shared_user",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "delete_shared_user",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	
	function delete_shared_user(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"118","shared_user_id":"51"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$data = $database->delete("design_share", 
					[
					"AND" =>[ 
					"userID" => $decoded['user_id'] ,
					"shared_with_id" => $decoded['shared_user_id']
					]
					]);			
			
			if($data){
				$request_data = $database->delete("send_request", 
					[
					"AND" =>[ 
					"sender_id" =>  $decoded['shared_user_id'],
					"receiver_id" => $decoded['user_id']
					]
					]);		
				
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'delete successfully';				
				$responseData['functionName'] = 'delete_shared_user';				
				$responseData['data'] =array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'delete unsucceccfully';				
				$responseData['functionName'] = 'delete_shared_user';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;				
			}
		}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid Request Type';				
				$responseData['functionName'] = 'delete_shared_user';				
				$responseData['data'] = array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
				
		}
		
	}
	
	
	
	/**
	* @api {get} get_country_info  Get Country Info
	* @apiName get_country_info
	* @apiGroup User
	* @apiHeaderExample {json} Header-Example
	*	{
	*        "Content-Type":"application/json"
	*	}
	*
	* @apiParam No params. 
	*
	*	 
	* @apiSuccess {Numeric} status 1.
	* @apiSuccess {Numeric} response_status 1.
	* @apiSuccess {Numeric} response_code 200.
	* @apiSuccess {String}  message success.
	* @apiSuccess {Boolean}  functionName get_country_info.
	* @apiSuccess {String}  data List of all data of Country.
	*
	* @apiSuccessExample Success-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 1,
	*           "response_code": 200,
	*           "message": "Success",
	*           "functionName":"get_country_info",
	*           "data": List Of All Data Of country
	*           
	*       }
	*     }
	*
	* @apiErrorExample Error-Response:
	*     HTTP/1.1 200 OK
	*     {
	*       "response": {
	*           "response_status": 0,
	*           "response_code": 400,
	*           "message": "No data found",
	*           "functionName":"get_country_info",
	*           "data":""
	*           
	*       }
	*     }
	*
	*/
	
	function get_country_info(){
		include("base.php");
		$responseData = array();
		if(!empty($_GET['function'])){
			$data = $database->select('countries',"*");
				
			if(!empty($data)){
				$responseData['status'] = 1;
				$responseData['statusCode'] = 200;
				$responseData['message'] = 'success';				
				$responseData['functionName'] = 'get_country_info';				
				$responseData['data'] = $data;
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode; 
			}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'no data available';				
					$responseData['functionName'] = 'get_country_info';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;					
			}
		}else{
			$responseData['status'] = 0;
			$responseData['statusCode'] = 400;
			$responseData['message'] = 'Invalid Request Type';				
			$responseData['functionName'] = 'get_country_info';				
			$responseData['data'] = array();	
			$result = array('response' => $responseData);
			$jsonEncode = json_encode($result);
			echo $jsonEncode;					
		}
				
	}
	
	
	
	
	/**
	 * @api {post} /user_validate Login User Validation
	 * @apiName user_validate
	 * @apiGroup User
	 * 
	 * @apiHeaderExample {json} Header-Example:
	 *     {
	 *       "Content-Type": "application/json"
	 *     } 
	 *
	 *
	 * @apiParam {Numeric}  user_id  User Id.
	 *
	 * @apiSuccess {Numeric} response_code 200.
	 * @apiSuccess {Numeric} status 1.
	 * @apiSuccess {String}  message success.
	 * @apiSuccess {Boolean} functionName user_validate.
	 * @apiSuccess {Array}  data .
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 1,
	 *           "response_code": 200,
	 *           "message": "success.",
	 *           "functionName":"user_validate",
	 *  		 "data":"".
	 *       }
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "response": {
	 *           "response_status": 0,
	 *           "response_code": 400,
	 *           "message": "appropriate error message.",
	 * 			 "functionName": "user_validate",
	 *			 "data": "",
	 *       }
	 *     }
	 *
	 *
	 */
	
	
	function user_validate(){
		include("base.php");
		$data = file_get_contents("php://input");
		//$data = '{"user_id":"240"}';
		$decoded = json_decode($data,true);
		$responseData = array();
		if(!empty($decoded)){
			$data = $database->select('users',"*",[
					"AND" =>
					[
						'userRole[!]'=> 3,
						"userActive"=>1,
						"UserID" => $decoded['user_id']
					]
				]);	
			//print_r($data);die;
			if(!empty($data)){
				$payment_date = $data[0]['payment_on'];
				$user_payment_date = substr($payment_date,0,10);
				$year_month = date("Y-m",strtotime($user_payment_date));
				$timestam = strtotime(date($user_payment_date)." +11 month");
				$after_eleven_date = date('Y-m-d', $timestam);
				$current_date = date('Y-m-d');
				//$current_date = "2018-10-30";
				if($after_eleven_date > $current_date){
					$responseData['status'] = 1;
					$responseData['statusCode'] = 200;
					$responseData['message'] = 'Successfully';				
					$responseData['functionName'] = 'user_validate';				
					$responseData['data'] = array();
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;
					 
				}else{
					$responseData['status'] = 0;
					$responseData['statusCode'] = 400;
					$responseData['message'] = 'You account is Inactive. Please renew your account by paying through the website.';				
					$responseData['functionName'] = 'user_validate';				
					$responseData['data'] = array();	
					$result = array('response' => $responseData);
					$jsonEncode = json_encode($result);
					echo $jsonEncode;				
				} 
					
					
			
			}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'User does not exist';				
				$responseData['functionName'] = 'user_validate';				
				$responseData['data'] = array();	
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;				
			}
		}else{
				$responseData['status'] = 0;
				$responseData['statusCode'] = 400;
				$responseData['message'] = 'Invalid Request Type';				
				$responseData['functionName'] = 'user_validate';				
				$responseData['data'] = array();
				$result = array('response' => $responseData);
				$jsonEncode = json_encode($result);
				echo $jsonEncode;
				
		}
		
	}
	
?>