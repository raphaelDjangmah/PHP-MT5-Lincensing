<!DOCTYPE html>
<html lang="en">
<head>
<title>Licensing Signup</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="FrontENDS/assets/img/favicon.png" rel="icon">
  <link href="FrontENDS/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="../../FE-BackEnd/signup.check.php" method="POST">
					<span class="login100-form-title">
						Create An Account
					</span>

					<div id="errorMessage" class="text-center p-b-20 text-danger font-weight-bold">
							<?php
								if(isset($_GET['signup_message_check'])){
									//-- if 1 then successfully logged in
									if($_GET['signup_message_check']!='1'){
										echo $_GET['signup_message_check'];
									}else{
										echo "<p style='color:lime'>Account created Successfull</p>";
											
										//--redirect to homepage
										header('location:../dashboards/user_dashboard.php');
									}
								}
							?>
					 </div>

					<div class="text-center p-b-10 text-danger">
						<a class="txt1" href="#">
							Signup as a Admin
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
					
					<!-- REQUIRING AN EMAIL -->
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="user_email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					

					<!-- REQUIRING A PASSWORD -->
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="user_pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<!-- CONFIRMING PASSWORD -->
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="user_pass_confirm" placeholder="Confirm Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<!-- PHONE NUMBER -->
					<div class="wrap-input100 validate-input" data-validate = "">
						<input class="input100" type="phone" name="user_phone" placeholder="Phone Number">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-phone" aria-hidden="true"></i>
						</span>
					</div>

					<!-- VERIFICATION -->
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" name="signup_btn_clicked">
							CREATE ACCOUNT
						</button>
					</div>


					<div class="text-center p-t-136">
						<a class="txt2" href="login.php">
							Already Have An account? Login
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->

	<!-- REMOVING JAVASCRIPT AUTHENTICATION -->
	<!-- <script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script> -->
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>