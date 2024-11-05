<?php 
session_start();
include_once("connection.php");
include_once("users.php");
?>
<html>
<body>
<?php  
if(!isset($_POST['login']))
{
   echo $admin->formLogin();  	 
 
}
else{
	$_SESSION['username']=$_POST['name'];
	$_SESSION['password']=$_POST['password'];
	$username=$_POST['name'];
	$password=$_POST['password'];
	$admin->validateForm($username, $password);
    echo $admin->check($conn);	
}
?>
</body>
<head>
<style>
    .form {
      background-color: white;
      border: 1px solid #007bff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin: 50px auto;
      max-width: 400px;
    }
 
	      body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }
	    .form h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-bottom: 15px;
    }

    .form input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }

    .form input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .form a {
      display: block;
      text-align: center;
      color: #007bff;
      text-decoration: none;
      margin-top: 10px;
    }

    .form a:hover {
      text-decoration: underline;
    }
</style>
</head>
</html>