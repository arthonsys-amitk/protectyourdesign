<?php
	include("base.php");
	check_logged(); /// function checks if visitor is logged.
	include("header.php");
	$ok = 0;
	$uploadlimit = '1';
	$profile = $database->select('users',
		[
			"[>]user_meta" => ["UserID" => "user_id"],
			"[>]account_type" => ["account_type_id"],
			"[>]countries" => ["country_code"],
		],"*",
		["user_id" => $_SESSION['user_id'], "LIMIT" => 1]);
	$upload_allowed = $profile[0]['upload_allowed'];
	$total_uploads = $database->count("designs", [ "user_id" => $_SESSION['user_id'] ]);

	if( $total_uploads != 0 && $upload_allowed != 0 ) {
		$s = ($upload_allowed - $total_uploads);
		if( $s <= 0 ) {
			$uploadlimit = 0;
		}
	}
?>

<?php
require_once __DIR__ . '/../imagehash/src/Implementation.php';
require_once __DIR__ . '/../imagehash/src/Implementations/DifferenceHash.php';
require_once __DIR__ . '/../imagehash/src/ImageHash.php';

use Jenssegers\ImageHash\ImageHash;
?>

<?php if(isset($_POST['submit'])): ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php
				$fName 			= strtolower($_FILES['design']['name']);
				$fType 			= strtolower($_FILES['design']['type']);
				$fSize 			= $_FILES['design']['size'];
				$fTmpName 	= $_FILES['design']['tmp_name'];
				$tmpname  	= $_FILES['design']['tmp_name'];

				$design_title = (isset($_POST['design_title'])) ? $_POST['design_title'] : '';
				$description 	= (isset($_POST['description'])) 	? $_POST['description'] : '';
				$category  		= (isset($_POST['category'])) 		? $_POST['category'] : '';
				$subcategory  = (isset($_POST['subcategory'])) 	? $_POST['subcategory'] : '';
				$visibility 	= (isset($_POST['visibility'])) 	? $_POST['visibility'] : '';
				$ext  = "";
				$size_full = "";
				$ok = '0';
				
				$hasher = new ImageHash;
				$hash1 = $hasher->hash($tmpname);
				$db_collection = $database->select('designs', "*",[]);
				$similar_img = 0;
				foreach($db_collection as $row) {
					$db_imagehash = $row['imagehash'];
					if(!is_null($db_imagehash) && $db_imagehash) {
						$distance = $hasher->distance($hash1, $db_imagehash);
						if($distance < 5) {
							$similar_img = 1;
							$error[] = "This image already exists on the server";
						}
					}
				}
				
				if( empty($design_title) ) {
					$error[] = "Design title required.";
				}

				if( empty($category) ) {
					$error[] = "Category required.";
				}

				if( empty($subcategory) ) {
					$error[] = "Sub-category required.";
				}

				if( empty($fName) ) {
					$error[] = "Photo required.";
				}

				if( $fName ) :
					switch($fType) {
							case 'image/png':
								$ext = '.png';
							case 'image/gif':
								$ext = '.gif';
							case 'image/jpeg':
								$ext = '.jpg';
							case 'image/pjpeg':
								$ext = '.jpg';
									break;
							default:
								$error[] = '<b>'.$fName.'</b> is an unsupported file type, please upload a photo';
					}

					if (($fSize > 4194304)) :
						$error[] = 'File too large. File must be less than 4 megabytes.';
				 	endif;

				endif;



				if( empty($error) ) {
					$t = microtime(true);
					$micro = sprintf("%06d",($t - floor($t)) * 1000000);
					$r = date('Y-m-d H:i:s.'.$micro, $t);

					$size_medium = md5( $_FILES['design']['tmp_name'].'size_medium'.$r );
					@img_resize( $tmpname , 900, "./uploads" , $size_medium.".jpg");

					$size_thumbnail = md5( $_FILES['design']['tmp_name'].'size_thumbnail'.$r );
					@img_resize( $tmpname , 400, "./uploads" , $size_thumbnail.".jpg");

					$size_full 	= md5( $_FILES['design']['tmp_name'].'size_full'.$r );
					$up_filename = str_replace("\\", "/", getcwd().'/uploads/'.$size_full.$ext);
					$move_file 	= move_uploaded_file( $fTmpName, $up_filename);
					
					
					if(!$similar_img) {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_POST, true);
						//$imgdata['bucketid'] = '592f7d178324eb8a7f54647e';
						$imgdata['bucketid'] = '92ca4dfb52d63b7e6b7454d8';
						//$imgdata['filetoupload'] = new CurlFile($fTmpName, 'image/png', basename($fTmpName));
						$imgdata['filetoupload'] = new CurlFile($up_filename, $fType, basename($up_filename));
						curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						//curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3000/postimage');
						curl_setopt($ch, CURLOPT_URL, 'http://188.166.168.216:3030/postimage');
						$response = curl_exec($ch);				
						if($response === false) {
							$error[] = "Error occurred." . curl_error($ch);
						} elseif(strpos($response, "File uploaded with fileid") === false) {
							//$error[] = "File already exists on server.";
							$error[] = $response;
						}
						curl_close($ch);
					}
					
					
					if(empty($error)) {					
						$result = $database->insert("designs",[
																				"user_id" 				=> $_SESSION['user_id'],
																				"design_title" 		=> $design_title,
																				"description" 		=> $description,
																				"category" 				=> $category,
																				"subcategory" 		=> $subcategory,
																				"size_thumbnail" 	=> $size_thumbnail.'.jpg',
																				"size_medium" 		=> $size_medium.'.jpg',
																				"size_full" 			=> $size_full.$ext,
																				"visibility" 			=> $visibility,
																				"imagehash" 			=> $hash1
																				]);
							if( $result ) :
								$ok = '1';
								$design_title = $_POST['design_title'] = '';
								$description 	= $_POST['description'] = '';
								$_FILES = '';
							endif;
					} else {
						//delete file
						$del_filename = getcwd().'/uploads/'.$size_full.$ext;
						@unlink($del_filename);
					}
				}
			?>


			<?php if( !empty($error) ) : $i = '1';?>
			<div class="alert alert-danger animated fadeIn" role="alert">
				<p><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"> <b>Error saving your design.</b></i></p>
				<ul>
				<?php foreach( $error AS $v ) : ?>
					<li><?php echo $i++.'. '.$v; ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>



<div class="body_wrap">
		<h1>/ Upload Design /</h1>

		<?php if( $ok == '1' ) : ?>
			<div class="alert alert-success animated fadeIn" role="alert">
				Design Successfully Uploaded! <br />
				<a href="<?php echo SITEURL;?>/gallery.php" class="alert-link">Click here to review uploaded design.</a>
			</div>
			<?php
				$to = $_SESSION['email'];
				$subject = "Design Number #" . $result . " uploaded to Protect Your Design";
				$upload_count = $database->count("designs", [ "user_id" => $_SESSION['user_id'] ]);
				if( $upload_allowed != '0' ) {
					$count = $upload_allowed - $upload_count;
				} else {
					$count = 'unlimited';
				}

				$message = "Your design was registed on Protect Your Design. <br><br>" .
									 "Your design's registration number is: " . $result . "<br><br>" .
									 "Please keep this number for your records." ."<br><br>".
									 "You currently have ".$count." upload(s) remaining in your current quota." . "<br><br><br>" .
									 "Protect Your Design" . "<br>" .
									 "Lawdit Solicitors" . "<br><br>";
				 $headers = 'From: nobody@protectyourdesign.com' . "<br>" .
				 					 'Reply-To: nobody@protectyourdesign.com';

				 @mail($to, $subject, $message, $headers,'-fnobody@protectyourdesign.com');
				/*
				$mail->addAddress( $to );

					$mail->Subject = $subject;
					$mail->msgHTML( $message );

					if (!$mail->send()) {
						echo "Mailer Error: " . $mail->ErrorInfo;
					}
				*/
			?>
			<?php else: ?>

				<?php
					// Check if upload limit or membership period not expired
					if( $uploadlimit == '0' ) {
				?>
					<h3>You need to upgrade your account.</h3>
					<p>You reach your <?php echo $upload_allowed; ?> image upload limit available for your account type. In order to upload more, please <a href="" style="text-decoration: underline">upgrade your account.</a></p>
				<?

					} else {
				?>
				<form class="js-upload-form" action="" enctype="multipart/form-data" id="js-upload-form" name="js-upload-form" method="POST">
					<div class="upload_form_wrap">
						<div class="form_field">
							<input type="text" name="design_title" id="design_title" class="input_txt" value="<?php echo (isset($design_title)) ? $design_title : ''; ?>" placeholder="Design Title">
						</div>
						<div class="form_field">
							<textarea class="form_txt" name="description" id="description" placeholder="A good description helps when searching for designs"><?php echo (isset($description)) ? $description : ''; ?></textarea>
						</div>

						<div class="form_field">
							<div class="selectdiv_lp1">
								<select name="category" id="category" class="form-control">
								<?php $options = $database->select("category","*",[ "cat_status[=]" => 1, "ORDER" => [ "catname", "catname" => "ASC" ] ]); ?>
									<option Value="" Disabled Selected>Choose a Category</option>
									<?php if(isset($options) && $options != false ) : ?>
									<?php foreach( $options AS $option ) : ?>
									<option value="<?php echo $option['catid'];?>"><?php echo $option['catname'];?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>

						<div class="form_field">
							<div class="selectdiv_lp1">
								<select name="subcategory" id="subcategory" class="form-control">
									<option Value="" Disabled Selected>Choose a Sub-Category</option>
								</select>
							</div>
						</div>

						<div class="form_field">
							<input type="file" id="design" name="design"><br />
							<span class="white_txt"><em>(Must be in PDF, JPEG, GIF, PNG, BMP or TIFF format)</em></span>
						</div>

						<div class="form_field"> <div class="selectdiv_lp1">
							<select id="visibility" name="visibility">
								<option value="" Disabled Selected>Visibility</option>
								<option value="Public">Public</option>
								<option value="Private">Private</option>
							</select>
						</div>
					</div>
				</div>
				<div class="form_field"><input type="submit" class="btn_upload" name="submit" value="Upload Your Design" /></div>
			</form>
			<?php } // Check if upload limit or membership period not expired ?>




<?php endif; ?>









	<div class="clear"></div>
</div></div>

<?php include_once("footer.php"); ?>
