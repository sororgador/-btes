<?php
include_once("connection.php");
session_start();
function form1()
{?>
	 <div class="form">
	 <h2>الرجاء ادخال بياناتك السابقة</h2>
    <form method="post" action="#">
      <label for="name">Username:</label>
      <input type="text" id="name" name="name">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
      <input type="submit" name="login" value="LOGIN">
	  
    </form>
  </div>
	<?php
}

function check($conn)
{   
      $flag=0;  
	  $u=$_POST['name'];
	  $p=$_POST['password'];
    try{
	$sql="SELECT * FROM sign_in" ;
	$rows=$conn->query($sql);
	while($row=$rows->fetch(PDO::FETCH_OBJ))
	{
		if(($row->username == $u) && ($row->Password == $p))
		{ 
	       header('LOCATION:  update_login.php');
			$flag=1;
			break;
		}
		
	}
	}
	catch(PDOException $e) 
   {
	
    echo "Error: " . $e->getMessage();
     
   }
  	   
  if($flag==0){ echo " ادخالك غير صحيح";}
	
}
?>
<html>
<body>
<?php

  
if(!isset($_POST['login']))
{
    form1();  	 
 
}
else{
	$_SESSION['username']=$_POST['name'];
	$_SESSION['password']=$_POST['password'];
  check($conn);	
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