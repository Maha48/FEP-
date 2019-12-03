<?php 
session_start();
$errors = [];

require_once './vendor/autoload.php';
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف

$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database);

if (isset($_POST['reset-password'])) {
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  // ensure that the user exists on our system
  $query = "SELECT Email FROM Admin WHERE Email='$email'";
  $results = mysqli_query($connection, $query)or die( mysqli_error($connection));

  if (empty($email)) {
    array_push($errors, "Your email is required");
  }else if(mysqli_num_rows($results)<0) {
    array_push($errors, "Sorry, no user exists on our system with that email");
  }
  // generate a unique random token of length 100
  $token = bin2hex(random_bytes(50));

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
    $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
    $results = mysqli_query($connection, $sql)or die( mysqli_error($connection));

    // Send email to user with the token in a link they can click on
    // Create the Transport
$transport = (new Swift_SmtpTransport('mail.flexibleexamsplanner.com',25))
->setUsername('fep@flexibleexamsplanner.com')
->setPassword('MMLG123')
;
$mailer = new Swift_Mailer($transport);
// Create a message
$body = "Hi there, click on this <a href=\"https://flexibleexamsplanner.com/new_pass.php?token=" . $token . "\">
link</a> to reset your password on our site";
$message = (new Swift_Message('Email Through Swift Mailer'))
  ->setFrom('fep@flexibleexamsplanner.com')
  ->setTo([$email])
  ->setBody($body)
  ->setContentType('text/html') ;
// Send the message
$mailer->send($message);
header('location: https://flexibleexamsplanner.com/pending.php?email='. $email);
  }
}
// ENTER A NEW PASSWORD
if (isset($_GET["token"])){
  $token = $_GET['token'];
  $_SESSION['token']=$token;
}
if (isset($_POST['new_password'])) {
    $new_pass = mysqli_real_escape_string($connection, $_POST['new_pass']);
    $new_pass_c = mysqli_real_escape_string($connection, $_POST['new_pass_c']);
  
    // Grab to token that came from the email link
    if (isset($_SESSION["token"])){
      $token = $_SESSION['token'];
    }
    
    if (empty($new_pass) || empty($new_pass_c)) {
      array_push($errors, "Password is required"); };
    if ($new_pass !== $new_pass_c) array_push($errors, "Password do not match");
    if (count($errors) == 0) {
      $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
      $results = mysqli_query($connection, $sql)or die( mysqli_error($connection));
      $email = mysqli_fetch_assoc($results)['email'];
      if ($email) {
        // does it reach here?no
        $new_pass = password_hash($new_pass,PASSWORD_BCRYPT);
        $sql = "UPDATE Admin SET Password='$new_pass' WHERE Email='$email'";
        $results = mysqli_query($connection, $sql);
        header('location: https://flexibleexamsplanner.com/index.php');
      }
    }
  }
  ?>