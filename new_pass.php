<?php include('app_logic.php'); ?>

<?php
 require_once './vendor/autoload.php';
 $dotenv = Dotenv\Dotenv::create(__DIR__);
 $dotenv->load();
 // نستطيع الان قراءة المتغيرات من الملف
 include('messages.php');
 $db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
 $db_username = getenv('DB_username');
 $db_password = getenv('DB_password');
 $Database = getenv('DB');
 $connection = mysqli_connect($db_host, $db_username, $db_password, $Database,'8889');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Password Reset PHP</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="basicdiv">
        <img id="psauimage" src="images/Prince_Sattam_Bin_Abdulaziz_University.png">
        <img id="fepimage" src="images/FEP.png">
        <hr style="width:400px">
        <div id="inputdiv" style="">
	<form class="login-form" action="new_pass.php" method="post">
		<h2 class="form-title">New password</h2>
		<!-- form validation messages -->
		<?php include('messages.php'); ?>
		<div class="form-group">
			<label>New password</label><br>
			<input type="password" name="new_pass">
		</div>
		<div class="form-group">
			<label>Confirm new password</label><br>
			<input type="password" name="new_pass_c">
		</div>
		<div class="form-group">
			<button type="submit"  id="loginsubmit"
			name="new_password" class="login-btn">Submit</button>
		</div>
	</form>
</body>
</html>

